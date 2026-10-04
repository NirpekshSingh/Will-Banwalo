<?php
// admin/auth.php — include at top of every protected admin page
if (session_status() === PHP_SESSION_NONE) session_start();
if (empty($_SESSION['admin_id'])) {
    header('Location: login.php'); exit;
}
require_once __DIR__ . '/../config/db.php';
$adminName = $_SESSION['admin_name'] ?? 'Admin';
$adminUser = $_SESSION['admin_user'] ?? '';
$adminRole = $_SESSION['admin_role'] ?? 'staff'; // default to least privilege
$isAdmin   = ($adminRole === 'admin');
