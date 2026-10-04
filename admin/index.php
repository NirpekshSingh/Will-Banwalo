<?php require_once __DIR__ . '/auth.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard — Will Banwalo Admin</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --bg:#080d0a;--sidebar:#0c1410;--panel:#101a12;--card:#141f16;
  --border:#1a2b1d;--border2:#223026;
  --accent:#4ade80;--accent2:#22c55e;--accent-dim:rgba(74,222,128,.12);
  --blue:#60a5fa;--amber:#fbbf24;--red:#f87171;--purple:#c084fc;
  --text:#edf7ef;--muted:#6b8f72;--muted2:#4a6b50;
  --radius:12px;--sidebar-w:240px;
}
html,body{height:100%;overflow:hidden}
body{display:flex;background:var(--bg);color:var(--text);font-family:'DM Sans',sans-serif;font-size:14px;}

/* ── SIDEBAR ── */
.sidebar{width:var(--sidebar-w);min-width:var(--sidebar-w);background:var(--sidebar);border-right:1px solid var(--border);display:flex;flex-direction:column;height:100vh;position:fixed;left:0;top:0;z-index:100;}
.sidebar-logo{padding:24px 20px 20px;border-bottom:1px solid var(--border);}
.logo-row{display:flex;align-items:center;gap:10px;}
.logo-mark{width:36px;height:36px;background:linear-gradient(135deg,var(--accent2),var(--accent));border-radius:8px;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:16px;color:#080d0a;flex-shrink:0;}
.logo-name{font-family:'DM Serif Display',serif;font-size:17px;color:var(--text);}
.logo-tag{font-size:10px;color:var(--muted);letter-spacing:.08em;text-transform:uppercase;margin-top:1px;}
.sidebar-nav{flex:1;overflow-y:auto;padding:12px 10px;}
.nav-section{font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--muted2);padding:16px 10px 6px;}
.nav-item{display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:8px;cursor:pointer;color:var(--muted);transition:all .15s;margin-bottom:2px;user-select:none;}
.nav-item:hover{background:var(--accent-dim);color:var(--text);}
.nav-item.active{background:var(--accent-dim);color:var(--accent);font-weight:600;}
.nav-item svg{width:16px;height:16px;flex-shrink:0;}
.nav-badge{margin-left:auto;background:var(--accent);color:#080d0a;font-size:10px;font-weight:700;padding:2px 6px;border-radius:20px;min-width:18px;text-align:center;}
.sidebar-footer{padding:16px 10px;border-top:1px solid var(--border);}
.admin-card{display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:8px;background:var(--card);}
.admin-avatar{width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,var(--accent2),var(--accent));display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;color:#080d0a;flex-shrink:0;}
.admin-name{font-size:13px;font-weight:600;color:var(--text);}
.admin-role{font-size:11px;color:var(--muted);}
.logout-btn{display:flex;align-items:center;gap:8px;margin-top:8px;padding:8px 12px;border-radius:8px;color:var(--muted);cursor:pointer;transition:all .15s;font-size:13px;}
.logout-btn:hover{color:var(--red);background:rgba(248,113,113,.08);}

/* ── MAIN ── */
.main{margin-left:var(--sidebar-w);flex:1;height:100vh;overflow-y:auto;display:flex;flex-direction:column;}
.topbar{display:flex;align-items:center;justify-content:space-between;padding:18px 28px;border-bottom:1px solid var(--border);background:var(--bg);position:sticky;top:0;z-index:50;gap:16px;}
.topbar-title{font-family:'DM Serif Display',serif;font-size:22px;color:var(--text);}
.topbar-right{display:flex;align-items:center;gap:12px;}
.search-box{display:flex;align-items:center;gap:8px;background:var(--card);border:1px solid var(--border2);border-radius:8px;padding:8px 14px;min-width:220px;}
.search-box input{background:none;border:none;outline:none;color:var(--text);font-size:13px;width:100%;}
.search-box input::placeholder{color:var(--muted);}
.badge{display:inline-flex;align-items:center;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;letter-spacing:.02em;}
.badge-paid{background:rgba(74,222,128,.15);color:var(--accent);}
.badge-pending{background:rgba(251,191,36,.12);color:var(--amber);}
.badge-failed{background:rgba(248,113,113,.12);color:var(--red);}
.badge-refunded{background:rgba(192,132,252,.12);color:var(--purple);}
.badge-draft{background:rgba(107,143,114,.12);color:var(--muted);}
.badge-submitted{background:rgba(96,165,250,.12);color:var(--blue);}
.badge-under_review{background:rgba(251,191,36,.12);color:var(--amber);}
.badge-completed{background:rgba(74,222,128,.15);color:var(--accent);}
.badge-cancelled{background:rgba(248,113,113,.12);color:var(--red);}
.badge-basic{background:rgba(107,143,114,.12);color:var(--muted);}
.badge-standard{background:rgba(96,165,250,.12);color:var(--blue);}
.badge-premium{background:rgba(192,132,252,.12);color:var(--purple);}

/* ── VIEWS ── */
.view{display:none;padding:24px 28px 40px;animation:fadeIn .2s ease;}
.view.active{display:block;}
@keyframes fadeIn{from{opacity:0;transform:translateY(6px)}to{opacity:1;transform:translateY(0)}}

/* ── STAT CARDS ── */
.stats-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px;}
.stat-card{background:var(--card);border:1px solid var(--border);border-radius:var(--radius);padding:20px 22px;position:relative;overflow:hidden;}
.stat-card::after{content:'';position:absolute;top:0;left:0;right:0;height:2px;background:var(--accent-color,var(--accent));}
.stat-label{font-size:11px;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);margin-bottom:10px;}
.stat-value{font-family:'DM Serif Display',serif;font-size:28px;color:var(--text);margin-bottom:4px;}
.stat-sub{font-size:12px;color:var(--muted);}
.stat-icon{position:absolute;right:18px;top:18px;width:36px;height:36px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:18px;}

/* ── CHARTS ── */
.charts-row{display:grid;grid-template-columns:2fr 1fr;gap:16px;margin-bottom:24px;}
.chart-card{background:var(--card);border:1px solid var(--border);border-radius:var(--radius);padding:20px;}
.chart-title{font-size:13px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:16px;}
.chart-wrap{position:relative;height:200px;}

/* ── TABLE ── */
.table-card{background:var(--card);border:1px solid var(--border);border-radius:var(--radius);overflow:hidden;}
.table-header{display:flex;align-items:center;justify-content:space-between;padding:16px 20px;border-bottom:1px solid var(--border);gap:12px;flex-wrap:wrap;}
.table-title{font-size:14px;font-weight:600;color:var(--text);}
.filters{display:flex;gap:8px;flex-wrap:wrap;}
select.filter{background:var(--panel);border:1px solid var(--border2);border-radius:6px;color:var(--text);padding:6px 10px;font-size:12px;outline:none;cursor:pointer;}
.btn{display:inline-flex;align-items:center;gap:6px;padding:8px 14px;border-radius:8px;border:none;font-family:'DM Sans',sans-serif;font-size:12px;font-weight:600;cursor:pointer;transition:all .15s;}
.btn-primary{background:var(--accent);color:#080d0a;}
.btn-primary:hover{background:var(--accent2);}
.btn-ghost{background:var(--panel);color:var(--muted);border:1px solid var(--border2);}
.btn-ghost:hover{color:var(--text);border-color:var(--muted2);}
.btn-danger{background:rgba(248,113,113,.12);color:var(--red);border:1px solid rgba(248,113,113,.2);}
.btn-danger:hover{background:rgba(248,113,113,.2);}
.btn-sm{padding:5px 10px;font-size:11px;}
table{width:100%;border-collapse:collapse;}
th{text-align:left;padding:10px 16px;font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--muted2);background:var(--panel);border-bottom:1px solid var(--border);}
td{padding:12px 16px;border-bottom:1px solid var(--border);color:var(--text);vertical-align:middle;}
tr:last-child td{border-bottom:none;}
tr:hover td{background:rgba(74,222,128,.03);}
.td-name{font-weight:500;}
.td-mono{font-family:monospace;font-size:12px;color:var(--muted);}
.action-btns{display:flex;gap:6px;}
.pagination{display:flex;align-items:center;justify-content:between;padding:12px 20px;border-top:1px solid var(--border);gap:8px;}
.page-btn{padding:6px 10px;border-radius:6px;border:1px solid var(--border2);background:var(--panel);color:var(--muted);cursor:pointer;font-size:12px;transition:all .15s;}
.page-btn:hover{color:var(--text);}
.page-btn.active{background:var(--accent-dim);color:var(--accent);border-color:var(--accent);}
.page-info{font-size:12px;color:var(--muted);margin-left:auto;}

