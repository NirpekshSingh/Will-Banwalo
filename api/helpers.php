<?php
// =============================================
// api/helpers.php — Shared utilities
// =============================================
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';

// ── CORS / JSON headers ───────────────────────
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: ' . APP_URL);
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-Requested-With');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }

// ── Response helpers ─────────────────────────
function jsonSuccess(array $data = [], int $code = 200): never {
    http_response_code($code);
    echo json_encode(['success' => true] + $data);
    exit;
}
function jsonError(string $message, int $code = 400, array $extra = []): never {
    http_response_code($code);
    echo json_encode(['success' => false, 'error' => $message] + $extra);
    exit;
}

// ── Input helpers ────────────────────────────
function getPost(): array {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);
    return is_array($data) ? $data : $_POST;
}
function sanitize(mixed $v): string {
    return htmlspecialchars(trim((string)$v), ENT_QUOTES, 'UTF-8');
}

// ── Order ref generator ──────────────────────
function generateOrderRef(): string {
    $year   = date('Y');
    $random = mt_rand(1000, 9999);
    return "LW-{$year}-{$random}-BNW";
}

// ── Plan pricing ─────────────────────────────
function planPrice(string $plan): float {
    return match ($plan) {
        'basic'   => 999.00,
        'premium' => 9999.00,
        default   => 4499.00,  // standard
    };
}
