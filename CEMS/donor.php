<?php
session_start();

if(!isset($_SESSION['user_id'])){ header("Location: login.php"); exit(); }

$allowed_roles = ['Donor', 'Donor/Partner'];
if(!in_array($_SESSION['role'], $allowed_roles)){
    $admin_roles = ['admin','Admin','Developer','Director'];
    $coord_roles = ['Dept. Coordinator'];
    $ts_roles    = ['Teaching Staff'];
    $nts_roles   = ['Non-Teaching Staff'];
    $stu_roles   = ['Student'];
    if(in_array($_SESSION['role'], $admin_roles))      header("Location: dashboard.php");
    elseif(in_array($_SESSION['role'], $coord_roles))  header("Location: coordinator.php");
    elseif(in_array($_SESSION['role'], $ts_roles))     header("Location: teaching_staff.php");
    elseif(in_array($_SESSION['role'], $nts_roles))    header("Location: non_teaching_staff.php");
    elseif(in_array($_SESSION['role'], $stu_roles))    header("Location: student.php");
    else { session_destroy(); session_start(); $_SESSION['login_error']='Access denied.'; header("Location: login.php"); }
    exit();
}

if(isset($_GET['section']) && $_GET['section'] === 'logout'){ session_destroy(); header("Location: login.php"); exit(); }

$allowed_sections = ['profile','location','activity','project','feedback'];
$section = isset($_GET['section']) && in_array($_GET['section'], $allowed_sections) ? $_GET['section'] : 'profile';

include_once 'db.php';
$uid  = $_SESSION['user_id'];
$urow = [];
$uq   = $conn->prepare("SELECT fullname,email,phone,assignments,join_date,status FROM users WHERE id=?");
if($uq){ $uq->bind_param("i",$uid); $uq->execute(); $urow=$uq->get_result()->fetch_assoc()??[]; }

$profileIncomplete = empty($urow['phone']) && empty($urow['assignments']);
if($profileIncomplete && $section !== 'profile'){
    $section = 'profile';
    $_SESSION['profile_notice'] = 'Please complete your profile first before accessing other sections.';
}