/* ── MODAL ── */
.modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,.7);backdrop-filter:blur(4px);z-index:200;display:none;align-items:center;justify-content:center;padding:20px;}
.modal-overlay.open{display:flex;}
.modal{background:var(--panel);border:1px solid var(--border2);border-radius:18px;width:100%;max-width:800px;max-height:90vh;overflow-y:auto;animation:modalIn .25s cubic-bezier(.16,1,.3,1);}
@keyframes modalIn{from{opacity:0;transform:scale(.95)}to{opacity:1;transform:scale(1)}}
.modal-header{display:flex;align-items:center;justify-content:space-between;padding:24px 28px;border-bottom:1px solid var(--border);}
.modal-title{font-family:'DM Serif Display',serif;font-size:20px;}
.modal-close{width:32px;height:32px;border-radius:50%;background:var(--card);border:none;color:var(--muted);cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:18px;transition:all .15s;}
.modal-close:hover{color:var(--text);}
.modal-body{padding:24px 28px;}
.detail-section{margin-bottom:24px;}
.detail-section h4{font-size:11px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--muted2);margin-bottom:12px;padding-bottom:8px;border-bottom:1px solid var(--border);}
.detail-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;}
.detail-item label{display:block;font-size:10px;font-weight:600;letter-spacing:.06em;text-transform:uppercase;color:var(--muted2);margin-bottom:3px;}
.detail-item span{font-size:13px;color:var(--text);}
.detail-item span.mono{font-family:monospace;font-size:12px;}
.log-item{display:flex;gap:12px;padding:10px 0;border-bottom:1px solid var(--border);}
.log-item:last-child{border-bottom:none;}
.log-dot{width:8px;height:8px;border-radius:50%;background:var(--accent);margin-top:4px;flex-shrink:0;}
.log-event{font-size:12px;font-weight:600;color:var(--text);}
.log-time{font-size:11px;color:var(--muted);}
.log-payload{font-size:11px;color:var(--muted);font-family:monospace;margin-top:2px;}
.status-select{background:var(--card);border:1px solid var(--border2);border-radius:6px;color:var(--text);padding:6px 10px;font-size:12px;outline:none;cursor:pointer;}
.note-input{width:100%;background:var(--card);border:1px solid var(--border2);border-radius:8px;padding:10px 14px;color:var(--text);font-family:'DM Sans',sans-serif;font-size:13px;resize:none;outline:none;height:80px;}
.note-input:focus{border-color:var(--accent);}

/* ── LOADING ── */
.skeleton{background:linear-gradient(90deg,var(--card) 25%,var(--border2) 50%,var(--card) 75%);background-size:200% 100%;animation:shimmer 1.5s infinite;border-radius:6px;}
@keyframes shimmer{0%{background-position:200% 0}100%{background-position:-200% 0}}
.loading-row td{padding:16px;}

/* ── TOAST ── */
#toast{position:fixed;bottom:28px;right:28px;z-index:999;display:flex;flex-direction:column;gap:8px;pointer-events:none;}
.toast-msg{background:var(--panel);border:1px solid var(--border2);border-radius:10px;padding:12px 18px;font-size:13px;color:var(--text);box-shadow:0 8px 32px rgba(0,0,0,.4);animation:toastIn .3s ease;pointer-events:all;}
.toast-msg.success{border-color:var(--accent);color:var(--accent);}
.toast-msg.error{border-color:var(--red);color:var(--red);}
@keyframes toastIn{from{opacity:0;transform:translateX(20px)}to{opacity:1;transform:translateX(0)}}

/* ── EMPTY ── */
.empty-state{padding:60px 20px;text-align:center;color:var(--muted);}
.empty-state svg{opacity:.3;margin-bottom:12px;}
.empty-state p{font-size:13px;}

/* ── FORM ── */
.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;}
.form-field{display:flex;flex-direction:column;gap:6px;}
.form-field label{font-size:11px;font-weight:600;letter-spacing:.06em;text-transform:uppercase;color:var(--muted);}
.form-field input,.form-field select{background:var(--card);border:1px solid var(--border2);border-radius:8px;padding:10px 14px;color:var(--text);font-family:'DM Sans',sans-serif;font-size:13px;outline:none;}
.form-field input:focus,.form-field select:focus{border-color:var(--accent);}
.form-field.full{grid-column:1/-1;}

/* ── SCROLLBAR ── */
::-webkit-scrollbar{width:5px;height:5px}
::-webkit-scrollbar-track{background:transparent}
::-webkit-scrollbar-thumb{background:var(--border2);border-radius:4px}

/* ── RESPONSIVE ── */
@media(max-width:1100px){.stats-grid{grid-template-columns:repeat(2,1fr)}.charts-row{grid-template-columns:1fr}}
@media(max-width:768px){.sidebar{transform:translateX(-100%)}.main{margin-left:0}}
</style>
</head>
<body data-role="<?= htmlspecialchars($adminRole) ?>">

<!-- ═══════════════════════════════════════════ SIDEBAR -->
<aside class="sidebar">
  <div class="sidebar-logo">
    <div class="logo-row">
      <div class="logo-mark">W</div>
      <div>
        <div class="logo-name">Will Banwalo</div>
        <div class="logo-tag">Admin Panel</div>
      </div>
    </div>
  </div>

  <nav class="sidebar-nav">
    <div class="nav-section">Main</div>
    <div class="nav-item active" onclick="showView('dashboard',this)">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
      Dashboard
    </div>
    <div class="nav-item" onclick="showView('orders',this)">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
      Orders
      <span class="nav-badge" id="badge-orders">—</span>
    </div>
    <div class="nav-item" onclick="showView('clients',this)">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
      Clients
    </div>
    <div class="nav-section">Finance</div>
    <?php if ($isAdmin): ?>
    <div class="nav-item" onclick="showView('revenue',this)">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
      Revenue
    </div>
    <?php endif; ?>
    <div class="nav-section">Settings</div>
    <?php if ($isAdmin): ?>
    <div class="nav-item" onclick="showView('admins',this)">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M6 20v-2a4 4 0 014-4h4a4 4 0 014 4v2"/></svg>
      Admins
    </div>
    <?php endif; ?>
  </nav>

  <div class="sidebar-footer">
    <div class="admin-card">
      <div class="admin-avatar"><?= strtoupper(substr($adminName,0,1)) ?></div>
      <div>
        <div class="admin-name"><?= htmlspecialchars($adminName) ?></div>
        <div class="admin-role">
          <?= $isAdmin ? 'Administrator' : 'Staff' ?>
          <span style="margin-left:5px;background:<?= $isAdmin ? 'rgba(74,222,128,.15)' : 'rgba(96,165,250,.15)' ?>;color:<?= $isAdmin ? 'var(--accent)' : 'var(--blue)' ?>;font-size:9px;font-weight:700;letter-spacing:.06em;padding:1px 6px;border-radius:20px;text-transform:uppercase"><?= $isAdmin ? 'Admin' : 'Staff' ?></span>
        </div>
      </div>
    </div>
    <div class="logout-btn" onclick="doLogout()">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/></svg>
      Sign Out
    </div>
  </div>
</aside>

