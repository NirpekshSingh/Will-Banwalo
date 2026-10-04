<?php
/**
 * send_confirmation_email.php  — Will Banwalo
 *
 * Sends a branded payment-confirmation email to the customer
 * immediately after a successful Razorpay payment verification.
 *
 * USAGE (call this from api/verify_payment.php after marking payment as paid):
 *
 *   require_once __DIR__ . '/../send_confirmation_email.php';
 *   send_will_confirmation_email($pdo, $order_id);
 *
 * REQUIREMENTS:
 *   - PHP mail() configured, OR swap the mailer section for PHPMailer/SMTP.
 *   - PHPMailer is strongly recommended for production (install via Composer).
 *     To use PHPMailer, uncomment the PHPMailer block and comment out mail().
 *
 * COMPOSER (PHPMailer):
 *   composer require phpmailer/phpmailer
 */

// ── Config ────────────────────────────────────────────────────────────────
define('SITE_URL',        'http://localhost/willbanwalo');          // ← change 'https://www.willbanwalo.com'
define('FROM_EMAIL',      'nirpekshguy@gmail.com');              // ← change noreply@willbanwalo.com
define('FROM_NAME',       'Will Banwalo');
define('SUPPORT_EMAIL',   'support@willbanwalo.com');              // ← change
define('SUPPORT_PHONE',   '+91-7709690578');                       // ← change
define('WHATSAPP_LINK',   'https://wa.me/917709690578');           // ← change

