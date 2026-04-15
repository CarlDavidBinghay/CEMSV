<?php
session_start();

if(!isset($_SESSION['user_id'])){ header("Location: login.php"); exit(); }

$allowed_roles = ['Non-Teaching Staff'];
if(!in_array($_SESSION['role'], $allowed_roles)){
    $admin_roles = ['admin','Admin','Developer','Director'];
    $coord_roles = ['Dept. Coordinator'];
    $ts_roles    = ['Teaching Staff'];
    if(in_array($_SESSION['role'], $admin_roles)) header("Location: dashboard.php");
    elseif(in_array($_SESSION['role'], $coord_roles)) header("Location: coordinator.php");
    elseif(in_array($_SESSION['role'], $ts_roles)) header("Location: teaching_staff.php");
    else { session_destroy(); session_start(); $_SESSION['login_error']='Access denied.'; header("Location: login.php"); }
    exit();
}

if(isset($_GET['section']) && $_GET['section'] === 'logout'){ session_destroy(); header("Location: login.php"); exit(); }

$allowed_sections = ['profile','location','activity','project','feedback'];
$section = isset($_GET['section']) && in_array($_GET['section'], $allowed_sections) ? $_GET['section'] : 'profile';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CEMS - Non-Teaching Staff Portal</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
<style>
:root {
    --green:  #1DB954;
    --green2: #16a34a;
    --purple: #8B2FC9;
    --pink:   #E91E8C;
    --yellow: #F5A623;
    --red:    #E32636;
    --blue:   #1565C0;
    --dark:   #0a2e1a;
    --teal:   #00B4A0;
}
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

body {
    min-height: 100vh;
    background: linear-gradient(145deg, #000000 0%, #1a1a1a 25%, #2d2d2d 50%, #1a1a1a 75%, #000000 100%);
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    overflow-x: hidden;
}

/* ── Sidebar (white — matches screenshot) ── */
.mac-sidebar {
    background: rgba(10,10,20,0.75);
    backdrop-filter: blur(50px) saturate(200%);
    -webkit-backdrop-filter: blur(50px) saturate(200%);
    border-right: 1px solid rgba(29,185,84,0.15);
    box-shadow: 4px 0 40px rgba(0,0,0,0.5);
    width: 148px;
    position: fixed; top: 0; left: 0; bottom: 0;
    display: flex; flex-direction: column;
    padding: 1rem 0.75rem;
    z-index: 40; overflow-y: auto;
}

/* Logo area in sidebar */
.sidebar-brand {
    display: flex; align-items: center; gap: 8px;
    padding: 0.5rem 0.25rem; margin-bottom: 1.2rem;
}
.sidebar-brand-icon {
    width: 28px; height: 28px; border-radius: 7px;
    background: linear-gradient(135deg, var(--green), var(--teal));
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.sidebar-brand-icon img { width: 20px; height: 20px; object-fit: contain; }
.sidebar-brand-text { font-size: 0.85rem; font-weight: 800; color: white; letter-spacing: -0.3px; line-height: 1.1; }
.sidebar-brand-sub { font-size: 0.55rem; color: rgba(29,185,84,0.6); font-weight: 600; }

/* User card in sidebar */
.sidebar-user {
    background: rgba(255,255,255,0.06);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 10px; padding: 0.6rem;
    margin-bottom: 1.2rem;
    display: flex; flex-direction: column; align-items: center; gap: 4px;
}
.sidebar-avatar {
    width: 36px; height: 36px; border-radius: 9px;
    background: linear-gradient(135deg, var(--green), var(--teal));
    display: flex; align-items: center; justify-content: center;
    font-weight: 800; font-size: 0.95rem; color: white;
}
.sidebar-uname { font-size: 0.7rem; font-weight: 700; color: white; text-align: center; line-height: 1.2; }
.sidebar-urole { font-size: 0.55rem; color: rgba(255,255,255,0.4); text-align: center; display: flex; align-items: center; gap: 3px; justify-content: center; }
.online-dot { width: 5px; height: 5px; border-radius: 50%; background: var(--green); box-shadow: 0 0 5px var(--green); animation: glow 2s infinite; display: inline-block; }
@keyframes glow { 0%,100%{box-shadow:0 0 0 0 rgba(29,185,84,0.5);} 50%{box-shadow:0 0 0 4px rgba(29,185,84,0);} }

/* Sidebar nav */
.nav-label { font-size: 0.5rem; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: rgba(255,255,255,0.22); padding: 0.5rem 0.5rem 0.1rem; display: block; }
.nav-divider { height: 1px; background: rgba(255,255,255,0.07); margin: 0.35rem 0.25rem; }
.nav-item {
    display: flex; flex-direction: column; align-items: center; gap: 3px;
    padding: 0.5rem 0.25rem; border-radius: 9px;
    text-decoration: none; font-size: 0.62rem; font-weight: 600;
    color: rgba(255,255,255,0.5); transition: all 0.2s ease;
    margin-bottom: 2px; border: 1px solid transparent; text-align: center;
}
.nav-item:hover { background: rgba(255,255,255,0.07); color: rgba(255,255,255,0.9); }
.nav-item.active {
    background: rgba(29,185,84,0.18);
    border-color: rgba(29,185,84,0.35);
    color: white; font-weight: 700;
    box-shadow: 0 0 12px rgba(29,185,84,0.15);
}
.nav-item i { font-size: 1.15rem; color: rgba(255,255,255,0.4); }
.nav-item.active i { color: var(--green); }
.nav-item:hover i { color: rgba(255,255,255,0.85); }

.logout-btn {
    display: flex; flex-direction: column; align-items: center; gap: 3px;
    padding: 0.5rem 0.25rem; border-radius: 9px;
    text-decoration: none; font-size: 0.62rem; font-weight: 600;
    color: rgba(255,100,100,0.65); transition: all 0.2s ease; margin-top: auto;
}
.logout-btn:hover { background: rgba(255,60,60,0.1); color: #f87171; }
.logout-btn i { font-size: 1.1rem; }
.version-badge { text-align: center; font-size: 0.5rem; color: rgba(255,255,255,0.18); padding: 0.3rem 0; }

/* ── Top bar ── */
.mac-topbar {
    background: rgba(10,20,15,0.7);
    backdrop-filter: blur(30px);
    border-bottom: 1px solid rgba(29,185,84,0.1);
    padding: 0.6rem 1.5rem;
    display: flex; align-items: center; justify-content: space-between;
    position: sticky; top: 0; z-index: 30;
}
.topbar-date { font-size: 0.78rem; color: rgba(255,255,255,0.45); font-weight: 500; }
.topbar-divider { width: 1px; height: 14px; background: rgba(255,255,255,0.15); margin: 0 0.75rem; }
.topbar-time { font-size: 0.78rem; color: rgba(255,255,255,0.45); font-variant-numeric: tabular-nums; }
.sys-status { display: flex; align-items: center; gap: 6px; font-size: 0.7rem; color: rgba(255,255,255,0.3); }
.refresh-btn {
    background: rgba(29,185,84,0.12); border: 1px solid rgba(29,185,84,0.25);
    border-radius: 8px; padding: 6px 14px; color: white; font-size: 0.75rem;
    font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 5px;
    transition: all 0.2s;
}
.refresh-btn:hover { background: rgba(29,185,84,0.22); transform: translateY(-1px); }

/* ── Page heading ── */
.page-heading { margin-bottom: 1.25rem; }
.page-title {
    font-size: 1.5rem; font-weight: 700; letter-spacing: -0.5px;
    background: linear-gradient(135deg, #fff, #aaa);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
}
.page-sub { font-size: 0.75rem; color: rgba(255,255,255,0.35); margin-top: 3px; display: flex; align-items: center; gap: 5px; }

/* ── Section glass card (matches dark card in screenshot) ── */
.glass-card {
    background: rgba(30,30,40,0.6);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 14px;
    overflow: hidden;
}

/* ── Section header gradient (matches screenshot top banner) ── */
.section-header {
    background: linear-gradient(135deg, #212529 0%, #343A40 50%, #495057 100%);
    padding: 1.25rem 1.5rem;
    position: relative; overflow: hidden;
}
.section-header::before {
    content: '';
    position: absolute; top: -80px; right: -40px;
    width: 220px; height: 220px;
    background: rgba(255,255,255,0.04); border-radius: 50%;
}
.section-header-badge {
    display: inline-flex; align-items: center; gap: 5px;
    background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.15);
    border-radius: 50px; padding: 3px 10px;
    font-size: 0.65rem; font-weight: 700; color: rgba(255,255,255,0.7);
    letter-spacing: 0.08em; text-transform: uppercase; margin-bottom: 0.5rem;
}
.section-title {
    font-size: 1.8rem; font-weight: 800; color: white; letter-spacing: -0.5px;
    display: flex; align-items: center; gap: 0.5rem;
}
.section-title span { color: rgba(255,255,255,0.4); font-weight: 400; font-size: 1.1rem; }
.section-desc { font-size: 0.75rem; color: rgba(255,255,255,0.4); margin-top: 4px; display: flex; align-items: center; gap: 5px; }

/* ── Stat cards (like screenshot colored cards) ── */
.stat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px,1fr)); gap: 0.75rem; padding: 1rem 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.06); }
.stat-card {
    background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.07);
    border-radius: 12px; padding: 1rem;
    display: flex; align-items: flex-start; justify-content: space-between;
    position: relative; overflow: hidden;
}
.stat-card::before { content: ''; position: absolute; top: 0; right: 0; width: 80px; height: 80px; border-radius: 50%; filter: blur(30px); }
.stat-card.green::before { background: rgba(29,185,84,0.15); }
.stat-card.blue::before  { background: rgba(59,130,246,0.15); }
.stat-card.teal::before  { background: rgba(0,180,160,0.15); }
.stat-card.yellow::before { background: rgba(245,166,35,0.15); }
.stat-label { font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: rgba(255,255,255,0.4); margin-bottom: 0.3rem; }
.stat-value { font-size: 1.8rem; font-weight: 800; color: white; line-height: 1; }
.stat-sub { font-size: 0.65rem; color: rgba(255,255,255,0.35); margin-top: 4px; }
.stat-icon { width: 36px; height: 36px; border-radius: 9px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; color: white; flex-shrink: 0; }