<!-- ═══════════════════════════════════════════ MAIN -->
<main class="main">
  <div class="topbar">
    <div class="topbar-title" id="page-title">Dashboard</div>
    <div class="topbar-right">
      <div class="search-box" id="global-search-box" style="display:none">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input type="text" id="global-search" placeholder="Search orders, clients…" oninput="onSearch(this.value)">
      </div>
      <button class="btn btn-ghost btn-sm" onclick="refreshCurrent()">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 11-2.12-9.36L23 10"/></svg>
        Refresh
      </button>
    </div>
  </div>

  <!-- ────── DASHBOARD ────── -->
  <div class="view active" id="view-dashboard">
    <div class="stats-grid" id="stats-grid">
      <!-- filled by JS -->
      <div class="stat-card"><div class="skeleton" style="height:80px"></div></div>
      <div class="stat-card"><div class="skeleton" style="height:80px"></div></div>
      <div class="stat-card"><div class="skeleton" style="height:80px"></div></div>
      <div class="stat-card"><div class="skeleton" style="height:80px"></div></div>
    </div>
    <div class="charts-row">
      <div class="chart-card">
        <div class="chart-title">Revenue — Last 7 Days</div>
        <div class="chart-wrap"><canvas id="revenueChart"></canvas></div>
      </div>
      <div class="chart-card">
        <div class="chart-title">Will Types</div>
        <div class="chart-wrap"><canvas id="willTypeChart"></canvas></div>
      </div>
    </div>
    <div class="table-card">
      <div class="table-header"><div class="table-title">Recent Orders</div></div>
      <table>
        <thead><tr><th>Order Ref</th><th>Client</th><th>Will Type</th><th>Amount</th><th>Payment</th><th>Status</th><th>Date</th></tr></thead>
        <tbody id="recent-tbody"><tr class="loading-row"><td colspan="7"><div class="skeleton" style="height:16px"></div></td></tr></tbody>
      </table>
    </div>
  </div>

  <!-- ────── ORDERS ────── -->
  <div class="view" id="view-orders">
    <div class="table-card">
      <div class="table-header">
        <div class="table-title">All Orders</div>
        <div class="filters">
          <select class="filter" id="filter-pay" onchange="loadOrders(1)">
            <option value="">All Payments</option>
            <option value="paid">Paid</option>
            <option value="pending">Pending</option>
            <option value="failed">Failed</option>
            <option value="refunded">Refunded</option>
          </select>
          <select class="filter" id="filter-status" onchange="loadOrders(1)">
            <option value="">All Statuses</option>
            <option value="draft">Draft</option>
            <option value="submitted">Submitted</option>
            <option value="under_review">Under Review</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
          </select>
          <select class="filter" id="filter-plan" onchange="loadOrders(1)">
            <option value="">All Plans</option>
            <option value="basic">Basic</option>
            <option value="standard">Standard</option>
            <option value="premium">Premium</option>
          </select>
          <select class="filter" id="sort-orders" onchange="loadOrders(1)">
            <option value="created_at|desc">Newest First</option>
            <option value="created_at|asc">Oldest First</option>
            <option value="total_amount|desc">Highest Amount</option>
            <option value="total_amount|asc">Lowest Amount</option>
          </select>
          <button class="btn btn-ghost btn-sm" onclick="exportCSV('orders')">⬇ Export CSV</button>
        </div>
      </div>
      <table>
        <thead><tr><th>Order Ref</th><th>Client</th><th>Will Type</th><th>Plan</th><th>Amount</th><th>Payment</th><th>Status</th><th>Date</th><th>Actions</th></tr></thead>
        <tbody id="orders-tbody"></tbody>
      </table>
      <div class="pagination" id="orders-pagination"></div>
    </div>
  </div>

  <!-- ────── CLIENTS ────── -->
  <div class="view" id="view-clients">
    <div class="table-card">
      <div class="table-header">
        <div class="table-title">All Clients</div>
        <div class="filters">
          <button class="btn btn-ghost btn-sm" onclick="exportCSV('clients')">⬇ Export CSV</button>
        </div>
      </div>
      <table>
        <thead><tr><th>Name</th><th>Mobile</th><th>Email</th><th>City</th><th>Orders</th><th>Total Paid</th><th>Joined</th></tr></thead>
        <tbody id="clients-tbody"></tbody>
      </table>
      <div class="pagination" id="clients-pagination"></div>
    </div>
  </div>

  <!-- ────── REVENUE ────── -->
  <div class="view" id="view-revenue">
    <div style="display:flex;align-items:center;gap:12px;margin-bottom:20px;">
      <select class="filter" id="revenue-range" onchange="loadRevenue()" style="padding:8px 12px;">
        <option value="7">Last 7 days</option>
        <option value="30" selected>Last 30 days</option>
        <option value="90">Last 90 days</option>
        <option value="365">Last 365 days</option>
      </select>
    </div>
    <div class="stats-grid" id="revenue-stats" style="grid-template-columns:repeat(4,1fr)">
      <div class="stat-card"><div class="skeleton" style="height:80px"></div></div>
      <div class="stat-card"><div class="skeleton" style="height:80px"></div></div>
      <div class="stat-card"><div class="skeleton" style="height:80px"></div></div>
      <div class="stat-card"><div class="skeleton" style="height:80px"></div></div>
    </div>
    <div class="charts-row" style="grid-template-columns:2fr 1fr">
      <div class="chart-card">
        <div class="chart-title">Daily Revenue</div>
        <div class="chart-wrap" style="height:240px"><canvas id="dailyRevenueChart"></canvas></div>
      </div>
      <div class="chart-card">
        <div class="chart-title">Revenue by Plan</div>
        <div class="chart-wrap" style="height:240px"><canvas id="planRevenueChart"></canvas></div>
      </div>
    </div>
    <div class="chart-card" style="margin-top:16px">
      <div class="chart-title">Revenue by Will Type</div>
      <div class="chart-wrap" style="height:200px"><canvas id="typeRevenueChart"></canvas></div>
    </div>
  </div>

  <!-- ────── ADMINS ────── -->
  <div class="view" id="view-admins">
    <div style="display:flex;gap:20px;align-items:flex-start;flex-wrap:wrap;">
      <div class="table-card" style="flex:2;min-width:300px">
        <div class="table-header">
          <div class="table-title">Admin Accounts</div>
          <button class="btn btn-primary btn-sm" onclick="openAddAdmin()">+ Add Admin</button>
        </div>
        <table>
          <thead><tr><th>Name</th><th>Username</th><th>Role</th><th>Last Login</th><th>Status</th><th>Actions</th></tr></thead>
          <tbody id="admins-tbody"></tbody>
        </table>
      </div>
      <div class="table-card" style="flex:1;min-width:260px">
        <div class="table-header"><div class="table-title">Change My Password</div></div>
        <div style="padding:20px">
          <div class="form-field" style="margin-bottom:14px">
            <label>Current Password</label>
            <input type="password" id="pwd-current" placeholder="••••••••">
          </div>
          <div class="form-field" style="margin-bottom:14px">
            <label>New Password</label>
            <input type="password" id="pwd-new" placeholder="min 8 chars">
          </div>
          <button class="btn btn-primary" style="width:100%" onclick="changePassword()">Update Password</button>
        </div>
      </div>
    </div>
  </div>
</main>

<!-- ═══════════════════════════════════════════ ORDER DETAIL MODAL -->
<div class="modal-overlay" id="order-modal">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title" id="modal-order-ref">Order Detail</div>
      <button class="modal-close" onclick="closeModal('order-modal')">✕</button>
    </div>
    <div class="modal-body" id="modal-body">Loading…</div>
  </div>
</div>

<!-- ═══════════════════════════════════════════ ADD ADMIN MODAL -->
<div class="modal-overlay" id="add-admin-modal">
  <div class="modal" style="max-width:480px">
    <div class="modal-header">
      <div class="modal-title">Add Admin</div>
      <button class="modal-close" onclick="closeModal('add-admin-modal')">✕</button>
    </div>
    <div class="modal-body">
      <div class="form-grid">
        <div class="form-field"><label>Full Name</label><input type="text" id="new-fname" placeholder="Full Name"></div>
        <div class="form-field"><label>Username</label><input type="text" id="new-uname" placeholder="username"></div>
        <div class="form-field"><label>Email</label><input type="email" id="new-email" placeholder="email@example.com"></div>
        <div class="form-field"><label>Password</label><input type="password" id="new-pwd" placeholder="min 8 chars"></div>
        <div class="form-field" style="grid-column:1/-1">
          <label>Role</label>
          <div style="display:flex;gap:10px;margin-top:2px">
            <label style="display:flex;align-items:center;gap:8px;padding:10px 16px;border:1px solid var(--border2);border-radius:8px;cursor:pointer;flex:1;background:var(--card);text-transform:none;letter-spacing:0;font-size:13px;font-weight:400" id="role-staff-label">
              <input type="radio" name="new-role" id="new-role-staff" value="staff" checked style="accent-color:var(--blue);width:14px;height:14px">
              <div>
                <div style="font-weight:600;color:var(--text)">Staff</div>
                <div style="font-size:11px;color:var(--muted);margin-top:1px">Can manage orders & clients. No revenue or admin access.</div>
              </div>
            </label>
            <label style="display:flex;align-items:center;gap:8px;padding:10px 16px;border:1px solid var(--border2);border-radius:8px;cursor:pointer;flex:1;background:var(--card);text-transform:none;letter-spacing:0;font-size:13px;font-weight:400" id="role-admin-label">
              <input type="radio" name="new-role" id="new-role-admin" value="admin" style="accent-color:var(--accent);width:14px;height:14px">
              <div>
                <div style="font-weight:600;color:var(--text)">Admin</div>
                <div style="font-size:11px;color:var(--muted);margin-top:1px">Full access including revenue reports and admin management.</div>
              </div>
            </label>
          </div>
        </div>
      </div>
      <button class="btn btn-primary" style="width:100%;margin-top:20px" onclick="submitAddAdmin()">Create Account</button>
    </div>
  </div>
