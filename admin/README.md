# Will Banwalo — Full Deployment Guide
## PHP + MySQL + Razorpay on Hostinger

---

## 📁 File Structure

```
willbanwalo/
├── .htaccess                   ← Security & PHP config
├── order.php                   ← Main frontend (your wired page)
├── config/
│   ├── config.php              ← ⚠️  Edit credentials here
│   └── db.php                  ← PDO database singleton
├── api/
│   ├── helpers.php             ← Shared utilities
│   ├── save_order.php          ← Saves each step's data
│   ├── upload_document.php     ← Handles file uploads
│   ├── create_payment.php      ← Creates Razorpay order
│   ├── verify_payment.php      ← Verifies payment signature
│   └── webhook.php             ← Razorpay server webhook
├── uploads/                    ← ⚠️  Must be chmod 750, NOT public
└── sql/
    └── schema.sql              ← Run this once to create tables
```

---

## STEP 1 — Create MySQL Database on Hostinger

1. Log in to **Hostinger hPanel**
2. Go to **Hosting → Manage → MySQL Databases**
3. Click **Create a New Database**
   - Database name: `willbanwalo`  (Hostinger prefixes it, e.g. `u123456789_willbanwalo`)
   - Username: choose one
   - Password: generate a strong password
4. Click **Create**
5. Note the full **Database name**, **Username**, and **Password**

### Import the schema:
- In hPanel → **phpMyAdmin** → select your new database
- Click **Import** → choose `sql/schema.sql` → click **Go**

---

## STEP 2 — Edit `config/config.php`

Open `config/config.php` and fill in:

```php
define('DB_HOST', 'localhost');          // Always localhost on Hostinger shared
define('DB_NAME', 'u123456789_willbanwalo');  // ← Your full DB name from hPanel
define('DB_USER', 'u123456789_youruser');     // ← Your DB username
define('DB_PASS', 'your_strong_password');    // ← Your DB password

define('RAZORPAY_KEY_ID',     'rzp_test_XXXXXXXXXXXX');  // ← From Razorpay Dashboard
define('RAZORPAY_KEY_SECRET', 'your_secret_here');       // ← From Razorpay Dashboard

define('APP_URL', 'https://yourdomain.com');  // ← Your actual domain (no trailing slash)

define('DEBUG_MODE', false);   // ← Set false in production!
```

---

## STEP 3 — Get Razorpay API Keys

1. Sign up / log in at **https://dashboard.razorpay.com**
2. Go to **Settings → API Keys**
3. Click **Generate Test Key** (use Test mode first!)
4. Copy **Key ID** and **Key Secret** into `config.php`

> **For production:** Generate Live keys, change `rzp_test_` → `rzp_live_`

---

## STEP 4 — Upload Files to Hostinger

### Via Hostinger File Manager:
1. hPanel → **File Manager**
2. Navigate to `public_html/`
3. Upload the entire `willbanwalo/` folder contents directly into `public_html/`
   - OR create a subdirectory: `public_html/order/`

### Via FTP (FileZilla):
- Host: `ftp.yourdomain.com`
- User: your Hostinger FTP user
- Password: your FTP password
- Port: `21`
- Upload all files maintaining the folder structure

---

## STEP 5 — Set Directory Permissions

In Hostinger File Manager or via SSH:

```bash
# uploads/ must be writable by the web server, not publicly browsable
chmod 750 uploads/
chmod 750 config/

# PHP files
chmod 644 api/*.php
chmod 644 config/*.php
chmod 644 order.php
```

The `.htaccess` already blocks direct URL access to `config/` and `uploads/`.

---

## STEP 6 — Configure Razorpay Webhook (Important!)

The webhook is a server-side backup — confirms payment even if the user closes the browser.

1. In **Razorpay Dashboard → Settings → Webhooks**
2. Click **Add New Webhook**
3. URL: `https://yourdomain.com/api/webhook.php`
4. Secret: create a random string (e.g. 32 chars) — paste it into `webhook.php`:
   ```php
   define('WEBHOOK_SECRET', 'your_webhook_secret_here');
   ```
5. Active Events: check ✅ `payment.captured` and ✅ `payment.failed`
6. Click **Save**

---

## STEP 7 — Test the Full Flow

### Test card details (Razorpay test mode):
| Field         | Value                  |
|---------------|------------------------|
| Card Number   | `4111 1111 1111 1111`  |
| Expiry        | Any future date        |
| CVV           | Any 3 digits           |
| OTP           | `1234`                 |

**Test UPI:** `success@razorpay`