/* ── Content area ── */
.content-area { padding: 1.25rem 1.5rem; }

/* ── Search / filter bar ── */
.filter-bar {
    background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);
    border-radius: 12px; padding: 0.875rem 1.25rem;
    display: flex; align-items: center; gap: 0.75rem;
    margin-bottom: 1rem;
}
.search-input {
    flex: 1; background: rgba(0,0,0,0.25); border: 1px solid rgba(255,255,255,0.08);
    border-radius: 8px; padding: 0.55rem 0.9rem 0.55rem 2.2rem;
    font-size: 0.8rem; color: white; outline: none; font-family: inherit;
    transition: all 0.2s;
}
.search-input::placeholder { color: rgba(255,255,255,0.25); }
.search-input:focus { border-color: var(--green); box-shadow: 0 0 0 2px rgba(29,185,84,0.1); }
.search-wrap { position: relative; flex: 1; }
.search-wrap i { position: absolute; left: 0.7rem; top: 50%; transform: translateY(-50%); color: rgba(255,255,255,0.3); font-size: 0.9rem; }

/* ── Table ── */
.data-table { width: 100%; border-collapse: collapse; }
.data-table thead tr { background: rgba(0,0,0,0.25); border-bottom: 1px solid rgba(255,255,255,0.07); }
.data-table th { padding: 0.65rem 1rem; font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: rgba(255,255,255,0.4); text-align: left; white-space: nowrap; }
.data-table td { padding: 0.75rem 1rem; font-size: 0.82rem; color: rgba(255,255,255,0.75); border-bottom: 1px solid rgba(255,255,255,0.04); }
.data-table tr:hover td { background: rgba(255,255,255,0.03); }