</div>

<!-- TOAST -->
<div id="toast"></div>

<script>
// ══════════════════════════════════════════════════════════
//  CORE UTILITIES
// ══════════════════════════════════════════════════════════
let currentView = 'dashboard';
let ordersPage  = 1;
let clientsPage = 1;
let searchTimer = null;
let revenueChartInst = null, willTypeInst = null, dailyRevInst = null, planRevInst = null, typeRevInst = null;

const fmt = n => '₹' + Number(n||0).toLocaleString('en-IN', {minimumFractionDigits:2, maximumFractionDigits:2});
const fmtDate = d => d ? new Date(d).toLocaleDateString('en-IN',{day:'2-digit',month:'short',year:'numeric'}) : '—';
const esc = s => String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');

function toast(msg, type='info') {
  const el = document.createElement('div');
  el.className = 'toast-msg ' + type;
  el.textContent = msg;
  document.getElementById('toast').appendChild(el);
  setTimeout(() => { el.style.opacity='0'; el.style.transition='opacity .4s'; setTimeout(()=>el.remove(),400); }, 3000);
}

async function api(action, data={}) {
  const res = await fetch('api.php', {
    method:'POST',
    headers:{'Content-Type':'application/json'},
    body: JSON.stringify({action, ...data})
  });
  const json = await res.json();
  if(!json.success) throw new Error(json.error||'Error');
  return json;
}

function badge(val, type) {
  return `<span class="badge badge-${esc(val)}">${esc(val)}</span>`;
}

// ══════════════════════════════════════════════════════════
//  NAVIGATION
// ══════════════════════════════════════════════════════════
function showView(name, navEl) {
  document.querySelectorAll('.view').forEach(v=>v.classList.remove('active'));
  document.querySelectorAll('.nav-item').forEach(n=>n.classList.remove('active'));
  document.getElementById('view-'+name).classList.add('active');
  if(navEl) navEl.classList.add('active');
  currentView = name;
  const titles = {dashboard:'Dashboard', orders:'Orders', clients:'Clients', revenue:'Revenue', admins:'Admins'};
  document.getElementById('page-title').textContent = titles[name]||name;
  const showSearch = ['orders','clients'].includes(name);
  document.getElementById('global-search-box').style.display = showSearch ? 'flex' : 'none';
  if(document.getElementById('global-search')) document.getElementById('global-search').value='';

  if(name==='orders')   loadOrders(1);
  if(name==='clients')  loadClients(1);
  if(name==='revenue')  loadRevenue();
  if(name==='admins')   loadAdmins();
}

function refreshCurrent() {
  if(currentView==='dashboard') loadDashboard();
  else if(currentView==='orders') loadOrders(ordersPage);
  else if(currentView==='clients') loadClients(clientsPage);
  else if(currentView==='revenue') loadRevenue();
  else if(currentView==='admins') loadAdmins();
}

function onSearch(val) {
  clearTimeout(searchTimer);
  searchTimer = setTimeout(() => {
    if(currentView==='orders') loadOrders(1);
    else if(currentView==='clients') loadClients(1);
  }, 350);
}

// ══════════════════════════════════════════════════════════
//  DASHBOARD
// ══════════════════════════════════════════════════════════
async function loadDashboard() {
  try {
    const d = await api('stats');
    // Stat cards
    document.getElementById('stats-grid').innerHTML = `
      <div class="stat-card" style="--accent-color:var(--accent)">
        <div class="stat-label">Total Revenue</div>
        <div class="stat-value">${fmt(d.revenue)}</div>
        <div class="stat-sub">${d.paid_orders} paid orders</div>
        <div class="stat-icon" style="background:rgba(74,222,128,.12);color:var(--accent)">₹</div>
      </div>
      <div class="stat-card" style="--accent-color:var(--blue)">
        <div class="stat-label">Total Orders</div>
        <div class="stat-value">${d.total_orders}</div>
        <div class="stat-sub">${d.pending_orders} pending · ${d.draft_orders} draft</div>
        <div class="stat-icon" style="background:rgba(96,165,250,.12);color:var(--blue)">📋</div>
      </div>
      <div class="stat-card" style="--accent-color:var(--purple)">
        <div class="stat-label">Clients</div>
        <div class="stat-value">${d.total_clients}</div>
        <div class="stat-sub">${d.docs_uploaded} documents uploaded</div>
        <div class="stat-icon" style="background:rgba(192,132,252,.12);color:var(--purple)">👥</div>
      </div>
      <div class="stat-card" style="--accent-color:var(--amber)">
        <div class="stat-label">Completed</div>
        <div class="stat-value">${d.completed}</div>
        <div class="stat-sub">Wills delivered</div>
        <div class="stat-icon" style="background:rgba(251,191,36,.12);color:var(--amber)">✅</div>
      </div>`;

    // Revenue badge
    document.getElementById('badge-orders').textContent = d.pending_orders||'0';
    applyRoleRestrictions();

    // Revenue chart
    const days7 = d.revenue_chart;
    const labels = days7.map(r=>r.d);
    const vals   = days7.map(r=>parseFloat(r.rev));
    if(revenueChartInst) revenueChartInst.destroy();
    revenueChartInst = new Chart(document.getElementById('revenueChart'), {
      type:'bar',
      data:{labels,datasets:[{label:'Revenue',data:vals,backgroundColor:'rgba(74,222,128,.4)',borderColor:'rgba(74,222,128,.8)',borderWidth:1,borderRadius:4}]},
      options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},scales:{x:{ticks:{color:'#6b8f72',font:{size:10}},grid:{display:false}},y:{ticks:{color:'#6b8f72',font:{size:10},callback:v=>'₹'+Number(v).toLocaleString('en-IN')},grid:{color:'rgba(255,255,255,.04)'}}}}
    });

    // Will type donut
    const wtLabels = d.will_types.map(r=>r.will_type);
    const wtVals   = d.will_types.map(r=>parseInt(r.cnt));
    if(willTypeInst) willTypeInst.destroy();
    willTypeInst = new Chart(document.getElementById('willTypeChart'), {
      type:'doughnut',
      data:{labels:wtLabels,datasets:[{data:wtVals,backgroundColor:['rgba(74,222,128,.7)','rgba(96,165,250,.7)','rgba(251,191,36,.7)','rgba(192,132,252,.7)','rgba(248,113,113,.7)','rgba(107,143,114,.5)'],borderWidth:0}]},
      options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{position:'right',labels:{color:'#6b8f72',font:{size:11},boxWidth:10}}}}
    });

    // Recent orders
    const tbody = document.getElementById('recent-tbody');
    if(!d.recent.length){tbody.innerHTML='<tr><td colspan="7" style="text-align:center;color:var(--muted);padding:32px">No orders yet</td></tr>';return;}
    tbody.innerHTML = d.recent.map(o=>`<tr>
      <td class="td-mono">${esc(o.order_ref)}</td>
      <td class="td-name">${esc(o.full_name||'—')}</td>
      <td>${esc(o.will_type)}</td>
      <td>${fmt(o.total_amount)}</td>
      <td>${badge(o.payment_status)}</td>
      <td>${badge(o.status)}</td>
      <td style="color:var(--muted)">${fmtDate(o.created_at)}</td>
    </tr>`).join('');
  } catch(e){ toast(e.message,'error'); }
}

