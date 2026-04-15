<?php
session_start();

// Redirect to login if not logged in
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

// Only Dept. Coordinator can access this dashboard
$allowed_roles = ['Dept. Coordinator'];
if(!in_array($_SESSION['role'], $allowed_roles)){
    // Admins go to admin dashboard
    $admin_roles = ['admin','Admin','Developer','Director'];
    if(in_array($_SESSION['role'], $admin_roles)){
        header("Location: dashboard.php");
    } else {
        session_destroy();
        session_start();
        $_SESSION['login_error'] = 'Access denied.';
        header("Location: login.php");
    }
    exit();
}

// Handle logout
if(isset($_GET['section']) && $_GET['section'] === 'logout'){
    session_destroy();
    header("Location: login.php");
    exit();
}

// Allowed sections for Dept. Coordinator (based on use case diagram)
$allowed_sections = ['home','beneficiary','program','activity','partner','resource','location','staff','evaluation','reports'];
$section = isset($_GET['section']) && in_array($_GET['section'], $allowed_sections) ? $_GET['section'] : 'home';
$sectionFile = "sections/{$section}.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CEMS - Coordinator Dashboard</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
<style>
    :root {
        --accent: #0A84FF;
    }

    body {
        min-height: 100vh;
        background: linear-gradient(145deg,
            #000000 0%, #1a1a1a 25%, #2d2d2d 50%, #1a1a1a 75%, #000000 100%);
        position: relative;
        overflow-x: hidden;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
    }

    .light-effect {
        position: fixed; width: 100%; height: 100%;
        background:
            radial-gradient(circle at 20% 30%, rgba(255,255,255,0.15) 0%, transparent 40%),
            radial-gradient(circle at 80% 70%, rgba(255,255,255,0.1) 0%, transparent 40%),
            radial-gradient(circle at 40% 80%, rgba(16,185,129,0.05) 0%, transparent 50%),
            radial-gradient(circle at 90% 20%, rgba(10,132,255,0.05) 0%, transparent 50%);
        pointer-events: none; z-index: 0;
    }

    .mac-glass-deep {
        background: rgba(255,255,255,0.08);
        backdrop-filter: blur(40px) saturate(200%);
        -webkit-backdrop-filter: blur(40px) saturate(200%);
        border: 1px solid rgba(255,255,255,0.15); border-radius: 12px;
        box-shadow: 0 20px 40px -10px rgba(0,0,0,0.3), inset 0 1px 3px rgba(255,255,255,0.2);
    }
    .mac-sidebar {
        background: rgba(18,18,28,0.8);
        backdrop-filter: blur(50px) saturate(200%);
        -webkit-backdrop-filter: blur(50px) saturate(200%);
        border-right: 1px solid rgba(255,255,255,0.08);
        box-shadow: 10px 0 30px -10px rgba(0,0,0,0.5);
    }
    .mac-dock-card {
        background: rgba(40,40,40,0.6);
        backdrop-filter: blur(20px) saturate(180%);
        -webkit-backdrop-filter: blur(20px) saturate(180%);
        border: 1px solid rgba(255,255,255,0.1); border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        transition: all 0.2s cubic-bezier(0.23,1,0.32,1);
    }
    .mac-menu-bar {
        background: rgba(18,18,28,0.85);
        backdrop-filter: blur(50px);
        border-bottom: 1px solid rgba(255,255,255,0.08);
    }
    .mac-button {
        background: rgba(255,255,255,0.08);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.12);
        border-radius: 8px; padding: 8px 16px;
        color: white; font-weight: 500; transition: all 0.2s ease;
        cursor: pointer;
    }
    .mac-button:hover {
        background: rgba(255,255,255,0.14);
        transform: scale(1.02);
    }
    .mac-active {
        background: rgba(16,185,129,0.18);
        border: 1px solid rgba(16,185,129,0.3);
        box-shadow: 0 0 15px rgba(16,185,129,0.15);
    }
    .mac-title {
        font-size: 24px; font-weight: 600; letter-spacing: -0.5px;
        background: linear-gradient(135deg, #fff, #aaa);
        -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    }
    @keyframes macFadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .mac-fade-in { animation: macFadeIn 0.3s ease-out; }

    .nav-section-label {
        font-size: 0.6rem;
        font-weight: 600;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: rgba(255,255,255,0.22);
        padding: 0.7rem 1rem 0.15rem;
        display: block;
    }
    .nav-divider {
        height: 1px;
        background: rgba(255,255,255,0.07);
        margin: 0.4rem 0.75rem;
    }

    /* Coordinator accent = emerald green instead of blue */
    .coord-active {
        background: rgba(16,185,129,0.18) !important;
        border: 1px solid rgba(16,185,129,0.3) !important;
        box-shadow: 0 0 15px rgba(16,185,129,0.15) !important;
    }
    .coord-dot { background: #10b981 !important; box-shadow: 0 0 6px #10b98166 !important; }

    ::-webkit-scrollbar { width: 6px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 20px; }
</style>
</head>

<body class="font-sans antialiased">
    <div class="light-effect"></div>

    <!-- Ambient orbs (emerald tint for coordinator) -->
    <div class="fixed top-40 left-20 w-96 h-96 rounded-full bg-gradient-to-br from-emerald-500/5 to-teal-500/5 blur-3xl animate-pulse"></div>
    <div class="fixed bottom-40 right-20 w-80 h-80 rounded-full bg-gradient-to-br from-blue-500/5 to-cyan-500/5 blur-3xl animate-pulse delay-1000"></div>
    <div class="fixed top-60 right-60 w-72 h-72 rounded-full bg-gradient-to-br from-green-500/5 to-blue-500/5 blur-3xl animate-pulse delay-2000"></div>

    <div class="relative z-10 flex min-h-screen">

        <!-- SIDEBAR -->
        <aside class="mac-sidebar w-72 hidden md:flex flex-col fixed h-screen p-4 overflow-y-auto">

            <!-- Traffic Lights -->
            <div class="flex gap-1.5 mb-6 px-1">
                <span class="w-3 h-3 rounded-full bg-[#FF5F57] cursor-pointer hover:brightness-110 shadow-[0_0_6px_#FF5F5766]"></span>
                <span class="w-3 h-3 rounded-full bg-[#FEBC2E] cursor-pointer hover:brightness-110 shadow-[0_0_6px_#FEBC2E66]"></span>
                <span class="w-3 h-3 rounded-full bg-[#28C840] cursor-pointer hover:brightness-110 shadow-[0_0_6px_#28C84066]"></span>
            </div>

            <!-- Logo -->
            <div class="flex items-center gap-3 mb-8 px-2">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-500 p-[2px] shadow-xl">
                    <div class="w-full h-full rounded-xl bg-black/30 backdrop-blur-xl flex items-center justify-center">
                        <img src="img/Community Logo.png" class="w-8 h-8 rounded-lg" alt="Logo">
                    </div>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-white tracking-tight">CEMS</h1>
                    <p class="text-xs text-emerald-400/70">Coordinator Portal</p>
                </div>
            </div>

            <!-- User Profile -->
            <div class="mac-dock-card p-4 mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center text-white text-xl font-bold">
                        <?php echo strtoupper(substr($_SESSION['fullname'] ?? 'U', 0, 1)); ?>
                    </div>
                    <div class="flex-1">
                        <p class="text-white font-medium text-sm"><?php echo htmlspecialchars($_SESSION['fullname'] ?? 'User'); ?></p>
                        <p class="text-white/40 text-xs flex items-center gap-1">
                            <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse"></span>
                            <?php echo htmlspecialchars($_SESSION['role'] ?? 'Coordinator'); ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1">
                <?php
                $navGroups = [
                    'Overview' => [
                        ['home',        'Dashboard',           'ri-dashboard-line'],
                    ],
                    'Programs & Activities' => [
                        ['beneficiary', 'Beneficiary Mgmt',    'ri-user-3-line'],
                        ['program',     'Program Management',  'ri-archive-line'],
                        ['activity',    'Activity Management', 'ri-calendar-line'],
                    ],
                    'Resources & Partners' => [
                        ['partner',     'Partner & Donor',     'ri-hand-heart-line'],
                        ['resource',    'Resource Management', 'ri-stack-line'],
                        ['location',    'Location Management', 'ri-map-pin-line'],
                    ],
                    'People & Reports' => [
                        ['staff',       'Coordinator Mgmt',    'ri-team-line'],
                        ['evaluation',  'Evaluation & Monitoring','ri-survey-line'],
                        ['reports',     'Reports & Analytics', 'ri-bar-chart-2-line'],
                    ],
                ];

                foreach ($navGroups as $label => $items):
                ?>
                <span class="nav-section-label"><?php echo $label; ?></span>
                <?php foreach ($items as $item):
                    $isActive = ($section === $item[0]);
                ?>
                <a href="coordinator.php?section=<?php echo $item[0]; ?>"
                   class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all <?php echo $isActive ? 'coord-active' : 'hover:bg-white/5'; ?> group">
                    <i class="<?php echo $item[2]; ?> text-xl <?php echo $isActive ? 'text-emerald-400' : 'text-white/60 group-hover:text-white/80'; ?>"></i>
                    <span class="text-sm <?php echo $isActive ? 'text-white font-medium' : 'text-white/70 group-hover:text-white/90'; ?>"><?php echo $item[1]; ?></span>
                    <?php if($isActive): ?><span class="ml-auto w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse coord-dot"></span><?php endif; ?>
                </a>
                <?php endforeach; ?>
                <div class="nav-divider"></div>
                <?php endforeach; ?>
            </nav>

            <!-- Logout -->
            <a href="coordinator.php?section=logout"
               class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-white/5 transition-all group mt-2">
                <i class="ri-logout-box-line text-xl text-white/60 group-hover:text-red-400"></i>
                <span class="text-sm text-white/70 group-hover:text-red-400">Logout</span>
            </a>

            <div class="mt-4 pt-4 border-t border-white/10 text-center">
                <p class="text-xs text-white/30">CEMS v2.0</p>
                <p class="text-xs text-emerald-400/30">Coordinator Edition</p>
            </div>
        </aside>

        <!-- Mobile Header -->
        <div class="md:hidden fixed top-0 left-0 right-0 z-50 mac-menu-bar px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-emerald-500 to-teal-500 p-[1px]">
                    <div class="w-full h-full rounded-lg bg-black/30 backdrop-blur-xl flex items-center justify-center">
                        <img src="img/Community Logo.png" class="w-5 h-5 rounded" alt="Logo">
                    </div>
                </div>
                <span class="text-white font-medium text-sm">CEMS Coordinator</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs text-white/50" id="mobileTime"></span>
                <button id="mobileMenuBtn" class="w-8 h-8 rounded-lg bg-white/5 hover:bg-white/10 flex items-center justify-center">
                    <i class="ri-menu-line text-white"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Sidebar -->
        <div id="mobileSidebar" class="md:hidden fixed inset-0 z-50 hidden">
            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" id="mobileOverlay"></div>
            <div class="absolute left-0 top-0 bottom-0 w-72 mac-sidebar p-4 overflow-y-auto">
                <div class="flex gap-1.5 mb-6 px-1">
                    <span class="w-3 h-3 rounded-full bg-[#FF5F57]"></span>
                    <span class="w-3 h-3 rounded-full bg-[#FEBC2E]"></span>
                    <span class="w-3 h-3 rounded-full bg-[#28C840]"></span>
                </div>
                <div class="mac-dock-card p-4 mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center text-white text-xl font-bold">
                            <?php echo strtoupper(substr($_SESSION['fullname'] ?? 'U', 0, 1)); ?>
                        </div>
                        <div>
                            <p class="text-white font-medium"><?php echo htmlspecialchars($_SESSION['fullname'] ?? 'User'); ?></p>
                            <p class="text-emerald-400/70 text-xs"><?php echo htmlspecialchars($_SESSION['role'] ?? 'Coordinator'); ?></p>
                        </div>
                    </div>
                </div>
                <nav>
                    <?php foreach ($navGroups as $label => $items): ?>
                    <span class="nav-section-label"><?php echo $label; ?></span>
                    <?php foreach ($items as $item): ?>
                    <a href="coordinator.php?section=<?php echo $item[0]; ?>"
                       class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-white/5 transition-all <?php echo ($section === $item[0]) ? 'coord-active' : ''; ?>">
                        <i class="<?php echo $item[2]; ?> <?php echo ($section===$item[0]) ? 'text-emerald-400' : 'text-white/60'; ?>"></i>
                        <span class="text-sm <?php echo ($section===$item[0]) ? 'text-white font-medium' : 'text-white/80'; ?>"><?php echo $item[1]; ?></span>
                    </a>
                    <?php endforeach; ?>
                    <div class="nav-divider"></div>
                    <?php endforeach; ?>
                    <a href="coordinator.php?section=logout" class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-white/5 transition-all">
                        <i class="ri-logout-box-line text-white/60"></i>
                        <span class="text-white/80">Logout</span>
                    </a>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <main class="flex-1 md:ml-72 p-6 md:p-8 mt-16 md:mt-0">
            <div class="mac-menu-bar rounded-2xl p-4 mb-6 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <span class="text-white/60 text-sm"><?php echo date('l, F j, Y'); ?></span>
                    <span class="text-white/20">|</span>
                    <span class="text-white/60 text-sm" id="currentTime"></span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                    <span class="text-white/40 text-xs">Coordinator Portal</span>
                </div>
            </div>

            <div class="flex items-center justify-between mb-6 mac-fade-in">
                <div>
                    <h1 class="mac-title text-3xl">
                        <?php
                        $titles = [
                            'home'        => 'Dashboard Overview',
                            'beneficiary' => 'Beneficiary Management',
                            'program'     => 'Program Management',
                            'activity'    => 'Activity Management',
                            'partner'     => 'Partner & Donor Management',
                            'resource'    => 'Resource Management',
                            'location'    => 'Location Management',
                            'staff'       => 'Coordinator Management',
                            'evaluation'  => 'Evaluation & Monitoring',
                            'reports'     => 'Reports & Analytics',
                        ];
                        echo $titles[$section] ?? ucfirst($section);
                        ?>
                    </h1>
                    <p class="text-white/40 text-sm mt-1">
                        <i class="ri-information-line mr-1"></i>
                        Manage and monitor your <?php echo $section; ?> data
                    </p>
                </div>
                <button onclick="window.location.reload()" class="mac-button flex items-center gap-2">
                    <i class="ri-refresh-line"></i>
                    <span class="hidden md:inline">Refresh</span>
                </button>
            </div>

            <div class="mac-glass-deep p-6 mac-fade-in">
                <?php
                if (file_exists($sectionFile)) {
                    include($sectionFile);
                } else {
                    include("sections/home.php");
                }
                ?>
            </div>

            <div class="mt-6 flex items-center justify-between text-white/20 text-xs">
                <span>© <?php echo date('Y'); ?> CEMS. All rights reserved.</span>
                <span>Coordinator Portal &nbsp;•&nbsp; v2.0</span>
            </div>
        </main>
    </div>

    <script>
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileSidebar = document.getElementById('mobileSidebar');
    const mobileOverlay = document.getElementById('mobileOverlay');

    mobileMenuBtn?.addEventListener('click', () => {
        mobileSidebar.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    });
    mobileOverlay?.addEventListener('click', () => {
        mobileSidebar.classList.add('hidden');
        document.body.style.overflow = '';
    });

    function updateTime() {
        const now = new Date();
        const t = now.toLocaleTimeString('en-US', { hour:'2-digit', minute:'2-digit' });
        const el = document.getElementById('currentTime');
        if (el) el.textContent = t;
        const mob = document.getElementById('mobileTime');
        if (mob) mob.textContent = t;
    }
    updateTime();
    setInterval(updateTime, 1000);

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            mobileSidebar?.classList.add('hidden');
            document.body.style.overflow = '';
        }
    });

    document.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', (e) => {
            if (link.href.includes('coordinator.php') && !link.href.includes('logout')) {
                e.preventDefault();
                document.querySelector('main').style.opacity = '0';
                document.querySelector('main').style.transform = 'translateY(10px)';
                setTimeout(() => { window.location.href = link.href; }, 200);
            }
        });
    });

    window.addEventListener('load', () => {
        document.querySelector('main').style.transition = 'all 0.3s ease';
        document.querySelector('main').style.opacity = '1';
        document.querySelector('main').style.transform = 'translateY(0)';
    });
    </script>
</body>
</html>