/* ── Badges ── */
.badge { display: inline-flex; align-items: center; padding: 0.2rem 0.65rem; border-radius: 50px; font-size: 0.68rem; font-weight: 700; }
.badge-green  { background: rgba(29,185,84,0.15); color: #4ade80; border: 1px solid rgba(29,185,84,0.3); }
.badge-blue   { background: rgba(59,130,246,0.15); color: #60a5fa; border: 1px solid rgba(59,130,246,0.3); }
.badge-yellow { background: rgba(245,166,35,0.15); color: #fbbf24; border: 1px solid rgba(245,166,35,0.3); }
.badge-gray   { background: rgba(255,255,255,0.06); color: rgba(255,255,255,0.4); border: 1px solid rgba(255,255,255,0.1); }
.badge-purple { background: rgba(139,47,201,0.15); color: #c084fc; border: 1px solid rgba(139,47,201,0.3); }

/* ── Join / action buttons ── */
.join-btn {
    padding: 0.35rem 0.85rem; border-radius: 7px; font-size: 0.72rem;
    font-weight: 700; cursor: pointer; transition: all 0.2s;
    background: rgba(29,185,84,0.12); border: 1px solid rgba(29,185,84,0.3);
    color: #4ade80; font-family: inherit;
}
.join-btn:hover { background: var(--green); color: white; border-color: var(--green); transform: translateY(-1px); }

/* ── Form inputs ── */
.form-group { margin-bottom: 1.1rem; }
.form-label { display: block; font-size: 0.65rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: rgba(255,255,255,0.4); margin-bottom: 0.4rem; }
.form-input {
    width: 100%; background: rgba(0,0,0,0.25);
    border: 1px solid rgba(255,255,255,0.1); border-radius: 10px;
    padding: 0.7rem 1rem; font-size: 0.85rem; color: white; outline: none;
    transition: all 0.2s; font-family: inherit;
}
.form-input::placeholder { color: rgba(255,255,255,0.2); }
.form-input:focus { border-color: var(--green); box-shadow: 0 0 0 3px rgba(29,185,84,0.1); }
.form-input:disabled { opacity: 0.4; cursor: not-allowed; }
.form-input option { background: #1a2a1a; }

.btn-save {
    background: linear-gradient(135deg, var(--green), var(--green2));
    color: white; border: none; border-radius: 9px;
    padding: 0.7rem 1.8rem; font-size: 0.875rem; font-weight: 700;
    cursor: pointer; transition: all 0.2s; font-family: inherit;
    box-shadow: 0 4px 14px rgba(29,185,84,0.3); display: inline-flex; align-items: center; gap: 6px;
}
.btn-save:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(29,185,84,0.4); }

/* Alerts */
.alert { display: flex; align-items: center; gap: 8px; padding: 0.7rem 1rem; border-radius: 9px; font-size: 0.82rem; font-weight: 600; margin-bottom: 1rem; }
.alert-success { background: rgba(29,185,84,0.1); border: 1px solid rgba(29,185,84,0.3); color: #4ade80; }
.alert-error   { background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); color: #f87171; }

/* Tab buttons */
.tab-wrap { display: flex; gap: 0.5rem; margin-bottom: 1rem; }
.tab-btn {
    padding: 0.45rem 1rem; border-radius: 8px; font-size: 0.78rem; font-weight: 600;
    cursor: pointer; transition: all 0.2s; border: 1px solid rgba(255,255,255,0.1);
    background: transparent; color: rgba(255,255,255,0.4); font-family: inherit;
    display: flex; align-items: center; gap: 5px;
}
.tab-btn.active { background: rgba(29,185,84,0.18); border-color: rgba(29,185,84,0.35); color: white; }
.tab-btn:hover:not(.active) { background: rgba(255,255,255,0.06); color: rgba(255,255,255,0.8); }

/* List rows */
.list-row {
    display: flex; align-items: center; justify-content: space-between; gap: 1rem;
    padding: 0.85rem 0; border-bottom: 1px solid rgba(255,255,255,0.05);
}
.list-row:last-child { border-bottom: none; }
.row-icon { width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1rem; color: white; flex-shrink: 0; }
.row-name { font-size: 0.85rem; font-weight: 700; color: white; }
.row-sub  { font-size: 0.72rem; color: rgba(255,255,255,0.35); margin-top: 2px; }

/* Location cards */
.loc-card {
    background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.07);
    border-radius: 11px; padding: 1rem; transition: all 0.2s;
}
.loc-card:hover { background: rgba(255,255,255,0.07); border-color: rgba(29,185,84,0.25); }

/* ── Custom Join Modal ── */
.modal-overlay {
    position: fixed; inset: 0; z-index: 999;
    background: rgba(0,0,0,0.7); backdrop-filter: blur(6px);
    display: flex; align-items: center; justify-content: center;
    opacity: 0; pointer-events: none; transition: opacity 0.2s;
}
.modal-overlay.show { opacity: 1; pointer-events: all; }
.modal-box {
    background: linear-gradient(135deg, #1e2228 0%, #16191e 100%);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 18px;
    padding: 2rem;
    width: 100%; max-width: 400px; margin: 1rem;
    box-shadow: 0 24px 64px rgba(0,0,0,0.5);
    transform: scale(0.95) translateY(10px);
    transition: all 0.25s cubic-bezier(0.175,0.885,0.32,1.275);
    text-align: center;
}
.modal-overlay.show .modal-box { transform: scale(1) translateY(0); }
.modal-icon-wrap {
    width: 64px; height: 64px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 1.25rem;
    font-size: 1.8rem;
}
.modal-title { font-size: 1.15rem; font-weight: 800; color: white; margin-bottom: 0.4rem; }
.modal-msg { font-size: 0.85rem; color: rgba(255,255,255,0.45); margin-bottom: 1.5rem; line-height: 1.5; }
.modal-name { color: white; font-weight: 700; }
.modal-btns { display: flex; gap: 0.75rem; justify-content: center; }
.modal-btn-cancel {
    flex: 1; padding: 0.7rem 1rem; border-radius: 10px; font-size: 0.85rem;
    font-weight: 700; cursor: pointer; font-family: inherit;
    background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.1);
    color: rgba(255,255,255,0.6); transition: all 0.2s;
}
.modal-btn-cancel:hover { background: rgba(255,255,255,0.12); color: white; }
.modal-btn-confirm {
    flex: 1; padding: 0.7rem 1rem; border-radius: 10px; font-size: 0.85rem;
    font-weight: 700; cursor: pointer; font-family: inherit;
    border: none; color: white; transition: all 0.2s;
    box-shadow: 0 4px 14px rgba(29,185,84,0.3);
}
.modal-btn-confirm:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(29,185,84,0.4); }

/* Loading spinner on confirm */
.modal-btn-confirm.loading { opacity: 0.7; pointer-events: none; }
.modal-btn-confirm.loading::after { content: ' ⟳'; animation: spin 0.8s linear infinite; display: inline-block; }
@keyframes spin { to { transform: rotate(360deg); } }

/* Toast notification */
.toast {
    position: fixed; bottom: 1.5rem; right: 1.5rem; z-index: 9999;
    background: linear-gradient(135deg, #1e2228, #16191e);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 12px; padding: 0.85rem 1.25rem;
    display: flex; align-items: center; gap: 10px;
    font-size: 0.85rem; font-weight: 600; color: white;
    box-shadow: 0 8px 32px rgba(0,0,0,0.4);
    transform: translateY(20px); opacity: 0;
    transition: all 0.3s cubic-bezier(0.175,0.885,0.32,1.275);
    min-width: 260px;
}
.toast.show { transform: translateY(0); opacity: 1; }
.toast.success { border-color: rgba(29,185,84,0.4); }
.toast.error   { border-color: rgba(239,68,68,0.4); }
.toast-icon { font-size: 1.1rem; flex-shrink: 0; }
::-webkit-scrollbar-track { background: transparent; }
::-webkit-scrollbar-thumb { background: rgba(29,185,84,0.2); border-radius: 20px; }

/* Fade */
@keyframes fadeIn { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }
.fade-in { animation: fadeIn 0.3s ease-out both; }

/* Status bar */
.status-bar { margin-top: 1.5rem; font-size: 0.68rem; color: rgba(255,255,255,0.2); display: flex; justify-content: space-between; padding: 0 0.25rem; }

/* Mobile */
.mobile-topbar {
    position: fixed; top:0; left:0; right:0; z-index:50;
    background: rgba(10,20,15,0.9); backdrop-filter: blur(20px);
    border-bottom: 1px solid rgba(29,185,84,0.1);
    padding: 0.75rem 1rem;
    display: flex; align-items: center; justify-content: space-between;
}
</style>
</head>
<body>

<!-- ── SIDEBAR ── -->
<aside class="mac-sidebar hidden md:flex flex-col">

    <div class="sidebar-brand">
        <div class="sidebar-brand-icon">
            <img src="img/Community Logo.png" alt="CEMS">
        </div>
        <div>
            <div class="sidebar-brand-text">CEMS</div>
            <div class="sidebar-brand-sub">Staff Portal</div>
        </div>
    </div>

    <div class="sidebar-user">
        <div class="sidebar-avatar"><?php echo strtoupper(substr($_SESSION['fullname'] ?? 'U', 0, 1)); ?></div>
        <div class="sidebar-uname"><?php echo htmlspecialchars(explode(' ', $_SESSION['fullname'] ?? 'User')[0]); ?></div>
        <div class="sidebar-urole"><span class="online-dot"></span>Staff</div>
    </div>

    <span class="nav-label">Profile</span>
    <a href="non_teaching_staff.php?section=profile" class="nav-item <?php echo $section==='profile'?'active':''; ?>">
        <i class="ri-user-3-line"></i><span>Profile</span>
    </a>
    <div class="nav-divider"></div>

    <span class="nav-label">Community</span>
    <a href="non_teaching_staff.php?section=location" class="nav-item <?php echo $section==='location'?'active':''; ?>">
        <i class="ri-map-pin-line"></i><span>Locations</span>
    </a>
    <a href="non_teaching_staff.php?section=activity" class="nav-item <?php echo $section==='activity'?'active':''; ?>">
        <i class="ri-calendar-event-line"></i><span>Activities</span>
    </a>
    <a href="non_teaching_staff.php?section=project" class="nav-item <?php echo $section==='project'?'active':''; ?>">
        <i class="ri-folder-2-line"></i><span>Projects</span>
    </a>
    <div class="nav-divider"></div>

    <span class="nav-label">Reports</span>
    <a href="non_teaching_staff.php?section=feedback" class="nav-item <?php echo $section==='feedback'?'active':''; ?>">
        <i class="ri-feedback-line"></i><span>Feedback</span>
    </a>

    <div style="flex:1"></div>

    <a href="non_teaching_staff.php?section=logout" class="logout-btn">
        <i class="ri-logout-box-line"></i><span>Logout</span>
    </a>
    <div class="version-badge">CEMS v2.0</div>
</aside>

<!-- ── MOBILE TOP BAR ── -->
<div class="md:hidden mobile-topbar">
    <div class="flex items-center gap-2">
        <img src="img/Community Logo.png" class="w-7 h-7 rounded-lg object-contain">
        <span class="font-bold text-white text-sm">CEMS Staff</span>
    </div>
    <div class="flex items-center gap-2">
        <span class="text-xs text-white/40" id="mobileTime"></span>
        <button id="mobileMenuBtn" class="w-8 h-8 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-white">
            <i class="ri-menu-line"></i>
        </button>
    </div>
</div>

<!-- ── MOBILE SIDEBAR ── -->
<div id="mobileSidebar" class="md:hidden hidden fixed inset-0 z-50">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" id="mobileOverlay"></div>
    <div class="mac-sidebar absolute" style="width:148px;">
        <div class="sidebar-brand">
            <div class="sidebar-brand-icon"><img src="img/Community Logo.png" alt="CEMS"></div>
            <div><div class="sidebar-brand-text">CEMS</div></div>
        </div>
        <div class="sidebar-user">
            <div class="sidebar-avatar"><?php echo strtoupper(substr($_SESSION['fullname'] ?? 'U', 0, 1)); ?></div>
            <div class="sidebar-uname"><?php echo htmlspecialchars(explode(' ', $_SESSION['fullname'] ?? 'User')[0]); ?></div>
        </div>
        <a href="non_teaching_staff.php?section=profile" class="nav-item <?php echo $section==='profile'?'active':''; ?>"><i class="ri-user-3-line"></i><span>Profile</span></a>
        <div class="nav-divider"></div>
        <a href="non_teaching_staff.php?section=location" class="nav-item <?php echo $section==='location'?'active':''; ?>"><i class="ri-map-pin-line"></i><span>Locations</span></a>
        <a href="non_teaching_staff.php?section=activity" class="nav-item <?php echo $section==='activity'?'active':''; ?>"><i class="ri-calendar-event-line"></i><span>Activities</span></a>
        <a href="non_teaching_staff.php?section=project" class="nav-item <?php echo $section==='project'?'active':''; ?>"><i class="ri-folder-2-line"></i><span>Projects</span></a>
        <div class="nav-divider"></div>
        <a href="non_teaching_staff.php?section=feedback" class="nav-item <?php echo $section==='feedback'?'active':''; ?>"><i class="ri-feedback-line"></i><span>Feedback</span></a>
        <div style="flex:1;min-height:1rem"></div>
        <a href="non_teaching_staff.php?section=logout" class="logout-btn"><i class="ri-logout-box-line"></i><span>Logout</span></a>
    </div>
</div>

<!-- ── MAIN ── -->
<main class="md:ml-[148px] min-h-screen flex flex-col">

    <!-- Top bar -->
    <div class="mac-topbar hidden md:flex">
        <div class="flex items-center">
            <span class="topbar-date"><?php echo date('l, F j, Y'); ?></span>
            <div class="topbar-divider"></div>
            <span class="topbar-time" id="currentTime"></span>
        </div>
        <div class="flex items-center gap-3">
            <span class="sys-status">
                <span class="w-2 h-2 rounded-full animate-pulse" style="background:var(--green);box-shadow:0 0 6px var(--green)"></span>
                System Online
            </span>
            <button onclick="window.location.reload()" class="refresh-btn">
                <i class="ri-refresh-line"></i> Refresh
            </button>
        </div>
    </div>

    <!-- Content -->
    <div class="flex-1 p-4 md:p-6 mt-14 md:mt-0 fade-in">

        <?php
        $pageInfo = [
            'profile'  => ['My Profile',     'PROFILE',    'ri-user-3-line',           'Create and update your personal profile'],
            'location' => ['View Locations',  'LOCATIONS',  'ri-map-pin-line',           'Browse available community extension locations'],
            'activity' => ['Activities',      'ACTIVITIES', 'ri-calendar-event-line',    'View and join community activities'],
            'project'  => ['Projects',        'PROJECTS',   'ri-folder-2-line',          'View and join community projects'],
            'feedback' => ['My Feedback',     'FEEDBACK',   'ri-feedback-line',          'All your submitted feedback'],
        ];
        $pi = $pageInfo[$section] ?? ['Dashboard','PORTAL','ri-dashboard-line',''];
        ?>

        <!-- Page heading -->
        <div class="page-heading">
            <h1 class="page-title"><?php echo $pi[0]; ?></h1>
            <p class="page-sub"><i class="ri-information-line"></i><?php echo $pi[3]; ?></p>
        </div>

        <!-- Main glass card -->
        <div class="glass-card">

            <!-- Section header (gradient, matches screenshot) -->
            <div class="section-header">
                <div class="section-header-badge">
                    <i class="<?php echo $pi[2]; ?>"></i>
                    <?php echo $pi[1]; ?>
                    <?php if($section==='activity'||$section==='project'||$section==='location'||$section==='feedback'): ?>
                    <span style="margin-left:4px;">•</span>
                    <span id="headerCount">0 Total</span>
                    <?php endif; ?>
                </div>
                <div class="section-title">
                    <?php echo $pi[0]; ?>
                    <span>/ <?php echo ucfirst($section); ?></span>
                </div>
                <div class="section-desc">
                    <i class="ri-sparkling-line" style="color:var(--green)"></i>
                    <?php echo $pi[3]; ?>
                </div>
            </div>

            <?php if($section === 'profile'): ?>
            <!-- ── PROFILE SECTION ── -->
            <?php
            include_once 'db.php';
            $uid  = $_SESSION['user_id'];
            $urow = [];
            $uq   = $conn->prepare("SELECT fullname,email,phone,assignments,join_date,status FROM users WHERE id=?");
            if($uq){ $uq->bind_param("i",$uid); $uq->execute(); $urow = $uq->get_result()->fetch_assoc() ?? []; }
            $initials = strtoupper(implode('', array_map(fn($w)=>$w[0], array_slice(explode(' ', $urow['fullname']??'U'), 0, 2))));
            ?>

            <!-- Profile Hero Banner -->
            <div style="background:linear-gradient(135deg,#1a1f2e 0%,#212529 60%,#2d3748 100%);padding:2rem 2rem 0;position:relative;overflow:hidden;">
                <div style="position:absolute;top:-60px;right:-40px;width:220px;height:220px;background:rgba(29,185,84,0.06);border-radius:50%;"></div>
                <div style="position:absolute;top:20px;right:120px;width:120px;height:120px;background:rgba(21,101,192,0.08);border-radius:50%;"></div>
                <div style="position:absolute;bottom:-30px;left:200px;width:160px;height:160px;background:rgba(139,47,201,0.06);border-radius:50%;"></div>

                <div style="display:flex;align-items:flex-end;gap:1.5rem;position:relative;z-index:1;">
                    <!-- Avatar -->
                    <div style="position:relative;flex-shrink:0;">
                        <div id="avatarCircle" style="width:90px;height:90px;border-radius:50%;background:linear-gradient(135deg,#1DB954,#16a34a);display:flex;align-items:center;justify-content:center;font-size:2rem;font-weight:800;color:white;border:4px solid rgba(255,255,255,0.1);box-shadow:0 8px 32px rgba(29,185,84,0.3);cursor:pointer;transition:all 0.2s;" onclick="document.getElementById('avatarOptions').classList.toggle('hidden')" title="Change avatar color">
                            <?php echo $initials; ?>
                        </div>
                        <div style="position:absolute;bottom:2px;right:2px;width:18px;height:18px;background:#1DB954;border-radius:50%;border:2px solid #1a1f2e;box-shadow:0 0 8px rgba(29,185,84,0.6);"></div>
                        <!-- Avatar color picker -->
                        <div id="avatarOptions" class="hidden" style="position:absolute;top:100%;left:0;margin-top:8px;background:rgba(20,25,35,0.95);border:1px solid rgba(255,255,255,0.1);border-radius:12px;padding:0.75rem;display:flex;gap:6px;z-index:10;box-shadow:0 8px 32px rgba(0,0,0,0.4);">
                            <?php
                            $colors = [
                                ['#1DB954','#16a34a'],['#1565C0','#1976D2'],['#8B2FC9','#6d24a8'],
                                ['#E91E8C','#c4176f'],['#F5A623','#e08c0a'],['#E32636','#b81c28'],
                                ['#00B4A0','#00897B'],['#FF6B35','#e55a24']
                            ];
                            foreach($colors as $c): ?>
                            <div onclick="setAvatarColor('<?php echo $c[0]; ?>','<?php echo $c[1]; ?>')" style="width:22px;height:22px;border-radius:50%;background:linear-gradient(135deg,<?php echo $c[0]; ?>,<?php echo $c[1]; ?>);cursor:pointer;transition:transform 0.15s;" onmouseover="this.style.transform='scale(1.2)'" onmouseout="this.style.transform='scale(1)'"></div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Name & Role -->
                    <div style="padding-bottom:1.25rem;flex:1;">
                        <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                            <h2 style="font-size:1.5rem;font-weight:800;color:white;letter-spacing:-0.5px;"><?php echo htmlspecialchars($urow['fullname']??'User'); ?></h2>
                            <span style="background:rgba(29,185,84,0.15);border:1px solid rgba(29,185,84,0.3);border-radius:50px;padding:2px 12px;font-size:0.68rem;font-weight:700;color:#4ade80;">● Active</span>
                        </div>
                        <div style="display:flex;align-items:center;gap:1rem;margin-top:6px;flex-wrap:wrap;">
                            <span style="font-size:0.8rem;color:rgba(255,255,255,0.45);display:flex;align-items:center;gap:5px;"><i class="ri-shield-user-line" style="color:var(--green)"></i>Non-Teaching Staff</span>
                            <?php if(!empty($urow['assignments'])): ?>
                            <span style="font-size:0.8rem;color:rgba(255,255,255,0.45);display:flex;align-items:center;gap:5px;"><i class="ri-building-line" style="color:#60a5fa"></i><?php echo htmlspecialchars($urow['assignments']); ?></span>
                            <?php endif; ?>
                            <?php if(!empty($urow['join_date'])): ?>
                            <span style="font-size:0.8rem;color:rgba(255,255,255,0.35);display:flex;align-items:center;gap:5px;"><i class="ri-calendar-line" style="color:#fbbf24"></i>Joined <?php echo date('M Y', strtotime($urow['join_date'])); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Quick action buttons -->
                    <div style="padding-bottom:1.25rem;display:flex;gap:8px;flex-shrink:0;">
                        <button onclick="document.getElementById('profileForm').scrollIntoView({behavior:'smooth'})" style="background:rgba(29,185,84,0.15);border:1px solid rgba(29,185,84,0.3);border-radius:9px;padding:7px 14px;color:#4ade80;font-size:0.78rem;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:5px;transition:all 0.2s;" onmouseover="this.style.background='rgba(29,185,84,0.25)'" onmouseout="this.style.background='rgba(29,185,84,0.15)'">
                            <i class="ri-edit-line"></i> Edit Profile
                        </button>
                    </div>
                </div>
            </div>

            <!-- Info Cards Row -->
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:0.75rem;padding:1rem 1.5rem;border-bottom:1px solid rgba(255,255,255,0.06);">
                <div class="stat-card green">
                    <div><div class="stat-label">Role</div><div class="stat-value" style="font-size:0.85rem;margin-top:4px;line-height:1.3;">Teaching<br>Staff</div><div class="stat-sub">Staff member</div></div>
                    <div class="stat-icon" style="background:linear-gradient(135deg,#1DB954,#16a34a)"><i class="ri-user-star-line"></i></div>
                </div>
                <div class="stat-card blue">
                    <div><div class="stat-label">Department</div><div class="stat-value" style="font-size:0.72rem;margin-top:4px;line-height:1.4;"><?php echo htmlspecialchars(substr($urow['assignments']??'Not set',0,22)); ?></div></div>
                    <div class="stat-icon" style="background:linear-gradient(135deg,#1565C0,#1976D2)"><i class="ri-building-line"></i></div>
                </div>
                <div class="stat-card teal">
                    <div><div class="stat-label">Email</div><div class="stat-value" style="font-size:0.68rem;margin-top:4px;line-height:1.4;word-break:break-all;"><?php echo htmlspecialchars(substr($urow['email']??'Not set',0,24)); ?></div></div>
                    <div class="stat-icon" style="background:linear-gradient(135deg,#00B4A0,#00897B)"><i class="ri-mail-line"></i></div>
                </div>
                <div class="stat-card yellow">
                    <div><div class="stat-label">Phone</div><div class="stat-value" style="font-size:0.8rem;margin-top:4px;"><?php echo htmlspecialchars($urow['phone']??'Not set'); ?></div></div>
                    <div class="stat-icon" style="background:linear-gradient(135deg,#F5A623,#e08c0a)"><i class="ri-phone-line"></i></div>
                </div>
            </div>

            <!-- Form Section -->
            <div class="content-area" id="profileForm">
                <?php if(isset($_SESSION['profile_success'])): ?>
                <div class="alert alert-success"><i class="ri-checkbox-circle-line"></i><?php echo $_SESSION['profile_success']; unset($_SESSION['profile_success']); ?></div>
                <?php endif; ?>
                <?php if(isset($_SESSION['profile_error'])): ?>
                <div class="alert alert-error"><i class="ri-error-warning-line"></i><?php echo $_SESSION['profile_error']; unset($_SESSION['profile_error']); ?></div>
                <?php endif; ?>

                <!-- Form heading -->
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:1.25rem;padding-bottom:0.75rem;border-bottom:1px solid rgba(255,255,255,0.07);">
                    <div style="width:32px;height:32px;border-radius:8px;background:rgba(29,185,84,0.15);border:1px solid rgba(29,185,84,0.25);display:flex;align-items:center;justify-content:center;"><i class="ri-edit-2-line" style="color:var(--green);font-size:0.95rem;"></i></div>
                    <div>
                        <div style="font-size:0.9rem;font-weight:700;color:white;">Edit Information</div>
                        <div style="font-size:0.7rem;color:rgba(255,255,255,0.35);">Update your personal details below</div>
                    </div>
                </div>

                <form id="profileFormEl" action="non_teaching_staff_process.php" method="POST" style="max-width:680px;">
                    <input type="hidden" name="action" value="update_profile">

                    <!-- Basic Info group -->
                    <div style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.07);border-radius:12px;padding:1.25rem;margin-bottom:1rem;">
                        <div style="font-size:0.68rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:rgba(255,255,255,0.3);margin-bottom:1rem;display:flex;align-items:center;gap:6px;"><i class="ri-information-line" style="color:var(--green)"></i>Basic Information</div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label class="form-label"><i class="ri-user-line" style="color:var(--green);margin-right:4px;"></i>Full Name</label>
                                <input type="text" name="fullname" id="inp_fullname" class="form-input" value="<?php echo htmlspecialchars($urow['fullname']??$_SESSION['fullname']??''); ?>" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label"><i class="ri-mail-line" style="color:#60a5fa;margin-right:4px;"></i>Email Address</label>
                                <input type="email" name="email" id="inp_email" class="form-input" value="<?php echo htmlspecialchars($urow['email']??$_SESSION['email']??''); ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label"><i class="ri-phone-line" style="color:#fbbf24;margin-right:4px;"></i>Phone Number</label>
                                <input type="text" name="phone" id="inp_phone" class="form-input" placeholder="+63 912 345 6789" value="<?php echo htmlspecialchars($urow['phone']??$_SESSION['phone']??''); ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label"><i class="ri-shield-user-line" style="color:#c084fc;margin-right:4px;"></i>Role</label>
                                <input type="text" class="form-input" value="Non-Teaching Staff" disabled style="opacity:0.4;">
                            </div>
                            <div class="form-group" style="grid-column:1/-1;">
                                <label class="form-label"><i class="ri-building-line" style="color:#60a5fa;margin-right:4px;"></i>Department / Assignment</label>
                                <input type="text" name="assignments" id="inp_assignments" class="form-input" placeholder="e.g., College of Education" value="<?php echo htmlspecialchars($urow['assignments']??$_SESSION['assignments']??''); ?>">
                            </div>
                        </div>
                    </div>

                    <!-- Security group -->
                    <div style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.07);border-radius:12px;padding:1.25rem;margin-bottom:1.25rem;">
                        <div style="font-size:0.68rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:rgba(255,255,255,0.3);margin-bottom:1rem;display:flex;align-items:center;gap:6px;"><i class="ri-lock-line" style="color:#fbbf24"></i>Security</div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label class="form-label"><i class="ri-lock-password-line" style="color:#fbbf24;margin-right:4px;"></i>New Password <span style="color:rgba(255,255,255,0.2);font-size:0.6rem;text-transform:none;letter-spacing:0;font-weight:400;">(leave blank to keep)</span></label>
                                <div style="position:relative;">
                                    <input type="password" name="new_password" id="inp_password" class="form-input" placeholder="••••••••" style="padding-right:2.8rem;">
                                    <button type="button" onclick="togglePw('inp_password',this)" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;color:rgba(255,255,255,0.3);cursor:pointer;font-size:1rem;"><i class="ri-eye-line"></i></button>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label"><i class="ri-shield-check-line" style="color:#4ade80;margin-right:4px;"></i>Confirm New Password</label>
                                <div style="position:relative;">
                                    <input type="password" name="confirm_password" id="inp_confirm" class="form-input" placeholder="••••••••" style="padding-right:2.8rem;">
                                    <button type="button" onclick="togglePw('inp_confirm',this)" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;color:rgba(255,255,255,0.3);cursor:pointer;font-size:1rem;"><i class="ri-eye-line"></i></button>
                                </div>
                            </div>
                        </div>
                        <!-- Password strength -->
                        <div id="pwStrengthWrap" style="display:none;margin-top:0.5rem;">
                            <div style="display:flex;align-items:center;gap:8px;">
                                <div style="flex:1;height:4px;background:rgba(255,255,255,0.1);border-radius:4px;overflow:hidden;">
                                    <div id="pwStrengthBar" style="height:100%;width:0%;border-radius:4px;transition:all 0.3s;"></div>
                                </div>
                                <span id="pwStrengthLabel" style="font-size:0.68rem;font-weight:700;width:60px;text-align:right;"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Save button -->
                    <div style="display:flex;align-items:center;gap:0.75rem;">
                        <button type="button" onclick="openSaveConfirm()" class="btn-save">
                            <i class="ri-save-3-line"></i> Save Changes
                        </button>
                        <button type="button" onclick="resetForm()" style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);border-radius:9px;padding:0.7rem 1.2rem;font-size:0.875rem;font-weight:600;color:rgba(255,255,255,0.5);cursor:pointer;font-family:inherit;transition:all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.1)';this.style.color='white'" onmouseout="this.style.background='rgba(255,255,255,0.06)';this.style.color='rgba(255,255,255,0.5)'">
                            <i class="ri-refresh-line"></i> Reset
                        </button>
                    </div>
                </form>
            </div>

            <!-- ── SAVE CONFIRM MODAL ── -->
            <div id="saveConfirmModal" style="display:none;position:fixed;inset:0;z-index:1000;background:rgba(0,0,0,0.7);backdrop-filter:blur(6px);align-items:center;justify-content:center;">
                <div style="background:linear-gradient(135deg,#1e2228,#16191e);border:1px solid rgba(255,255,255,0.1);border-radius:18px;padding:2rem;width:100%;max-width:420px;margin:1rem;box-shadow:0 24px 64px rgba(0,0,0,0.5);text-align:center;animation:popIn 0.25s cubic-bezier(0.175,0.885,0.32,1.275);">
                    <div style="width:64px;height:64px;border-radius:50%;background:rgba(29,185,84,0.15);border:1px solid rgba(29,185,84,0.3);display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;font-size:1.8rem;color:var(--green);">
                        <i class="ri-save-3-line"></i>
                    </div>
                    <div style="font-size:1.1rem;font-weight:800;color:white;margin-bottom:0.4rem;">Save Changes?</div>
                    <div style="font-size:0.85rem;color:rgba(255,255,255,0.45);margin-bottom:0.5rem;">Your profile information will be updated.</div>
                    <!-- Preview of changes -->
                    <div id="changePreview" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:10px;padding:0.75rem;margin:0.75rem 0 1.25rem;text-align:left;font-size:0.78rem;color:rgba(255,255,255,0.5);"></div>
                    <div style="display:flex;gap:0.75rem;">
                        <button onclick="closeSaveConfirm()" style="flex:1;padding:0.7rem;border-radius:10px;background:rgba(255,255,255,0.07);border:1px solid rgba(255,255,255,0.1);color:rgba(255,255,255,0.6);font-weight:700;cursor:pointer;font-family:inherit;font-size:0.85rem;transition:all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.12)';this.style.color='white'" onmouseout="this.style.background='rgba(255,255,255,0.07)';this.style.color='rgba(255,255,255,0.6)'">
                            <i class="ri-close-line"></i> Cancel
                        </button>
                        <button onclick="submitProfileForm()" style="flex:1;padding:0.7rem;border-radius:10px;background:linear-gradient(135deg,#1DB954,#16a34a);border:none;color:white;font-weight:700;cursor:pointer;font-family:inherit;font-size:0.85rem;box-shadow:0 4px 14px rgba(29,185,84,0.3);transition:all 0.2s;" onmouseover="this.style.transform='translateY(-1px)'" onmouseout="this.style.transform='translateY(0)'">
                            <i class="ri-check-line"></i> Yes, Save
                        </button>
                    </div>
                </div>
            </div>
           

            <script>
            // Avatar color picker
            function setAvatarColor(c1,c2){
                document.getElementById('avatarCircle').style.background='linear-gradient(135deg,'+c1+','+c2+')';
                document.getElementById('avatarOptions').classList.add('hidden');
            }
            document.addEventListener('click',function(e){
                const opts=document.getElementById('avatarOptions');
                if(opts&&!opts.contains(e.target)&&e.target.id!=='avatarCircle'&&!document.getElementById('avatarCircle').contains(e.target)){
                    opts.classList.add('hidden');
                }
            });

            // Password toggle
            function togglePw(id,btn){
                const inp=document.getElementById(id);
                if(inp.type==='password'){inp.type='text';btn.innerHTML='<i class="ri-eye-off-line"></i>';}
                else{inp.type='password';btn.innerHTML='<i class="ri-eye-line"></i>';}
            }

            // Password strength
            document.getElementById('inp_password')?.addEventListener('input',function(){
                const v=this.value; const wrap=document.getElementById('pwStrengthWrap');
                const bar=document.getElementById('pwStrengthBar'); const lbl=document.getElementById('pwStrengthLabel');
                if(!v){wrap.style.display='none';return;}
                wrap.style.display='block';
                let score=0;
                if(v.length>=8) score++;
                if(/[A-Z]/.test(v)) score++;
                if(/[0-9]/.test(v)) score++;
                if(/[^A-Za-z0-9]/.test(v)) score++;
                const levels=[{w:'25%',c:'#ef4444',l:'Weak'},{w:'50%',c:'#f97316',l:'Fair'},{w:'75%',c:'#fbbf24',l:'Good'},{w:'100%',c:'#4ade80',l:'Strong'}];
                const lv=levels[score-1]||levels[0];
                bar.style.width=lv.w; bar.style.background=lv.c; lbl.textContent=lv.l; lbl.style.color=lv.c;
            });

            // Reset form
            function resetForm(){
                document.getElementById('profileFormEl').reset();
                document.getElementById('inp_fullname').value='<?php echo addslashes($urow['fullname']??''); ?>';
                document.getElementById('inp_email').value='<?php echo addslashes($urow['email']??''); ?>';
                document.getElementById('inp_phone').value='<?php echo addslashes($urow['phone']??''); ?>';
                document.getElementById('inp_assignments').value='<?php echo addslashes($urow['assignments']??''); ?>';
                document.getElementById('pwStrengthWrap').style.display='none';
            }

            // Save confirm modal
            function openSaveConfirm(){
                const name=document.getElementById('inp_fullname').value;
                const email=document.getElementById('inp_email').value;
                const phone=document.getElementById('inp_phone').value;
                const dept=document.getElementById('inp_assignments').value;
                const hasPw=document.getElementById('inp_password').value!=='';
                let preview='';
                preview+=`<div style="margin-bottom:5px;"><span style="color:rgba(255,255,255,0.4);width:80px;display:inline-block;">Name:</span><span style="color:white;font-weight:600;">${name}</span></div>`;
                preview+=`<div style="margin-bottom:5px;"><span style="color:rgba(255,255,255,0.4);width:80px;display:inline-block;">Email:</span><span style="color:white;font-weight:600;">${email||'—'}</span></div>`;
                preview+=`<div style="margin-bottom:5px;"><span style="color:rgba(255,255,255,0.4);width:80px;display:inline-block;">Phone:</span><span style="color:white;font-weight:600;">${phone||'—'}</span></div>`;
                preview+=`<div style="margin-bottom:5px;"><span style="color:rgba(255,255,255,0.4);width:80px;display:inline-block;">Dept:</span><span style="color:white;font-weight:600;">${dept||'—'}</span></div>`;
                if(hasPw) preview+=`<div style="margin-top:6px;color:#fbbf24;font-size:0.72rem;"><i class="ri-lock-line"></i> Password will also be updated</div>`;
                document.getElementById('changePreview').innerHTML=preview;
                document.getElementById('saveConfirmModal').style.display='flex';
                document.body.style.overflow='hidden';
            }
            function closeSaveConfirm(){
                document.getElementById('saveConfirmModal').style.display='none';
                document.body.style.overflow='';
            }
            function submitProfileForm(){
                closeSaveConfirm();
                document.getElementById('profileFormEl').submit();
            }
            // Close modal on backdrop click
            document.getElementById('saveConfirmModal')?.addEventListener('click',function(e){
                if(e.target===this) closeSaveConfirm();
            });
            </script>

            <?php elseif($section === 'location'): ?>
            <!-- ── LOCATIONS ── -->
            <div class="content-area">
                <div id="locationsGrid">
                    <div class="text-center py-12">
                        <i class="ri-loader-4-line text-3xl animate-spin" style="color:var(--green)"></i>
                        <p class="text-sm mt-3" style="color:rgba(255,255,255,0.35)">Loading locations...</p>
                    </div>
                </div>
            </div>

            <?php elseif($section === 'activity'): ?>
            <!-- ── ACTIVITIES ── -->
            <!-- Stat cards -->
            <div class="stat-grid" id="activityStats">
                <div class="stat-card blue"><div><div class="stat-label">Total Activities</div><div class="stat-value" id="statTotal">0</div><div class="stat-sub">All registered</div></div><div class="stat-icon" style="background:linear-gradient(135deg,#1565C0,#1976D2)"><i class="ri-calendar-line"></i></div></div>
                <div class="stat-card green"><div><div class="stat-label">Ongoing</div><div class="stat-value" id="statOngoing">0</div><div class="stat-sub" style="color:#4ade80">• In progress</div></div><div class="stat-icon" style="background:linear-gradient(135deg,#1DB954,#16a34a)"><i class="ri-play-circle-line"></i></div></div>
                <div class="stat-card teal"><div><div class="stat-label">Upcoming</div><div class="stat-value" id="statUpcoming">0</div><div class="stat-sub">Scheduled</div></div><div class="stat-icon" style="background:linear-gradient(135deg,#00B4A0,#00897B)"><i class="ri-calendar-check-line"></i></div></div>
                <div class="stat-card yellow"><div><div class="stat-label">Completed</div><div class="stat-value" id="statCompleted">0</div><div class="stat-sub" style="color:#fbbf24">✓ Delivered</div></div><div class="stat-icon" style="background:linear-gradient(135deg,#F5A623,#e08c0a)"><i class="ri-checkbox-circle-line"></i></div></div>
            </div>

            <div class="content-area">
                <div class="tab-wrap">
                    <button onclick="showActivityTab('view')" id="tab-view" class="tab-btn active"><i class="ri-eye-line"></i> View Activities</button>
                    <button onclick="showActivityTab('feedback')" id="tab-feedback" class="tab-btn"><i class="ri-feedback-line"></i> Provide Feedback</button>
                </div>

                <div id="activity-view">
                    <div class="filter-bar">
                        <div class="search-wrap">
                            <i class="ri-search-line"></i>
                            <input type="text" id="actSearch" class="search-input" placeholder="Search activities by name, location...">
                        </div>
                    </div>
                    <div id="activitiesContent">
                        <div class="text-center py-8"><i class="ri-loader-4-line animate-spin" style="color:var(--green);font-size:1.5rem"></i></div>
                    </div>
                </div>

                <div id="activity-feedback" class="hidden" style="max-width:520px;">
                    <h3 class="font-bold mb-4" style="color:white;font-size:1rem;">Provide Activity Feedback</h3>
                    <form method="POST" action="non_teaching_staff_process.php" class="space-y-4">
                        <input type="hidden" name="action" value="activity_feedback">
                        <div class="form-group">
                            <label class="form-label">Select Activity</label>
                            <select name="activity_id" class="form-input" id="activityFeedbackSelect"><option value="">-- Select Activity --</option></select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Your Feedback</label>
                            <textarea name="feedback" rows="4" class="form-input" placeholder="Share your thoughts about this activity..." style="resize:none;"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Rating</label>
                            <select name="rating" class="form-input">
                                <option value="5">⭐⭐⭐⭐⭐ Excellent</option>
                                <option value="4">⭐⭐⭐⭐ Good</option>
                                <option value="3">⭐⭐⭐ Average</option>
                                <option value="2">⭐⭐ Below Average</option>
                                <option value="1">⭐ Poor</option>
                            </select>
                        </div>
                        <button type="submit" class="btn-save"><i class="ri-send-plane-line"></i> Submit Feedback</button>
                    </form>
                </div>
            </div>

            <?php elseif($section === 'project'): ?>
            <!-- ── PROJECTS ── -->
            <div class="stat-grid">
                <div class="stat-card blue"><div><div class="stat-label">Total Projects</div><div class="stat-value" id="pStatTotal">0</div><div class="stat-sub">All registered projects</div></div><div class="stat-icon" style="background:linear-gradient(135deg,#8B2FC9,#6d24a8)"><i class="ri-folder-2-line"></i></div></div>
                <div class="stat-card green"><div><div class="stat-label">Active</div><div class="stat-value" id="pStatActive">0</div><div class="stat-sub" style="color:#4ade80">• In progress</div></div><div class="stat-icon" style="background:linear-gradient(135deg,#1DB954,#16a34a)"><i class="ri-play-circle-line"></i></div></div>
                <div class="stat-card teal"><div><div class="stat-label">Planning</div><div class="stat-value" id="pStatPlanning">0</div><div class="stat-sub">Scheduled</div></div><div class="stat-icon" style="background:linear-gradient(135deg,#1565C0,#1976D2)"><i class="ri-calendar-check-line"></i></div></div>
                <div class="stat-card yellow"><div><div class="stat-label">Completed</div><div class="stat-value" id="pStatCompleted">0</div><div class="stat-sub" style="color:#fbbf24">✓ Delivered</div></div><div class="stat-icon" style="background:linear-gradient(135deg,#F5A623,#e08c0a)"><i class="ri-checkbox-circle-line"></i></div></div>
            </div>

            <div class="content-area">
                <div class="tab-wrap">
                    <button onclick="showProjectTab('view')" id="ptab-view" class="tab-btn active"><i class="ri-eye-line"></i> View Projects</button>
                    <button onclick="showProjectTab('feedback')" id="ptab-feedback" class="tab-btn"><i class="ri-feedback-line"></i> Provide Feedback</button>
                </div>

                <div id="project-view">
                    <div class="filter-bar">
                        <div class="search-wrap">
                            <i class="ri-search-line"></i>
                            <input type="text" id="projSearch" class="search-input" placeholder="Search projects by name, status...">
                        </div>
                    </div>
                    <div id="projectsContent">
                        <div class="text-center py-8"><i class="ri-loader-4-line animate-spin" style="color:var(--green);font-size:1.5rem"></i></div>
                    </div>
                </div>

                <div id="project-feedback" class="hidden" style="max-width:520px;">
                    <h3 class="font-bold mb-4" style="color:white;font-size:1rem;">Provide Project Feedback</h3>
                    <form method="POST" action="non_teaching_staff_process.php" class="space-y-4">
                        <input type="hidden" name="action" value="project_feedback">
                        <div class="form-group">
                            <label class="form-label">Select Project</label>
                            <select name="project_id" class="form-input" id="projectFeedbackSelect"><option value="">-- Select Project --</option></select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Your Feedback</label>
                            <textarea name="feedback" rows="4" class="form-input" placeholder="Share your thoughts about this project..." style="resize:none;"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Rating</label>
                            <select name="rating" class="form-input">
                                <option value="5">⭐⭐⭐⭐⭐ Excellent</option>
                                <option value="4">⭐⭐⭐⭐ Good</option>
                                <option value="3">⭐⭐⭐ Average</option>
                                <option value="2">⭐⭐ Below Average</option>
                                <option value="1">⭐ Poor</option>
                            </select>
                        </div>
                        <button type="submit" class="btn-save"><i class="ri-send-plane-line"></i> Submit Feedback</button>
                    </form>
                </div>
            </div>

            <?php elseif($section === 'feedback'): ?>
            <!-- ── MY FEEDBACK ── -->
            <?php
            include_once 'db.php';
            $uid      = $_SESSION['user_id'];
            $username = $_SESSION['fullname'] ?? '';
            $evals    = [];
            // evaluations table uses 'evaluator' (name text), not evaluator_id
            $eq = $conn->prepare("SELECT e.*, e.program as project_name FROM evaluations e WHERE e.evaluator = ? ORDER BY e.created_at DESC");
            if($eq){
                $eq->bind_param("s", $username);
                $eq->execute();
                $res = $eq->get_result();
                while($row = $res->fetch_assoc()) $evals[] = $row;
            }
            ?>
            <div class="content-area">
                <?php if(empty($evals)): ?>
                <div class="text-center py-16">
                    <i class="ri-feedback-line text-5xl" style="color:rgba(255,255,255,0.08)"></i>
                    <p class="mt-4 text-sm" style="color:rgba(255,255,255,0.35)">No feedback submitted yet.</p>
                    <p class="text-xs mt-1" style="color:rgba(255,255,255,0.2)">Go to Activities or Projects to submit feedback.</p>
                </div>
                <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Program</th>
                                <th>Title</th>
                                <th>Findings</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($evals as $ev): ?>
                            <tr>
                                <td class="font-semibold text-white"><?php echo htmlspecialchars($ev['program']??'—'); ?></td>
                                <td class="text-white"><?php echo htmlspecialchars($ev['title']??'—'); ?></td>
                                <td style="max-width:260px;color:rgba(255,255,255,0.5)"><?php echo htmlspecialchars(substr($ev['findings']??'',0,70)).(strlen($ev['findings']??'')>70?'...':''); ?></td>
                                <td><span class="badge <?php echo ($ev['status']??'')==='Completed'?'badge-green':(($ev['status']??'')==='In Progress'?'badge-blue':'badge-gray'); ?>"><?php echo htmlspecialchars($ev['status']??'—'); ?></span></td>
                                <td style="color:rgba(255,255,255,0.35);font-size:0.75rem"><?php echo $ev['eval_date']??$ev['created_at']??''; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 text-xs" style="color:rgba(255,255,255,0.3)">
                    Total: <strong style="color:white"><?php echo count($evals); ?></strong> feedback submissions
                </div>
                <?php endif; ?>
            </div>

            <?php endif; ?>

        </div><!-- end glass-card -->

        <div class="status-bar">
            <span>© <?php echo date('Y'); ?> CEMS. All rights reserved.</span>
            <span>Non-Teaching Staff Portal &nbsp;•&nbsp; macOS Liquid Glass Edition</span>
        </div>
    </div>
