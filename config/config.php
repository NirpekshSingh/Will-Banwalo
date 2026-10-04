<?php
// =============================================
// config/config.php — Central configuration
// =============================================

// ── Database ──────────────────────────────────
define('DB_HOST', 'localhost');
define('DB_NAME', 'willbanwalo');
define('DB_USER', 'root');          // ← change
define('DB_PASS', '');      // ← change
define('DB_CHARSET', 'utf8mb4');

// ── Razorpay ──────────────────────────────────
define('RAZORPAY_KEY_ID',     'rzp_test_SipVyqH86vauXJ');   // ← change
define('RAZORPAY_KEY_SECRET', 'iuhZ8yn2M3Xx5nAvL8GeIvMr');    // ← change
// For production change rzp_test_ → rzp_live_

// ── App ───────────────────────────────────────
define('APP_URL',    'http://localhost/willbanwalo');           // ← change (no trailing slash)
define('UPLOAD_DIR', __DIR__ . '/../uploads/');           // writable folder
define('MAX_FILE_MB', 5);

// ── Timezone ──────────────────────────────────
date_default_timezone_set('Asia/Kolkata');

// ── Error handling (set false in production) ──
define('DEBUG_MODE', true);

if (DEBUG_MODE) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}