### Checklist:
- [ ] Open `https://yourdomain.com/order.php`
- [ ] Step 1: Select a will type → click Next (should create DB record)
- [ ] Step 2: Fill personal details → Next
- [ ] Step 3: Add a beneficiary → Next
- [ ] Step 4: Add an asset → Next
- [ ] Step 5: Select funeral preference → Next
- [ ] Step 6: Upload Aadhaar (JPG/PDF) → check upload works
- [ ] Step 7: Select a plan → click "Proceed to Payment"
- [ ] Razorpay popup opens → pay with test card
- [ ] Redirects to Step 8 (Confirmation) with Order ID
- [ ] Check database: `orders` table shows `payment_status = 'paid'`

---

## STEP 8 — Go Live

1. In Razorpay Dashboard, activate your account (submit KYC)
2. Generate **Live API Keys**
3. Update `config.php` with live keys
4. Set `define('DEBUG_MODE', false);`
5. Enable HTTPS redirect in `.htaccess` (uncomment the RewriteRule lines)

---

## Database Tables Summary

| Table                 | Purpose                              |
|-----------------------|--------------------------------------|
| `orders`              | Master order record + payment status |
| `personal_details`    | Step 2 form data                     |
| `beneficiaries`       | Step 3 beneficiary list              |
| `assets`              | Step 4 asset portfolio               |
| `special_instructions`| Step 5 funeral/charity wishes        |
| `documents`           | Step 6 uploaded file references      |
| `payment_log`         | Full audit trail of payment events   |

---

## Troubleshooting

| Problem | Fix |
|---------|-----|
| "Database connection failed" | Check DB credentials in `config.php`; confirm DB user has SELECT/INSERT/UPDATE/DELETE privileges |
| Razorpay popup doesn't open | Check browser console; verify `RAZORPAY_KEY_ID` is correct; ensure Razorpay SDK loads (internet required) |
| File upload fails | Check `uploads/` directory exists and has write permission (`chmod 750`) |
| 500 error on API calls | Enable `DEBUG_MODE = true` temporarily; check Hostinger error logs in hPanel → Logs |
| Webhook not firing | Ensure your domain has valid SSL; Razorpay requires HTTPS for webhooks |
| Payment verifies but Step 8 doesn't show | Check browser console for JS errors |

---

## Security Notes

- `config/` and `uploads/` are blocked from direct browser access via `.htaccess`
- All user input is sanitised with `htmlspecialchars()` and prepared statements (SQL injection safe)
- Razorpay signature is verified server-side with HMAC-SHA256 before marking payment as complete
- Uploaded files are stored with random UUID names; original names are recorded in DB only
- File types are validated by MIME type (not just extension)

---

## 🔐 Admin Dashboard

### Access
```
http://localhost/willbanwalo/admin/login.php
```

### First-Time Setup (XAMPP / fresh install)
1. Import `sql/schema.sql` in phpMyAdmin (includes the `admins` table)
2. Open `http://localhost/willbanwalo/admin/setup.php`
3. Fill in your admin username, password, and name
4. Click **Create Admin Account**
5. **Delete `admin/setup.php`** immediately after — it has no auth protection

### Admin Dashboard Features

| Section | Features |
|---------|----------|
| **Dashboard** | Revenue stats, total orders, clients count, completed wills, 7-day revenue bar chart, will-type donut chart, recent orders table |
| **Orders** | Full orders list with search, filter by payment/status/plan, sort, pagination, view full order detail in modal |
| **Order Detail** | All steps data (personal, beneficiaries, assets, special instructions, documents), change order status, change payment status, add internal notes, full activity/payment log |
| **Clients** | All clients with order count, total paid, search, export CSV |
| **Revenue** | Daily revenue line chart, plan breakdown donut, will-type bar chart, totals with GST breakdown, date range selector (7/30/90/365 days) |
| **Admins** | List all admins, add new admin, enable/disable accounts, change your own password |
| **Export** | One-click CSV export for orders and clients |

### Admin File Structure
```
admin/
├── login.php       ← Login page
├── index.php       ← Main dashboard (all views in one file)
├── api.php         ← All AJAX endpoints
├── auth.php        ← Session guard (included by index.php)
├── setup.php       ← First-time admin creation (DELETE after use)
└── .htaccess       ← Blocks directory listing
```

### Security Notes for Production
- Set `DEBUG_MODE = false` in `config/config.php`
- Delete `admin/setup.php` after creating your admin
- Consider IP-restricting the `/admin/` folder in `.htaccess`
- Use HTTPS — Hostinger provides free SSL via hPanel → SSL
