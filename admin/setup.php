<?php
// =============================================
// admin/setup.php
// Run ONCE to create the first admin account.
// DELETE THIS FILE after running!
// URL: http://localhost/willbanwalo/admin/setup.php
// =============================================
require_once __DIR__ . '/../config/db.php';

$message = '';
$success = false;

// Block if admin already exists
$db   = getDB();
$chk  = $db->query('SELECT COUNT(*) FROM admins');
$count = $chk ? (int)$chk->fetchColumn() : 0;

if ($count > 0 && empty($_POST['force'])) {
    $message = 'Admin account already exists. Delete this file or use the dashboard to manage admins.';
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username  = trim($_POST['username']  ?? '');
    $password  = $_POST['password']  ?? '';
    $password2 = $_POST['password2'] ?? '';
    $fullname  = trim($_POST['fullname']  ?? '');
    $email     = trim($_POST['email']     ?? '');

    if (!$username || !$password || !$fullname) {
        $message = 'Username, password and full name are required.';
    } elseif (strlen($password) < 8) {
        $message = 'Password must be at least 8 characters.';
    } elseif ($password !== $password2) {
        $message = 'Passwords do not match.';
    } else {
        $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
        try {
            $stmt = $db->prepare('INSERT INTO admins (username, password_hash, full_name, email, role) VALUES (?, ?, ?, ?, "admin")
                ON DUPLICATE KEY UPDATE password_hash=VALUES(password_hash), full_name=VALUES(full_name), email=VALUES(email), role="admin"');
            $stmt->execute([$username, $hash, $fullname, $email]);
            $success = true;
            $message = "Admin '{$username}' created successfully! Please DELETE this file now.";
        } catch (Exception $e) {
            $message = 'Error: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Admin Setup — Will Banwalo</title>
<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@400;600&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{background:#080d0a;color:#edf7ef;font-family:'DM Sans',sans-serif;min-height:100vh;display:flex;align-items:center;justify-content:center;}
.card{background:#101a12;border:1px solid #1a2b1d;border-radius:20px;padding:48px;width:100%;max-width:440px;}
h1{font-family:'DM Serif Display',serif;font-size:26px;margin-bottom:6px;}
.sub{color:#6b8f72;font-size:13px;margin-bottom:32px;}
.warning{background:rgba(251,191,36,.1);border:1px solid rgba(251,191,36,.3);border-radius:10px;padding:12px 16px;color:#fbbf24;font-size:13px;margin-bottom:24px;}
.msg{border-radius:10px;padding:12px 16px;font-size:13px;margin-bottom:20px;}
.msg.error{background:rgba(248,113,113,.1);border:1px solid rgba(248,113,113,.3);color:#f87171;}
.msg.success{background:rgba(74,222,128,.1);border:1px solid rgba(74,222,128,.3);color:#4ade80;}
.field{margin-bottom:18px;}
label{display:block;font-size:11px;font-weight:600;letter-spacing:.06em;text-transform:uppercase;color:#6b8f72;margin-bottom:6px;}
input{width:100%;background:#0d1810;border:1px solid #1a2b1d;border-radius:10px;padding:12px 14px;color:#edf7ef;font-family:'DM Sans',sans-serif;font-size:14px;outline:none;}
input:focus{border-color:#4ade80;}
.btn{width:100%;background:linear-gradient(135deg,#22c55e,#4ade80);color:#080d0a;border:none;border-radius:10px;padding:14px;font-family:'DM Sans',sans-serif;font-size:14px;font-weight:700;cursor:pointer;margin-top:8px;}
.delete-note{margin-top:24px;padding:12px;background:rgba(248,113,113,.08);border:1px solid rgba(248,113,113,.2);border-radius:8px;font-size:12px;color:#f87171;text-align:center;}
</style>
</head>
<body>
<div class="card">
  <h1>Admin Setup</h1>
  <p class="sub">Create the first administrator account</p>
  <div class="warning">⚠️ Run this script only once. Delete it immediately after use.</div>

  <?php if ($message): ?>
  <div class="msg <?= $success ? 'success' : 'error' ?>"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>

  <?php if (!$success): ?>
  <form method="POST">
    <?php if ($count > 0): ?><input type="hidden" name="force" value="1"><?php endif; ?>
    <div class="field"><label>Full Name</label><input type="text" name="fullname" placeholder="Your Full Name" required></div>
    <div class="field"><label>Username</label><input type="text" name="username" placeholder="admin" required autocomplete="off"></div>
    <div class="field"><label>Email</label><input type="email" name="email" placeholder="admin@example.com"></div>
    <div class="field"><label>Password</label><input type="password" name="password" placeholder="min 8 characters" required></div>
    <div class="field"><label>Confirm Password</label><input type="password" name="password2" placeholder="repeat password" required></div>
    <button type="submit" class="btn">Create Admin Account →</button>
  </form>
  <?php else: ?>
  <div class="delete-note">🔴 DELETE this file from your server now:<br><code>willbanwalo/admin/setup.php</code></div>
  <div style="margin-top:16px;text-align:center"><a href="login.php" style="color:#4ade80;font-size:14px">→ Go to Login</a></div>
  <?php endif; ?>
</div>
</body>
</html>