// Fetch sponsorship totals for stats
$totalActivitySponsored = 0; $totalProjectSponsored = 0; $totalAmount = 0;
$uid_int = intval($uid);
$sq1 = $conn->query("SHOW TABLES LIKE 'activity_sponsorships'");
if($sq1 && $sq1->num_rows > 0){
    $sq2=$conn->prepare("SELECT COUNT(*) as cnt, COALESCE(SUM(amount),0) as total FROM activity_sponsorships WHERE user_id=?");
    if($sq2){$sq2->bind_param("i",$uid_int);$sq2->execute();$r=$sq2->get_result()->fetch_assoc();$totalActivitySponsored=$r['cnt']??0;$totalAmount+=$r['total']??0;}
}
$sq3 = $conn->query("SHOW TABLES LIKE 'project_sponsorships'");
if($sq3 && $sq3->num_rows > 0){
    $sq4=$conn->prepare("SELECT COUNT(*) as cnt, COALESCE(SUM(amount),0) as total FROM project_sponsorships WHERE user_id=?");
    if($sq4){$sq4->bind_param("i",$uid_int);$sq4->execute();$r=$sq4->get_result()->fetch_assoc();$totalProjectSponsored=$r['cnt']??0;$totalAmount+=$r['total']??0;}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CEMS - Donor / Partner Portal</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
<style>
:root{
    --gold:   #F5A623;
    --gold2:  #e08c0a;
    --amber:  #F59E0B;
    --orange: #EA580C;
    --green:  #16a34a;
    --teal:   #0d9488;
    --purple: #7c3aed;
    --dark:   #0c0a00;
}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
body{
    min-height:100vh;
    background:linear-gradient(145deg,#0c0800 0%,#1a1200 25%,#221800 50%,#1a1200 75%,#0c0800 100%);
    font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;
    overflow-x:hidden;
}
.mac-sidebar{
    background:rgba(20,14,0,0.82);
    backdrop-filter:blur(50px) saturate(200%);
    border-right:1px solid rgba(245,166,35,0.18);
    box-shadow:4px 0 40px rgba(0,0,0,0.6);
    width:148px;position:fixed;top:0;left:0;bottom:0;
    display:flex;flex-direction:column;
    padding:1rem 0.75rem;z-index:40;overflow-y:auto;
}
.sidebar-brand{display:flex;align-items:center;gap:8px;padding:0.5rem 0.25rem;margin-bottom:1.2rem;}
.sidebar-brand-icon{width:28px;height:28px;border-radius:7px;background:linear-gradient(135deg,var(--gold),var(--orange));display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.sidebar-brand-icon img{width:20px;height:20px;object-fit:contain;}
.sidebar-brand-text{font-size:0.85rem;font-weight:800;color:white;letter-spacing:-0.3px;line-height:1.1;}
.sidebar-brand-sub{font-size:0.55rem;color:rgba(245,166,35,0.7);font-weight:600;}
.sidebar-user{background:rgba(255,255,255,0.05);border:1px solid rgba(245,166,35,0.12);border-radius:10px;padding:0.6rem;margin-bottom:1.2rem;display:flex;flex-direction:column;align-items:center;gap:4px;}
.sidebar-avatar{width:36px;height:36px;border-radius:9px;background:linear-gradient(135deg,var(--gold),var(--orange));display:flex;align-items:center;justify-content:center;font-weight:800;font-size:0.95rem;color:white;}
.sidebar-uname{font-size:0.7rem;font-weight:700;color:white;text-align:center;line-height:1.2;}
.sidebar-urole{font-size:0.55rem;color:rgba(255,255,255,0.4);text-align:center;display:flex;align-items:center;gap:3px;justify-content:center;}
.online-dot{width:5px;height:5px;border-radius:50%;background:var(--gold);box-shadow:0 0 5px var(--gold);animation:glow 2s infinite;display:inline-block;}
@keyframes glow{0%,100%{box-shadow:0 0 0 0 rgba(245,166,35,0.5);}50%{box-shadow:0 0 0 4px rgba(245,166,35,0);}}
.nav-label{font-size:0.5rem;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:rgba(255,255,255,0.22);padding:0.5rem 0.5rem 0.1rem;display:block;}
.nav-divider{height:1px;background:rgba(255,255,255,0.07);margin:0.35rem 0.25rem;}
.nav-item{display:flex;flex-direction:column;align-items:center;gap:3px;padding:0.5rem 0.25rem;border-radius:9px;text-decoration:none;font-size:0.62rem;font-weight:600;color:rgba(255,255,255,0.5);transition:all 0.2s ease;margin-bottom:2px;border:1px solid transparent;text-align:center;position:relative;}
.nav-item:hover{background:rgba(255,255,255,0.07);color:rgba(255,255,255,0.9);}
.nav-item.active{background:rgba(245,166,35,0.18);border-color:rgba(245,166,35,0.35);color:white;font-weight:700;box-shadow:0 0 12px rgba(245,166,35,0.15);}
.nav-item i{font-size:1.15rem;color:rgba(255,255,255,0.4);}
.nav-item.active i{color:var(--gold);}
.nav-item:hover i{color:rgba(255,255,255,0.85);}
.nav-item.locked{opacity:0.4;cursor:not-allowed;}
.nav-item.locked:hover{background:transparent;color:rgba(255,255,255,0.5);}
.nav-badge{position:absolute;top:4px;right:4px;width:8px;height:8px;background:#ef4444;border-radius:50%;box-shadow:0 0 6px rgba(239,68,68,0.6);}
.logout-btn{display:flex;flex-direction:column;align-items:center;gap:3px;padding:0.5rem 0.25rem;border-radius:9px;text-decoration:none;font-size:0.62rem;font-weight:600;color:rgba(255,100,100,0.65);transition:all 0.2s ease;margin-top:auto;}
.logout-btn:hover{background:rgba(255,60,60,0.1);color:#f87171;}
.logout-btn i{font-size:1.1rem;}
.version-badge{text-align:center;font-size:0.5rem;color:rgba(255,255,255,0.18);padding:0.3rem 0;}
.mac-topbar{background:rgba(20,14,0,0.8);backdrop-filter:blur(30px);border-bottom:1px solid rgba(245,166,35,0.12);padding:0.6rem 1.5rem;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:30;}
.topbar-date{font-size:0.78rem;color:rgba(255,255,255,0.45);font-weight:500;}
.topbar-divider{width:1px;height:14px;background:rgba(255,255,255,0.15);margin:0 0.75rem;}
.topbar-time{font-size:0.78rem;color:rgba(255,255,255,0.45);font-variant-numeric:tabular-nums;}
.sys-status{display:flex;align-items:center;gap:6px;font-size:0.7rem;color:rgba(255,255,255,0.3);}
.refresh-btn{background:rgba(245,166,35,0.12);border:1px solid rgba(245,166,35,0.25);border-radius:8px;padding:6px 14px;color:white;font-size:0.75rem;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:5px;transition:all 0.2s;}
.refresh-btn:hover{background:rgba(245,166,35,0.24);transform:translateY(-1px);}
.page-title{font-size:1.5rem;font-weight:700;letter-spacing:-0.5px;background:linear-gradient(135deg,#fff,#aaa);-webkit-background-clip:text;-webkit-text-fill-color:transparent;}
.page-sub{font-size:0.75rem;color:rgba(255,255,255,0.35);margin-top:3px;display:flex;align-items:center;gap:5px;}
.glass-card{background:rgba(25,18,0,0.6);backdrop-filter:blur(20px);border:1px solid rgba(245,166,35,0.12);border-radius:14px;overflow:hidden;}
.section-header{background:linear-gradient(135deg,#1a1200 0%,#2a1e00 50%,#332500 100%);padding:1.25rem 1.5rem;position:relative;overflow:hidden;}
.section-header::before{content:'';position:absolute;top:-80px;right:-40px;width:220px;height:220px;background:rgba(245,166,35,0.06);border-radius:50%;}
.section-header-badge{display:inline-flex;align-items:center;gap:5px;background:rgba(245,166,35,0.12);border:1px solid rgba(245,166,35,0.25);border-radius:50px;padding:3px 10px;font-size:0.65rem;font-weight:700;color:rgba(255,255,255,0.7);letter-spacing:0.08em;text-transform:uppercase;margin-bottom:0.5rem;}
.section-title{font-size:1.8rem;font-weight:800;color:white;letter-spacing:-0.5px;display:flex;align-items:center;gap:0.5rem;}
.section-title span{color:rgba(255,255,255,0.4);font-weight:400;font-size:1.1rem;}
.section-desc{font-size:0.75rem;color:rgba(255,255,255,0.4);margin-top:4px;display:flex;align-items:center;gap:5px;}
.stat-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:0.75rem;padding:1rem 1.5rem;border-bottom:1px solid rgba(255,255,255,0.06);}
.stat-card{background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.07);border-radius:12px;padding:1rem;display:flex;align-items:flex-start;justify-content:space-between;position:relative;overflow:hidden;}
.stat-card::before{content:'';position:absolute;top:0;right:0;width:80px;height:80px;border-radius:50%;filter:blur(30px);}
.stat-card.gold::before   {background:rgba(245,166,35,0.2);}
.stat-card.orange::before {background:rgba(234,88,12,0.15);}
.stat-card.green::before  {background:rgba(22,163,74,0.15);}
.stat-card.purple::before {background:rgba(124,58,237,0.15);}
.stat-label{font-size:0.65rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:rgba(255,255,255,0.4);margin-bottom:0.3rem;}
.stat-value{font-size:1.8rem;font-weight:800;color:white;line-height:1;}
.stat-sub{font-size:0.65rem;color:rgba(255,255,255,0.35);margin-top:4px;}
.stat-icon{width:36px;height:36px;border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;color:white;flex-shrink:0;}
.content-area{padding:1.25rem 1.5rem;}
.filter-bar{background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:12px;padding:0.875rem 1.25rem;display:flex;align-items:center;gap:0.75rem;margin-bottom:1rem;}
.search-input{flex:1;background:rgba(0,0,0,0.3);border:1px solid rgba(255,255,255,0.08);border-radius:8px;padding:0.55rem 0.9rem 0.55rem 2.2rem;font-size:0.8rem;color:white;outline:none;font-family:inherit;transition:all 0.2s;}
.search-input::placeholder{color:rgba(255,255,255,0.25);}
.search-input:focus{border-color:var(--gold);box-shadow:0 0 0 2px rgba(245,166,35,0.1);}
.search-wrap{position:relative;flex:1;}
.search-wrap i{position:absolute;left:0.7rem;top:50%;transform:translateY(-50%);color:rgba(255,255,255,0.3);font-size:0.9rem;}
.data-table{width:100%;border-collapse:collapse;}
.data-table thead tr{background:rgba(0,0,0,0.3);border-bottom:1px solid rgba(255,255,255,0.07);}
.data-table th{padding:0.65rem 1rem;font-size:0.65rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:rgba(255,255,255,0.4);text-align:left;white-space:nowrap;}
.data-table td{padding:0.75rem 1rem;font-size:0.82rem;color:rgba(255,255,255,0.75);border-bottom:1px solid rgba(255,255,255,0.04);}
.data-table tr:hover td{background:rgba(255,255,255,0.03);}
.badge{display:inline-flex;align-items:center;padding:0.2rem 0.65rem;border-radius:50px;font-size:0.68rem;font-weight:700;}
.badge-gold   {background:rgba(245,166,35,0.15);color:#fbbf24;border:1px solid rgba(245,166,35,0.3);}
.badge-green  {background:rgba(22,163,74,0.15);color:#4ade80;border:1px solid rgba(22,163,74,0.3);}
.badge-blue   {background:rgba(59,130,246,0.15);color:#60a5fa;border:1px solid rgba(59,130,246,0.3);}
.badge-orange {background:rgba(234,88,12,0.15);color:#fb923c;border:1px solid rgba(234,88,12,0.3);}
.badge-gray   {background:rgba(255,255,255,0.06);color:rgba(255,255,255,0.4);border:1px solid rgba(255,255,255,0.1);}
.fund-btn{padding:0.35rem 0.85rem;border-radius:7px;font-size:0.72rem;font-weight:700;cursor:pointer;transition:all 0.2s;background:rgba(245,166,35,0.15);border:1px solid rgba(245,166,35,0.35);color:#fbbf24;font-family:inherit;display:inline-flex;align-items:center;gap:4px;}
.fund-btn:hover{background:var(--gold);color:white;border-color:var(--gold);transform:translateY(-1px);}
.form-group{margin-bottom:1.1rem;}
.form-label{display:block;font-size:0.65rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:rgba(255,255,255,0.4);margin-bottom:0.4rem;}
.form-input{width:100%;background:rgba(0,0,0,0.3);border:1px solid rgba(255,255,255,0.1);border-radius:10px;padding:0.7rem 1rem;font-size:0.85rem;color:white;outline:none;transition:all 0.2s;font-family:inherit;}
.form-input::placeholder{color:rgba(255,255,255,0.2);}
.form-input:focus{border-color:var(--gold);box-shadow:0 0 0 3px rgba(245,166,35,0.1);}
.form-input:disabled{opacity:0.4;cursor:not-allowed;}
.form-input option{background:#1a1200;}
.btn-save{background:linear-gradient(135deg,var(--gold),var(--orange));color:white;border:none;border-radius:9px;padding:0.7rem 1.8rem;font-size:0.875rem;font-weight:700;cursor:pointer;transition:all 0.2s;font-family:inherit;box-shadow:0 4px 14px rgba(245,166,35,0.35);display:inline-flex;align-items:center;gap:6px;}
.btn-save:hover{transform:translateY(-1px);box-shadow:0 6px 20px rgba(245,166,35,0.5);}
.alert{display:flex;align-items:center;gap:8px;padding:0.7rem 1rem;border-radius:9px;font-size:0.82rem;font-weight:600;margin-bottom:1rem;}
.alert-success{background:rgba(22,163,74,0.1);border:1px solid rgba(22,163,74,0.3);color:#4ade80;}
.alert-error  {background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#f87171;}
.alert-notice {background:rgba(245,166,35,0.1);border:1px solid rgba(245,166,35,0.3);color:#fbbf24;}
.tab-wrap{display:flex;gap:0.5rem;margin-bottom:1rem;}
.tab-btn{padding:0.45rem 1rem;border-radius:8px;font-size:0.78rem;font-weight:600;cursor:pointer;transition:all 0.2s;border:1px solid rgba(255,255,255,0.1);background:transparent;color:rgba(255,255,255,0.4);font-family:inherit;display:flex;align-items:center;gap:5px;}
.tab-btn.active{background:rgba(245,166,35,0.18);border-color:rgba(245,166,35,0.35);color:white;}
.tab-btn:hover:not(.active){background:rgba(255,255,255,0.06);color:rgba(255,255,255,0.8);}
.row-icon{width:38px;height:38px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1rem;color:white;flex-shrink:0;}
.row-name{font-size:0.85rem;font-weight:700;color:white;}
.row-sub{font-size:0.72rem;color:rgba(255,255,255,0.35);margin-top:2px;}
.loc-card{background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.07);border-radius:11px;padding:1rem;transition:all 0.2s;}
.loc-card:hover{background:rgba(255,255,255,0.07);border-color:rgba(245,166,35,0.25);}
.modal-overlay{position:fixed;inset:0;z-index:999;background:rgba(0,0,0,0.8);backdrop-filter:blur(6px);display:flex;align-items:center;justify-content:center;opacity:0;pointer-events:none;transition:opacity 0.2s;}
.modal-overlay.show{opacity:1;pointer-events:all;}
.modal-box{background:linear-gradient(135deg,#1a1200,#120e00);border:1px solid rgba(245,166,35,0.2);border-radius:18px;padding:2rem;width:100%;max-width:440px;margin:1rem;box-shadow:0 24px 64px rgba(0,0,0,0.7);transform:scale(0.95) translateY(10px);transition:all 0.25s cubic-bezier(0.175,0.885,0.32,1.275);text-align:center;}
.modal-overlay.show .modal-box{transform:scale(1) translateY(0);}
.modal-icon-wrap{width:64px;height:64px;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;font-size:1.8rem;}
.modal-title{font-size:1.15rem;font-weight:800;color:white;margin-bottom:0.4rem;}
.modal-msg{font-size:0.85rem;color:rgba(255,255,255,0.45);margin-bottom:1rem;line-height:1.5;}
.modal-name{color:#fbbf24;font-weight:700;}
.modal-btns{display:flex;gap:0.75rem;margin-top:1rem;}
.modal-btn-cancel{flex:1;padding:0.7rem 1rem;border-radius:10px;font-size:0.85rem;font-weight:700;cursor:pointer;font-family:inherit;background:rgba(255,255,255,0.07);border:1px solid rgba(255,255,255,0.1);color:rgba(255,255,255,0.6);transition:all 0.2s;}
.modal-btn-cancel:hover{background:rgba(255,255,255,0.12);color:white;}
.modal-btn-confirm{flex:1;padding:0.7rem 1rem;border-radius:10px;font-size:0.85rem;font-weight:700;cursor:pointer;font-family:inherit;border:none;color:white;transition:all 0.2s;background:linear-gradient(135deg,#F5A623,#EA580C);box-shadow:0 4px 14px rgba(245,166,35,0.3);}
.modal-btn-confirm:hover{transform:translateY(-1px);}
.modal-btn-confirm.loading{opacity:0.7;pointer-events:none;}
.amount-input{width:100%;background:rgba(0,0,0,0.4);border:2px solid rgba(245,166,35,0.3);border-radius:12px;padding:0.85rem 1rem 0.85rem 2.2rem;font-size:1.1rem;font-weight:700;color:#fbbf24;outline:none;font-family:inherit;transition:all 0.2s;}
.amount-input:focus{border-color:var(--gold);box-shadow:0 0 0 3px rgba(245,166,35,0.15);}
.amount-input::placeholder{color:rgba(245,166,35,0.3);font-weight:400;font-size:0.9rem;}
.amount-wrap{position:relative;}
.amount-wrap::before{content:'₱';position:absolute;left:0.85rem;top:50%;transform:translateY(-50%);color:#fbbf24;font-weight:800;font-size:1rem;}
.quick-amounts{display:flex;gap:6px;flex-wrap:wrap;margin-top:8px;}
.qa-chip{padding:4px 12px;border-radius:20px;background:rgba(245,166,35,0.1);border:1px solid rgba(245,166,35,0.25);color:#fbbf24;font-size:0.72rem;font-weight:700;cursor:pointer;transition:all 0.15s;font-family:inherit;}
.qa-chip:hover{background:rgba(245,166,35,0.25);border-color:rgba(245,166,35,0.5);}
.toast{position:fixed;bottom:1.5rem;right:1.5rem;z-index:9999;background:linear-gradient(135deg,#1a1200,#120e00);border:1px solid rgba(255,255,255,0.1);border-radius:12px;padding:0.85rem 1.25rem;display:flex;align-items:center;gap:10px;font-size:0.85rem;font-weight:600;color:white;box-shadow:0 8px 32px rgba(0,0,0,0.5);transform:translateY(20px);opacity:0;transition:all 0.3s cubic-bezier(0.175,0.885,0.32,1.275);min-width:260px;}
.toast.show{transform:translateY(0);opacity:1;}
.toast.success{border-color:rgba(22,163,74,0.4);}
.toast.error  {border-color:rgba(239,68,68,0.4);}
.toast-icon{font-size:1.1rem;flex-shrink:0;}
::-webkit-scrollbar{width:5px;}
::-webkit-scrollbar-track{background:transparent;}
::-webkit-scrollbar-thumb{background:rgba(245,166,35,0.2);border-radius:20px;}
@keyframes fadeIn{from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:translateY(0)}}
.fade-in{animation:fadeIn 0.3s ease-out both;}
.status-bar{margin-top:1.5rem;font-size:0.68rem;color:rgba(255,255,255,0.2);display:flex;justify-content:space-between;padding:0 0.25rem;}
.mobile-topbar{position:fixed;top:0;left:0;right:0;z-index:50;background:rgba(20,14,0,0.95);backdrop-filter:blur(20px);border-bottom:1px solid rgba(245,166,35,0.12);padding:0.75rem 1rem;display:flex;align-items:center;justify-content:space-between;}
/* Wizard */
.wizard-wrap{padding:2rem 1.5rem;}
.wizard-steps{display:flex;align-items:center;justify-content:center;gap:0;margin-bottom:2.5rem;}
.wstep{display:flex;flex-direction:column;align-items:center;gap:6px;}
.wstep-circle{width:42px;height:42px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.85rem;font-weight:800;border:2px solid rgba(255,255,255,0.1);background:rgba(255,255,255,0.04);color:rgba(255,255,255,0.3);transition:all 0.4s ease;}
.wstep-circle.active{background:linear-gradient(135deg,#F5A623,#EA580C);border-color:#F5A623;color:white;box-shadow:0 0 20px rgba(245,166,35,0.5);}
.wstep-circle.done  {background:rgba(245,166,35,0.2);border-color:rgba(245,166,35,0.5);color:#fbbf24;}
.wstep-label{font-size:0.6rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:rgba(255,255,255,0.25);transition:color 0.3s;white-space:nowrap;}
.wstep-label.active{color:#fbbf24;}
.wstep-label.done  {color:rgba(245,166,35,0.6);}
.wstep-line{width:80px;height:2px;background:rgba(255,255,255,0.07);margin-bottom:18px;transition:background 0.4s;flex-shrink:0;}
.wstep-line.done{background:rgba(245,166,35,0.4);}
.wizard-panel{display:none;animation:wfadeIn 0.35s ease both;}
.wizard-panel.active{display:block;}
@keyframes wfadeIn{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:translateY(0)}}
.wizard-panel-title{font-size:1.3rem;font-weight:800;color:white;margin-bottom:0.3rem;}
.wizard-panel-sub  {font-size:0.8rem;color:rgba(255,255,255,0.35);margin-bottom:1.75rem;}
.field-group{margin-bottom:1.25rem;}
.field-label{display:block;font-size:0.65rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:rgba(255,255,255,0.4);margin-bottom:0.45rem;}
.field-input{width:100%;background:rgba(0,0,0,0.35);border:1.5px solid rgba(255,255,255,0.08);border-radius:11px;padding:0.75rem 1rem;font-size:0.875rem;color:white;outline:none;transition:all 0.2s;font-family:inherit;}
.field-input::placeholder{color:rgba(255,255,255,0.18);}
.field-input:focus{border-color:var(--gold);box-shadow:0 0 0 3px rgba(245,166,35,0.12);background:rgba(0,0,0,0.45);}
.field-input.error{border-color:#ef4444;box-shadow:0 0 0 3px rgba(239,68,68,0.1);}
.field-hint {font-size:0.68rem;color:rgba(255,255,255,0.25);margin-top:5px;display:flex;align-items:center;gap:4px;}
.field-error{font-size:0.68rem;color:#f87171;margin-top:5px;display:none;}
.wizard-nav{display:flex;align-items:center;justify-content:space-between;margin-top:2rem;padding-top:1.25rem;border-top:1px solid rgba(255,255,255,0.07);}
.wbtn-back{padding:0.65rem 1.25rem;border-radius:10px;background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);color:rgba(255,255,255,0.5);font-size:0.82rem;font-weight:700;cursor:pointer;font-family:inherit;transition:all 0.2s;display:flex;align-items:center;gap:6px;}
.wbtn-back:hover{background:rgba(255,255,255,0.1);color:white;}
.wbtn-next{padding:0.65rem 1.6rem;border-radius:10px;background:linear-gradient(135deg,#F5A623,#EA580C);border:none;color:white;font-size:0.85rem;font-weight:800;cursor:pointer;font-family:inherit;transition:all 0.2s;display:flex;align-items:center;gap:6px;box-shadow:0 4px 16px rgba(245,166,35,0.35);}
.wbtn-next:hover{transform:translateY(-1px);box-shadow:0 6px 22px rgba(245,166,35,0.5);}
.wbtn-next:disabled{opacity:0.5;cursor:not-allowed;transform:none;}
.avatar-picker{display:flex;gap:10px;flex-wrap:wrap;margin-top:0.75rem;}
.avatar-swatch{width:44px;height:44px;border-radius:50%;cursor:pointer;border:3px solid transparent;transition:all 0.2s;}
.avatar-swatch:hover {transform:scale(1.1);}
.avatar-swatch.chosen{border-color:white;box-shadow:0 0 0 3px rgba(245,166,35,0.5);transform:scale(1.1);}
.avatar-preview{width:80px;height:80px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:1.8rem;font-weight:800;color:white;border:4px solid rgba(255,255,255,0.15);box-shadow:0 8px 28px rgba(0,0,0,0.4);transition:background 0.3s;flex-shrink:0;}
.review-row {display:flex;align-items:center;gap:12px;padding:0.65rem 0;border-bottom:1px solid rgba(255,255,255,0.05);}
.review-icon{width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.review-key {font-size:0.68rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:rgba(255,255,255,0.3);width:90px;flex-shrink:0;}
.review-val {font-size:0.88rem;font-weight:600;color:white;flex:1;}
.pw-bar-wrap{height:4px;background:rgba(255,255,255,0.08);border-radius:4px;overflow:hidden;margin-top:8px;}
.pw-bar{height:100%;border-radius:4px;transition:all 0.3s;width:0%;}
@keyframes spin{from{transform:rotate(0deg)}to{transform:rotate(360deg)}}
</style>
</head>
<body>

<!-- SIDEBAR -->
<aside class="mac-sidebar hidden md:flex flex-col">
    <div class="sidebar-brand">
        <div class="sidebar-brand-icon"><img src="img/Community Logo.png" alt="CEMS"></div>
        <div><div class="sidebar-brand-text">CEMS</div><div class="sidebar-brand-sub">Donor Portal</div></div>
    </div>
    <div class="sidebar-user">
        <div class="sidebar-avatar"><?php echo strtoupper(substr($_SESSION['fullname']??'D',0,1)); ?></div>
        <div class="sidebar-uname"><?php echo htmlspecialchars(explode(' ',$_SESSION['fullname']??'Donor')[0]); ?></div>
        <div class="sidebar-urole"><span class="online-dot"></span>Donor</div>
    </div>
    <span class="nav-label">Profile</span>
    <a href="donor.php?section=profile" class="nav-item <?php echo $section==='profile'?'active':''; ?>">
        <i class="ri-user-3-line"></i><span><?php echo $profileIncomplete?'Setup':'Profile'; ?></span>
        <?php if($profileIncomplete): ?><span class="nav-badge"></span><?php endif; ?>
    </a>
    <div class="nav-divider"></div>
    <span class="nav-label">Community</span>
    <a href="<?php echo $profileIncomplete?'#':'donor.php?section=location'; ?>" class="nav-item <?php echo $section==='location'?'active':''; ?> <?php echo $profileIncomplete?'locked':''; ?>" <?php if($profileIncomplete) echo 'onclick="showSetupAlert();return false;"'; ?>>
        <i class="ri-map-pin-line"></i><span>Locations</span>
    </a>
    <a href="<?php echo $profileIncomplete?'#':'donor.php?section=activity'; ?>" class="nav-item <?php echo $section==='activity'?'active':''; ?> <?php echo $profileIncomplete?'locked':''; ?>" <?php if($profileIncomplete) echo 'onclick="showSetupAlert();return false;"'; ?>>
        <i class="ri-calendar-event-line"></i><span>Activities</span>
    </a>
    <a href="<?php echo $profileIncomplete?'#':'donor.php?section=project'; ?>" class="nav-item <?php echo $section==='project'?'active':''; ?> <?php echo $profileIncomplete?'locked':''; ?>" <?php if($profileIncomplete) echo 'onclick="showSetupAlert();return false;"'; ?>>
        <i class="ri-folder-2-line"></i><span>Projects</span>
    </a>
    <div class="nav-divider"></div>
    <span class="nav-label">Reports</span>
    <a href="<?php echo $profileIncomplete?'#':'donor.php?section=feedback'; ?>" class="nav-item <?php echo $section==='feedback'?'active':''; ?> <?php echo $profileIncomplete?'locked':''; ?>" <?php if($profileIncomplete) echo 'onclick="showSetupAlert();return false;"'; ?>>
        <i class="ri-feedback-line"></i><span>Feedback</span>
    </a>
    <div style="flex:1"></div>
    <a href="donor.php?section=logout" class="logout-btn"><i class="ri-logout-box-line"></i><span>Logout</span></a>
    <div class="version-badge">CEMS v2.0</div>
</aside>

<!-- MOBILE TOPBAR -->
<div class="md:hidden mobile-topbar">
    <div class="flex items-center gap-2">
        <img src="img/Community Logo.png" class="w-7 h-7 rounded-lg object-contain">
        <span class="font-bold text-white text-sm">CEMS Donor</span>
    </div>
    <div class="flex items-center gap-2">
        <span class="text-xs text-white/40" id="mobileTime"></span>
        <button id="mobileMenuBtn" class="w-8 h-8 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-white"><i class="ri-menu-line"></i></button>
    </div>
</div>

<!-- MOBILE SIDEBAR -->
<div id="mobileSidebar" class="md:hidden hidden fixed inset-0 z-50">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" id="mobileOverlay"></div>
    <div class="mac-sidebar absolute" style="width:148px;">
        <div class="sidebar-brand"><div class="sidebar-brand-icon"><img src="img/Community Logo.png" alt="CEMS"></div><div><div class="sidebar-brand-text">CEMS</div></div></div>
        <div class="sidebar-user"><div class="sidebar-avatar"><?php echo strtoupper(substr($_SESSION['fullname']??'D',0,1)); ?></div><div class="sidebar-uname"><?php echo htmlspecialchars(explode(' ',$_SESSION['fullname']??'Donor')[0]); ?></div></div>
        <a href="donor.php?section=profile"   class="nav-item <?php echo $section==='profile'?'active':''; ?>"><i class="ri-user-3-line"></i><span>Profile</span><?php if($profileIncomplete): ?><span class="nav-badge"></span><?php endif; ?></a>
        <div class="nav-divider"></div>
        <a href="donor.php?section=location"  class="nav-item <?php echo $section==='location'?'active':''; ?>"><i class="ri-map-pin-line"></i><span>Locations</span></a>
        <a href="donor.php?section=activity"  class="nav-item <?php echo $section==='activity'?'active':''; ?>"><i class="ri-calendar-event-line"></i><span>Activities</span></a>
        <a href="donor.php?section=project"   class="nav-item <?php echo $section==='project'?'active':''; ?>"><i class="ri-folder-2-line"></i><span>Projects</span></a>
        <div class="nav-divider"></div>
        <a href="donor.php?section=feedback"  class="nav-item <?php echo $section==='feedback'?'active':''; ?>"><i class="ri-feedback-line"></i><span>Feedback</span></a>
        <div style="flex:1;min-height:1rem"></div>
        <a href="donor.php?section=logout" class="logout-btn"><i class="ri-logout-box-line"></i><span>Logout</span></a>
    </div>
</div>

<!-- MAIN -->
<main class="md:ml-[148px] min-h-screen flex flex-col">
    <div class="mac-topbar hidden md:flex">
        <div class="flex items-center">
            <span class="topbar-date"><?php echo date('l, F j, Y'); ?></span>
            <div class="topbar-divider"></div>
            <span class="topbar-time" id="currentTime"></span>
        </div>
        <div class="flex items-center gap-3">
            <span class="sys-status"><span class="w-2 h-2 rounded-full animate-pulse" style="background:var(--gold);box-shadow:0 0 6px var(--gold)"></span>System Online</span>
            <button onclick="window.location.reload()" class="refresh-btn"><i class="ri-refresh-line"></i> Refresh</button>
        </div>
    </div>

    <div class="flex-1 p-4 md:p-6 mt-14 md:mt-0 fade-in">
        <?php
        $pageInfo = [
            'profile'  => ['My Profile',    'PROFILE',    'ri-user-3-line',         $profileIncomplete?'Complete your profile to unlock all features':'View and update your donor profile'],
            'location' => ['View Locations', 'LOCATIONS',  'ri-map-pin-line',        'Browse available community extension locations'],
            'activity' => ['Activities',     'ACTIVITIES', 'ri-calendar-event-line', 'View and sponsor community activities'],
            'project'  => ['Projects',       'PROJECTS',   'ri-folder-2-line',       'View and sponsor community projects'],
            'feedback' => ['My Feedback',    'FEEDBACK',   'ri-feedback-line',       'All your submitted feedback'],
        ];
        $pi = $pageInfo[$section] ?? ['Dashboard','PORTAL','ri-dashboard-line',''];
        ?>
        <div style="margin-bottom:1.25rem;">
            <h1 class="page-title"><?php echo $pi[0]; ?></h1>
            <p class="page-sub"><i class="ri-information-line"></i><?php echo $pi[3]; ?></p>
        </div>

        <div class="glass-card">
            <div class="section-header">
                <div class="section-header-badge">
                    <i class="<?php echo $pi[2]; ?>"></i><?php echo $pi[1]; ?>
                    <?php if(in_array($section,['activity','project','location','feedback'])): ?><span style="margin-left:4px;">•</span><span id="headerCount">0 Total</span><?php endif; ?>
                    <?php if($profileIncomplete && $section==='profile'): ?><span style="margin-left:4px;color:#fbbf24;">• Setup Required</span><?php endif; ?>
                </div>
                <div class="section-title"><?php echo $pi[0]; ?><span>/ <?php echo ucfirst($section); ?></span></div>
                <div class="section-desc"><i class="ri-sparkling-line" style="color:var(--gold)"></i><?php echo $pi[3]; ?></div>
            </div>

            <?php if($section === 'profile'): ?>
            <?php
            $initials = strtoupper(implode('',array_map(fn($w)=>$w[0],array_slice(explode(' ',$urow['fullname']??'D'),0,2))));
            $swatches = [['#F5A623','#EA580C'],['#1565C0','#00B4D8'],['#1DB954','#16a34a'],['#8B2FC9','#6d24a8'],['#E91E8C','#c4176f'],['#E32636','#b81c28'],['#00B4A0','#00897B'],['#FF6B35','#e55a24']];
            ?>

            <?php if($profileIncomplete): ?>
            <!-- CREATE PROFILE WIZARD -->
            <form id="createProfileForm" action="donor_process.php" method="POST">
                <input type="hidden" name="action"           value="create_profile">
                <input type="hidden" name="fullname"         id="cp_fullname_hidden">
                <input type="hidden" name="email"            id="cp_email_hidden">
                <input type="hidden" name="phone"            id="cp_phone_hidden">
                <input type="hidden" name="assignments"      id="cp_assignments_hidden">
                <input type="hidden" name="new_password"     id="cp_password_hidden">
                <input type="hidden" name="confirm_password" id="cp_confirm_hidden">
            </form>

            <div class="wizard-wrap">
                <div class="wizard-steps">
                    <div class="wstep"><div class="wstep-circle active" id="ws1"><i class="ri-user-line"></i></div><div class="wstep-label active" id="wl1">Identity</div></div>
                    <div class="wstep-line" id="wline1"></div>
                    <div class="wstep"><div class="wstep-circle" id="ws2"><i class="ri-building-line"></i></div><div class="wstep-label" id="wl2">Organization</div></div>
                    <div class="wstep-line" id="wline2"></div>
                    <div class="wstep"><div class="wstep-circle" id="ws3"><i class="ri-lock-line"></i></div><div class="wstep-label" id="wl3">Security</div></div>
                    <div class="wstep-line" id="wline3"></div>
                    <div class="wstep"><div class="wstep-circle" id="ws4"><i class="ri-check-line"></i></div><div class="wstep-label" id="wl4">Review</div></div>
                </div>

                <?php if(isset($_SESSION['profile_error'])): ?>
                <div class="alert alert-error"><i class="ri-error-warning-line"></i><?php echo $_SESSION['profile_error']; unset($_SESSION['profile_error']); ?></div>
                <?php endif; ?>

                <!-- Step 1 -->
                <div class="wizard-panel active" id="wpanel1">
                    <div class="wizard-panel-title">💛 Welcome, Partner! Let's set up your profile</div>
                    <div class="wizard-panel-sub">Tell us who you are so we can connect you with the right programs.</div>
                    <div style="display:flex;align-items:flex-start;gap:1.5rem;margin-bottom:1.75rem;flex-wrap:wrap;">
                        <div style="display:flex;flex-direction:column;align-items:center;gap:0.75rem;">
                            <div class="avatar-preview" id="avatarPreview" style="background:linear-gradient(135deg,#F5A623,#EA580C)">
                                <span id="avatarInitials"><?php echo $initials; ?></span>
                            </div>
                            <span style="font-size:0.62rem;color:rgba(255,255,255,0.3);text-align:center;">Pick a color</span>
                            <div class="avatar-picker">
                                <?php foreach($swatches as $i=>$sw): ?>
                                <div class="avatar-swatch <?php echo $i===0?'chosen':''; ?>"
                                     style="background:linear-gradient(135deg,<?php echo $sw[0];?>,<?php echo $sw[1];?>)"
                                     data-c1="<?php echo $sw[0];?>" data-c2="<?php echo $sw[1];?>"
                                     onclick="pickAvatarColor(this)"></div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div style="flex:1;min-width:220px;">
                            <div class="field-group">
                                <label class="field-label"><i class="ri-user-line" style="color:var(--gold);margin-right:4px;"></i>Full Name / Rep Name <span style="color:#f87171;">*</span></label>
                                <input type="text" id="cp_fullname" class="field-input" placeholder="e.g., Juan Dela Cruz or ABC Foundation"
                                       value="<?php echo htmlspecialchars($urow['fullname']??$_SESSION['fullname']??''); ?>"
                                       oninput="updateInitials(this.value)">
                                <div class="field-error" id="err_fullname">Please enter your name.</div>
                            </div>
                            <div class="field-group">
                                <label class="field-label"><i class="ri-mail-line" style="color:#60a5fa;margin-right:4px;"></i>Email Address</label>
                                <input type="email" id="cp_email" class="field-input" placeholder="contact@organization.com"
                                       value="<?php echo htmlspecialchars($urow['email']??$_SESSION['email']??''); ?>">
                                <div class="field-hint"><i class="ri-information-line"></i>For sponsorship receipts and updates</div>
                            </div>
                            <div class="field-group">
                                <label class="field-label"><i class="ri-phone-line" style="color:#fbbf24;margin-right:4px;"></i>Phone Number <span style="color:#f87171;">*</span></label>
                                <input type="text" id="cp_phone" class="field-input" placeholder="+63 912 345 6789">
                                <div class="field-error" id="err_phone">Phone number is required.</div>
                            </div>
                        </div>
                    </div>
                    <div class="wizard-nav">
                        <div></div>
                        <button type="button" class="wbtn-next" onclick="wizardNext(1)">Next <i class="ri-arrow-right-line"></i></button>
                    </div>
                </div>

                <!-- Step 2: Organization -->
                <div class="wizard-panel" id="wpanel2">
                    <div class="wizard-panel-title">🏢 Your Organization</div>
                    <div class="wizard-panel-sub">Are you an individual donor or representing an organization? Let us know.</div>
                    <div class="field-group">
                        <label class="field-label"><i class="ri-building-line" style="color:var(--gold);margin-right:4px;"></i>Organization / Partner Type <span style="color:#f87171;">*</span></label>
                        <input type="text" id="cp_assignments" class="field-input" placeholder="e.g., ABC Corporation, Individual Donor, NGO Partner">
                        <div class="field-error" id="err_assignments">Please enter your organization or partner type.</div>
                        <div class="field-hint"><i class="ri-information-line"></i>You can update this anytime</div>
                    </div>
                    <div style="margin-top:0.5rem;">
                        <div style="font-size:0.62rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:rgba(255,255,255,0.25);margin-bottom:0.6rem;">Quick select:</div>
                        <div style="display:flex;flex-wrap:wrap;gap:6px;">
                            <?php foreach(['Individual Donor','Corporate Sponsor','NGO / Foundation','Government Agency','Religious Organization','Alumni Association','Business Partner','Media Partner'] as $t): ?>
                            <button type="button" onclick="document.getElementById('cp_assignments').value='<?php echo $t; ?>'"
                                    style="padding:5px 12px;border-radius:20px;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);color:rgba(255,255,255,0.5);font-size:0.72rem;font-weight:600;cursor:pointer;transition:all 0.15s;font-family:inherit;"
                                    onmouseover="this.style.background='rgba(245,166,35,0.15)';this.style.borderColor='rgba(245,166,35,0.4)';this.style.color='#fbbf24'"
                                    onmouseout="this.style.background='rgba(255,255,255,0.05)';this.style.borderColor='rgba(255,255,255,0.1)';this.style.color='rgba(255,255,255,0.5)'"><?php echo $t; ?></button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="wizard-nav">
                        <button type="button" class="wbtn-back" onclick="wizardBack(2)"><i class="ri-arrow-left-line"></i> Back</button>
                        <button type="button" class="wbtn-next" onclick="wizardNext(2)">Next <i class="ri-arrow-right-line"></i></button>
                    </div>
                </div>

                <!-- Step 3: Security -->
                <div class="wizard-panel" id="wpanel3">
                    <div class="wizard-panel-title">🔒 Set Your Password</div>
                    <div class="wizard-panel-sub">Leave blank to keep your current password.</div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                        <div class="field-group">
                            <label class="field-label"><i class="ri-lock-password-line" style="color:#fbbf24;margin-right:4px;"></i>New Password</label>
                            <div style="position:relative;">
                                <input type="password" id="cp_password" class="field-input" placeholder="Min. 6 characters" style="padding-right:2.8rem;" oninput="checkPwStrength(this.value)">
                                <button type="button" onclick="toggleWizPw('cp_password',this)" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;color:rgba(255,255,255,0.3);cursor:pointer;font-size:1rem;font-family:inherit;"><i class="ri-eye-line"></i></button>
                            </div>
                            <div class="pw-bar-wrap"><div class="pw-bar" id="pwBar"></div></div>
                            <div id="pwLabel" style="font-size:0.65rem;margin-top:4px;font-weight:700;color:rgba(255,255,255,0.3);text-align:right;"></div>
                            <div class="field-error" id="err_password">Password must be at least 6 characters.</div>
                        </div>
                        <div class="field-group">
                            <label class="field-label"><i class="ri-shield-check-line" style="color:#4ade80;margin-right:4px;"></i>Confirm Password</label>
                            <div style="position:relative;">
                                <input type="password" id="cp_confirm" class="field-input" placeholder="Repeat password" style="padding-right:2.8rem;">
                                <button type="button" onclick="toggleWizPw('cp_confirm',this)" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;color:rgba(255,255,255,0.3);cursor:pointer;font-size:1rem;font-family:inherit;"><i class="ri-eye-line"></i></button>
                            </div>
                            <div class="field-error" id="err_confirm">Passwords do not match.</div>
                        </div>
                    </div>
                    <div style="margin-top:0.75rem;padding:0.75rem 1rem;background:rgba(245,166,35,0.06);border:1px solid rgba(245,166,35,0.2);border-radius:10px;display:flex;align-items:center;gap:8px;">
                        <i class="ri-information-line" style="color:#fbbf24;flex-shrink:0;"></i>
                        <span style="font-size:0.75rem;color:rgba(255,255,255,0.45);">You can skip this and use your existing credentials.</span>
                    </div>
                    <div class="wizard-nav">
                        <button type="button" class="wbtn-back" onclick="wizardBack(3)"><i class="ri-arrow-left-line"></i> Back</button>
                        <button type="button" class="wbtn-next" onclick="wizardNext(3)">Next <i class="ri-arrow-right-line"></i></button>
                    </div>
                </div>

                <!-- Step 4: Review -->
                <div class="wizard-panel" id="wpanel4">
                    <div class="wizard-panel-title">✅ Review & Create Profile</div>
                    <div class="wizard-panel-sub">Confirm your details and unlock the ability to sponsor activities & projects.</div>
                    <div style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.07);border-radius:14px;padding:1.25rem;margin-bottom:1.5rem;">
                        <div style="display:flex;align-items:center;gap:14px;margin-bottom:1.25rem;padding-bottom:1rem;border-bottom:1px solid rgba(255,255,255,0.06);">
                            <div id="reviewAvatar" style="width:56px;height:56px;border-radius:50%;background:linear-gradient(135deg,#F5A623,#EA580C);display:flex;align-items:center;justify-content:center;font-size:1.4rem;font-weight:800;color:white;border:3px solid rgba(255,255,255,0.1);flex-shrink:0;"></div>
                            <div>
                                <div id="reviewName" style="font-size:1.1rem;font-weight:800;color:white;"></div>
                                <div style="font-size:0.75rem;color:rgba(255,255,255,0.4);margin-top:2px;display:flex;align-items:center;gap:5px;"><i class="ri-heart-line" style="color:var(--gold)"></i>Donor / Partner</div>
                            </div>
                            <div style="margin-left:auto;padding:4px 12px;background:rgba(245,166,35,0.15);border:1px solid rgba(245,166,35,0.3);border-radius:50px;font-size:0.65rem;font-weight:800;color:#fbbf24;">NEW PROFILE</div>
                        </div>
                        <div class="review-row"><div class="review-icon" style="background:rgba(96,165,250,0.15);"><i class="ri-mail-line" style="color:#60a5fa;"></i></div><div class="review-key">Email</div><div class="review-val" id="rv_email">—</div></div>
                        <div class="review-row"><div class="review-icon" style="background:rgba(251,191,36,0.15);"><i class="ri-phone-line" style="color:#fbbf24;"></i></div><div class="review-key">Phone</div><div class="review-val" id="rv_phone">—</div></div>
                        <div class="review-row"><div class="review-icon" style="background:rgba(245,166,35,0.15);"><i class="ri-building-line" style="color:#fbbf24;"></i></div><div class="review-key">Organization</div><div class="review-val" id="rv_dept">—</div></div>
                        <div class="review-row" style="border-bottom:none;"><div class="review-icon" style="background:rgba(74,222,128,0.15);"><i class="ri-lock-line" style="color:#4ade80;"></i></div><div class="review-key">Password</div><div class="review-val" id="rv_pw" style="color:rgba(255,255,255,0.4);">Keeping current</div></div>
                    </div>
                    <div style="background:linear-gradient(135deg,rgba(245,166,35,0.1),rgba(234,88,12,0.06));border:1px solid rgba(245,166,35,0.2);border-radius:12px;padding:1rem 1.25rem;margin-bottom:1.5rem;">
                        <div style="font-size:0.72rem;font-weight:800;text-transform:uppercase;letter-spacing:0.1em;color:#fbbf24;margin-bottom:0.75rem;display:flex;align-items:center;gap:6px;"><i class="ri-unlock-line"></i>Features you'll unlock</div>
                        <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:8px;">
                            <?php foreach(['📍 View Locations','📅 View Activities','💰 Fund Activities','📁 View Projects','💎 Fund Projects','💬 Submit Feedback'] as $f): ?>
                            <div style="font-size:0.78rem;color:rgba(255,255,255,0.6);display:flex;align-items:center;gap:6px;"><span style="color:#fbbf24;">✓</span><?php echo $f; ?></div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="wizard-nav">
                        <button type="button" class="wbtn-back" onclick="wizardBack(4)"><i class="ri-arrow-left-line"></i> Back</button>
                        <button type="button" class="wbtn-next" id="createBtn" onclick="submitCreateProfile()"
                                style="background:linear-gradient(135deg,#F5A623,#EA580C);padding-left:2rem;padding-right:2rem;">
                            <i class="ri-user-add-line"></i> Create Profile
                        </button>
                    </div>
                </div>
            </div>

            <script>
            let currentWizStep=1,chosenC1='#F5A623',chosenC2='#EA580C';
            function pickAvatarColor(el){document.querySelectorAll('.avatar-swatch').forEach(s=>s.classList.remove('chosen'));el.classList.add('chosen');chosenC1=el.dataset.c1;chosenC2=el.dataset.c2;document.getElementById('avatarPreview').style.background=`linear-gradient(135deg,${chosenC1},${chosenC2})`;document.getElementById('reviewAvatar').style.background=`linear-gradient(135deg,${chosenC1},${chosenC2})`;}
            function updateInitials(val){const init=val.trim().split(' ').filter(Boolean).slice(0,2).map(w=>w[0].toUpperCase()).join('');document.getElementById('avatarInitials').textContent=init||'?';}
            function checkPwStrength(v){const bar=document.getElementById('pwBar'),lbl=document.getElementById('pwLabel');if(!v){bar.style.width='0%';lbl.textContent='';return;}let s=0;if(v.length>=6)s++;if(v.length>=10)s++;if(/[A-Z]/.test(v))s++;if(/[0-9]/.test(v))s++;if(/[^A-Za-z0-9]/.test(v))s++;const lvl=[{w:'20%',c:'#ef4444',l:'Weak'},{w:'40%',c:'#f97316',l:'Fair'},{w:'65%',c:'#fbbf24',l:'Good'},{w:'85%',c:'#86efac',l:'Strong'},{w:'100%',c:'#4ade80',l:'Very Strong'}][Math.min(s,4)];bar.style.width=lvl.w;bar.style.background=lvl.c;lbl.textContent=lvl.l;lbl.style.color=lvl.c;}
            function toggleWizPw(id,btn){const i=document.getElementById(id);if(i.type==='password'){i.type='text';btn.innerHTML='<i class="ri-eye-off-line"></i>';}else{i.type='password';btn.innerHTML='<i class="ri-eye-line"></i>';}}
            function wizardNext(from){if(!validateStep(from))return;goToStep(from+1);if(from+1===4)populateReview();}
            function wizardBack(from){goToStep(from-1);}
            function goToStep(n){
                document.getElementById('wpanel'+currentWizStep).classList.remove('active');
                currentWizStep=n;document.getElementById('wpanel'+n).classList.add('active');
                const icons=['ri-user-line','ri-building-line','ri-lock-line','ri-check-line'];
                for(let i=1;i<=4;i++){const c=document.getElementById('ws'+i),l=document.getElementById('wl'+i);c.classList.remove('active','done');l.classList.remove('active','done');if(i<n){c.classList.add('done');l.classList.add('done');c.innerHTML='<i class="ri-check-line"></i>';}else if(i===n){c.classList.add('active');l.classList.add('active');c.innerHTML=`<i class="${icons[i-1]}"></i>`;}else{c.innerHTML=`<i class="${icons[i-1]}"></i>`;}if(i<4)document.getElementById('wline'+i).classList.toggle('done',i<n);}
            }
            function validateStep(step){
                let ok=true;
                const show=(id,s)=>{const el=document.getElementById(id);if(el)el.style.display=s?'flex':'none';const inp=document.getElementById(id.replace('err_','cp_'));if(inp)inp.classList.toggle('error',s);};
                if(step===1){const name=document.getElementById('cp_fullname').value.trim(),phone=document.getElementById('cp_phone').value.trim();show('err_fullname',!name);show('err_phone',!phone);if(!name||!phone)ok=false;}
                if(step===2){const dept=document.getElementById('cp_assignments').value.trim();show('err_assignments',!dept);if(!dept)ok=false;}
                if(step===3){const pw=document.getElementById('cp_password').value,cfm=document.getElementById('cp_confirm').value;if(pw!==''&&pw.length<6){show('err_password',true);ok=false;}else show('err_password',false);if(pw!==''&&pw!==cfm){show('err_confirm',true);ok=false;}else show('err_confirm',false);}
                return ok;
            }
            function populateReview(){
                const name=document.getElementById('cp_fullname').value.trim();
                const init=name.split(' ').filter(Boolean).slice(0,2).map(w=>w[0].toUpperCase()).join('');
                document.getElementById('reviewAvatar').textContent=init||'?';
                document.getElementById('reviewAvatar').style.background=`linear-gradient(135deg,${chosenC1},${chosenC2})`;
                document.getElementById('reviewName').textContent=name;
                document.getElementById('rv_email').textContent=document.getElementById('cp_email').value.trim()||'—';
                document.getElementById('rv_phone').textContent=document.getElementById('cp_phone').value.trim();
                document.getElementById('rv_dept').textContent=document.getElementById('cp_assignments').value.trim();
                const hasPw=document.getElementById('cp_password').value!=='';
                document.getElementById('rv_pw').textContent=hasPw?'•••••••• (new password set)':'Keeping current';
                document.getElementById('rv_pw').style.color=hasPw?'white':'rgba(255,255,255,0.4)';
            }
            function submitCreateProfile(){
                if(!validateStep(3)){goToStep(3);return;}
                const btn=document.getElementById('createBtn');btn.disabled=true;
                btn.innerHTML='<i class="ri-loader-4-line" style="animation:spin 0.8s linear infinite;display:inline-block;"></i> Creating...';
                document.getElementById('cp_fullname_hidden').value=document.getElementById('cp_fullname').value.trim();
                document.getElementById('cp_email_hidden').value=document.getElementById('cp_email').value.trim();
                document.getElementById('cp_phone_hidden').value=document.getElementById('cp_phone').value.trim();
                document.getElementById('cp_assignments_hidden').value=document.getElementById('cp_assignments').value.trim();
                document.getElementById('cp_password_hidden').value=document.getElementById('cp_password').value;
                document.getElementById('cp_confirm_hidden').value=document.getElementById('cp_confirm').value;
                document.getElementById('createProfileForm').submit();
            }
            </script>

            <?php else: ?>
            <!-- UPDATE PROFILE -->
            <div style="background:linear-gradient(135deg,#1a1200 0%,#2a1e00 60%,#332500 100%);padding:2rem 2rem 0;position:relative;overflow:hidden;">
                <div style="position:absolute;top:-60px;right:-40px;width:220px;height:220px;background:rgba(245,166,35,0.06);border-radius:50%;"></div>
                <div style="display:flex;align-items:flex-end;gap:1.5rem;position:relative;z-index:1;">
                    <div style="position:relative;flex-shrink:0;">
                        <div id="avatarCircle" onclick="document.getElementById('avatarOptions').classList.toggle('hidden')" style="width:90px;height:90px;border-radius:50%;background:linear-gradient(135deg,#F5A623,#EA580C);display:flex;align-items:center;justify-content:center;font-size:2rem;font-weight:800;color:white;border:4px solid rgba(255,255,255,0.1);box-shadow:0 8px 32px rgba(245,166,35,0.3);cursor:pointer;transition:all 0.2s;" title="Click to change color">
                            <?php echo $initials; ?>
                        </div>
                        <div style="position:absolute;bottom:2px;right:2px;width:18px;height:18px;background:var(--gold);border-radius:50%;border:2px solid #1a1200;box-shadow:0 0 8px rgba(245,166,35,0.6);"></div>
                        <div id="avatarOptions" class="hidden" style="position:absolute;top:100%;left:0;margin-top:8px;background:rgba(20,14,0,0.95);border:1px solid rgba(255,255,255,0.1);border-radius:12px;padding:0.75rem;display:flex;gap:6px;z-index:10;box-shadow:0 8px 32px rgba(0,0,0,0.5);">
                            <?php foreach($swatches as $c): ?>
                            <div onclick="setAvatarColor('<?php echo $c[0];?>','<?php echo $c[1];?>')" style="width:22px;height:22px;border-radius:50%;background:linear-gradient(135deg,<?php echo $c[0];?>,<?php echo $c[1];?>);cursor:pointer;transition:transform 0.15s;" onmouseover="this.style.transform='scale(1.2)'" onmouseout="this.style.transform='scale(1)'"></div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div style="padding-bottom:1.25rem;flex:1;">
                        <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                            <h2 style="font-size:1.5rem;font-weight:800;color:white;"><?php echo htmlspecialchars($urow['fullname']??'Donor'); ?></h2>
                            <span style="background:rgba(245,166,35,0.15);border:1px solid rgba(245,166,35,0.3);border-radius:50px;padding:2px 12px;font-size:0.68rem;font-weight:700;color:#fbbf24;">● Active Partner</span>
                        </div>
                        <div style="display:flex;align-items:center;gap:1rem;margin-top:6px;flex-wrap:wrap;">
                            <span style="font-size:0.8rem;color:rgba(255,255,255,0.45);display:flex;align-items:center;gap:5px;"><i class="ri-heart-line" style="color:var(--gold)"></i>Donor / Partner</span>
                            <?php if(!empty($urow['assignments'])): ?><span style="font-size:0.8rem;color:rgba(255,255,255,0.45);display:flex;align-items:center;gap:5px;"><i class="ri-building-line" style="color:#60a5fa"></i><?php echo htmlspecialchars($urow['assignments']); ?></span><?php endif; ?>
                            <?php if(!empty($urow['join_date'])): ?><span style="font-size:0.8rem;color:rgba(255,255,255,0.35);display:flex;align-items:center;gap:5px;"><i class="ri-calendar-line" style="color:#fbbf24"></i>Since <?php echo date('M Y',strtotime($urow['join_date'])); ?></span><?php endif; ?>
                        </div>
                    </div>
                    <div style="padding-bottom:1.25rem;">
                        <button onclick="document.getElementById('profileForm').scrollIntoView({behavior:'smooth'})" style="background:rgba(245,166,35,0.15);border:1px solid rgba(245,166,35,0.3);border-radius:9px;padding:7px 14px;color:#fbbf24;font-size:0.78rem;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:5px;transition:all 0.2s;"><i class="ri-edit-line"></i>Edit Profile</button>
                    </div>
                </div>
            </div>

            <!-- Donor Stats -->
            <div class="stat-grid">
                <div class="stat-card gold"><div><div class="stat-label">Total Donated</div><div class="stat-value" style="font-size:1.1rem;margin-top:4px;">₱<?php echo number_format($totalAmount,0); ?></div><div class="stat-sub">Lifetime contributions</div></div><div class="stat-icon" style="background:linear-gradient(135deg,#F5A623,#EA580C)"><i class="ri-money-dollar-circle-line"></i></div></div>
                <div class="stat-card orange"><div><div class="stat-label">Activities Funded</div><div class="stat-value"><?php echo $totalActivitySponsored; ?></div><div class="stat-sub">Sponsored</div></div><div class="stat-icon" style="background:linear-gradient(135deg,#EA580C,#dc2626)"><i class="ri-calendar-event-line"></i></div></div>
                <div class="stat-card green"><div><div class="stat-label">Projects Funded</div><div class="stat-value"><?php echo $totalProjectSponsored; ?></div><div class="stat-sub">Sponsored</div></div><div class="stat-icon" style="background:linear-gradient(135deg,#16a34a,#15803d)"><i class="ri-folder-2-line"></i></div></div>
                <div class="stat-card purple"><div><div class="stat-label">Organization</div><div class="stat-value" style="font-size:0.72rem;margin-top:4px;line-height:1.4;"><?php echo htmlspecialchars(substr($urow['assignments']??'Not set',0,22)); ?></div></div><div class="stat-icon" style="background:linear-gradient(135deg,#7c3aed,#6d28d9)"><i class="ri-building-line"></i></div></div>
            </div>

            <div class="content-area" id="profileForm">
                <?php if(isset($_SESSION['profile_success'])): ?>
                <div class="alert alert-success"><i class="ri-checkbox-circle-line"></i><?php echo $_SESSION['profile_success']; unset($_SESSION['profile_success']); ?></div>
                <?php endif; ?>
                <?php if(isset($_SESSION['profile_error'])): ?>
                <div class="alert alert-error"><i class="ri-error-warning-line"></i><?php echo $_SESSION['profile_error']; unset($_SESSION['profile_error']); ?></div>
                <?php endif; ?>
                <?php if(isset($_SESSION['profile_notice'])): ?>
                <div class="alert alert-notice"><i class="ri-information-line"></i><?php echo $_SESSION['profile_notice']; unset($_SESSION['profile_notice']); ?></div>
                <?php endif; ?>

                <div style="display:flex;align-items:center;gap:10px;margin-bottom:1.25rem;padding-bottom:0.75rem;border-bottom:1px solid rgba(255,255,255,0.07);">
                    <div style="width:32px;height:32px;border-radius:8px;background:rgba(245,166,35,0.15);border:1px solid rgba(245,166,35,0.25);display:flex;align-items:center;justify-content:center;"><i class="ri-edit-2-line" style="color:var(--gold);font-size:0.95rem;"></i></div>
                    <div><div style="font-size:0.9rem;font-weight:700;color:white;">Edit Information</div><div style="font-size:0.7rem;color:rgba(255,255,255,0.35);">Update your donor profile details</div></div>
                </div>

                <form id="profileFormEl" action="donor_process.php" method="POST" style="max-width:680px;">
                    <input type="hidden" name="action" value="update_profile">
                    <div style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.07);border-radius:12px;padding:1.25rem;margin-bottom:1rem;">
                        <div style="font-size:0.68rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:rgba(255,255,255,0.3);margin-bottom:1rem;display:flex;align-items:center;gap:6px;"><i class="ri-information-line" style="color:var(--gold)"></i>Basic Information</div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group"><label class="form-label"><i class="ri-user-line" style="color:var(--gold);margin-right:4px;"></i>Full Name / Rep Name</label><input type="text" name="fullname" id="inp_fullname" class="form-input" value="<?php echo htmlspecialchars($urow['fullname']??''); ?>" required></div>
                            <div class="form-group"><label class="form-label"><i class="ri-mail-line" style="color:#60a5fa;margin-right:4px;"></i>Email Address</label><input type="email" name="email" id="inp_email" class="form-input" value="<?php echo htmlspecialchars($urow['email']??''); ?>"></div>
                            <div class="form-group"><label class="form-label"><i class="ri-phone-line" style="color:#fbbf24;margin-right:4px;"></i>Phone Number</label><input type="text" name="phone" id="inp_phone" class="form-input" value="<?php echo htmlspecialchars($urow['phone']??''); ?>"></div>
                            <div class="form-group"><label class="form-label"><i class="ri-heart-line" style="color:var(--gold);margin-right:4px;"></i>Role</label><input type="text" class="form-input" value="Donor / Partner" disabled style="opacity:0.4;"></div>
                            <div class="form-group" style="grid-column:1/-1;"><label class="form-label"><i class="ri-building-line" style="color:#60a5fa;margin-right:4px;"></i>Organization / Partner Type</label><input type="text" name="assignments" id="inp_assignments" class="form-input" value="<?php echo htmlspecialchars($urow['assignments']??''); ?>"></div>
                        </div>
                    </div>
                    <div style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.07);border-radius:12px;padding:1.25rem;margin-bottom:1.25rem;">
                        <div style="font-size:0.68rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:rgba(255,255,255,0.3);margin-bottom:1rem;display:flex;align-items:center;gap:6px;"><i class="ri-lock-line" style="color:#fbbf24"></i>Security</div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group"><label class="form-label"><i class="ri-lock-password-line" style="color:#fbbf24;margin-right:4px;"></i>New Password <span style="color:rgba(255,255,255,0.2);font-size:0.6rem;text-transform:none;letter-spacing:0;font-weight:400;">(leave blank to keep)</span></label><div style="position:relative;"><input type="password" name="new_password" id="inp_password" class="form-input" placeholder="••••••••" style="padding-right:2.8rem;"><button type="button" onclick="togglePw('inp_password',this)" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;color:rgba(255,255,255,0.3);cursor:pointer;font-size:1rem;"><i class="ri-eye-line"></i></button></div></div>
                            <div class="form-group"><label class="form-label"><i class="ri-shield-check-line" style="color:#4ade80;margin-right:4px;"></i>Confirm New Password</label><div style="position:relative;"><input type="password" name="confirm_password" id="inp_confirm" class="form-input" placeholder="••••••••" style="padding-right:2.8rem;"><button type="button" onclick="togglePw('inp_confirm',this)" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;color:rgba(255,255,255,0.3);cursor:pointer;font-size:1rem;"><i class="ri-eye-line"></i></button></div></div>
                        </div>
                        <div id="pwStrengthWrap" style="display:none;margin-top:0.5rem;"><div style="display:flex;align-items:center;gap:8px;"><div style="flex:1;height:4px;background:rgba(255,255,255,0.1);border-radius:4px;overflow:hidden;"><div id="pwStrengthBar" style="height:100%;width:0%;border-radius:4px;transition:all 0.3s;"></div></div><span id="pwStrengthLabel" style="font-size:0.68rem;font-weight:700;width:60px;text-align:right;"></span></div></div>
                    </div>
                    <div style="display:flex;align-items:center;gap:0.75rem;">
                        <button type="button" onclick="openSaveConfirm()" class="btn-save"><i class="ri-save-3-line"></i>Save Changes</button>
                        <button type="button" onclick="resetForm()" style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);border-radius:9px;padding:0.7rem 1.2rem;font-size:0.875rem;font-weight:600;color:rgba(255,255,255,0.5);cursor:pointer;font-family:inherit;transition:all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.1)';this.style.color='white'" onmouseout="this.style.background='rgba(255,255,255,0.06)';this.style.color='rgba(255,255,255,0.5)'"><i class="ri-refresh-line"></i> Reset</button>
                    </div>
                </form>
            </div>

            <!-- Save Confirm Modal -->
            <div id="saveConfirmModal" style="display:none;position:fixed;inset:0;z-index:1000;background:rgba(0,0,0,0.8);backdrop-filter:blur(6px);align-items:center;justify-content:center;">
                <div style="background:linear-gradient(135deg,#1a1200,#120e00);border:1px solid rgba(245,166,35,0.2);border-radius:18px;padding:2rem;width:100%;max-width:420px;margin:1rem;box-shadow:0 24px 64px rgba(0,0,0,0.7);text-align:center;">
                    <div style="width:64px;height:64px;border-radius:50%;background:rgba(245,166,35,0.15);border:1px solid rgba(245,166,35,0.3);display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;font-size:1.8rem;color:var(--gold);"><i class="ri-save-3-line"></i></div>
                    <div style="font-size:1.1rem;font-weight:800;color:white;margin-bottom:0.4rem;">Save Changes?</div>
                    <div style="font-size:0.85rem;color:rgba(255,255,255,0.45);margin-bottom:0.5rem;">Your profile information will be updated.</div>
                    <div id="changePreview" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:10px;padding:0.75rem;margin:0.75rem 0 1.25rem;text-align:left;font-size:0.78rem;color:rgba(255,255,255,0.5);"></div>
                    <div style="display:flex;gap:0.75rem;">
                        <button onclick="closeSaveConfirm()" style="flex:1;padding:0.7rem;border-radius:10px;background:rgba(255,255,255,0.07);border:1px solid rgba(255,255,255,0.1);color:rgba(255,255,255,0.6);font-weight:700;cursor:pointer;font-family:inherit;font-size:0.85rem;"><i class="ri-close-line"></i> Cancel</button>
                        <button onclick="submitProfileForm()" style="flex:1;padding:0.7rem;border-radius:10px;background:linear-gradient(135deg,#F5A623,#EA580C);border:none;color:white;font-weight:700;cursor:pointer;font-family:inherit;font-size:0.85rem;box-shadow:0 4px 14px rgba(245,166,35,0.3);"><i class="ri-check-line"></i> Yes, Save</button>
                    </div>
                </div>
            </div>

            <script>
            function setAvatarColor(c1,c2){document.getElementById('avatarCircle').style.background='linear-gradient(135deg,'+c1+','+c2+')';document.getElementById('avatarOptions').classList.add('hidden');}
            document.addEventListener('click',function(e){const o=document.getElementById('avatarOptions');if(o&&!o.contains(e.target)&&e.target.id!=='avatarCircle'&&!document.getElementById('avatarCircle')?.contains(e.target))o.classList.add('hidden');});
            function togglePw(id,btn){const i=document.getElementById(id);if(i.type==='password'){i.type='text';btn.innerHTML='<i class="ri-eye-off-line"></i>';}else{i.type='password';btn.innerHTML='<i class="ri-eye-line"></i>';}}
            document.getElementById('inp_password')?.addEventListener('input',function(){const v=this.value,wrap=document.getElementById('pwStrengthWrap'),bar=document.getElementById('pwStrengthBar'),lbl=document.getElementById('pwStrengthLabel');if(!v){wrap.style.display='none';return;}wrap.style.display='block';let s=0;if(v.length>=8)s++;if(/[A-Z]/.test(v))s++;if(/[0-9]/.test(v))s++;if(/[^A-Za-z0-9]/.test(v))s++;const lv=[{w:'25%',c:'#ef4444',l:'Weak'},{w:'50%',c:'#f97316',l:'Fair'},{w:'75%',c:'#fbbf24',l:'Good'},{w:'100%',c:'#4ade80',l:'Strong'}][s-1]||{w:'25%',c:'#ef4444',l:'Weak'};bar.style.width=lv.w;bar.style.background=lv.c;lbl.textContent=lv.l;lbl.style.color=lv.c;});
            function resetForm(){document.getElementById('inp_fullname').value='<?php echo addslashes($urow['fullname']??''); ?>';document.getElementById('inp_email').value='<?php echo addslashes($urow['email']??''); ?>';document.getElementById('inp_phone').value='<?php echo addslashes($urow['phone']??''); ?>';document.getElementById('inp_assignments').value='<?php echo addslashes($urow['assignments']??''); ?>';document.getElementById('pwStrengthWrap').style.display='none';}
            function openSaveConfirm(){
                const name=document.getElementById('inp_fullname').value,email=document.getElementById('inp_email').value,phone=document.getElementById('inp_phone').value,dept=document.getElementById('inp_assignments').value,hasPw=document.getElementById('inp_password').value!=='';
                let p=`<div style="margin-bottom:5px;"><span style="color:rgba(255,255,255,0.4);width:80px;display:inline-block;">Name:</span><span style="color:white;font-weight:600;">${name}</span></div>`;
                p+=`<div style="margin-bottom:5px;"><span style="color:rgba(255,255,255,0.4);width:80px;display:inline-block;">Email:</span><span style="color:white;font-weight:600;">${email||'—'}</span></div>`;
                p+=`<div style="margin-bottom:5px;"><span style="color:rgba(255,255,255,0.4);width:80px;display:inline-block;">Phone:</span><span style="color:white;font-weight:600;">${phone||'—'}</span></div>`;
                p+=`<div style="margin-bottom:5px;"><span style="color:rgba(255,255,255,0.4);width:80px;display:inline-block;">Org:</span><span style="color:white;font-weight:600;">${dept||'—'}</span></div>`;
                if(hasPw)p+=`<div style="margin-top:6px;color:#fbbf24;font-size:0.72rem;"><i class="ri-lock-line"></i> Password will also be updated</div>`;
                document.getElementById('changePreview').innerHTML=p;
                document.getElementById('saveConfirmModal').style.display='flex';
                document.body.style.overflow='hidden';
            }
            function closeSaveConfirm(){document.getElementById('saveConfirmModal').style.display='none';document.body.style.overflow='';}
            function submitProfileForm(){closeSaveConfirm();document.getElementById('profileFormEl').submit();}
            document.getElementById('saveConfirmModal')?.addEventListener('click',function(e){if(e.target===this)closeSaveConfirm();});
            </script>
            <?php endif; ?>

            <?php elseif($section === 'location'): ?>
            <div class="content-area">
                <div id="locationsGrid"><div class="text-center py-12"><i class="ri-loader-4-line text-3xl animate-spin" style="color:var(--gold)"></i><p class="text-sm mt-3" style="color:rgba(255,255,255,0.35)">Loading locations...</p></div></div>
            </div>

            <?php elseif($section === 'activity'): ?>
            <div class="stat-grid">
                <div class="stat-card gold"><div><div class="stat-label">Total</div><div class="stat-value" id="statTotal">0</div><div class="stat-sub">All activities</div></div><div class="stat-icon" style="background:linear-gradient(135deg,#F5A623,#EA580C)"><i class="ri-calendar-line"></i></div></div>
                <div class="stat-card orange"><div><div class="stat-label">Ongoing</div><div class="stat-value" id="statOngoing">0</div><div class="stat-sub" style="color:#fb923c">• In progress</div></div><div class="stat-icon" style="background:linear-gradient(135deg,#EA580C,#dc2626)"><i class="ri-play-circle-line"></i></div></div>
                <div class="stat-card gold"><div><div class="stat-label">Upcoming</div><div class="stat-value" id="statUpcoming">0</div><div class="stat-sub">Scheduled</div></div><div class="stat-icon" style="background:linear-gradient(135deg,#7c3aed,#6d28d9)"><i class="ri-calendar-check-line"></i></div></div>
                <div class="stat-card green"><div><div class="stat-label">Completed</div><div class="stat-value" id="statCompleted">0</div><div class="stat-sub" style="color:#4ade80">✓ Delivered</div></div><div class="stat-icon" style="background:linear-gradient(135deg,#16a34a,#15803d)"><i class="ri-checkbox-circle-line"></i></div></div>
            </div>
            <div class="content-area">
                <div class="tab-wrap">
                    <button onclick="showActivityTab('view')"     id="tab-view"     class="tab-btn active"><i class="ri-eye-line"></i> View Activities</button>
                    <button onclick="showActivityTab('feedback')" id="tab-feedback" class="tab-btn"><i class="ri-feedback-line"></i> Provide Feedback</button>
                </div>
                <div id="activity-view">
                    <div class="filter-bar"><div class="search-wrap"><i class="ri-search-line"></i><input type="text" id="actSearch" class="search-input" placeholder="Search activities..."></div></div>
                    <div id="activitiesContent"><div class="text-center py-8"><i class="ri-loader-4-line animate-spin" style="color:var(--gold);font-size:1.5rem"></i></div></div>
                </div>
                <div id="activity-feedback" class="hidden" style="max-width:520px;">
                    <h3 style="font-weight:700;color:white;font-size:1rem;margin-bottom:1rem;">Provide Activity Feedback</h3>
                    <form method="POST" action="donor_process.php" class="space-y-4">
                        <input type="hidden" name="action" value="activity_feedback">
                        <div class="form-group"><label class="form-label">Select Activity</label><select name="activity_id" class="form-input" id="activityFeedbackSelect"><option value="">-- Select Activity --</option></select></div>
                        <div class="form-group"><label class="form-label">Your Feedback</label><textarea name="feedback" rows="4" class="form-input" placeholder="Share your thoughts as a sponsor..." style="resize:none;"></textarea></div>
                        <div class="form-group"><label class="form-label">Rating</label><select name="rating" class="form-input"><option value="5">⭐⭐⭐⭐⭐ Excellent</option><option value="4">⭐⭐⭐⭐ Good</option><option value="3">⭐⭐⭐ Average</option><option value="2">⭐⭐ Below Average</option><option value="1">⭐ Poor</option></select></div>
                        <button type="submit" class="btn-save"><i class="ri-send-plane-line"></i> Submit Feedback</button>
                    </form>
                </div>
            </div>

            <?php elseif($section === 'project'): ?>
            <div class="stat-grid">
                <div class="stat-card gold"><div><div class="stat-label">Total</div><div class="stat-value" id="pStatTotal">0</div><div class="stat-sub">All projects</div></div><div class="stat-icon" style="background:linear-gradient(135deg,#F5A623,#EA580C)"><i class="ri-folder-2-line"></i></div></div>
                <div class="stat-card green"><div><div class="stat-label">Active</div><div class="stat-value" id="pStatActive">0</div><div class="stat-sub" style="color:#4ade80">• In progress</div></div><div class="stat-icon" style="background:linear-gradient(135deg,#16a34a,#15803d)"><i class="ri-play-circle-line"></i></div></div>
                <div class="stat-card gold"><div><div class="stat-label">Planning</div><div class="stat-value" id="pStatPlanning">0</div><div class="stat-sub">Scheduled</div></div><div class="stat-icon" style="background:linear-gradient(135deg,#7c3aed,#6d28d9)"><i class="ri-calendar-check-line"></i></div></div>
                <div class="stat-card orange"><div><div class="stat-label">Completed</div><div class="stat-value" id="pStatCompleted">0</div><div class="stat-sub" style="color:#fb923c">✓ Delivered</div></div><div class="stat-icon" style="background:linear-gradient(135deg,#EA580C,#dc2626)"><i class="ri-checkbox-circle-line"></i></div></div>
            </div>
            <div class="content-area">
                <div class="tab-wrap">
                    <button onclick="showProjectTab('view')"     id="ptab-view"     class="tab-btn active"><i class="ri-eye-line"></i> View Projects</button>
                    <button onclick="showProjectTab('feedback')" id="ptab-feedback" class="tab-btn"><i class="ri-feedback-line"></i> Provide Feedback</button>
                </div>
                <div id="project-view">
                    <div class="filter-bar"><div class="search-wrap"><i class="ri-search-line"></i><input type="text" id="projSearch" class="search-input" placeholder="Search projects..."></div></div>
                    <div id="projectsContent"><div class="text-center py-8"><i class="ri-loader-4-line animate-spin" style="color:var(--gold);font-size:1.5rem"></i></div></div>
                </div>
                <div id="project-feedback" class="hidden" style="max-width:520px;">
                    <h3 style="font-weight:700;color:white;font-size:1rem;margin-bottom:1rem;">Provide Project Feedback</h3>
                    <form method="POST" action="donor_process.php" class="space-y-4">
                        <input type="hidden" name="action" value="project_feedback">
                        <div class="form-group"><label class="form-label">Select Project</label><select name="project_id" class="form-input" id="projectFeedbackSelect"><option value="">-- Select Project --</option></select></div>
                        <div class="form-group"><label class="form-label">Your Feedback</label><textarea name="feedback" rows="4" class="form-input" placeholder="Share your thoughts as a sponsor..." style="resize:none;"></textarea></div>
                        <div class="form-group"><label class="form-label">Rating</label><select name="rating" class="form-input"><option value="5">⭐⭐⭐⭐⭐ Excellent</option><option value="4">⭐⭐⭐⭐ Good</option><option value="3">⭐⭐⭐ Average</option><option value="2">⭐⭐ Below Average</option><option value="1">⭐ Poor</option></select></div>
                        <button type="submit" class="btn-save"><i class="ri-send-plane-line"></i> Submit Feedback</button>
                    </form>
                </div>
            </div>

            <?php elseif($section === 'feedback'): ?>
            <?php
            $username=$_SESSION['fullname']??'';
            $evals=[];
            $eq=$conn->prepare("SELECT * FROM evaluations WHERE evaluator=? ORDER BY created_at DESC");
            if($eq){$eq->bind_param("s",$username);$eq->execute();$res=$eq->get_result();while($row=$res->fetch_assoc())$evals[]=$row;}
            ?>
            <div class="content-area">
                <?php if(empty($evals)): ?>
                <div class="text-center py-16"><i class="ri-feedback-line text-5xl" style="color:rgba(255,255,255,0.08)"></i><p class="mt-4 text-sm" style="color:rgba(255,255,255,0.35)">No feedback submitted yet.</p></div>
                <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead><tr><th>Program</th><th>Title</th><th>Findings</th><th>Status</th><th>Date</th></tr></thead>
                        <tbody>
                            <?php foreach($evals as $ev): ?>
                            <tr>
                                <td style="font-weight:600;color:white;"><?php echo htmlspecialchars($ev['program']??'—'); ?></td>
                                <td style="color:white;"><?php echo htmlspecialchars($ev['title']??'—'); ?></td>
                                <td style="max-width:260px;color:rgba(255,255,255,0.5)"><?php echo htmlspecialchars(substr($ev['findings']??'',0,70)).(strlen($ev['findings']??'')>70?'...':''); ?></td>
                                <td><span class="badge <?php echo ($ev['status']??'')==='Completed'?'badge-green':'badge-gray'; ?>"><?php echo htmlspecialchars($ev['status']??'—'); ?></span></td>
                                <td style="color:rgba(255,255,255,0.35);font-size:0.75rem"><?php echo $ev['eval_date']??$ev['created_at']??''; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 text-xs" style="color:rgba(255,255,255,0.3)">Total: <strong style="color:white"><?php echo count($evals); ?></strong> submissions</div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

        </div><!-- glass-card -->
        <div class="status-bar"><span>© <?php echo date('Y'); ?> CEMS. All rights reserved.</span><span>Donor / Partner Portal &nbsp;•&nbsp; v2.0</span></div>
    </div>
</main>

<!-- FUND MODAL (Activity) -->
<div class="modal-overlay" id="fundActivityModal">
    <div class="modal-box" style="max-width:460px;">
        <div class="modal-icon-wrap" style="background:rgba(245,166,35,0.15);border:1px solid rgba(245,166,35,0.3);"><i class="ri-calendar-event-line" style="color:#fbbf24;"></i></div>
        <div class="modal-title">Fund / Sponsor Activity</div>
        <div class="modal-msg">You are sponsoring:<br><span class="modal-name" id="fundActName"></span></div>
        <div class="amount-wrap" style="margin-bottom:8px;">
            <input type="number" id="fundActAmount" class="amount-input" placeholder="Enter sponsorship amount" min="1" step="0.01">
        </div>
        <div class="quick-amounts">
            <?php foreach([500,1000,2500,5000,10000] as $qa): ?>
            <button type="button" class="qa-chip" onclick="document.getElementById('fundActAmount').value=<?php echo $qa; ?>">₱<?php echo number_format($qa); ?></button>
            <?php endforeach; ?>
        </div>
        <div style="margin-top:1rem;">
            <input type="text" id="fundActNote" class="form-input" placeholder="Optional note / dedication (e.g., 'In honor of...')">
        </div>
        <div class="modal-btns">
            <button class="modal-btn-cancel" onclick="closeFundActivityModal()"><i class="ri-close-line"></i> Cancel</button>
            <button class="modal-btn-confirm" id="fundActConfirmBtn" onclick="confirmFundActivity()"><i class="ri-money-dollar-circle-line"></i> Sponsor Now</button>
        </div>
    </div>
</div>

<!-- FUND MODAL (Project) -->
<div class="modal-overlay" id="fundProjectModal">
    <div class="modal-box" style="max-width:460px;">
        <div class="modal-icon-wrap" style="background:rgba(22,163,74,0.15);border:1px solid rgba(22,163,74,0.3);"><i class="ri-folder-2-line" style="color:#4ade80;"></i></div>
        <div class="modal-title">Fund / Sponsor Project</div>
        <div class="modal-msg">You are sponsoring:<br><span class="modal-name" id="fundProjName"></span></div>
        <div class="amount-wrap" style="margin-bottom:8px;">
            <input type="number" id="fundProjAmount" class="amount-input" placeholder="Enter sponsorship amount" min="1" step="0.01">
        </div>
        <div class="quick-amounts">
            <?php foreach([500,1000,2500,5000,10000] as $qa): ?>
            <button type="button" class="qa-chip" onclick="document.getElementById('fundProjAmount').value=<?php echo $qa; ?>">₱<?php echo number_format($qa); ?></button>
            <?php endforeach; ?>
        </div>
        <div style="margin-top:1rem;">
            <input type="text" id="fundProjNote" class="form-input" placeholder="Optional note / dedication">
        </div>
        <div class="modal-btns">
            <button class="modal-btn-cancel" onclick="closeFundProjectModal()"><i class="ri-close-line"></i> Cancel</button>
            <button class="modal-btn-confirm" id="fundProjConfirmBtn" onclick="confirmFundProject()" style="background:linear-gradient(135deg,#16a34a,#15803d);box-shadow:0 4px 14px rgba(22,163,74,0.3);"><i class="ri-money-dollar-circle-line"></i> Sponsor Now</button>
        </div>
    </div>
</div>

<!-- TOAST -->
<div class="toast" id="toast"><i class="toast-icon" id="toastIcon"></i><span id="toastMsg"></span></div>

<script>
function updateTime(){const t=new Date().toLocaleTimeString('en-US',{hour:'2-digit',minute:'2-digit'});const el=document.getElementById('currentTime');if(el)el.textContent=t;const m=document.getElementById('mobileTime');if(m)m.textContent=t;}
updateTime();setInterval(updateTime,1000);

const mobileMenuBtn=document.getElementById('mobileMenuBtn');
const mobileSidebar=document.getElementById('mobileSidebar');
const mobileOverlay=document.getElementById('mobileOverlay');
mobileMenuBtn?.addEventListener('click',()=>{mobileSidebar.classList.remove('hidden');document.body.style.overflow='hidden';});
function closeMob(){mobileSidebar.classList.add('hidden');document.body.style.overflow='';}
mobileOverlay?.addEventListener('click',closeMob);
document.addEventListener('keydown',e=>{if(e.key==='Escape'){closeMob();closeFundActivityModal();closeFundProjectModal();}});

document.querySelectorAll('a.nav-item:not(.locked)').forEach(link=>{
    link.addEventListener('click',e=>{
        if(link.href.includes('donor.php')&&!link.href.includes('logout')){
            e.preventDefault();
            const main=document.querySelector('main');
            main.style.transition='opacity 0.2s,transform 0.2s';
            main.style.opacity='0';main.style.transform='translateY(8px)';
            setTimeout(()=>{window.location.href=link.href;},200);
        }
    });
});
window.addEventListener('load',()=>{const main=document.querySelector('main');main.style.transition='all 0.3s ease';main.style.opacity='1';main.style.transform='translateY(0)';});

function showSetupAlert(){showToast('Please complete your profile first!','error');}

function showToast(msg,type='success'){
    const toast=document.getElementById('toast'),icon=document.getElementById('toastIcon');
    document.getElementById('toastMsg').textContent=msg;
    toast.className=`toast ${type}`;
    icon.className=type==='success'?'toast-icon ri-checkbox-circle-line':'toast-icon ri-error-warning-line';
    icon.style.color=type==='success'?'#fbbf24':'#f87171';
    setTimeout(()=>toast.classList.add('show'),10);
    setTimeout(()=>toast.classList.remove('show'),3500);
}

// ── Fund Activity ──
let _fundActId=0;
function openFundActivity(id,name){
    _fundActId=id;
    document.getElementById('fundActName').textContent='"'+name+'"';
    document.getElementById('fundActAmount').value='';
    document.getElementById('fundActNote').value='';
    document.getElementById('fundActivityModal').classList.add('show');
    document.body.style.overflow='hidden';
}
function closeFundActivityModal(){document.getElementById('fundActivityModal').classList.remove('show');document.body.style.overflow='';}
function confirmFundActivity(){
    const amount=parseFloat(document.getElementById('fundActAmount').value);
    const note=document.getElementById('fundActNote').value.trim();
    if(!amount||amount<=0){showToast('Please enter a valid amount.','error');return;}
    const btn=document.getElementById('fundActConfirmBtn');
    btn.classList.add('loading');btn.textContent='Processing...';
    const fd=new FormData();fd.append('action','fund_activity');fd.append('activity_id',_fundActId);fd.append('amount',amount);fd.append('note',note);
    fetch('donor_process.php',{method:'POST',body:fd}).then(r=>r.json()).then(d=>{
        closeFundActivityModal();
        if(d.status==='success')showToast(d.message,'success');
        else showToast(d.message,'error');
    }).catch(()=>{closeFundActivityModal();showToast('Network error.','error');}).finally(()=>{btn.classList.remove('loading');btn.innerHTML='<i class="ri-money-dollar-circle-line"></i> Sponsor Now';});
}
document.getElementById('fundActivityModal')?.addEventListener('click',function(e){if(e.target===this)closeFundActivityModal();});

// ── Fund Project ──
let _fundProjId=0;
function openFundProject(id,name){
    _fundProjId=id;
    document.getElementById('fundProjName').textContent='"'+name+'"';
    document.getElementById('fundProjAmount').value='';
    document.getElementById('fundProjNote').value='';
    document.getElementById('fundProjectModal').classList.add('show');
    document.body.style.overflow='hidden';
}
function closeFundProjectModal(){document.getElementById('fundProjectModal').classList.remove('show');document.body.style.overflow='';}
function confirmFundProject(){
    const amount=parseFloat(document.getElementById('fundProjAmount').value);
    const note=document.getElementById('fundProjNote').value.trim();
    if(!amount||amount<=0){showToast('Please enter a valid amount.','error');return;}
    const btn=document.getElementById('fundProjConfirmBtn');
    btn.classList.add('loading');btn.textContent='Processing...';
    const fd=new FormData();fd.append('action','fund_project');fd.append('project_id',_fundProjId);fd.append('amount',amount);fd.append('note',note);
    fetch('donor_process.php',{method:'POST',body:fd}).then(r=>r.json()).then(d=>{
        closeFundProjectModal();
        if(d.status==='success')showToast(d.message,'success');
        else showToast(d.message,'error');
    }).catch(()=>{closeFundProjectModal();showToast('Network error.','error');}).finally(()=>{btn.classList.remove('loading');btn.innerHTML='<i class="ri-money-dollar-circle-line"></i> Sponsor Now';});
}
document.getElementById('fundProjectModal')?.addEventListener('click',function(e){if(e.target===this)closeFundProjectModal();});

<?php if($section==='location'): ?>
fetch('api/location_api.php').then(r=>r.json()).then(resp=>{
    const data=resp.data||resp||[];
    const hc=document.getElementById('headerCount');if(hc)hc.textContent=(Array.isArray(data)?data.length:0)+' Total';
    if(!Array.isArray(data)||!data.length){document.getElementById('locationsGrid').innerHTML='<div class="text-center py-12"><i class="ri-map-pin-line text-4xl" style="color:rgba(255,255,255,0.1)"></i><p class="text-sm mt-3" style="color:rgba(255,255,255,0.35)">No locations found.</p></div>';return;}
    document.getElementById('locationsGrid').innerHTML=`<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">${data.map(l=>`<div class="loc-card"><div class="flex items-center gap-3 mb-2"><div class="row-icon" style="background:linear-gradient(135deg,#F5A623,#EA580C)"><i class="ri-map-pin-line"></i></div><div class="row-name">${l.name||'—'}</div></div>${l.address?`<p class="text-xs ml-12 mt-1" style="color:rgba(255,255,255,0.4)"><i class="ri-road-map-line mr-1"></i>${l.address}</p>`:''}${l.region?`<p class="text-xs ml-12 mt-1" style="color:rgba(255,255,255,0.3)"><i class="ri-compass-3-line mr-1"></i>${l.region}</p>`:''}${l.facilities?`<p class="text-xs ml-12 mt-1" style="color:rgba(255,255,255,0.3)"><i class="ri-building-line mr-1"></i>${l.facilities}</p>`:''}</div>`).join('')}</div>`;
}).catch(()=>{document.getElementById('locationsGrid').innerHTML='<p style="color:#f87171;padding:1rem;font-size:0.875rem;">Failed to load locations.</p>';});
<?php endif; ?>

<?php if($section==='activity'): ?>
let allActivities=[];
fetch('api/activity_api.php?action=get').then(r=>r.json()).then(data=>{
    allActivities=Array.isArray(data)?data:[];
    const hc=document.getElementById('headerCount');if(hc)hc.textContent=allActivities.length+' Total';
    document.getElementById('statTotal').textContent=allActivities.length;
    document.getElementById('statOngoing').textContent=allActivities.filter(a=>a.status==='Ongoing').length;
    document.getElementById('statUpcoming').textContent=allActivities.filter(a=>a.status==='Upcoming').length;
    document.getElementById('statCompleted').textContent=allActivities.filter(a=>a.status==='Completed').length;
    renderActivities(allActivities);
    const sel=document.getElementById('activityFeedbackSelect');
    if(sel)allActivities.forEach(a=>{const o=document.createElement('option');o.value=a.id;o.textContent=a.name||'Activity #'+a.id;sel.appendChild(o);});
}).catch(()=>{document.getElementById('activitiesContent').innerHTML='<p style="color:#f87171;font-size:0.875rem;">Failed to load activities.</p>';});
function renderActivities(data){
    if(!data.length){document.getElementById('activitiesContent').innerHTML='<div class="text-center py-8"><i class="ri-calendar-line text-4xl" style="color:rgba(255,255,255,0.08)"></i><p class="text-sm mt-3" style="color:rgba(255,255,255,0.35)">No activities found.</p></div>';return;}
    const sc={'Upcoming':'badge-blue','Ongoing':'badge-gold','Completed':'badge-gray','Cancelled':'badge-gray'};
    document.getElementById('activitiesContent').innerHTML=`<div class="overflow-x-auto"><table class="data-table"><thead><tr><th>Activity</th><th>Date / Location</th><th>Status</th><th>Action</th></tr></thead><tbody>${data.map(a=>`<tr><td><div class="flex items-center gap-3"><div class="row-icon" style="background:linear-gradient(135deg,#F5A623,#EA580C)"><i class="ri-calendar-event-line"></i></div><div class="row-name">${a.name||'—'}</div></div></td><td class="row-sub">${a.date_time||'—'} ${a.location?'• '+a.location:''}</td><td><span class="badge ${sc[a.status]||'badge-gray'}">${a.status||'—'}</span></td><td><button class="fund-btn" onclick="openFundActivity(${a.id},'${(a.name||'').replace(/'/g,"\\'")}')"><i class="ri-hand-coin-line"></i> Fund</button></td></tr>`).join('')}</tbody></table><div class="mt-3 text-xs" style="color:rgba(255,255,255,0.3)">Total: <strong style="color:white">${data.length}</strong> activities</div></div>`;
}
document.getElementById('actSearch')?.addEventListener('input',e=>{const q=e.target.value.toLowerCase();renderActivities(allActivities.filter(a=>(a.name||'').toLowerCase().includes(q)||(a.location||'').toLowerCase().includes(q)));});
<?php endif; ?>

<?php if($section==='project'): ?>
let allProjects=[];
fetch('api/project_api.php?action=get').then(r=>r.json()).then(data=>{
    allProjects=Array.isArray(data)?data:[];
    const hc=document.getElementById('headerCount');if(hc)hc.textContent=allProjects.length+' Total';
    document.getElementById('pStatTotal').textContent=allProjects.length;
    document.getElementById('pStatActive').textContent=allProjects.filter(p=>p.status==='Active').length;
    document.getElementById('pStatPlanning').textContent=allProjects.filter(p=>p.status==='Planning').length;
    document.getElementById('pStatCompleted').textContent=allProjects.filter(p=>p.status==='Completed').length;
    renderProjects(allProjects);
    const sel=document.getElementById('projectFeedbackSelect');
    if(sel)allProjects.forEach(p=>{const o=document.createElement('option');o.value=p.id;o.textContent=p.name||'Project #'+p.id;sel.appendChild(o);});
}).catch(()=>{document.getElementById('projectsContent').innerHTML='<p style="color:#f87171;font-size:0.875rem;">Failed to load projects.</p>';});
function renderProjects(data){
    if(!data.length){document.getElementById('projectsContent').innerHTML='<div class="text-center py-8"><i class="ri-folder-2-line text-4xl" style="color:rgba(255,255,255,0.08)"></i><p class="text-sm mt-3" style="color:rgba(255,255,255,0.35)">No projects found.</p></div>';return;}
    const sc={'Active':'badge-green','Planning':'badge-blue','Completed':'badge-gray','On Hold':'badge-gold'};
    document.getElementById('projectsContent').innerHTML=`<div class="overflow-x-auto"><table class="data-table"><thead><tr><th>Project</th><th>Timeline</th><th>Status</th><th>Action</th></tr></thead><tbody>${data.map(p=>`<tr><td><div class="flex items-center gap-3"><div class="row-icon" style="background:linear-gradient(135deg,#7c3aed,#F5A623)"><i class="ri-folder-2-line"></i></div><div class="row-name">${p.name||'—'}</div></div></td><td class="row-sub">${p.start_date||'—'}</td><td><span class="badge ${sc[p.status]||'badge-gray'}">${p.status||'—'}</span></td><td><button class="fund-btn" onclick="openFundProject(${p.id},'${(p.name||'').replace(/'/g,"\\'")}')"><i class="ri-hand-coin-line"></i> Fund</button></td></tr>`).join('')}</tbody></table><div class="mt-3 text-xs" style="color:rgba(255,255,255,0.3)">Total: <strong style="color:white">${data.length}</strong> projects</div></div>`;
}
document.getElementById('projSearch')?.addEventListener('input',e=>{const q=e.target.value.toLowerCase();renderProjects(allProjects.filter(p=>(p.name||'').toLowerCase().includes(q)||(p.status||'').toLowerCase().includes(q)));});
<?php endif; ?>

function showActivityTab(tab){['view','feedback'].forEach(t=>{document.getElementById('activity-'+t).classList.add('hidden');document.getElementById('tab-'+t).classList.remove('active');});document.getElementById('activity-'+tab).classList.remove('hidden');document.getElementById('tab-'+tab).classList.add('active');}
function showProjectTab(tab){['view','feedback'].forEach(t=>{document.getElementById('project-'+t).classList.add('hidden');document.getElementById('ptab-'+t).classList.remove('active');});document.getElementById('project-'+tab).classList.remove('hidden');document.getElementById('ptab-'+tab).classList.add('active');}
</script>
</body>
</html>