</main>

<!-- ── JOIN MODAL ── -->
<div class="modal-overlay" id="joinModal">
    <div class="modal-box">
        <div class="modal-icon-wrap" id="modalIconWrap">
            <i id="modalIcon" class="ri-user-add-line"></i>
        </div>
        <div class="modal-title" id="modalTitle">Join Activity</div>
        <div class="modal-msg">Are you sure you want to join<br><span class="modal-name" id="modalName"></span>?</div>
        <div class="modal-btns">
            <button class="modal-btn-cancel" onclick="closeJoinModal()"><i class="ri-close-line mr-1"></i>Cancel</button>
            <button class="modal-btn-confirm" id="modalConfirmBtn" onclick="confirmJoin()">
                <i class="ri-check-line mr-1"></i>Join Now
            </button>
        </div>
    </div>
</div>

<!-- ── TOAST ── -->
<div class="toast" id="toast">
    <i class="toast-icon" id="toastIcon"></i>
    <span id="toastMsg"></span>
</div>

<script>
// ── Time ──
function updateTime(){
    const t = new Date().toLocaleTimeString('en-US',{hour:'2-digit',minute:'2-digit'});
    const el=document.getElementById('currentTime'); if(el) el.textContent=t;
    const m=document.getElementById('mobileTime'); if(m) m.textContent=t;
}
updateTime(); setInterval(updateTime,1000);