// ══════════════════════════════════════════════════════════
//  ORDERS
// ══════════════════════════════════════════════════════════
async function loadOrders(page=1) {
  ordersPage = page;
  const search  = document.getElementById('global-search')?.value||'';
  const pay     = document.getElementById('filter-pay')?.value||'';
  const status  = document.getElementById('filter-status')?.value||'';
  const plan    = document.getElementById('filter-plan')?.value||'';
  const sortRaw = document.getElementById('sort-orders')?.value||'created_at|desc';
  const [sort,dir] = sortRaw.split('|');

  const tbody = document.getElementById('orders-tbody');
  tbody.innerHTML = '<tr class="loading-row"><td colspan="9"><div class="skeleton" style="height:16px"></div></td></tr>';
  try {
    const d = await api('orders', {page, search, payment:pay, status, plan, sort, dir});
    document.getElementById('badge-orders').textContent = d.total||'0';
    if(!d.orders.length){tbody.innerHTML='<tr><td colspan="9" style="text-align:center;color:var(--muted);padding:32px">No orders found</td></tr>';renderPagination('orders-pagination',0,1,1);return;}
    tbody.innerHTML = d.orders.map(o=>`<tr>
      <td class="td-mono" style="cursor:pointer;color:var(--accent)" onclick="openOrder(${o.id})">${esc(o.order_ref)}</td>
      <td><div class="td-name">${esc(o.full_name||'—')}</div><div style="font-size:11px;color:var(--muted)">${esc(o.mobile||'')}</div></td>
      <td>${esc(o.will_type)}</td>
      <td>${badge(o.plan,'plan')}</td>
      <td>${fmt(o.total_amount)}</td>
      <td>${badge(o.payment_status)}</td>
      <td>${badge(o.status)}</td>
      <td style="color:var(--muted);font-size:12px">${fmtDate(o.created_at)}</td>
      <td><div class="action-btns">
        <button class="btn btn-ghost btn-sm" onclick="openOrder(${o.id})">View</button>
        ${o.payment_status!=='paid'?`<button class="btn btn-danger btn-sm" onclick="deleteOrder(${o.id},'${esc(o.order_ref)}')">Del</button>`:''}
      </div></td>
    </tr>`).join('');
    renderPagination('orders-pagination', d.total, d.page, d.pages);
  } catch(e){ toast(e.message,'error'); }
}

function renderPagination(id, total, page, pages) {
  const el = document.getElementById(id);
  if(pages<=1){el.innerHTML='';return;}
  let btns='';
  for(let i=1;i<=Math.min(pages,10);i++){
    btns+=`<button class="page-btn ${i===page?'active':''}" onclick="gotoPage('${id}',${i})">${i}</button>`;
  }
  el.innerHTML = btns + `<span class="page-info">${total} records</span>`;
}
function gotoPage(paginationId, page) {
  if(paginationId.includes('orders')) loadOrders(page);
  else if(paginationId.includes('clients')) loadClients(page);
}

