<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';

// Already logged in
if (!empty($_SESSION['admin_id'])) {
    header('Location: index.php'); exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username && $password) {
        $db   = getDB();
        $stmt = $db->prepare('SELECT id, username, password_hash, full_name, role FROM admins WHERE username = ? AND is_active = 1');
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id']   = $admin['id'];
            $_SESSION['admin_name'] = $admin['full_name'];
            $_SESSION['admin_user'] = $admin['username'];
            $_SESSION['admin_role'] = $admin['role'];  // 'admin' or 'staff'
            // Log login
            $db->prepare('UPDATE admins SET last_login = NOW() WHERE id = ?')->execute([$admin['id']]);
            header('Location: index.php'); exit;
        } else {
            $error = 'Invalid username or password.';
        }
    } else {
        $error = 'Please enter both username and password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login — Will Banwalo</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --bg:#0a0f0d;--panel:#111a14;--border:#1e2e22;
  --accent:#4ade80;--accent2:#22c55e;
  --text:#f0f7f2;--muted:#7a9b82;
  --error:#f87171;--radius:14px;
}
body{background:var(--bg);color:var(--text);font-family:'DM Sans',sans-serif;min-height:100vh;display:flex;align-items:center;justify-content:center;overflow:hidden;}
.bg-pattern{position:fixed;inset:0;background:radial-gradient(ellipse 80% 80% at 50% -20%,rgba(74,222,128,.08),transparent),radial-gradient(ellipse 60% 60% at 80% 80%,rgba(34,197,94,.05),transparent);pointer-events:none;}
.card{background:var(--panel);border:1px solid var(--border);border-radius:24px;padding:52px 48px;width:100%;max-width:440px;position:relative;animation:slideUp .5s cubic-bezier(.16,1,.3,1);}
@keyframes slideUp{from{opacity:0;transform:translateY(24px)}to{opacity:1;transform:translateY(0)}}
.logo{display:flex;align-items:center;gap:10px;margin-bottom:36px;}
.logo-mark{width:40px;height:40px;background:linear-gradient(135deg,var(--accent2),var(--accent));border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:18px;font-weight:700;color:#0a0f0d;}
.logo-text{font-family:'DM Serif Display',serif;font-size:20px;color:var(--text);}
.logo-sub{font-size:11px;color:var(--muted);letter-spacing:.08em;text-transform:uppercase;margin-top:2px;}
h1{font-family:'DM Serif Display',serif;font-size:28px;color:var(--text);margin-bottom:6px;}
.subtitle{color:var(--muted);font-size:14px;margin-bottom:36px;}
label{display:block;font-size:12px;font-weight:600;letter-spacing:.06em;text-transform:uppercase;color:var(--muted);margin-bottom:8px;}
input{width:100%;background:#0d1810;border:1px solid var(--border);border-radius:var(--radius);padding:14px 16px;color:var(--text);font-family:'DM Sans',sans-serif;font-size:15px;outline:none;transition:border .2s;}
input:focus{border-color:var(--accent);}
.field{margin-bottom:20px;}
.error-box{background:rgba(248,113,113,.1);border:1px solid rgba(248,113,113,.3);border-radius:10px;padding:12px 16px;color:var(--error);font-size:13px;margin-bottom:20px;}
.btn{width:100%;background:linear-gradient(135deg,var(--accent2),var(--accent));color:#0a0f0d;border:none;border-radius:var(--radius);padding:16px;font-family:'DM Sans',sans-serif;font-size:15px;font-weight:700;cursor:pointer;transition:opacity .2s,transform .1s;margin-top:8px;}
.btn:hover{opacity:.9;}
.btn:active{transform:scale(.98);}
.hint{text-align:center;font-size:12px;color:var(--muted);margin-top:24px;}
</style>
</head>
<body>
<div class="bg-pattern"></div>
<div class="card">
  <div class="logo">
    <div class="logo-mark">W</div>
    <div>
      <div class="logo-text">Will Banwalo</div>
      <div class="logo-sub">Admin Panel</div>
    </div>
  </div>
  <h1>Welcome back</h1>
  <p class="subtitle">Sign in to access the dashboard</p>

  <?php if ($error): ?>
  <div class="error-box"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="POST">
    <div class="field">
      <label>Username</label>
      <input type="text" name="username" autocomplete="username" autofocus value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
    </div>
    <div class="field">
      <label>Password</label>
      <input type="password" name="password" autocomplete="current-password">
    </div>
    <button type="submit" class="btn">Sign In →</button>
  </form>
  <p class="hint">Protected area — authorised personnel only</p>
</div>
</body>
</html>