// ── Mobile menu ──
const mobileMenuBtn=document.getElementById('mobileMenuBtn');
const mobileSidebar=document.getElementById('mobileSidebar');
const mobileOverlay=document.getElementById('mobileOverlay');
mobileMenuBtn?.addEventListener('click',()=>{mobileSidebar.classList.remove('hidden');document.body.style.overflow='hidden';});
function closeMob(){mobileSidebar.classList.add('hidden');document.body.style.overflow='';}
mobileOverlay?.addEventListener('click',closeMob);
document.addEventListener('keydown',e=>{if(e.key==='Escape'){closeMob();closeJoinModal();}});

// ── Smooth nav ──
document.querySelectorAll('a.nav-item').forEach(link=>{
    link.addEventListener('click',e=>{
        if(link.href.includes('non_teaching_staff.php')&&!link.href.includes('logout')){
            e.preventDefault();
            const main=document.querySelector('main');
            main.style.transition='opacity 0.2s,transform 0.2s';
            main.style.opacity='0'; main.style.transform='translateY(8px)';
            setTimeout(()=>{window.location.href=link.href;},200);
        }
    });
});
window.addEventListener('load',()=>{
    const main=document.querySelector('main');
    main.style.transition='all 0.3s ease';
    main.style.opacity='1'; main.style.transform='translateY(0)';
});

