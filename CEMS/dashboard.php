<?php
session_start();

if(!isset($_SESSION['user_id'])){ header("Location: login.php"); exit(); }
if(isset($_GET['section']) && $_GET['section'] === 'logout'){ session_destroy(); header("Location: login.php"); exit(); }

$allowed_roles = ['admin','Admin','Developer','Director'];
if(!in_array($_SESSION['role'], $allowed_roles)){
    session_destroy(); session_start();
    $_SESSION['login_error'] = 'Access denied. Only administrators can access this page.';
    header("Location: login.php"); exit();
}

$allowed_sections = ['home','beneficiary','program','project','activity','partner','resource','location','staff','evaluation','reports','participants','feedback_log'];
$section = isset($_GET['section']) && in_array($_GET['section'], $allowed_sections) ? $_GET['section'] : 'home';
$sectionFile = "sections/{$section}.php";
$isDevDir = in_array($_SESSION['role'], ['Developer','Director']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
<title>CEMS Dashboard</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
<style>
:root {
    --accent-blue:#0A84FF; --accent-purple:#BF5AF2; --accent-green:#32D74B;
    --accent-yellow:#FFD60A; --accent-red:#FF453A; --accent-orange:#FF9F0A;
    --sidebar-w: 260px;
}
*,*::before,*::after{box-sizing:border-box;}
body {
    min-height:100vh;
    background:linear-gradient(145deg,#000 0%,#1a1a1a 25%,#2d2d2d 50%,#1a1a1a 75%,#000 100%);
    font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;
    overflow-x:hidden; margin:0;
}
.light-effect{position:fixed;width:100%;height:100%;background:radial-gradient(circle at 20% 30%,rgba(255,255,255,0.12) 0%,transparent 40%),radial-gradient(circle at 80% 70%,rgba(255,255,255,0.08) 0%,transparent 40%),radial-gradient(circle at 90% 20%,rgba(10,132,255,0.05) 0%,transparent 50%);pointer-events:none;z-index:0;}
.mac-glass-deep{background:rgba(255,255,255,0.08);backdrop-filter:blur(40px) saturate(200%);-webkit-backdrop-filter:blur(40px) saturate(200%);border:1px solid rgba(255,255,255,0.15);border-radius:12px;box-shadow:0 20px 40px -10px rgba(0,0,0,0.3);}
.mac-sidebar{background:rgba(30,30,30,0.85);backdrop-filter:blur(50px) saturate(200%);-webkit-backdrop-filter:blur(50px) saturate(200%);border-right:1px solid rgba(255,255,255,0.1);box-shadow:10px 0 30px -10px rgba(0,0,0,0.5);}
.mac-dock-card{background:rgba(40,40,40,0.6);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,0.1);border-radius:12px;box-shadow:0 4px 12px rgba(0,0,0,0.2);transition:all 0.2s cubic-bezier(0.23,1,0.32,1);}
.mac-dock-card:hover{transform:translateY(-2px) scale(1.02);background:rgba(50,50,50,0.7);border-color:rgba(255,255,255,0.2);}
.mac-button{background:rgba(255,255,255,0.1);backdrop-filter:blur(10px);border:1px solid rgba(255,255,255,0.15);border-radius:8px;padding:8px 16px;color:white;font-weight:500;transition:all 0.2s ease;cursor:pointer;}
.mac-button:hover{background:rgba(255,255,255,0.15);border-color:rgba(255,255,255,0.25);}
.mac-menu-bar{background:rgba(30,30,30,0.8);backdrop-filter:blur(50px);-webkit-backdrop-filter:blur(50px);border-bottom:1px solid rgba(255,255,255,0.1);}
.mac-active{background:rgba(10,132,255,0.2);border:1px solid rgba(10,132,255,0.3);box-shadow:0 0 15px rgba(10,132,255,0.2);}
.mac-title{font-size:clamp(18px,2.5vw,24px);font-weight:600;letter-spacing:-0.5px;background:linear-gradient(135deg,#fff,#aaa);-webkit-background-clip:text;-webkit-text-fill-color:transparent;}
@keyframes macFadeIn{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}
.mac-fade-in{animation:macFadeIn 0.3s ease-out;}
.nav-section-label{font-size:0.6rem;font-weight:600;letter-spacing:0.1em;text-transform:uppercase;color:rgba(255,255,255,0.22);padding:0.7rem 1rem 0.15rem;display:block;}
.nav-divider{height:1px;background:rgba(255,255,255,0.07);margin:0.4rem 0.75rem;}
::-webkit-scrollbar{width:6px;height:6px;}
::-webkit-scrollbar-track{background:rgba(255,255,255,0.03);}
::-webkit-scrollbar-thumb{background:rgba(255,255,255,0.15);border-radius:20px;}

/* ── Sidebar ── */
.sidebar-fixed{position:fixed;top:0;left:0;height:100vh;width:var(--sidebar-w);z-index:40;display:flex;flex-direction:column;overflow-y:auto;}
.main-content{margin-left:var(--sidebar-w);min-height:100vh;}

/* ── Notification Bell ── */
.notif-bell-btn{position:relative;width:38px;height:38px;border-radius:10px;background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.12);display:flex;align-items:center;justify-content:center;cursor:pointer;transition:all 0.2s;flex-shrink:0;}
.notif-bell-btn:hover{background:rgba(255,255,255,0.14);border-color:rgba(255,255,255,0.2);}
.notif-badge{position:absolute;top:-4px;right:-4px;min-width:18px;height:18px;background:#FF453A;border-radius:50%;font-size:0.6rem;font-weight:800;color:white;display:flex;align-items:center;justify-content:center;border:2px solid #1a1a1a;padding:0 3px;display:none;}
.notif-badge.show{display:flex;}
.notif-dropdown{position:absolute;top:calc(100% + 8px);right:0;width:340px;max-width:calc(100vw - 2rem);background:rgba(28,28,35,0.97);backdrop-filter:blur(30px);-webkit-backdrop-filter:blur(30px);border:1px solid rgba(255,255,255,0.12);border-radius:16px;box-shadow:0 20px 60px rgba(0,0,0,0.5);z-index:100;display:none;overflow:hidden;}
.notif-dropdown.open{display:block;animation:macFadeIn 0.2s ease;}
.notif-header{padding:0.875rem 1rem;border-bottom:1px solid rgba(255,255,255,0.07);display:flex;align-items:center;justify-content:space-between;}
.notif-header-title{font-size:0.875rem;font-weight:700;color:white;}
.notif-mark-btn{font-size:0.7rem;color:#0A84FF;cursor:pointer;background:none;border:none;font-family:inherit;font-weight:600;padding:0;}
.notif-mark-btn:hover{text-decoration:underline;}
.notif-list{max-height:380px;overflow-y:auto;}
.notif-item{padding:0.75rem 1rem;border-bottom:1px solid rgba(255,255,255,0.05);cursor:pointer;transition:background 0.15s;display:flex;align-items:flex-start;gap:10px;}
.notif-item:hover{background:rgba(255,255,255,0.04);}
.notif-item.unread{background:rgba(10,132,255,0.05);}
.notif-dot{width:8px;height:8px;border-radius:50%;flex-shrink:0;margin-top:5px;}
.notif-icon-wrap{width:34px;height:34px;border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0;}
.notif-title{font-size:0.8rem;font-weight:700;color:white;line-height:1.3;}
.notif-msg{font-size:0.72rem;color:rgba(255,255,255,0.45);margin-top:2px;line-height:1.4;}
.notif-time{font-size:0.65rem;color:rgba(255,255,255,0.3);margin-top:3px;}
.notif-empty{padding:2rem 1rem;text-align:center;color:rgba(255,255,255,0.3);font-size:0.82rem;}

/* ── Mobile nav ── */
@media(max-width:767px){
    .sidebar-fixed{display:none;}
    .main-content{margin-left:0;}
    .mobile-topbar{display:flex!important;}
    .desktop-topbar{display:none!important;}
}
@media(min-width:768px){
    .mobile-topbar{display:none!important;}
    .desktop-topbar{display:flex!important;}
    .mobile-sidebar-overlay{display:none!important;}
}
.mobile-topbar{position:fixed;top:0;left:0;right:0;z-index:50;padding:0.6rem 1rem;display:none;align-items:center;justify-content:space-between;}
.hamburger{width:36px;height:36px;border-radius:8px;background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.12);display:flex;align-items:center;justify-content:center;cursor:pointer;flex-shrink:0;}
.mobile-sidebar-overlay{position:fixed;inset:0;z-index:45;display:none;}
.mobile-sidebar-overlay.open{display:block;}
.mobile-sidebar-panel{position:fixed;top:0;left:0;bottom:0;width:280px;z-index:46;transform:translateX(-100%);transition:transform 0.3s cubic-bezier(0.23,1,0.32,1);}
.mobile-sidebar-panel.open{transform:translateX(0);}
.mobile-sidebar-bg{position:absolute;inset:0;background:rgba(0,0,0,0.6);backdrop-filter:blur(4px);}

/* Responsive content */
@media(max-width:767px){
    .main-content{padding-top:58px!important;}
    .content-pad{padding:0.75rem!important;}
}
@media(min-width:768px) and (max-width:1023px){
    :root{--sidebar-w:220px;}
    .content-pad{padding:1.25rem!important;}
}
@media(min-width:1024px){
    .content-pad{padding:1.5rem 2rem!important;}
}
</style>
</head>
<body class="font-sans antialiased">
<div class="light-effect"></div>
<div class="fixed top-40 left-20 w-96 h-96 rounded-full bg-gradient-to-br from-blue-500/5 to-purple-500/5 blur-3xl animate-pulse pointer-events-none"></div>
<div class="fixed bottom-40 right-20 w-80 h-80 rounded-full bg-gradient-to-br from-orange-500/5 to-red-500/5 blur-3xl animate-pulse delay-1000 pointer-events-none"></div>

<div class="relative z-10 flex min-h-screen">

<!-- ══ DESKTOP SIDEBAR ══ -->
<aside class="mac-sidebar sidebar-fixed p-4">
    <div class="flex items-center gap-3 mb-6 px-1">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-purple-500 p-[2px] shadow-xl flex-shrink-0">
            <div class="w-full h-full rounded-xl bg-black/30 backdrop-blur-xl flex items-center justify-center">
                <img src="img/Community Logo.png" class="w-6 h-6 rounded" alt="Logo">
            </div>
        </div>
        <div>
            <h1 class="text-xl font-bold text-white tracking-tight">CEMS</h1>
            <p class="text-xs text-white/40">Community Extension</p>
        </div>
    </div>
    <div class="mac-dock-card p-3 mb-5">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-purple-500 flex items-center justify-center text-white text-lg font-bold flex-shrink-0">
                <?php echo strtoupper(substr($_SESSION['fullname']??'U',0,1)); ?>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-white font-medium text-sm truncate"><?php echo htmlspecialchars($_SESSION['fullname']??'User'); ?></p>
                <p class="text-white/40 text-xs flex items-center gap-1">
                    <span class="w-1.5 h-1.5 bg-green-400 rounded-full animate-pulse"></span>
                    <?php echo htmlspecialchars($_SESSION['role']??'Staff'); ?>
                </p>
            </div>
        </div>
    </div>
    <nav class="flex-1">
        <?php
        $navGroups = [
            'Main' => [
                ['home','Dashboard','ri-dashboard-line'],
                ['beneficiary','Beneficiary','ri-user-3-line'],
                ['program','Program','ri-archive-line'],
                ['project','Project','ri-folder-2-line'],
                ['activity','Activity','ri-calendar-line'],
            ],
            'Management' => [
                ['partner','Partners','ri-hand-heart-line'],
                ['resource','Resources','ri-stack-line'],
                ['location','Locations','ri-map-pin-line'],
            ],
            'Analytics' => [
                ['staff','Staff','ri-team-line'],
                ['evaluation','Evaluation','ri-survey-line'],
                ['reports','Reports','ri-bar-chart-2-line'],
            ],
        ];
        foreach($navGroups as $label=>$items):
        ?>
        <span class="nav-section-label"><?php echo $label; ?></span>
        <?php foreach($items as $item): $isA=($section===$item[0]); ?>
        <a href="dashboard.php?section=<?php echo $item[0]; ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all <?php echo $isA?'mac-active':'hover:bg-white/5'; ?> group">
            <i class="<?php echo $item[2]; ?> text-lg <?php echo $isA?'text-blue-400':'text-white/60 group-hover:text-white/80'; ?>"></i>
            <span class="text-sm <?php echo $isA?'text-white font-medium':'text-white/70 group-hover:text-white/90'; ?>"><?php echo $item[1]; ?></span>
            <?php if($isA): ?><span class="ml-auto w-1.5 h-1.5 bg-blue-400 rounded-full animate-pulse"></span><?php endif; ?>
        </a>
        <?php endforeach; ?>
        <div class="nav-divider"></div>
        <?php endforeach; ?>

        <?php if($isDevDir): ?>
        <span class="nav-section-label">Dev Tools</span>
        <?php foreach([['participants','Join Logs','ri-user-add-line'],['feedback_log','Feedback Log','ri-feedback-line']] as $item): $isA=($section===$item[0]); ?>
        <a href="dashboard.php?section=<?php echo $item[0]; ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all <?php echo $isA?'mac-active':'hover:bg-white/5'; ?> group">
            <i class="<?php echo $item[2]; ?> text-lg <?php echo $isA?'text-blue-400':'text-white/60 group-hover:text-white/80'; ?>"></i>
            <span class="text-sm <?php echo $isA?'text-white font-medium':'text-white/70 group-hover:text-white/90'; ?>"><?php echo $item[1]; ?></span>
            <?php if($isA): ?><span class="ml-auto w-1.5 h-1.5 bg-blue-400 rounded-full animate-pulse"></span>
            <?php else: ?><span class="ml-auto text-[0.5rem] bg-blue-500/20 border border-blue-500/30 rounded px-1.5 py-0.5 text-blue-400 font-bold">DEV</span><?php endif; ?>
        </a>
        <?php endforeach; ?>
        <div class="nav-divider"></div>
        <?php endif; ?>
    </nav>
    <a href="dashboard.php?section=logout" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-white/5 transition-all group mt-1">
        <i class="ri-logout-box-line text-lg text-white/60 group-hover:text-white/80"></i>
        <span class="text-sm text-white/70 group-hover:text-white/90">Logout</span>
    </a>
    <div class="mt-3 pt-3 border-t border-white/10 text-center">
        <p class="text-xs text-white/30">CEMS v2.0 &nbsp;•&nbsp; macOS Liquid Glass</p>
    </div>
</aside>

<!-- ══ MOBILE TOPBAR ══ -->
<div class="mobile-topbar mac-menu-bar">
    <div class="flex items-center gap-2">
        <button class="hamburger" id="mobileHamburger">
            <i class="ri-menu-line text-white text-lg"></i>
        </button>
        <div class="flex items-center gap-2">
            <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-blue-500 to-purple-500 flex items-center justify-center">
                <img src="img/Community Logo.png" class="w-4 h-4 rounded" alt="">
            </div>
            <span class="text-white font-bold text-sm">CEMS</span>
        </div>
    </div>
    <div class="flex items-center gap-2">
        <span class="text-white/40 text-xs" id="mobileTime"></span>
        <!-- Mobile notification bell -->
        <div style="position:relative;">
            <div class="notif-bell-btn" id="mobileNotifBtn" onclick="toggleNotif('mobile')">
                <i class="ri-notification-3-line text-white/70 text-lg"></i>
                <span class="notif-badge" id="mobileNotifBadge"></span>
            </div>
            <div class="notif-dropdown" id="mobileNotifDropdown"></div>
        </div>
    </div>
</div>

<!-- ══ MOBILE SIDEBAR OVERLAY ══ -->
<div class="mobile-sidebar-overlay" id="mobileSidebarOverlay" onclick="closeMobileSidebar()">
    <div class="mobile-sidebar-bg"></div>
</div>
<div class="mac-sidebar mobile-sidebar-panel" id="mobileSidebarPanel">
    <div class="p-4 h-full overflow-y-auto flex flex-col">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-purple-500 flex items-center justify-center">
                    <img src="img/Community Logo.png" class="w-5 h-5 rounded" alt="">
                </div>
                <span class="text-white font-bold">CEMS</span>
            </div>
            <button onclick="closeMobileSidebar()" class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center">
                <i class="ri-close-line text-white/60"></i>
            </button>
        </div>
        <div class="mac-dock-card p-3 mb-4">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-500 to-purple-500 flex items-center justify-center text-white font-bold"><?php echo strtoupper(substr($_SESSION['fullname']??'U',0,1)); ?></div>
                <div><p class="text-white font-medium text-sm"><?php echo htmlspecialchars($_SESSION['fullname']??'User'); ?></p><p class="text-white/40 text-xs"><?php echo htmlspecialchars($_SESSION['role']??''); ?></p></div>
            </div>
        </div>
        <nav class="flex-1">
            <?php foreach($navGroups as $label=>$items): ?>
            <span class="nav-section-label"><?php echo $label; ?></span>
            <?php foreach($items as $item): $isA=($section===$item[0]); ?>
            <a href="dashboard.php?section=<?php echo $item[0]; ?>" onclick="closeMobileSidebar()" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all <?php echo $isA?'mac-active':'hover:bg-white/5'; ?>">
                <i class="<?php echo $item[2]; ?> text-lg <?php echo $isA?'text-blue-400':'text-white/60'; ?>"></i>
                <span class="text-sm <?php echo $isA?'text-white font-medium':'text-white/70'; ?>"><?php echo $item[1]; ?></span>
            </a>
            <?php endforeach; ?>
            <div class="nav-divider"></div>
            <?php endforeach; ?>
            <?php if($isDevDir): ?>
            <span class="nav-section-label">Dev Tools</span>
            <?php foreach([['participants','Join Logs','ri-user-add-line'],['feedback_log','Feedback Log','ri-feedback-line']] as $item): $isA=($section===$item[0]); ?>
            <a href="dashboard.php?section=<?php echo $item[0]; ?>" onclick="closeMobileSidebar()" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all <?php echo $isA?'mac-active':'hover:bg-white/5'; ?>">
                <i class="<?php echo $item[2]; ?> text-lg <?php echo $isA?'text-blue-400':'text-white/60'; ?>"></i>
                <span class="text-sm <?php echo $isA?'text-white font-medium':'text-white/70'; ?>"><?php echo $item[1]; ?></span>
            </a>
            <?php endforeach; ?>
            <div class="nav-divider"></div>
            <?php endif; ?>
        </nav>
        <a href="dashboard.php?section=logout" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-white/5 mt-2">
            <i class="ri-logout-box-line text-white/60"></i>
            <span class="text-sm text-white/70">Logout</span>
        </a>
    </div>
</div>

<!-- ══ MAIN CONTENT ══ -->
<main class="main-content flex-1 flex flex-col">

    <!-- Desktop Topbar -->
    <div class="desktop-topbar mac-menu-bar sticky top-0 z-30 px-6 py-3 items-center justify-between">
        <div class="flex items-center gap-4">
            <span class="text-white/60 text-sm"><?php echo date('l, F j, Y'); ?></span>
            <span class="text-white/20">|</span>
            <span class="text-white/60 text-sm" id="currentTime"></span>
        </div>
        <div class="flex items-center gap-3">
            <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
            <span class="text-white/40 text-xs">System Online</span>
            <!-- Desktop notification bell -->
            <div style="position:relative;">
                <div class="notif-bell-btn" id="desktopNotifBtn" onclick="toggleNotif('desktop')">
                    <i class="ri-notification-3-line text-white/70 text-lg"></i>
                    <span class="notif-badge" id="desktopNotifBadge"></span>
                </div>
                <div class="notif-dropdown" id="desktopNotifDropdown"></div>
            </div>
        </div>
    </div>

    <!-- Content area -->
    <div class="flex-1 content-pad" style="padding:1.5rem 2rem;">
        <div class="flex items-center justify-between mb-5 mac-fade-in flex-wrap gap-3">
            <div>
                <h1 class="mac-title"><?php echo ucfirst($section); ?> Management</h1>
                <p class="text-white/40 text-sm mt-1"><i class="ri-information-line mr-1"></i>Manage and monitor your <?php echo $section; ?> data</p>
            </div>
            <button onclick="window.location.reload()" class="mac-button flex items-center gap-2 text-sm">
                <i class="ri-refresh-line"></i><span>Refresh</span>
            </button>
        </div>

        <div class="mac-glass-deep p-4 md:p-6 mac-fade-in">
            <?php
            if(file_exists($sectionFile)) include($sectionFile);
            else include("sections/home.php");
            ?>
        </div>

        <div class="mt-5 flex items-center justify-between text-white/20 text-xs flex-wrap gap-2">
            <span>&copy; <?php echo date('Y'); ?> CEMS. All rights reserved.</span>
            <span>macOS Liquid Glass Edition</span>
        </div>
    </div>
</main>
</div>

<script>
// ── Time ──
function updateTime(){const t=new Date().toLocaleTimeString('en-US',{hour:'2-digit',minute:'2-digit'});['currentTime','mobileTime'].forEach(id=>{const el=document.getElementById(id);if(el)el.textContent=t;});}
updateTime();setInterval(updateTime,1000);

// ── Mobile Sidebar ──
function openMobileSidebar(){document.getElementById('mobileSidebarOverlay').classList.add('open');document.getElementById('mobileSidebarPanel').classList.add('open');document.body.style.overflow='hidden';}
function closeMobileSidebar(){document.getElementById('mobileSidebarOverlay').classList.remove('open');document.getElementById('mobileSidebarPanel').classList.remove('open');document.body.style.overflow='';}
document.getElementById('mobileHamburger')?.addEventListener('click',openMobileSidebar);
document.addEventListener('keydown',e=>{if(e.key==='Escape'){closeMobileSidebar();closeAllNotif();}});

// ── Page transitions ──
document.querySelectorAll('a[href*="dashboard.php"]').forEach(link=>{
    link.addEventListener('click',e=>{
        if(!link.href.includes('logout')){
            e.preventDefault();
            const main=document.querySelector('main');
            main.style.transition='opacity 0.2s,transform 0.2s';
            main.style.opacity='0';main.style.transform='translateY(8px)';
            setTimeout(()=>{window.location.href=link.href;},200);
        }
    });
});
window.addEventListener('load',()=>{
    const main=document.querySelector('main');
    main.style.transition='all 0.3s ease';main.style.opacity='1';main.style.transform='translateY(0)';
});

// ══════════════════════════════════════════
// ── NOTIFICATION SYSTEM ──
// ══════════════════════════════════════════
let notifData = [];
let notifLoaded = false;

function getNotifIcon(type){
    const map={feedback:{icon:'ri-feedback-line',bg:'rgba(10,132,255,0.15)',color:'#60a5fa'},join:{icon:'ri-user-add-line',bg:'rgba(50,215,75,0.15)',color:'#4ade80'},user:{icon:'ri-user-3-line',bg:'rgba(191,90,242,0.15)',color:'#c084fc'},activity:{icon:'ri-calendar-event-line',bg:'rgba(255,159,10,0.15)',color:'#fbbf24'},info:{icon:'ri-information-line',bg:'rgba(255,255,255,0.08)',color:'rgba(255,255,255,0.5)'}};
    return map[type]||map.info;
}

function renderNotifDropdown(containerId){
    const container=document.getElementById(containerId);
    if(!container)return;
    const unread=notifData.filter(n=>!n.read).length;
    let html=`<div class="notif-header"><span class="notif-header-title"><i class="ri-notification-3-line mr-1"></i>Notifications${unread>0?` <span style="background:#FF453A;color:white;border-radius:50px;padding:1px 7px;font-size:0.65rem;font-weight:800;margin-left:6px;">${unread}</span>`:''}</span><button class="notif-mark-btn" onclick="markAllRead()">Mark all read</button></div>`;
    html+=`<div class="notif-list">`;
    if(notifData.length===0){html+=`<div class="notif-empty"><i class="ri-notification-off-line" style="font-size:2rem;display:block;margin-bottom:0.5rem;opacity:0.4;"></i>No notifications</div>`;}
    else{
        notifData.forEach(n=>{
            const ic=getNotifIcon(n.type);
            html+=`<div class="notif-item ${n.read?'':'unread'}" onclick="notifClick('${n.link||''}')">
                <div class="notif-icon-wrap" style="background:${ic.bg}"><i class="${ic.icon}" style="color:${ic.color};"></i></div>
                <div style="flex:1;min-width:0;">
                    <div class="notif-title">${n.title}</div>
                    ${n.message?`<div class="notif-msg">${n.message}</div>`:''}
                    ${n.time?`<div class="notif-time">${n.time}</div>`:''}
                </div>
                ${!n.read?`<div class="notif-dot" style="background:#0A84FF;"></div>`:''}
            </div>`;
        });
    }
    html+=`</div>`;
    container.innerHTML=html;
}

function loadNotifications(){
    fetch('api/notifications_api.php?action=get')
        .then(r=>r.json())
        .then(d=>{
            if(d.status==='success'){
                notifData=d.notifications||[];
                const unread=d.unread||0;
                ['desktopNotifBadge','mobileNotifBadge'].forEach(id=>{
                    const el=document.getElementById(id);
                    if(!el)return;
                    el.textContent=unread>9?'9+':unread;
                    el.classList.toggle('show',unread>0);
                });
                renderNotifDropdown('desktopNotifDropdown');
                renderNotifDropdown('mobileNotifDropdown');
            }
        }).catch(()=>{});
}

function toggleNotif(type){
    const ddId = type==='desktop'?'desktopNotifDropdown':'mobileNotifDropdown';
    const dd=document.getElementById(ddId);
    const isOpen=dd.classList.contains('open');
    closeAllNotif();
    if(!isOpen){dd.classList.add('open');if(!notifLoaded){loadNotifications();notifLoaded=true;}}
}

function closeAllNotif(){
    document.querySelectorAll('.notif-dropdown').forEach(d=>d.classList.remove('open'));
}

function markAllRead(){
    notifData=notifData.map(n=>({...n,read:true}));
    ['desktopNotifBadge','mobileNotifBadge'].forEach(id=>{const el=document.getElementById(id);if(el){el.classList.remove('show');}});
    renderNotifDropdown('desktopNotifDropdown');
    renderNotifDropdown('mobileNotifDropdown');
    fetch('api/notifications_api.php?action=mark_read&method=POST',{method:'POST'});
}

function notifClick(link){
    closeAllNotif();
    if(link&&link!==''){window.location.href=link;}
}

// Close dropdown on outside click
document.addEventListener('click',e=>{
    if(!e.target.closest('.notif-bell-btn')&&!e.target.closest('.notif-dropdown')){closeAllNotif();}
});

// Auto-load notifications on page load
loadNotifications();
// Refresh every 60 seconds
setInterval(()=>{loadNotifications();},60000);
</script>
</body>
</html>