// ══════════════════════════════════════════════════════════
//  ORDER DETAIL MODAL
// ══════════════════════════════════════════════════════════
async function openOrder(id) {
  document.getElementById('order-modal').classList.add('open');
  document.getElementById('modal-body').innerHTML = '<div style="text-align:center;padding:40px;color:var(--muted)">Loading…</div>';
  try {
    const d = await api('order_detail', {id});
    const o=d.order, p=d.personal||{}, si=d.special||{};
    document.getElementById('modal-order-ref').textContent = o.order_ref;
    document.getElementById('modal-body').innerHTML = `
      <!-- Status controls -->
      <div style="display:flex;gap:12px;margin-bottom:24px;flex-wrap:wrap;align-items:center">
        <div>
          <label style="font-size:10px;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);display:block;margin-bottom:4px">Order Status</label>
          <select class="status-select" onchange="updateStatus(${o.id},this.value,'status')">
            ${['draft','submitted','under_review','completed','cancelled'].map(s=>`<option value="${s}" ${s===o.status?'selected':''}>${s.replace('_',' ')}</option>`).join('')}
          </select>
        </div>
        <div>
          <label style="font-size:10px;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);display:block;margin-bottom:4px">Payment Status</label>
          <select class="status-select" onchange="updateStatus(${o.id},this.value,'payment')">
            ${['pending','paid','failed','refunded'].map(s=>`<option value="${s}" ${s===o.payment_status?'selected':''}>${s}</option>`).join('')}
          </select>
        </div>
        <div style="margin-left:auto;text-align:right">
          <div style="font-size:11px;color:var(--muted)">Total Amount</div>
          <div style="font-family:'DM Serif Display',serif;font-size:24px;color:var(--accent)">${fmt(o.total_amount)}</div>
          <div style="font-size:11px;color:var(--muted)">incl. GST ${fmt(o.gst_amount)}</div>
        </div>
      </div>

      <!-- Order Info -->
      <div class="detail-section">
        <h4>Order Info</h4>
        <div class="detail-grid">
          <div class="detail-item"><label>Order Ref</label><span class="mono">${esc(o.order_ref)}</span></div>
          <div class="detail-item"><label>Will Type</label><span>${esc(o.will_type)}</span></div>
          <div class="detail-item"><label>Plan</label><span>${badge(o.plan)}</span></div>
          <div class="detail-item"><label>Plan Amount</label><span>${fmt(o.plan_amount)}</span></div>
          <div class="detail-item"><label>GST</label><span>${fmt(o.gst_amount)}</span></div>
          <div class="detail-item"><label>Created</label><span>${fmtDate(o.created_at)}</span></div>
          <div class="detail-item"><label>RZP Order ID</label><span class="mono" style="font-size:11px">${esc(o.razorpay_order_id||'—')}</span></div>
          <div class="detail-item"><label>RZP Payment ID</label><span class="mono" style="font-size:11px">${esc(o.razorpay_payment_id||'—')}</span></div>
          <div class="detail-item"><label>IP Address</label><span class="mono">${esc(o.ip_address||'—')}</span></div>
        </div>
      </div>

      <!-- Personal -->
      ${p.full_name?`<div class="detail-section">
        <h4>Personal Details</h4>
        <div class="detail-grid">
          <div class="detail-item"><label>Full Name</label><span>${esc(p.full_name)}</span></div>
          <div class="detail-item"><label>DOB</label><span>${fmtDate(p.dob)}</span></div>
          <div class="detail-item"><label>Gender</label><span>${esc(p.gender||'—')}</span></div>
          <div class="detail-item"><label>Mobile</label><span>${esc(p.mobile)}</span></div>
          <div class="detail-item"><label>Email</label><span>${esc(p.email||'—')}</span></div>
          <div class="detail-item"><label>Religion</label><span>${esc(p.religion||'—')}</span></div>
          <div class="detail-item"><label>Aadhaar</label><span class="mono">${esc(p.aadhaar||'—')}</span></div>
          <div class="detail-item"><label>PAN</label><span class="mono">${esc(p.pan||'—')}</span></div>
          <div class="detail-item"><label>City/State</label><span>${esc(p.city||'')} ${esc(p.state||'')}</span></div>
          <div class="detail-item" style="grid-column:1/-1"><label>Address</label><span>${esc(p.address||'—')}</span></div>
          ${p.executor_name?`<div class="detail-item"><label>Executor</label><span>${esc(p.executor_name)}</span></div><div class="detail-item"><label>Executor Phone</label><span>${esc(p.executor_phone||'—')}</span></div>`:''}
        </div>
      </div>`:''}

      <!-- Beneficiaries -->
      ${d.bens.length?`<div class="detail-section">
        <h4>Beneficiaries (${d.bens.length})</h4>
        <table style="font-size:12px"><thead><tr><th>Name</th><th>Relationship</th><th>DOB</th><th>Share %</th></tr></thead>
        <tbody>${d.bens.map(b=>`<tr><td>${esc(b.full_name)}</td><td>${esc(b.relationship)}</td><td>${fmtDate(b.dob)}</td><td>${b.share_pct}%</td></tr>`).join('')}</tbody></table>
      </div>`:''}

      <!-- Assets -->
      ${d.assets.length?`<div class="detail-section">
        <h4>Assets (${d.assets.length})</h4>
        <table style="font-size:12px"><thead><tr><th>Type</th><th>Description</th><th>Value</th></tr></thead>
        <tbody>${d.assets.map(a=>`<tr><td>${badge(a.asset_type)}</td><td>${esc(a.description)}</td><td>${a.estimated_value?fmt(a.estimated_value):'—'}</td></tr>`).join('')}</tbody></table>
      </div>`:''}

      <!-- Special Instructions -->
      ${si.id?`<div class="detail-section">
        <h4>Special Instructions</h4>
        <div class="detail-grid">
          <div class="detail-item"><label>Funeral</label><span>${esc(si.funeral_preference||'—')}</span></div>
          <div class="detail-item"><label>Organ Donation</label><span>${si.organ_donation?'Yes':'No'}</span></div>
          ${si.funeral_notes?`<div class="detail-item" style="grid-column:1/-1"><label>Funeral Notes</label><span>${esc(si.funeral_notes)}</span></div>`:''}
          ${si.charity_notes?`<div class="detail-item" style="grid-column:1/-1"><label>Charity</label><span>${esc(si.charity_notes)}</span></div>`:''}
        </div>
      </div>`:''}

      <!-- Documents -->
      <div class="detail-section">
        <h4>Customer Documents (${d.docs.filter(x=>x.doc_type!=='final_will').length})</h4>
        ${d.docs.filter(x=>x.doc_type!=='final_will').length ? `
        <div style="display:flex;flex-wrap:wrap;gap:10px">
          ${d.docs.filter(x=>x.doc_type!=='final_will').map(doc=>{
            const isImg = (doc.mime_type||'').startsWith('image/');
            const isPdf = doc.mime_type === 'application/pdf';
            const icon  = isPdf ? '📄' : isImg ? '🖼️' : '📎';
            const label = doc.doc_type.replace(/_/g,' ').replace(/\b\w/g,c=>c.toUpperCase());
            const size  = doc.file_size ? (doc.file_size>1024*1024?(doc.file_size/1024/1024).toFixed(1)+'MB':(Math.round(doc.file_size/1024))+'KB') : '';
            return `<div style="background:var(--card);border:1px solid var(--border2);border-radius:10px;padding:12px 14px;font-size:12px;min-width:160px;max-width:200px">
              <div style="font-size:20px;margin-bottom:6px">${icon}</div>
              <div style="font-weight:600;color:var(--accent);margin-bottom:2px">${esc(label)}</div>
              <div style="color:var(--muted);font-size:11px;margin-bottom:8px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis" title="${esc(doc.original_name)}">${esc(doc.original_name)}</div>
              <div style="color:var(--muted2);font-size:10px;margin-bottom:10px">${size}</div>
              <div style="display:flex;gap:6px">
                <a href="serve_file.php?doc_id=${doc.id}" target="_blank"
                   style="display:inline-flex;align-items:center;gap:4px;background:var(--accent-dim);color:var(--accent);border:1px solid rgba(74,222,128,.3);border-radius:6px;padding:4px 10px;font-size:11px;font-weight:600;text-decoration:none;cursor:pointer">
                  👁 View
                </a>
                <a href="serve_file.php?doc_id=${doc.id}" download="${esc(doc.original_name)}"
                   style="display:inline-flex;align-items:center;gap:4px;background:var(--panel);color:var(--muted);border:1px solid var(--border2);border-radius:6px;padding:4px 10px;font-size:11px;font-weight:600;text-decoration:none;cursor:pointer">
                  ⬇ Save
                </a>
              </div>
            </div>`;
          }).join('')}
        </div>` : `<p style="color:var(--muted);font-size:13px">No documents uploaded by customer yet.</p>`}
      </div>

      <!-- Final Will Upload -->
      <div class="detail-section">
        <h4>Final Will Document</h4>
        ${(()=>{
          const fw = d.docs.find(x=>x.doc_type==='final_will');
          if(fw){
            return `<div style="display:flex;align-items:center;gap:16px;background:rgba(74,222,128,.06);border:1px solid rgba(74,222,128,.2);border-radius:12px;padding:16px">
              <div style="font-size:32px">📜</div>
              <div style="flex:1">
                <div style="font-weight:600;color:var(--accent);margin-bottom:2px">Final Will Uploaded</div>
                <div style="font-size:12px;color:var(--muted)">${esc(fw.original_name)} · ${fw.file_size?Math.round(fw.file_size/1024)+'KB':''}</div>
                <div style="font-size:11px;color:var(--muted2);margin-top:4px">Customer download link is active</div>
              </div>
              <div style="display:flex;flex-direction:column;gap:6px">
                <a href="serve_file.php?doc_id=${fw.id}" target="_blank" style="display:inline-flex;align-items:center;gap:4px;background:var(--accent-dim);color:var(--accent);border:1px solid rgba(74,222,128,.3);border-radius:6px;padding:6px 12px;font-size:12px;font-weight:600;text-decoration:none">👁 View</a>
                <button onclick="document.getElementById('fw-upload-${o.id}').click()" style="display:inline-flex;align-items:center;gap:4px;background:var(--panel);color:var(--muted);border:1px solid var(--border2);border-radius:6px;padding:6px 12px;font-size:12px;font-weight:600;cursor:pointer">↺ Replace</button>
              </div>
            </div>`;
          } else {
            return `<div style="background:rgba(251,191,36,.05);border:1px dashed rgba(251,191,36,.25);border-radius:12px;padding:20px;text-align:center">
              <div style="font-size:28px;margin-bottom:8px">📭</div>
              <div style="font-size:13px;color:var(--muted);margin-bottom:14px">No final will uploaded yet. Upload the completed PDF to mark this order as done and enable customer download.</div>
            </div>`;
          }
        })()}
        <div style="margin-top:14px">
          <input type="file" id="fw-upload-${o.id}" accept="application/pdf,.pdf" style="display:none" onchange="uploadFinalWill(${o.id}, this)">
          <button onclick="document.getElementById('fw-upload-${o.id}').click()"
            style="display:inline-flex;align-items:center;gap:8px;background:linear-gradient(135deg,#22c55e,#4ade80);color:#080d0a;border:none;border-radius:8px;padding:10px 18px;font-family:'DM Sans',sans-serif;font-size:13px;font-weight:700;cursor:pointer">
            📤 Upload Final Will PDF
          </button>
          <span style="font-size:11px;color:var(--muted);margin-left:10px">PDF only · max 20 MB · triggers customer download link</span>
        </div>
        <div id="fw-status-${o.id}" style="display:none;margin-top:10px;font-size:13px;padding:10px 14px;border-radius:8px"></div>
      </div>

      <!-- Dispatch — Standard & Premium only -->
      ${o.plan !== 'basic' ? `
      <div class="detail-section" id="dispatch-section-${o.id}">
        <h4>🚚 Dispatch Information <span style="font-size:10px;font-weight:500;text-transform:none;color:var(--blue);background:rgba(96,165,250,.1);border:1px solid rgba(96,165,250,.2);padding:2px 8px;border-radius:20px;margin-left:6px">${o.plan.toUpperCase()}</span></h4>
        <div id="dispatch-body-${o.id}">
          <div style="color:var(--muted);font-size:13px;padding:12px 0">⏳ Loading…</div>
        </div>
      </div>` : `
      <div class="detail-section">
        <h4>🚚 Dispatch Information</h4>
        <div style="background:rgba(107,143,114,.08);border:1px solid rgba(107,143,114,.2);border-radius:10px;padding:14px 16px;display:flex;align-items:center;gap:10px">
          <span style="font-size:18px">🔒</span>
          <div>
            <div style="font-size:13px;font-weight:600;color:var(--muted)">Not available on Basic plan</div>
            <div style="font-size:12px;color:var(--muted2);margin-top:2px">Physical dispatch is included in Standard and Premium plans only.</div>
          </div>
        </div>
      </div>`}

      <!-- Add Note -->
      <div class="detail-section">
        <h4>Add Note</h4>
        <textarea class="note-input" id="note-input-${o.id}" placeholder="Add an internal note about this order…"></textarea>
        <button class="btn btn-ghost btn-sm" style="margin-top:8px" onclick="addNote(${o.id})">Save Note</button>
      </div>

      <!-- Payment Log -->
      ${d.log.length?`<div class="detail-section">
        <h4>Activity Log</h4>
        ${d.log.map(l=>`<div class="log-item">
          <div class="log-dot" style="background:${l.event.includes('fail')||l.event.includes('mismatch')?'var(--red)':l.event.includes('paid')||l.event.includes('verified')?'var(--accent)':'var(--blue)'}"></div>
          <div>
            <div class="log-event">${esc(l.event)}</div>
            <div class="log-time">${fmtDate(l.created_at)}</div>
            ${l.payload&&l.event==='admin_note'?`<div class="log-payload">${esc(JSON.parse(l.payload).note||'')}</div>`:''}
          </div>
        </div>`).join('')}
      </div>`:''}
    `;
    // Trigger dispatch load for standard/premium
    if (o.plan !== 'basic') {
      setTimeout(() => loadDispatch(o.id), 50);
    }

  } catch(e){ document.getElementById('modal-body').innerHTML=`<p style="color:var(--red);padding:20px">${e.message}</p>`; }
}

async function updateStatus(id, val, type) {
  try {
    const action = type==='payment'?'update_payment':'update_status';
    const param  = type==='payment'?{id,status:val}:{id,status:val};
    await api(action, param);
    toast('Status updated', 'success');
    if(currentView==='orders') loadOrders(ordersPage);
  } catch(e){ toast(e.message,'error'); }
}

async function addNote(orderId) {
  const note = document.getElementById('note-input-'+orderId)?.value?.trim();
  if(!note) return toast('Note is empty','error');
  try {
    await api('add_note',{id:orderId,note});
    toast('Note saved','success');
    document.getElementById('note-input-'+orderId).value='';
  } catch(e){ toast(e.message,'error'); }
}