// ── Toast ──
function showToast(msg, type='success'){
    const toast = document.getElementById('toast');
    const icon  = document.getElementById('toastIcon');
    document.getElementById('toastMsg').textContent = msg;
    toast.className = `toast ${type}`;
    icon.className = type==='success' ? 'toast-icon ri-checkbox-circle-line' : 'toast-icon ri-error-warning-line';
    icon.style.color = type==='success' ? '#4ade80' : '#f87171';
    setTimeout(()=>toast.classList.add('show'),10);
    setTimeout(()=>toast.classList.remove('show'),3500);
}

// ── Join Modal ──
let _joinType = ''; // 'activity' or 'project'
let _joinId   = 0;

function joinActivity(id, name){
    _joinType = 'activity';
    _joinId   = id;
    const wrap = document.getElementById('modalIconWrap');
    const icon = document.getElementById('modalIcon');
    wrap.style.background = 'rgba(233,30,140,0.15)';
    wrap.style.border = '1px solid rgba(233,30,140,0.3)';
    icon.className = 'ri-calendar-event-line';
    icon.style.color = '#E91E8C';
    document.getElementById('modalTitle').textContent = 'Join Activity';
    document.getElementById('modalName').textContent  = '"' + name + '"';
    const btn = document.getElementById('modalConfirmBtn');
    btn.style.background = 'linear-gradient(135deg,#E91E8C,#8B2FC9)';
    btn.innerHTML = '<i class="ri-calendar-check-line mr-1"></i>Join Activity';
    openJoinModal();
}