// ── Main function ─────────────────────────────────────────────────────────
function send_will_confirmation_email(PDO $pdo, int $order_id): bool
{
    // ── 1. Fetch all data ────────────────────────────────────────────────
    $order = fetch_one($pdo,
        'SELECT o.order_ref, o.will_type, o.plan, o.plan_amount, o.gst_amount,
                o.total_amount, o.razorpay_payment_id, o.created_at
         FROM orders o
         WHERE o.id = ?',
        [$order_id]
    );
    if (!$order) return false;

    $person = fetch_one($pdo,
        'SELECT full_name, email, mobile, dob, city, state,
                executor_name, executor_phone
         FROM personal_details WHERE order_id = ?',
        [$order_id]
    );
    if (!$person || empty($person['email'])) return false;

    $beneficiaries = fetch_all($pdo,
        'SELECT full_name, relationship, share_pct FROM beneficiaries WHERE order_id = ? ORDER BY id ASC',
        [$order_id]
    );

    $assets = fetch_all($pdo,
        'SELECT asset_type, description, estimated_value FROM assets WHERE order_id = ? ORDER BY id ASC',
        [$order_id]
    );

    $special = fetch_one($pdo,
        'SELECT funeral_preference, organ_donation, funeral_notes, charity_notes, other_instructions
         FROM special_instructions WHERE order_id = ?',
        [$order_id]
    );

    // ── 2. Build values ───────────────────────────────────────────────────
    $order_ref   = $order['order_ref'];
    $plan_label  = ucfirst($order['plan']);
    $will_type   = $order['will_type'];
    $name        = $person['full_name'];
    $email_to    = $person['email'];
    $mobile      = $person['mobile'];
    $location    = implode(', ', array_filter([$person['city'], $person['state']]));
    $executor    = $person['executor_name']
                   ? $person['executor_name'] . ($person['executor_phone'] ? ' · ' . $person['executor_phone'] : '')
                   : null;

    $plan_amt    = fmt_inr($order['plan_amount']);
    $gst_amt     = fmt_inr($order['gst_amount']);
    $total_amt   = fmt_inr($order['total_amount']);
    $payment_id  = $order['razorpay_payment_id'] ?: '—';
    $order_date  = date('d M Y, h:i A', strtotime($order['created_at']));

    $track_url   = SITE_URL . '/track_order.html?ref=' . urlencode($order_ref);

    // Steps included by plan
    $plan_features = [
        'basic'    => ['Digital will document (PDF)', 'Email delivery', 'Legal review by advocate'],
        'standard' => ['Digital will document (PDF)', 'Email delivery', 'Legal review by advocate', 'Printed & notarised hard copy', 'Courier delivery to your address'],
        'premium'  => ['Digital will document (PDF)', 'Email delivery', 'Senior legal review', 'Printed & notarised hard copy', 'Priority courier delivery', 'Dedicated relationship manager', 'Lifetime storage & updates'],
    ];
    $features = $plan_features[$order['plan']] ?? $plan_features['basic'];

    // Status badge colour (inline for email)
    $plan_colors = [
        'basic'    => ['bg' => '#e2e3de', 'text' => '#414941'],
        'standard' => ['bg' => '#d3e8d3', 'text' => '#08391b'],
        'premium'  => ['bg' => '#bcefc3', 'text' => '#08391b'],
    ];
    $pc = $plan_colors[$order['plan']] ?? $plan_colors['basic'];

    // ── 3. Build HTML ─────────────────────────────────────────────────────
    $html = build_email_html(compact(
        'name', 'order_ref', 'will_type', 'plan_label',
        'plan_amt', 'gst_amt', 'total_amt', 'payment_id', 'order_date',
        'track_url', 'features', 'pc', 'mobile', 'location', 'executor',
        'beneficiaries', 'assets', 'special'
    ));

    // ── 4. Build plain-text fallback ──────────────────────────────────────
    $text = build_email_text(compact(
        'name', 'order_ref', 'will_type', 'plan_label',
        'total_amt', 'payment_id', 'order_date', 'track_url', 'features',
        'beneficiaries', 'assets'
    ));

    // ── 5. Send ───────────────────────────────────────────────────────────
    $boundary = '----=_Part_' . md5(uniqid());
    $subject  = 'Your Will is Confirmed — Order #' . $order_ref . ' | Will Banwalo';

    $headers  = "From: " . FROM_NAME . " <" . FROM_EMAIL . ">\r\n";
    $headers .= "Reply-To: " . SUPPORT_EMAIL . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: multipart/alternative; boundary=\"{$boundary}\"\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";

    $body  = "--{$boundary}\r\n";
    $body .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $body .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
    $body .= $text . "\r\n\r\n";
    $body .= "--{$boundary}\r\n";
    $body .= "Content-Type: text/html; charset=UTF-8\r\n";
    $body .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
    $body .= $html . "\r\n\r\n";
    $body .= "--{$boundary}--";

    // ── PHP mail() ──────────────────────────────────────────────────────
    $sent = mail($email_to, $subject, $body, $headers);

    /* ── PHPMailer (recommended for production — uncomment to use) ──────
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    require_once __DIR__ . '/vendor/autoload.php';

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.yourprovider.com';   // e.g. smtp.gmail.com
        $mail->SMTPAuth   = true;
        $mail->Username   = 'your@email.com';
        $mail->Password   = 'your_app_password';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom(FROM_EMAIL, FROM_NAME);
        $mail->addReplyTo(SUPPORT_EMAIL, FROM_NAME);
        $mail->addAddress($email_to, $name);
        $mail->Subject    = $subject;
        $mail->isHTML(true);
        $mail->Body       = $html;
        $mail->AltBody    = $text;
        $mail->send();
        $sent = true;
    } catch (\Exception $e) {
        error_log('PHPMailer error: ' . $mail->ErrorInfo);
        $sent = false;
    }
    ── end PHPMailer ──────────────────────────────────────────────────── */

    return $sent;
}