async function deleteOrder(id, ref) {
  if(!confirm(`Delete order ${ref}? This cannot be undone.`)) return;
  try {
    await api('delete_order',{id});
    toast('Order deleted','success');
    closeModal('order-modal');
    loadOrders(ordersPage);
    loadDashboard();
  } catch(e){ toast(e.message,'error'); }
}

// ══════════════════════════════════════════════════════════
//  CLIENTS
// ══════════════════════════════════════════════════════════
async function loadClients(page=1) {
  clientsPage = page;
  const search = document.getElementById('global-search')?.value||'';
  const tbody  = document.getElementById('clients-tbody');
  tbody.innerHTML = '<tr class="loading-row"><td colspan="7"><div class="skeleton" style="height:16px"></div></td></tr>';
  try {
    const d = await api('clients',{page, search});
    if(!d.clients.length){tbody.innerHTML='<tr><td colspan="7" style="text-align:center;color:var(--muted);padding:32px">No clients found</td></tr>';return;}
    tbody.innerHTML = d.clients.map(c=>`<tr>
      <td class="td-name">${esc(c.full_name)}</td>
      <td class="td-mono">${esc(c.mobile)}</td>
      <td style="color:var(--muted)">${esc(c.email||'—')}</td>
      <td>${esc(c.city||'—')}</td>
      <td style="text-align:center">${c.order_count}</td>
      <td>${fmt(c.total_paid)}</td>
      <td style="color:var(--muted);font-size:12px">${fmtDate(c.created_at)}</td>
    </tr>`).join('');
    renderPagination('clients-pagination', d.total, d.page, d.pages);
  } catch(e){ toast(e.message,'error'); }
}

// ══════════════════════════════════════════════════════════
//  REVENUE
// ══════════════════════════════════════════════════════════
async function loadRevenue() {
  const range = document.getElementById('revenue-range')?.value||'30';
  try {
    const d = await api('revenue',{range});
    const t = d.totals||{};
    document.getElementById('revenue-stats').innerHTML = `
      <div class="stat-card" style="--accent-color:var(--accent)">
        <div class="stat-label">Total Revenue</div>
        <div class="stat-value">${fmt(t.total)}</div>
        <div class="stat-sub">Last ${range} days</div>
      </div>
      <div class="stat-card" style="--accent-color:var(--blue)">
        <div class="stat-label">Subtotal</div>
        <div class="stat-value">${fmt(t.subtotal)}</div>
        <div class="stat-sub">Before GST</div>
      </div>
      <div class="stat-card" style="--accent-color:var(--amber)">
        <div class="stat-label">GST Collected</div>
        <div class="stat-value">${fmt(t.gst)}</div>
        <div class="stat-sub">18% GST</div>
      </div>
      <div class="stat-card" style="--accent-color:var(--purple)">
        <div class="stat-label">Paid Orders</div>
        <div class="stat-value">${t.count||0}</div>
        <div class="stat-sub">Successful payments</div>
      </div>`;

    const days  = d.daily;
    const dLabels = days.map(r=>r.d);
    const dVals   = days.map(r=>parseFloat(r.revenue||0));

    if(dailyRevInst) dailyRevInst.destroy();
    dailyRevInst = new Chart(document.getElementById('dailyRevenueChart'),{
      type:'line',
      data:{labels:dLabels,datasets:[{label:'Revenue',data:dVals,fill:true,backgroundColor:'rgba(74,222,128,.1)',borderColor:'rgba(74,222,128,.8)',borderWidth:2,tension:.3,pointRadius:3,pointBackgroundColor:'var(--accent)'}]},
      options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},scales:{x:{ticks:{color:'#6b8f72',font:{size:10}},grid:{display:false}},y:{ticks:{color:'#6b8f72',font:{size:10},callback:v=>'₹'+Number(v).toLocaleString('en-IN')},grid:{color:'rgba(255,255,255,.04)'}}}}
    });

    if(planRevInst) planRevInst.destroy();
    planRevInst = new Chart(document.getElementById('planRevenueChart'),{
      type:'doughnut',
      data:{labels:d.by_plan.map(r=>r.plan),datasets:[{data:d.by_plan.map(r=>parseFloat(r.rev)),backgroundColor:['rgba(107,143,114,.7)','rgba(96,165,250,.7)','rgba(192,132,252,.7)'],borderWidth:0}]},
      options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{position:'bottom',labels:{color:'#6b8f72',font:{size:11},boxWidth:10}}}}
    });

    if(typeRevInst) typeRevInst.destroy();
    typeRevInst = new Chart(document.getElementById('typeRevenueChart'),{
      type:'bar',
      data:{labels:d.by_type.map(r=>r.will_type),datasets:[{label:'Revenue',data:d.by_type.map(r=>parseFloat(r.rev)),backgroundColor:'rgba(96,165,250,.5)',borderColor:'rgba(96,165,250,.8)',borderWidth:1,borderRadius:4}]},
      options:{responsive:true,maintainAspectRatio:false,indexAxis:'y',plugins:{legend:{display:false}},scales:{x:{ticks:{color:'#6b8f72',font:{size:10},callback:v=>'₹'+Number(v).toLocaleString('en-IN')},grid:{color:'rgba(255,255,255,.04)'}},y:{ticks:{color:'#6b8f72',font:{size:10}},grid:{display:false}}}}
    });
  } catch(e){ toast(e.message,'error'); }
}

// ══════════════════════════════════════════════════════════
//  EXPORT CSV
// ══════════════════════════════════════════════════════════
async function exportCSV(type) {
  try {
    toast('Preparing export…');
    const d = await api('export_csv',{type});
    const blob = new Blob([d.csv],{type:'text/csv'});
    const url  = URL.createObjectURL(blob);
    const a    = document.createElement('a');
    a.href=url; a.download=d.filename; a.click();
    URL.revokeObjectURL(url);
    toast('Downloaded: '+d.filename,'success');
  } catch(e){ toast(e.message,'error'); }
}

// ══════════════════════════════════════════════════════════
//  ADMINS
// ══════════════════════════════════════════════════════════
async function loadAdmins() {
  try {
    const d = await api('admins');
    const tbody = document.getElementById('admins-tbody');
    tbody.innerHTML = d.admins.map(a=>`<tr>
      <td class="td-name">${esc(a.full_name)}</td>
      <td class="td-mono">${esc(a.username)}</td>
      <td><span class="badge ${a.role==='admin'?'badge-paid':'badge-submitted'}">${a.role==='admin'?'Admin':'Staff'}</span></td>
      <td style="color:var(--muted);font-size:12px">${fmtDate(a.last_login)||'Never'}</td>
      <td><span class="badge ${a.is_active?'badge-paid':'badge-cancelled'}">${a.is_active?'Active':'Inactive'}</span></td>
      <td><button class="btn btn-ghost btn-sm" onclick="toggleAdmin(${a.id})">${a.is_active?'Disable':'Enable'}</button></td>
    </tr>`).join('');
  } catch(e){ toast(e.message,'error'); }
}

function openAddAdmin(){document.getElementById('add-admin-modal').classList.add('open');}

async function submitAddAdmin(){
  const fn=document.getElementById('new-fname').value.trim();
  const un=document.getElementById('new-uname').value.trim();
  const em=document.getElementById('new-email').value.trim();
  const pw=document.getElementById('new-pwd').value;
  const role=document.querySelector('input[name="new-role"]:checked')?.value||'staff';
  try{
    await api('add_admin',{full_name:fn,username:un,email:em,password:pw,role});
    toast((role==='admin'?'Admin':'Staff')+' account created','success');
    closeModal('add-admin-modal');
    loadAdmins();
    ['new-fname','new-uname','new-email','new-pwd'].forEach(id=>document.getElementById(id).value='');
    document.getElementById('new-role-staff').checked=true;
  }catch(e){toast(e.message,'error');}
}

async function toggleAdmin(id){
  try{await api('toggle_admin',{id});toast('Updated','success');loadAdmins();}
  catch(e){toast(e.message,'error');}
}

async function changePassword(){
  const cur=document.getElementById('pwd-current').value;
  const nw=document.getElementById('pwd-new').value;
  try{
    await api('change_password',{current:cur,new:nw});
    toast('Password changed','success');
    document.getElementById('pwd-current').value='';
    document.getElementById('pwd-new').value='';
  }catch(e){toast(e.message,'error');}
}

// ══════════════════════════════════════════════════════════
//  MODAL HELPERS
// ══════════════════════════════════════════════════════════
function closeModal(id){document.getElementById(id).classList.remove('open');}
document.querySelectorAll('.modal-overlay').forEach(el=>{
  el.addEventListener('click',function(e){if(e.target===this)this.classList.remove('open');});
});