function joinProject(id, name){
    _joinType = 'project';
    _joinId   = id;
    const wrap = document.getElementById('modalIconWrap');
    const icon = document.getElementById('modalIcon');
    wrap.style.background = 'rgba(29,185,84,0.15)';
    wrap.style.border = '1px solid rgba(29,185,84,0.3)';
    icon.className = 'ri-folder-add-line';
    icon.style.color = '#1DB954';
    document.getElementById('modalTitle').textContent = 'Join Project';
    document.getElementById('modalName').textContent  = '"' + name + '"';
    const btn = document.getElementById('modalConfirmBtn');
    btn.style.background = 'linear-gradient(135deg,#1DB954,#16a34a)';
    btn.innerHTML = '<i class="ri-folder-add-line mr-1"></i>Join Project';
    openJoinModal();
}

function openJoinModal(){
    document.getElementById('joinModal').classList.add('show');
    document.body.style.overflow = 'hidden';
}
function closeJoinModal(){
    document.getElementById('joinModal').classList.remove('show');
    document.body.style.overflow = '';
}

function confirmJoin(){
    if(!_joinId) return;
    const btn = document.getElementById('modalConfirmBtn');
    btn.classList.add('loading');
    btn.textContent = 'Joining...';

    const fd = new FormData();
    fd.append('action', _joinType==='activity' ? 'join_activity' : 'join_project');
    fd.append(_joinType==='activity' ? 'activity_id' : 'project_id', _joinId);

    fetch('non_teaching_staff_process.php',{method:'POST',body:fd})
        .then(r=>r.json())
        .then(d=>{
            closeJoinModal();
            if(d.status==='success'){
                showToast(d.message || 'Joined successfully! 🎉', 'success');
            } else {
                showToast(d.message || 'Something went wrong.', 'error');
            }
        })
        .catch(()=>{
            closeJoinModal();
            showToast('Network error. Please try again.', 'error');
        })
        .finally(()=>{
            btn.classList.remove('loading');
        });
}

