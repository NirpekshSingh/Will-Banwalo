<?php
// admin/api.php — all dashboard AJAX calls come here
// POST: { action, ...params }
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../config/db.php';
header('Content-Type: application/json; charset=utf-8');

// Auth check
if (empty($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['success'=>false,'error'=>'Unauthenticated']); exit;
}

$adminRole = $_SESSION['admin_role'] ?? 'staff';
$isAdmin   = ($adminRole === 'admin');

// Helper — restrict to admin role only
function requireAdmin(): void {
    global $isAdmin;
    if (!$isAdmin) fail('Access denied. Admin role required.', 403);
}

function ok(array $d=[]){echo json_encode(['success'=>true]+$d);exit;}
function fail(string $m,int $c=400){http_response_code($c);echo json_encode(['success'=>false,'error'=>$m]);exit;}

$raw    = file_get_contents('php://input');
$input  = json_decode($raw, true) ?: $_POST;
$action = $input['action'] ?? ($_GET['action'] ?? '');
$db     = getDB();

switch ($action) {

// ─── DASHBOARD STATS ───────────────────────────────────────────
case 'stats':
    $stats = [];
    $stats['role'] = $adminRole;
    // Total revenue (paid)
    $stats['revenue']       = $db->query("SELECT COALESCE(SUM(total_amount),0) FROM orders WHERE payment_status='paid'")->fetchColumn();
    $stats['total_orders']  = $db->query("SELECT COUNT(*) FROM orders")->fetchColumn();
    $stats['paid_orders']   = $db->query("SELECT COUNT(*) FROM orders WHERE payment_status='paid'")->fetchColumn();
    $stats['pending_orders']= $db->query("SELECT COUNT(*) FROM orders WHERE payment_status='pending'")->fetchColumn();
    $stats['draft_orders']  = $db->query("SELECT COUNT(*) FROM orders WHERE status='draft'")->fetchColumn();
    $stats['completed']     = $db->query("SELECT COUNT(*) FROM orders WHERE status='completed'")->fetchColumn();
    $stats['total_clients'] = $db->query("SELECT COUNT(DISTINCT mobile) FROM personal_details")->fetchColumn();
    $stats['docs_uploaded'] = $db->query("SELECT COUNT(*) FROM documents")->fetchColumn();
    // Revenue last 7 days
    $chart = $db->query("SELECT DATE(created_at) as d, SUM(total_amount) as rev
        FROM orders WHERE payment_status='paid' AND created_at >= DATE_SUB(NOW(),INTERVAL 7 DAY)
        GROUP BY DATE(created_at) ORDER BY d")->fetchAll();
    $stats['revenue_chart'] = $chart;
    // Will type breakdown
    $wt = $db->query("SELECT will_type, COUNT(*) as cnt FROM orders GROUP BY will_type ORDER BY cnt DESC")->fetchAll();
    $stats['will_types'] = $wt;
    // Plan breakdown
    $plans = $db->query("SELECT plan, COUNT(*) as cnt FROM orders GROUP BY plan")->fetchAll();
    $stats['plans'] = $plans;
    // Recent orders
    $recent = $db->query("SELECT o.order_ref, o.will_type, o.total_amount, o.payment_status, o.status,
        o.created_at, pd.full_name, pd.mobile
        FROM orders o LEFT JOIN personal_details pd ON pd.order_id=o.id
        ORDER BY o.created_at DESC LIMIT 5")->fetchAll();
    $stats['recent'] = $recent;
    ok($stats);

// ─── ORDERS LIST ───────────────────────────────────────────────
case 'orders':
    $page    = max(1,(int)($input['page']??1));
    $limit   = 20;
    $offset  = ($page-1)*$limit;
    $search  = trim($input['search']??'');
    $status  = $input['status']??'';
    $pay     = $input['payment']??'';
    $plan    = $input['plan']??'';
    $sort    = in_array($input['sort']??'',['created_at','total_amount','order_ref'])?$input['sort']:'created_at';
    $dir     = ($input['dir']??'desc')==='asc'?'ASC':'DESC';

    $where=[]; $params=[];
    if($search){
        $where[]='(o.order_ref LIKE ? OR pd.full_name LIKE ? OR pd.mobile LIKE ? OR pd.email LIKE ?)';
        $s="%$search%"; $params=array_merge($params,[$s,$s,$s,$s]);
    }
    if($status){$where[]='o.status=?';$params[]=$status;}
    if($pay){$where[]='o.payment_status=?';$params[]=$pay;}
    if($plan){$where[]='o.plan=?';$params[]=$plan;}

    $wSQL=$where?'WHERE '.implode(' AND ',$where):'';
    $total=$db->prepare("SELECT COUNT(*) FROM orders o LEFT JOIN personal_details pd ON pd.order_id=o.id $wSQL");
    $total->execute($params);
    $total=$total->fetchColumn();

    $stmt=$db->prepare("SELECT o.id,o.order_ref,o.will_type,o.plan,o.plan_amount,o.gst_amount,o.total_amount,
        o.payment_status,o.status,o.razorpay_payment_id,o.created_at,o.updated_at,
        pd.full_name,pd.mobile,pd.email,pd.city,pd.state
        FROM orders o LEFT JOIN personal_details pd ON pd.order_id=o.id
        $wSQL ORDER BY o.$sort $dir LIMIT $limit OFFSET $offset");
    $stmt->execute($params);
    $orders=$stmt->fetchAll();
    ok(['orders'=>$orders,'total'=>(int)$total,'page'=>$page,'pages'=>(int)ceil($total/$limit)]);

// ─── SINGLE ORDER DETAIL ───────────────────────────────────────
case 'order_detail':
    $id=(int)($input['id']??0);
    if(!$id) fail('ID required');
    $o=$db->prepare('SELECT * FROM orders WHERE id=?');$o->execute([$id]);$order=$o->fetch();
    if(!$order) fail('Not found',404);
    $pd=$db->prepare('SELECT * FROM personal_details WHERE order_id=?');$pd->execute([$id]);$personal=$pd->fetch();
    $ben=$db->prepare('SELECT * FROM beneficiaries WHERE order_id=? ORDER BY id');$ben->execute([$id]);$bens=$ben->fetchAll();
    $ast=$db->prepare('SELECT * FROM assets WHERE order_id=? ORDER BY id');$ast->execute([$id]);$assets=$ast->fetchAll();
    $si=$db->prepare('SELECT * FROM special_instructions WHERE order_id=?');$si->execute([$id]);$special=$si->fetch();
    $docs=$db->prepare('SELECT * FROM documents WHERE order_id=? ORDER BY created_at');$docs->execute([$id]);$docs=$docs->fetchAll();
    $log=$db->prepare('SELECT * FROM payment_log WHERE order_id=? ORDER BY created_at DESC');$log->execute([$id]);$log=$log->fetchAll();
    $disp=$db->prepare('SELECT * FROM dispatch WHERE order_id=?');$disp->execute([$id]);$dispatch=$disp->fetch()?:null;
    ok(compact('order','personal','bens','assets','special','docs','log','dispatch'));

// ─── UPDATE ORDER STATUS ───────────────────────────────────────
case 'update_status':
    $id=(int)($input['id']??0);
    $status=$input['status']??'';
    $allowed=['draft','submitted','under_review','completed','cancelled'];
    if(!$id||!in_array($status,$allowed)) fail('Invalid');
    $db->prepare('UPDATE orders SET status=? WHERE id=?')->execute([$status,$id]);
    ok(['message'=>'Status updated']);

// ─── UPDATE PAYMENT STATUS ─────────────────────────────────────
case 'update_payment':
    $id=(int)($input['id']??0);
    $status=$input['status']??'';
    $allowed=['pending','paid','failed','refunded'];
    if(!$id||!in_array($status,$allowed)) fail('Invalid');
    $db->prepare('UPDATE orders SET payment_status=? WHERE id=?')->execute([$status,$id]);
    ok(['message'=>'Payment status updated']);

// ─── ADD ADMIN NOTE / UPDATE INSTRUCTIONS ─────────────────────
case 'add_note':
    $id=(int)($input['id']??0);
    $note=htmlspecialchars(trim($input['note']??''),ENT_QUOTES,'UTF-8');
    if(!$id||!$note) fail('Invalid');
    $db->prepare('INSERT INTO payment_log (order_id,event,payload) VALUES (?,"admin_note",?)')->execute([$id,json_encode(['note'=>$note,'by'=>$_SESSION['admin_user']])]);
    ok(['message'=>'Note added']);

// ─── DELETE ORDER ──────────────────────────────────────────────
case 'delete_order':
    $id=(int)($input['id']??0);
    if(!$id) fail('ID required');
    // Check not paid
    $chk=$db->prepare('SELECT payment_status FROM orders WHERE id=?');$chk->execute([$id]);$row=$chk->fetch();
    if(!$row) fail('Not found',404);
    if($row['payment_status']==='paid') fail('Cannot delete a paid order.');
    $db->prepare('DELETE FROM orders WHERE id=?')->execute([$id]);
    ok(['message'=>'Order deleted']);

// ─── CLIENTS LIST ──────────────────────────────────────────────
case 'clients':
    $page=max(1,(int)($input['page']??1));$limit=20;$offset=($page-1)*$limit;
    $search=trim($input['search']??'');
    $where=[];$params=[];
    if($search){$where[]='(pd.full_name LIKE ? OR pd.mobile LIKE ? OR pd.email LIKE ?)';$s="%$search%";$params=[$s,$s,$s];}
    $wSQL=$where?'WHERE '.implode(' AND ',$where):'';
    $total=$db->prepare("SELECT COUNT(DISTINCT pd.id) FROM personal_details pd $wSQL");$total->execute($params);$total=$total->fetchColumn();
    $stmt=$db->prepare("SELECT pd.full_name,pd.mobile,pd.email,pd.city,pd.state,pd.created_at,
        COUNT(o.id) as order_count, SUM(CASE WHEN o.payment_status='paid' THEN o.total_amount ELSE 0 END) as total_paid
        FROM personal_details pd LEFT JOIN orders o ON o.id=pd.order_id
        $wSQL GROUP BY pd.id ORDER BY pd.created_at DESC LIMIT $limit OFFSET $offset");
    $stmt->execute($params);
    ok(['clients'=>$stmt->fetchAll(),'total'=>(int)$total,'page'=>$page,'pages'=>(int)ceil($total/$limit)]);

// ─── REVENUE REPORT ────────────────────────────────────────────
case 'revenue':
    requireAdmin();
    $range=$input['range']??'30';
    $days=(int)$range;if($days<1||$days>365)$days=30;
    $daily=$db->prepare("SELECT DATE(created_at) as d,COUNT(*) as orders,SUM(total_amount) as revenue
        FROM orders WHERE payment_status='paid' AND created_at>=DATE_SUB(NOW(),INTERVAL ? DAY)
        GROUP BY DATE(created_at) ORDER BY d");
    $daily->execute([$days]);
    $byPlan=$db->prepare("SELECT plan,COUNT(*) as cnt,SUM(total_amount) as rev FROM orders WHERE payment_status='paid' AND created_at>=DATE_SUB(NOW(),INTERVAL ? DAY) GROUP BY plan");
    $byPlan->execute([$days]);
    $byType=$db->prepare("SELECT will_type,COUNT(*) as cnt,SUM(total_amount) as rev FROM orders WHERE payment_status='paid' AND created_at>=DATE_SUB(NOW(),INTERVAL ? DAY) GROUP BY will_type ORDER BY rev DESC");
    $byType->execute([$days]);
    $totals=$db->prepare("SELECT SUM(total_amount) as total,SUM(plan_amount) as subtotal,SUM(gst_amount) as gst,COUNT(*) as count FROM orders WHERE payment_status='paid' AND created_at>=DATE_SUB(NOW(),INTERVAL ? DAY)");
    $totals->execute([$days]);
    ok(['daily'=>$daily->fetchAll(),'by_plan'=>$byPlan->fetchAll(),'by_type'=>$byType->fetchAll(),'totals'=>$totals->fetch()]);

// ─── EXPORT CSV ────────────────────────────────────────────────
case 'export_csv':
    requireAdmin();
    $type=$input['type']??'orders';
    if($type==='orders'){
        $rows=$db->query("SELECT o.order_ref,o.will_type,o.plan,o.total_amount,o.payment_status,o.status,
            o.razorpay_payment_id,o.created_at,pd.full_name,pd.mobile,pd.email,pd.city,pd.state
            FROM orders o LEFT JOIN personal_details pd ON pd.order_id=o.id
            ORDER BY o.created_at DESC")->fetchAll();
        $headers=['Order Ref','Will Type','Plan','Amount','Payment','Status','RZP Payment ID','Created','Name','Mobile','Email','City','State'];
    } else {
        $rows=$db->query("SELECT pd.full_name,pd.mobile,pd.email,pd.city,pd.state,pd.created_at,
            COUNT(o.id) as orders FROM personal_details pd LEFT JOIN orders o ON o.id=pd.order_id
            GROUP BY pd.id ORDER BY pd.created_at DESC")->fetchAll();
        $headers=['Name','Mobile','Email','City','State','Joined','Orders'];
    }
    ob_start();
    $f=fopen('php://output','w');
    fputcsv($f,$headers);
    foreach($rows as $r) fputcsv($f,array_values($r));
    fclose($f);
    $csv=ob_get_clean();
    ok(['csv'=>$csv,'filename'=>$type.'_'.date('Y-m-d').'.csv']);

// ─── ADMINS LIST ───────────────────────────────────────────────
case 'admins':
    requireAdmin();
    $rows=$db->query("SELECT id,username,full_name,email,role,is_active,last_login,created_at FROM admins ORDER BY role,id")->fetchAll();
    ok(['admins'=>$rows]);

// ─── ADD ADMIN ─────────────────────────────────────────────────
case 'add_admin':
    requireAdmin();
    $un=trim($input['username']??'');
    $pw=$input['password']??'';
    $fn=trim($input['full_name']??'');
    $em=trim($input['email']??'');
    $role=($input['role']??'staff')==='admin'?'admin':'staff';
    if(!$un||!$pw||!$fn) fail('Username, password and name are required.');
    if(strlen($pw)<8) fail('Password must be at least 8 characters.');
    $chk=$db->prepare('SELECT id FROM admins WHERE username=?');$chk->execute([$un]);
    if($chk->fetch()) fail('Username already exists.');
    $hash=password_hash($pw,PASSWORD_BCRYPT);
    $db->prepare('INSERT INTO admins (username,password_hash,full_name,email,role) VALUES (?,?,?,?,?)')->execute([$un,$hash,$fn,$em,$role]);
    ok(['message'=>'Account created.']);

// ─── TOGGLE ADMIN ACTIVE ───────────────────────────────────────
case 'toggle_admin':
    requireAdmin();
    $id=(int)($input['id']??0);
    if(!$id||$id==(int)$_SESSION['admin_id']) fail('Cannot deactivate yourself.');
    $db->prepare('UPDATE admins SET is_active=NOT is_active WHERE id=?')->execute([$id]);
    ok(['message'=>'Updated.']);

// ─── CHANGE OWN PASSWORD ───────────────────────────────────────
case 'change_password':
    $cur=$input['current']??'';
    $new=$input['new']??'';
    if(!$cur||!$new) fail('Both fields required.');
    if(strlen($new)<8) fail('New password must be at least 8 characters.');
    $stmt=$db->prepare('SELECT password_hash FROM admins WHERE id=?');$stmt->execute([$_SESSION['admin_id']]);
    $row=$stmt->fetch();
    if(!$row||!password_verify($cur,$row['password_hash'])) fail('Current password incorrect.');
    $db->prepare('UPDATE admins SET password_hash=? WHERE id=?')->execute([password_hash($new,PASSWORD_BCRYPT),$_SESSION['admin_id']]);
    ok(['message'=>'Password changed.']);

// ─── GET DISPATCH ──────────────────────────────────────────────
case 'get_dispatch':
    $id=(int)($input['id']??0);
    if(!$id) fail('order_id required');
    $pchk=$db->prepare('SELECT plan FROM orders WHERE id=?');$pchk->execute([$id]);
    $prow=$pchk->fetch();
    if(!$prow) fail('Order not found',404);
    if($prow['plan']==='basic') fail('Dispatch is not available for Basic plan.');
    $stmt=$db->prepare('SELECT * FROM dispatch WHERE order_id=?');$stmt->execute([$id]);
    ok(['dispatch'=>$stmt->fetch()?:null,'plan'=>$prow['plan']]);

// ─── SAVE DISPATCH ─────────────────────────────────────────────
case 'save_dispatch':
    $id=(int)($input['id']??0);
    if(!$id) fail('order_id required');
    $pchk=$db->prepare('SELECT plan FROM orders WHERE id=?');$pchk->execute([$id]);
    $prow=$pchk->fetch();
    if(!$prow) fail('Order not found',404);
    if($prow['plan']==='basic') fail('Dispatch is not available for Basic plan.');

    $tracking  = htmlspecialchars(trim($input['tracking_number']??''),ENT_QUOTES,'UTF-8');
    $dispDT    = $input['dispatch_datetime']??null;
    $notes     = htmlspecialchars(trim($input['admin_notes']??''),ENT_QUOTES,'UTF-8');
    $markDisp  = !empty($input['mark_dispatched']) ? 1 : 0;

    $chk=$db->prepare('SELECT id,dispatched_at FROM dispatch WHERE order_id=?');$chk->execute([$id]);
    $existing=$chk->fetch();
    $dispAt = $markDisp ? ($existing&&$existing['dispatched_at'] ? $existing['dispatched_at'] : date('Y-m-d H:i:s')) : null;

    if($existing){
        $db->prepare('UPDATE dispatch SET tracking_number=?,dispatch_datetime=?,admin_notes=?,dispatched=?,dispatched_at=? WHERE order_id=?')
           ->execute([$tracking,$dispDT,$notes,$markDisp,$dispAt,$id]);
    } else {
        $db->prepare('INSERT INTO dispatch (order_id,tracking_number,dispatch_datetime,admin_notes,dispatched,dispatched_at) VALUES (?,?,?,?,?,?)')
           ->execute([$id,$tracking,$dispDT,$notes,$markDisp,$dispAt]);
    }
    if($markDisp){
        $db->prepare('UPDATE orders SET status="completed" WHERE id=? AND status IN ("submitted","under_review")')->execute([$id]);
    }
    $db->prepare('INSERT INTO payment_log (order_id,event,payload) VALUES (?,?,?)')
       ->execute([$id,$markDisp?'dispatched':'dispatch_updated',
           json_encode(['tracking'=>$tracking,'by'=>$_SESSION['admin_user']??'admin','dispatched'=>(bool)$markDisp])]);
    ok(['message'=>$markDisp?'Order marked as dispatched.':'Dispatch details saved.']);

// ─── LOGOUT ────────────────────────────────────────────────────
case 'logout':
    session_destroy();
    ok(['message'=>'Logged out.']);

default:
    fail('Unknown action.',404);
}