// ── DB helpers ────────────────────────────────────────────────────────────
function fetch_one(PDO $pdo, string $sql, array $params): ?array
{
    $s = $pdo->prepare($sql); $s->execute($params);
    return $s->fetch(PDO::FETCH_ASSOC) ?: null;
}
function fetch_all(PDO $pdo, string $sql, array $params): array
{
    $s = $pdo->prepare($sql); $s->execute($params);
    return $s->fetchAll(PDO::FETCH_ASSOC);
}
function fmt_inr(float $v): string
{
    return '₹' . number_format($v, 2, '.', ',');
}
function e(string $s): string { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
function asset_label(string $type): string
{
    return [
        'real_estate' => 'Real Estate', 'bank' => 'Bank Account',
        'investments' => 'Investments',  'vehicles' => 'Vehicle',
        'jewellery'   => 'Jewellery',    'digital' => 'Digital Asset',
        'business'    => 'Business',     'other' => 'Other',
    ][$type] ?? ucfirst(str_replace('_', ' ', $type));
}

// ── HTML email builder ────────────────────────────────────────────────────
function build_email_html(array $d): string
{
    $features_html = '';
    foreach ($d['features'] as $f) {
        $features_html .= '<tr><td style="padding:6px 0;color:#374151;font-size:14px;font-family:\'Plus Jakarta Sans\',Arial,sans-serif;">
            <span style="display:inline-block;width:20px;height:20px;background:#d3e8d3;border-radius:50%;text-align:center;line-height:20px;font-size:11px;color:#08391b;font-weight:700;margin-right:10px;">✓</span>'
            . e($f) . '</td></tr>';
    }

    $bene_html = '';
    if (!empty($d['beneficiaries'])) {
        $bene_html = '<tr><td style="padding-top:24px;">
            <p style="margin:0 0 10px;font-size:12px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#9ca3af;font-family:\'Plus Jakarta Sans\',Arial,sans-serif;">Beneficiaries</p>
            <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">';
        foreach ($d['beneficiaries'] as $b) {
            $share = $b['share_pct'] > 0 ? e($b['share_pct']).'%' : '—';
            $bene_html .= '<tr>
                <td style="padding:8px 12px;font-size:14px;color:#1f2937;font-family:\'Plus Jakarta Sans\',Arial,sans-serif;border-bottom:1px solid #f3f4ef;">'.e($b['full_name']).'</td>
                <td style="padding:8px 12px;font-size:13px;color:#6b7280;font-family:\'Plus Jakarta Sans\',Arial,sans-serif;border-bottom:1px solid #f3f4ef;">'.e($b['relationship']).'</td>
                <td style="padding:8px 12px;font-size:14px;color:#08391b;font-weight:700;text-align:right;font-family:\'Plus Jakarta Sans\',Arial,sans-serif;border-bottom:1px solid #f3f4ef;">'.$share.'</td>
            </tr>';
        }
        $bene_html .= '</table></td></tr>';
    }

    $assets_html = '';
    if (!empty($d['assets'])) {
        $assets_html = '<tr><td style="padding-top:24px;">
            <p style="margin:0 0 10px;font-size:12px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#9ca3af;font-family:\'Plus Jakarta Sans\',Arial,sans-serif;">Assets Listed</p>
            <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">';
        foreach ($d['assets'] as $a) {
            $val = $a['estimated_value'] ? fmt_inr((float)$a['estimated_value']) : '—';
            $assets_html .= '<tr>
                <td style="padding:8px 12px;font-size:13px;font-family:\'Plus Jakarta Sans\',Arial,sans-serif;border-bottom:1px solid #f3f4ef;">
                    <span style="display:inline-block;padding:2px 8px;background:#f3f4ef;border-radius:4px;font-size:11px;font-weight:700;color:#374151;">'.e(asset_label($a['asset_type'])).'</span>
                </td>
                <td style="padding:8px 12px;font-size:14px;color:#374151;font-family:\'Plus Jakarta Sans\',Arial,sans-serif;border-bottom:1px solid #f3f4ef;">'.e($a['description']).'</td>
                <td style="padding:8px 12px;font-size:14px;color:#08391b;font-weight:700;text-align:right;font-family:\'Plus Jakarta Sans\',Arial,sans-serif;border-bottom:1px solid #f3f4ef;">'.$val.'</td>
            </tr>';
        }
        $assets_html .= '</table></td></tr>';
    }

    $special_html = '';
    $si = $d['special'] ?? null;
    if ($si) {
        $lines = [];
        if (!empty($si['funeral_preference'])) $lines[] = ['Funeral Preference', $si['funeral_preference']];
        if (isset($si['organ_donation']))        $lines[] = ['Organ Donation', $si['organ_donation'] ? 'Yes' : 'No'];
        if (!empty($si['funeral_notes']))        $lines[] = ['Funeral Notes', $si['funeral_notes']];
        if (!empty($si['charity_notes']))        $lines[] = ['Charity / Donations', $si['charity_notes']];
        if (!empty($si['other_instructions']))   $lines[] = ['Other Instructions', $si['other_instructions']];

        if ($lines) {
            $special_html = '<tr><td style="padding-top:24px;">
                <p style="margin:0 0 10px;font-size:12px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#9ca3af;font-family:\'Plus Jakarta Sans\',Arial,sans-serif;">Special Instructions</p>';
            foreach ($lines as [$label, $val]) {
                $special_html .= '<p style="margin:4px 0;font-size:14px;color:#374151;font-family:\'Plus Jakarta Sans\',Arial,sans-serif;">
                    <strong style="color:#08391b;">'.e($label).':</strong> '.e($val).'</p>';
            }
            $special_html .= '</td></tr>';
        }
    }

    $executor_html = $d['executor']
        ? '<tr><td width="50%" style="padding:8px 0;"><p style="margin:0;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#9ca3af;font-family:\'Plus Jakarta Sans\',Arial,sans-serif;">Executor</p>
               <p style="margin:4px 0 0;font-size:14px;font-weight:600;color:#111827;font-family:\'Plus Jakarta Sans\',Arial,sans-serif;">'.e($d['executor']).'</p></td></tr>'
        : '';

    $track_url = e($d['track_url']);
    $site      = e(SITE_URL);
    $support   = e(SUPPORT_EMAIL);
    $whatsapp  = e(WHATSAPP_LINK);

ob_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="color-scheme" content="light">
  <title>Order Confirmed — Will Banwalo</title>
</head>
<body style="margin:0;padding:0;background-color:#f1f5f0;font-family:'Plus Jakarta Sans',Arial,sans-serif;-webkit-font-smoothing:antialiased;">

<!-- Preheader (hidden) -->
<div style="display:none;max-height:0;overflow:hidden;mso-hide:all;">
Your Will is confirmed! Order <?= e($d['order_ref']) ?> · <?= e($d['plan_label']) ?> Plan · <?= e($d['total_amt']) ?> paid. Track your order anytime.
</div>

<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f1f5f0;padding:40px 16px;">
<tr><td align="center">
<table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;">

  <!-- ── LOGO HEADER ────────────────────────────────────────────── -->
  <tr>
    <td style="background-color:#08391b;border-radius:16px 16px 0 0;padding:32px 40px;text-align:center;">
      <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
          <td align="center">
            <!-- Logo mark -->
            <div style="display:inline-block;background:rgba(255,255,255,.12);border-radius:12px;padding:10px 14px;margin-bottom:16px;">
              <span style="font-size:24px;">⚖️</span>
            </div>
            <h1 style="margin:0;font-size:26px;font-weight:700;color:#ffffff;letter-spacing:-.5px;font-family:'Playfair Display',Georgia,serif;">Will Banwalo</h1>
            <p style="margin:6px 0 0;font-size:13px;color:rgba(255,255,255,.55);letter-spacing:.08em;text-transform:uppercase;font-family:'Plus Jakarta Sans',Arial,sans-serif;">Your Legacy, Secured.</p>
          </td>
        </tr>
      </table>
    </td>
  </tr>

  <!-- ── SUCCESS BANNER ─────────────────────────────────────────── -->
  <tr>
    <td style="background-color:#ffffff;padding:40px 40px 28px;text-align:center;border-bottom:2px solid #f1f5f0;">
      <div style="display:inline-block;background:#d3e8d3;border-radius:50%;width:64px;height:64px;line-height:64px;font-size:28px;text-align:center;margin-bottom:20px;">✓</div>
      <h2 style="margin:0 0 8px;font-size:26px;font-weight:700;color:#08391b;font-family:'Playfair Display',Georgia,serif;">Payment Successful!</h2>
      <p style="margin:0 0 20px;font-size:16px;color:#4b5563;line-height:1.6;font-family:'Plus Jakarta Sans',Arial,sans-serif;">Dear <strong style="color:#08391b;"><?= e($d['name']) ?></strong>, your will order has been received and confirmed. Our legal team will begin processing it shortly.</p>
      <!-- Order Ref chip -->
      <div style="display:inline-block;background:#f1f5f0;border:1.5px solid #c1c9bf;border-radius:50px;padding:10px 24px;">
        <span style="font-size:11px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#6b7280;font-family:'Plus Jakarta Sans',Arial,sans-serif;">Order Reference</span><br>
        <span style="font-size:20px;font-weight:700;color:#08391b;letter-spacing:.06em;font-family:'Plus Jakarta Sans',Arial,sans-serif;"><?= e($d['order_ref']) ?></span>
      </div>
      <p style="margin:12px 0 0;font-size:12px;color:#9ca3af;font-family:'Plus Jakarta Sans',Arial,sans-serif;">Save this reference number — you'll need it to track your order.</p>
    </td>
  </tr>

  <!-- ── TRACK CTA ──────────────────────────────────────────────── -->
  <tr>
    <td style="background-color:#08391b;padding:24px 40px;text-align:center;">
      <p style="margin:0 0 14px;font-size:13px;color:rgba(255,255,255,.65);font-family:'Plus Jakarta Sans',Arial,sans-serif;">Track your order status, download your documents &amp; more</p>
      <a href="<?= $track_url ?>" style="display:inline-block;background:#d4af37;color:#08391b;font-size:14px;font-weight:700;padding:14px 32px;border-radius:50px;text-decoration:none;letter-spacing:.02em;font-family:'Plus Jakarta Sans',Arial,sans-serif;">→ Track My Order</a>
    </td>
  </tr>

  <!-- ── ORDER DETAILS ──────────────────────────────────────────── -->
  <tr>
    <td style="background-color:#ffffff;padding:36px 40px;">
      <p style="margin:0 0 20px;font-size:12px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#9ca3af;font-family:'Plus Jakarta Sans',Arial,sans-serif;">Order Details</p>
      <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
          <td width="50%" style="padding:8px 0;vertical-align:top;">
            <p style="margin:0;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#9ca3af;font-family:'Plus Jakarta Sans',Arial,sans-serif;">Will Type</p>
            <p style="margin:4px 0 0;font-size:15px;font-weight:600;color:#111827;font-family:'Plus Jakarta Sans',Arial,sans-serif;"><?= e($d['will_type']) ?></p>
          </td>
          <td width="50%" style="padding:8px 0;vertical-align:top;">
            <p style="margin:0;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#9ca3af;font-family:'Plus Jakarta Sans',Arial,sans-serif;">Plan</p>
            <p style="margin:4px 0 0;">
              <span style="display:inline-block;padding:3px 12px;border-radius:50px;font-size:12px;font-weight:700;background:<?= e($d['pc']['bg']) ?>;color:<?= e($d['pc']['text']) ?>;font-family:'Plus Jakarta Sans',Arial,sans-serif;"><?= e($d['plan_label']) ?></span>
            </p>
          </td>
        </tr>
        <tr>
          <td width="50%" style="padding:8px 0;vertical-align:top;">
            <p style="margin:0;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#9ca3af;font-family:'Plus Jakarta Sans',Arial,sans-serif;">Order Date</p>
            <p style="margin:4px 0 0;font-size:14px;font-weight:600;color:#111827;font-family:'Plus Jakarta Sans',Arial,sans-serif;"><?= e($d['order_date']) ?></p>
          </td>
          <td width="50%" style="padding:8px 0;vertical-align:top;">
            <p style="margin:0;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#9ca3af;font-family:'Plus Jakarta Sans',Arial,sans-serif;">Payment ID</p>
            <p style="margin:4px 0 0;font-size:13px;font-weight:600;color:#374151;font-family:'Plus Jakarta Sans',Arial,sans-serif;word-break:break-all;"><?= e($d['payment_id']) ?></p>
          </td>
        </tr>
        <?php if($d['location']): ?>
        <tr>
          <td width="50%" style="padding:8px 0;vertical-align:top;">
            <p style="margin:0;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#9ca3af;font-family:'Plus Jakarta Sans',Arial,sans-serif;">Location</p>
            <p style="margin:4px 0 0;font-size:14px;font-weight:600;color:#111827;font-family:'Plus Jakarta Sans',Arial,sans-serif;"><?= e($d['location']) ?></p>
          </td>
          <td width="50%" style="padding:8px 0;vertical-align:top;"></td>
        </tr>
        <?php endif; ?>
        <?= $executor_html ?>
      </table>

      <!-- Divider -->
      <div style="height:1px;background:#f1f5f0;margin:20px 0;"></div>

      <!-- Pricing breakdown -->
      <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
          <td style="padding:6px 0;font-size:14px;color:#6b7280;font-family:'Plus Jakarta Sans',Arial,sans-serif;">Plan Amount</td>
          <td style="padding:6px 0;font-size:14px;color:#374151;text-align:right;font-family:'Plus Jakarta Sans',Arial,sans-serif;"><?= e($d['plan_amt']) ?></td>
        </tr>
        <tr>
          <td style="padding:6px 0;font-size:14px;color:#6b7280;font-family:'Plus Jakarta Sans',Arial,sans-serif;">GST (18%)</td>
          <td style="padding:6px 0;font-size:14px;color:#374151;text-align:right;font-family:'Plus Jakarta Sans',Arial,sans-serif;"><?= e($d['gst_amt']) ?></td>
        </tr>
        <tr>
          <td style="padding:10px 0 6px;font-size:15px;font-weight:700;color:#08391b;border-top:1.5px solid #f1f5f0;font-family:'Plus Jakarta Sans',Arial,sans-serif;">Total Paid</td>
          <td style="padding:10px 0 6px;font-size:18px;font-weight:700;color:#08391b;text-align:right;border-top:1.5px solid #f1f5f0;font-family:'Plus Jakarta Sans',Arial,sans-serif;"><?= e($d['total_amt']) ?></td>
        </tr>
      </table>
    </td>
  </tr>

  <!-- ── PLAN INCLUDES ──────────────────────────────────────────── -->
  <tr>
    <td style="background-color:#f8faf5;padding:28px 40px;border-top:2px solid #f1f5f0;border-bottom:2px solid #f1f5f0;">
      <p style="margin:0 0 16px;font-size:12px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#9ca3af;font-family:'Plus Jakarta Sans',Arial,sans-serif;">Your <?= e($d['plan_label']) ?> Plan Includes</p>
      <table width="100%" cellpadding="0" cellspacing="0">
        <?= $features_html ?>
      </table>
    </td>
  </tr>

  <!-- ── WILL SUMMARY (benes + assets + special) ───────────────── -->
  <?php if ($bene_html || $assets_html || $special_html): ?>
  <tr>
    <td style="background-color:#ffffff;padding:28px 40px;border-bottom:2px solid #f1f5f0;">
      <p style="margin:0 0 4px;font-size:12px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#9ca3af;font-family:'Plus Jakarta Sans',Arial,sans-serif;">Will Summary</p>
      <p style="margin:0 0 16px;font-size:12px;color:#9ca3af;font-family:'Plus Jakarta Sans',Arial,sans-serif;">A summary of the information you provided. Keep this for your records.</p>
      <table width="100%" cellpadding="0" cellspacing="0">
        <?= $bene_html ?>
        <?= $assets_html ?>
        <?= $special_html ?>
      </table>
    </td>
  </tr>
  <?php endif; ?>

  <!-- ── WHAT HAPPENS NEXT ──────────────────────────────────────── -->
  <tr>
    <td style="background-color:#f8faf5;padding:32px 40px;">
      <p style="margin:0 0 20px;font-size:12px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#9ca3af;font-family:'Plus Jakarta Sans',Arial,sans-serif;">What Happens Next</p>
      <table width="100%" cellpadding="0" cellspacing="0">
        <?php
        $steps = [
            ['🔍', 'Legal Review',        'Our qualified advocate reviews your will details within 1–2 business days.'],
            ['📄', 'Will Drafting',        'Your customised legal will document is prepared and formatted.'],
            ['✅', 'Final Will Ready',     'You\'ll receive your final will via email and can download it from your order page.'],
            ['📦', 'Physical Delivery',    'Standard & Premium plans: notarised hard copy dispatched by courier.'],
        ];
        foreach ($steps as $i => [$icon, $title, $desc]):
        ?>
        <tr>
          <td style="vertical-align:top;padding:0 0 20px;width:44px;">
            <div style="width:36px;height:36px;background:#e8f0e8;border-radius:50%;text-align:center;line-height:36px;font-size:16px;"><?= $icon ?></div>
          </td>
          <td style="vertical-align:top;padding:4px 0 20px 12px;">
            <p style="margin:0 0 3px;font-size:14px;font-weight:700;color:#08391b;font-family:'Plus Jakarta Sans',Arial,sans-serif;"><?= e($title) ?></p>
            <p style="margin:0;font-size:13px;color:#6b7280;line-height:1.55;font-family:'Plus Jakarta Sans',Arial,sans-serif;"><?= e($desc) ?></p>
          </td>
        </tr>
        <?php endforeach; ?>
      </table>
    </td>
  </tr>

  <!-- ── TRACK BUTTON (repeat) ─────────────────────────────────── -->
  <tr>
    <td style="background-color:#ffffff;padding:28px 40px;text-align:center;border-top:2px solid #f1f5f0;">
      <p style="margin:0 0 14px;font-size:14px;color:#6b7280;font-family:'Plus Jakarta Sans',Arial,sans-serif;">Check your order status at any time using your reference number</p>
      <a href="<?= $track_url ?>" style="display:inline-block;background:#08391b;color:#ffffff;font-size:14px;font-weight:700;padding:14px 36px;border-radius:50px;text-decoration:none;letter-spacing:.02em;margin-bottom:10px;font-family:'Plus Jakarta Sans',Arial,sans-serif;">Track Order #<?= e($d['order_ref']) ?></a>
      <p style="margin:8px 0 0;font-size:11px;color:#9ca3af;font-family:'Plus Jakarta Sans',Arial,sans-serif;">Or copy this link: <span style="color:#08391b;"><?= $track_url ?></span></p>
    </td>
  </tr>

  <!-- ── SUPPORT ────────────────────────────────────────────────── -->
  <tr>
    <td style="background-color:#f8faf5;padding:24px 40px;border-top:2px solid #f1f5f0;">
      <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
          <td width="50%" style="vertical-align:top;">
            <p style="margin:0 0 4px;font-size:12px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#9ca3af;font-family:'Plus Jakarta Sans',Arial,sans-serif;">Email Support</p>
            <a href="mailto:<?= $support ?>" style="color:#08391b;font-size:14px;font-weight:600;text-decoration:none;font-family:'Plus Jakarta Sans',Arial,sans-serif;"><?= $support ?></a>
          </td>
          <td width="50%" style="vertical-align:top;">
            <p style="margin:0 0 4px;font-size:12px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#9ca3af;font-family:'Plus Jakarta Sans',Arial,sans-serif;">WhatsApp</p>
            <a href="<?= $whatsapp ?>" style="color:#08391b;font-size:14px;font-weight:600;text-decoration:none;font-family:'Plus Jakarta Sans',Arial,sans-serif;"><?= e(SUPPORT_PHONE) ?></a>
          </td>
        </tr>
      </table>
    </td>
  </tr>

  <!-- ── FOOTER ─────────────────────────────────────────────────── -->
  <tr>
    <td style="background-color:#08391b;border-radius:0 0 16px 16px;padding:28px 40px;text-align:center;">
      <p style="margin:0 0 6px;font-size:15px;font-weight:700;color:#ffffff;font-family:'Playfair Display',Georgia,serif;">Will Banwalo</p>
      <p style="margin:0 0 16px;font-size:12px;color:rgba(255,255,255,.45);font-family:'Plus Jakarta Sans',Arial,sans-serif;">A premium legal technology boutique. Redefining how India plans its future.</p>
      <p style="margin:0;font-size:11px;color:rgba(255,255,255,.30);font-family:'Plus Jakarta Sans',Arial,sans-serif;">
        © <?= date('Y') ?> Will Banwalo · All rights reserved ·
        <a href="<?= $site ?>/privacy.html" style="color:rgba(255,255,255,.45);text-decoration:none;">Privacy Policy</a> ·
        <a href="<?= $site ?>/terms.html"   style="color:rgba(255,255,255,.45);text-decoration:none;">Terms of Use</a>
      </p>
      <p style="margin:10px 0 0;font-size:10px;color:rgba(255,255,255,.20);font-family:'Plus Jakarta Sans',Arial,sans-serif;">
        This is an automated email. Please do not reply directly — use the support email above.
      </p>
    </td>
  </tr>

</table>
</td></tr>
</table>
</body>
</html>
<?php
    return ob_get_clean();
}

// ── Plain-text fallback ───────────────────────────────────────────────────
function build_email_text(array $d): string
{
    $lines = [];
    $lines[] = '================================================';
    $lines[] = 'WILL BANWALO — Payment Confirmation';
    $lines[] = '================================================';
    $lines[] = '';
    $lines[] = 'Dear ' . $d['name'] . ',';
    $lines[] = '';
    $lines[] = 'Your will order has been confirmed and payment received.';
    $lines[] = '';
    $lines[] = 'ORDER REFERENCE : ' . $d['order_ref'];
    $lines[] = 'Will Type       : ' . $d['will_type'];
    $lines[] = 'Plan            : ' . $d['plan_label'];
    $lines[] = 'Total Paid      : ' . $d['total_amt'];
    $lines[] = 'Payment ID      : ' . $d['payment_id'];
    $lines[] = 'Date            : ' . $d['order_date'];
    $lines[] = '';
    $lines[] = '── PLAN INCLUDES ──────────────────────────────';
    foreach ($d['features'] as $f) { $lines[] = '  ✓ ' . $f; }
    $lines[] = '';

    if (!empty($d['beneficiaries'])) {
        $lines[] = '── BENEFICIARIES ──────────────────────────────';
        foreach ($d['beneficiaries'] as $b) {
            $share = $b['share_pct'] > 0 ? $b['share_pct'].'%' : '';
            $lines[] = '  • ' . $b['full_name'] . ' (' . $b['relationship'] . ')' . ($share ? ' — ' . $share : '');
        }
        $lines[] = '';
    }
    if (!empty($d['assets'])) {
        $lines[] = '── ASSETS ─────────────────────────────────────';
        foreach ($d['assets'] as $a) {
            $val = $a['estimated_value'] ? fmt_inr((float)$a['estimated_value']) : '';
            $lines[] = '  • [' . asset_label($a['asset_type']) . '] ' . $a['description'] . ($val ? ' — ' . $val : '');
        }
        $lines[] = '';
    }

    $lines[] = '── TRACK YOUR ORDER ───────────────────────────';
    $lines[] = $d['track_url'];
    $lines[] = '';
    $lines[] = '── SUPPORT ────────────────────────────────────';
    $lines[] = 'Email : ' . SUPPORT_EMAIL;
    $lines[] = 'Phone : ' . SUPPORT_PHONE;
    $lines[] = '';
    $lines[] = '© ' . date('Y') . ' Will Banwalo. All rights reserved.';

    return implode("\r\n", $lines);
}