// Close modal on overlay click
document.getElementById('joinModal')?.addEventListener('click', function(e){
    if(e.target === this) closeJoinModal();
});

// ── Load locations ──
<?php if($section==='location'): ?>
fetch('api/location_api.php')
    .then(r=>r.json())
    .then(resp=>{
        const data=resp.data||resp||[];
        const hc=document.getElementById('headerCount');
        if(hc) hc.textContent=(Array.isArray(data)?data.length:0)+' Total';
        if(!Array.isArray(data)||!data.length){
            document.getElementById('locationsGrid').innerHTML='<div class="text-center py-12"><i class="ri-map-pin-line text-4xl" style="color:rgba(255,255,255,0.1)"></i><p class="text-sm mt-3" style="color:rgba(255,255,255,0.35)">No locations found.</p></div>';
            return;
        }
        document.getElementById('locationsGrid').innerHTML=`<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            ${data.map(l=>`
            <div class="loc-card">
                <div class="flex items-center gap-3 mb-2">
                    <div class="row-icon" style="background:linear-gradient(135deg,#1DB954,#16a34a)"><i class="ri-map-pin-line"></i></div>
                    <div class="row-name">${l.name||'—'}</div>
                </div>
                ${l.address?`<p class="text-xs ml-12 mt-1" style="color:rgba(255,255,255,0.4)"><i class="ri-road-map-line mr-1"></i>${l.address}</p>`:''}
                ${l.region?`<p class="text-xs ml-12 mt-1" style="color:rgba(255,255,255,0.3)"><i class="ri-compass-3-line mr-1"></i>${l.region}</p>`:''}
                ${l.facilities?`<p class="text-xs ml-12 mt-1" style="color:rgba(255,255,255,0.3)"><i class="ri-building-line mr-1"></i>${l.facilities}</p>`:''}
            </div>`).join('')}
        </div>`;
    }).catch(()=>{document.getElementById('locationsGrid').innerHTML='<p class="text-red-400 text-sm p-4">Failed to load locations.</p>';});
<?php endif; ?>

// ── Load activities ──
<?php if($section==='activity'): ?>
let allActivities=[];
fetch('api/activity_api.php?action=get')
    .then(r=>r.json())
    .then(data=>{
        allActivities=Array.isArray(data)?data:[];
        const hc=document.getElementById('headerCount'); if(hc) hc.textContent=allActivities.length+' Total';
        document.getElementById('statTotal').textContent=allActivities.length;
        document.getElementById('statOngoing').textContent=allActivities.filter(a=>a.status==='Ongoing').length;
        document.getElementById('statUpcoming').textContent=allActivities.filter(a=>a.status==='Upcoming').length;
        document.getElementById('statCompleted').textContent=allActivities.filter(a=>a.status==='Completed').length;
        renderActivities(allActivities);
        const sel=document.getElementById('activityFeedbackSelect');
        if(sel) allActivities.forEach(a=>{const o=document.createElement('option');o.value=a.id;o.textContent=a.name||'Activity #'+a.id;sel.appendChild(o);});
    }).catch(()=>{document.getElementById('activitiesContent').innerHTML='<p class="text-red-400 text-sm">Failed to load activities.</p>';});

function renderActivities(data){
    if(!data.length){document.getElementById('activitiesContent').innerHTML='<div class="text-center py-8"><i class="ri-calendar-line text-4xl" style="color:rgba(255,255,255,0.08)"></i><p class="text-sm mt-3" style="color:rgba(255,255,255,0.35)">No activities found.</p></div>';return;}
    const sc={'Upcoming':'badge-blue','Ongoing':'badge-green','Completed':'badge-gray','Cancelled':'badge-gray'};
    document.getElementById('activitiesContent').innerHTML=`<div class="overflow-x-auto"><table class="data-table">
        <thead><tr><th>Activity</th><th>Date / Location</th><th>Status</th><th>Action</th></tr></thead>
        <tbody>${data.map(a=>`<tr>
            <td><div class="flex items-center gap-3"><div class="row-icon" style="background:linear-gradient(135deg,#E91E8C,#8B2FC9)"><i class="ri-calendar-event-line"></i></div><div class="row-name">${a.name||'—'}</div></div></td>
            <td class="row-sub">${a.date_time||'—'} ${a.location?'• '+a.location:''}</td>
            <td><span class="badge ${sc[a.status]||'badge-gray'}">${a.status||'—'}</span></td>
            <td><button class="join-btn" onclick="joinActivity(${a.id},'${(a.name||'').replace(/'/g,"\\'")}')">+ Join</button></td>
        </tr>`).join('')}</tbody>
    </table><div class="mt-3 text-xs" style="color:rgba(255,255,255,0.3)">Total: <strong style="color:white">${data.length}</strong> activities</div></div>`;
}

document.getElementById('actSearch')?.addEventListener('input',e=>{
    const q=e.target.value.toLowerCase();
    renderActivities(allActivities.filter(a=>(a.name||'').toLowerCase().includes(q)||(a.location||'').toLowerCase().includes(q)));
});
<?php endif; ?>

// ── Load projects ──
<?php if($section==='project'): ?>
let allProjects=[];
fetch('api/project_api.php?action=get')
    .then(r=>r.json())
    .then(data=>{
        allProjects=Array.isArray(data)?data:[];
        const hc=document.getElementById('headerCount'); if(hc) hc.textContent=allProjects.length+' Total';
        document.getElementById('pStatTotal').textContent=allProjects.length;
        document.getElementById('pStatActive').textContent=allProjects.filter(p=>p.status==='Active').length;
        document.getElementById('pStatPlanning').textContent=allProjects.filter(p=>p.status==='Planning').length;
        document.getElementById('pStatCompleted').textContent=allProjects.filter(p=>p.status==='Completed').length;
        renderProjects(allProjects);
        const sel=document.getElementById('projectFeedbackSelect');
        if(sel) allProjects.forEach(p=>{const o=document.createElement('option');o.value=p.id;o.textContent=p.name||'Project #'+p.id;sel.appendChild(o);});
    }).catch(()=>{document.getElementById('projectsContent').innerHTML='<p class="text-red-400 text-sm">Failed to load projects.</p>';});

function renderProjects(data){
    if(!data.length){document.getElementById('projectsContent').innerHTML='<div class="text-center py-8"><i class="ri-folder-2-line text-4xl" style="color:rgba(255,255,255,0.08)"></i><p class="text-sm mt-3" style="color:rgba(255,255,255,0.35)">No projects found.</p></div>';return;}
    const sc={'Active':'badge-green','Planning':'badge-blue','Completed':'badge-gray','On Hold':'badge-yellow'};
    document.getElementById('projectsContent').innerHTML=`<div class="overflow-x-auto"><table class="data-table">
        <thead><tr><th>Project</th><th>Timeline</th><th>Status</th><th>Action</th></tr></thead>
        <tbody>${data.map(p=>`<tr>
            <td><div class="flex items-center gap-3"><div class="row-icon" style="background:linear-gradient(135deg,#1565C0,#8B2FC9)"><i class="ri-folder-2-line"></i></div><div class="row-name">${p.name||'—'}</div></div></td>
            <td class="row-sub">${p.start_date||'—'}</td>
            <td><span class="badge ${sc[p.status]||'badge-gray'}">${p.status||'—'}</span></td>
            <td><button class="join-btn" onclick="joinProject(${p.id},'${(p.name||'').replace(/'/g,"\\'")}')">+ Join</button></td>
        </tr>`).join('')}</tbody>
    </table><div class="mt-3 text-xs" style="color:rgba(255,255,255,0.3)">Total: <strong style="color:white">${data.length}</strong> projects</div></div>`;
}

document.getElementById('projSearch')?.addEventListener('input',e=>{
    const q=e.target.value.toLowerCase();
    renderProjects(allProjects.filter(p=>(p.name||'').toLowerCase().includes(q)||(p.status||'').toLowerCase().includes(q)));
});
<?php endif; ?>

// ── Tab switching ──
function showActivityTab(tab){
    ['view','feedback'].forEach(t=>{document.getElementById('activity-'+t).classList.add('hidden');document.getElementById('tab-'+t).classList.remove('active');});
    document.getElementById('activity-'+tab).classList.remove('hidden');
    document.getElementById('tab-'+tab).classList.add('active');
}
function showProjectTab(tab){
    ['view','feedback'].forEach(t=>{document.getElementById('project-'+t).classList.add('hidden');document.getElementById('ptab-'+t).classList.remove('active');});
    document.getElementById('project-'+tab).classList.remove('hidden');
    document.getElementById('ptab-'+tab).classList.add('active');
}
</script>
</body>
</html>