// ══════════════════════════════════════════════════════════
//  LOGOUT
// ══════════════════════════════════════════════════════════
async function doLogout(){
  await fetch('api.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({action:'logout'})});
  window.location.href='login.php';
}

// ══════════════════════════════════════════════════════════
//  DISPATCH
// ══════════════════════════════════════════════════════════
async function loadDispatch(orderId) {
  const body = document.getElementById('dispatch-body-' + orderId);
  if (!body) return;
  try {
    const d  = await api('get_dispatch', {id: orderId});
    const ds = d.dispatch || {};
    const isDispatched = ds.dispatched == 1;
    // Format stored datetime-local value
    let dtVal = '';
    if (ds.dispatch_datetime) {
      // MySQL returns "YYYY-MM-DD HH:MM:SS" — convert to "YYYY-MM-DDTHH:MM" for input
      dtVal = ds.dispatch_datetime.slice(0,16).replace(' ','T');
    }
    body.innerHTML = `
      ${isDispatched ? `
      <div style="display:flex;align-items:center;gap:10px;background:rgba(74,222,128,.08);border:1px solid rgba(74,222,128,.25);border-radius:10px;padding:10px 16px;margin-bottom:16px">
        <span style="font-size:16px">✅</span>
        <span style="font-weight:600;color:var(--accent)">Dispatched</span>
        ${ds.tracking_number?`<span style="margin-left:auto;font-family:monospace;font-size:12px;color:var(--muted)">Tracking: ${esc(ds.tracking_number)}</span>`:''}
      </div>` : `
      <div style="display:flex;align-items:center;gap:10px;background:rgba(251,191,36,.06);border:1px solid rgba(251,191,36,.2);border-radius:10px;padding:10px 16px;margin-bottom:16px">
        <span style="font-size:16px">🕐</span>
        <span style="font-weight:600;color:var(--amber)">Pending Dispatch</span>
      </div>`}

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px">
        <div class="form-field">
          <label>Courier Tracking Number</label>
          <input id="d-tracking-${orderId}" type="text" placeholder="e.g. EE123456789IN" value="${esc(ds.tracking_number||'')}">
        </div>
        <div class="form-field">
          <label>Dispatch Date &amp; Time</label>
          <input id="d-datetime-${orderId}" type="datetime-local" value="${dtVal}">
        </div>
        <div class="form-field" style="grid-column:1/-1">
          <label>Admin Notes (Visible to Customer)</label>
          <textarea id="d-notes-${orderId}"
            style="background:var(--card);border:1px solid var(--border2);border-radius:8px;padding:10px 14px;color:var(--text);font-family:'DM Sans',sans-serif;font-size:13px;resize:vertical;min-height:80px;outline:none;width:100%"
            placeholder="e.g. Sent via Speed Post. Expected delivery in 3-5 working days.">${esc(ds.admin_notes||'')}</textarea>
        </div>
      </div>

      <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">
        <button onclick="saveDispatch(${orderId}, false)"
          style="display:inline-flex;align-items:center;gap:8px;background:var(--panel);color:var(--text);border:1px solid var(--border2);border-radius:8px;padding:10px 18px;font-family:'DM Sans',sans-serif;font-size:13px;font-weight:600;cursor:pointer">
          💾 Save Details
        </button>
        <button onclick="saveDispatch(${orderId}, true)"
          style="display:inline-flex;align-items:center;gap:8px;background:linear-gradient(135deg,#0f4a2e,#166534);color:#4ade80;border:1px solid rgba(74,222,128,.3);border-radius:8px;padding:10px 20px;font-family:'DM Sans',sans-serif;font-size:13px;font-weight:700;cursor:pointer">
          🚚 Save &amp; Mark as Dispatched
        </button>
        ${ds.tracking_number?`<a href="https://www.indiapost.gov.in/VAS/Pages/trackconsignment.aspx" target="_blank"
          style="display:inline-flex;align-items:center;gap:6px;background:var(--panel);color:var(--muted);border:1px solid var(--border2);border-radius:8px;padding:10px 14px;font-size:12px;font-weight:600;text-decoration:none">
          🔍 Track
        </a>`:''}
        <span id="d-status-${orderId}" style="font-size:12px"></span>
      </div>`;
  } catch(e) {
    if(body) body.innerHTML = `<p style="color:var(--red);font-size:13px">Could not load dispatch details: ${e.message}</p>`;
  }
}

async function saveDispatch(orderId, markDispatched) {
  const g = id => (document.getElementById(id)||{}).value||'';
  const statusEl = document.getElementById('d-status-' + orderId);
  if(statusEl){ statusEl.style.color='var(--blue)'; statusEl.textContent='Saving…'; }
  try {
    const res = await api('save_dispatch', {
      id:                orderId,
      tracking_number:   g('d-tracking-'+orderId),
      dispatch_datetime: g('d-datetime-'+orderId),
      admin_notes:       g('d-notes-'+orderId),
      mark_dispatched:   markDispatched ? 1 : 0,
    });
    if(statusEl){ statusEl.style.color='var(--accent)'; statusEl.textContent='✓ '+res.message; setTimeout(()=>{ if(statusEl) statusEl.textContent=''; },3000); }
    toast(res.message, 'success');
    loadDispatch(orderId);
    if(currentView==='orders') loadOrders(ordersPage);
  } catch(e) {
    if(statusEl){ statusEl.style.color='var(--red)'; statusEl.textContent='✗ '+e.message; }
    toast(e.message, 'error');
  }
}

// ══════════════════════════════════════════════════════════
//  FINAL WILL UPLOAD
// ══════════════════════════════════════════════════════════
async function uploadFinalWill(orderId, input) {
  if (!input.files || !input.files[0]) return;
  const file = input.files[0];
  const statusEl = document.getElementById('fw-status-' + orderId);

  if (file.type !== 'application/pdf') {
    toast('Only PDF files are allowed for the final will.', 'error'); input.value=''; return;
  }
  if (file.size > 20 * 1024 * 1024) {
    toast('File too large. Maximum 20 MB.', 'error'); input.value=''; return;
  }

  statusEl.style.display = 'block';
  statusEl.style.background = 'rgba(96,165,250,.1)';
  statusEl.style.border = '1px solid rgba(96,165,250,.2)';
  statusEl.style.color = 'var(--blue)';
  statusEl.textContent = '⏳ Uploading ' + file.name + '…';

  const formData = new FormData();
  formData.append('order_id', orderId);
  formData.append('file', file);

  try {
    const res  = await fetch('upload_final_will.php', { method: 'POST', body: formData });
    const json = await res.json();
    if (json.success) {
      statusEl.style.background = 'rgba(74,222,128,.1)';
      statusEl.style.border     = '1px solid rgba(74,222,128,.25)';
      statusEl.style.color      = 'var(--accent)';
      statusEl.innerHTML = `✅ Uploaded successfully! <br>
        <span style="font-size:11px;color:var(--muted)">Customer download link:</span><br>
        <a href="${json.download_url}" target="_blank" style="color:var(--accent);font-size:11px;word-break:break-all">${json.download_url}</a>
        <button onclick="navigator.clipboard.writeText('${json.download_url}');toast('Link copied!','success')"
          style="margin-left:8px;background:var(--panel);border:1px solid var(--border2);border-radius:4px;color:var(--muted);padding:2px 8px;font-size:11px;cursor:pointer">Copy</button>`;
      toast('Final will uploaded. Order marked as completed.', 'success');
      // Reload detail after 1.5s to show updated state
      setTimeout(() => openOrder(orderId), 1500);
    } else {
      throw new Error(json.error || 'Upload failed');
    }
  } catch(err) {
    statusEl.style.background = 'rgba(248,113,113,.1)';
    statusEl.style.border     = '1px solid rgba(248,113,113,.25)';
    statusEl.style.color      = 'var(--red)';
    statusEl.textContent = '✗ ' + err.message;
    toast(err.message, 'error');
  }
  input.value = '';
}

// ══════════════════════════════════════════════════════════
//  INIT
// ══════════════════════════════════════════════════════════
const ROLE = document.body.dataset.role || 'staff';
const IS_ADMIN = ROLE === 'admin';

// Hide revenue stat card for staff after render
function applyRoleRestrictions() {
  if (!IS_ADMIN) {
    // Hide revenue stat card (first card in stats-grid)
    const grid = document.getElementById('stats-grid');
    if (grid && grid.children[0]) grid.children[0].style.display = 'none';
    // Hide export CSV buttons
    document.querySelectorAll('[onclick*="exportCSV"]').forEach(b => b.style.display = 'none');
  }
}

loadDashboard();
</script>
</body>
</html>
