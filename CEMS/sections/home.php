<?php
include_once __DIR__ . '/../db.php';

$stats = [
    'beneficiaries'       => 0, 'programs'     => 0, 'projects'    => 0,
    'activities'          => 0, 'partners'      => 0, 'resources'   => 0,
    'locations'           => 0, 'team'          => 0, 'evaluations' => 0,
    'reports'             => 0, 'teaching'      => 0, 'nonteaching' => 0,
    'coordinators'        => 0, 'donors'        => 0, 'beneficiary_users' => 0,
    'students'            => 0, 'ongoing_projects' => 0, 'upcoming_activities' => 0,
    'total_joins'         => 0, 'today_feedback'   => 0,
];
$recentMembers   = [];
$recentFeedback  = [];
$recentActivities= [];

try {
    foreach(['beneficiaries','programs','projects','activities','partners','resources','locations','evaluations','reports'] as $t){
        $r=$conn->query("SELECT COUNT(*) as c FROM `$t`");
        if($r) $stats[$t]=(int)$r->fetch_assoc()['c'];
    }
    $r=$conn->query("SELECT COUNT(*) as c FROM users"); if($r) $stats['team']=(int)$r->fetch_assoc()['c'];
    $r=$conn->query("SELECT COUNT(*) as c FROM users WHERE role='Teaching Staff'");     if($r) $stats['teaching']=(int)$r->fetch_assoc()['c'];
    $r=$conn->query("SELECT COUNT(*) as c FROM users WHERE role='Non-Teaching Staff'"); if($r) $stats['nonteaching']=(int)$r->fetch_assoc()['c'];
    $r=$conn->query("SELECT COUNT(*) as c FROM users WHERE role='Dept. Coordinator'");  if($r) $stats['coordinators']=(int)$r->fetch_assoc()['c'];
    $r=$conn->query("SELECT COUNT(*) as c FROM users WHERE role IN ('Donor','Donor/Partner')"); if($r) $stats['donors']=(int)$r->fetch_assoc()['c'];
    $r=$conn->query("SELECT COUNT(*) as c FROM users WHERE role='Beneficiary'");        if($r) $stats['beneficiary_users']=(int)$r->fetch_assoc()['c'];
    $r=$conn->query("SELECT COUNT(*) as c FROM users WHERE role='Student'");            if($r) $stats['students']=(int)$r->fetch_assoc()['c'];
    $r=$conn->query("SELECT COUNT(*) as c FROM projects WHERE status='Active'");        if($r) $stats['ongoing_projects']=(int)$r->fetch_assoc()['c'];
    $r=$conn->query("SELECT COUNT(*) as c FROM activities WHERE status='Upcoming'");    if($r) $stats['upcoming_activities']=(int)$r->fetch_assoc()['c'];
    $conn->query("CREATE TABLE IF NOT EXISTS activity_participants (id INT AUTO_INCREMENT PRIMARY KEY,activity_id INT,user_id INT,joined_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,UNIQUE KEY u(activity_id,user_id))");
    $conn->query("CREATE TABLE IF NOT EXISTS project_participants  (id INT AUTO_INCREMENT PRIMARY KEY,project_id  INT,user_id INT,joined_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,UNIQUE KEY u(project_id,user_id))");
    $r=$conn->query("SELECT (SELECT COUNT(*) FROM activity_participants)+(SELECT COUNT(*) FROM project_participants) as c");
    if($r) $stats['total_joins']=(int)$r->fetch_assoc()['c'];
    $r=$conn->query("SELECT COUNT(*) as c FROM evaluations WHERE DATE(created_at)=CURDATE()"); if($r) $stats['today_feedback']=(int)$r->fetch_assoc()['c'];
    $r=$conn->query("SELECT fullname,role,created_at FROM users ORDER BY created_at DESC LIMIT 5");
    if($r) while($row=$r->fetch_assoc()) $recentMembers[]=$row;
    $r=$conn->query("SELECT evaluator,program,findings,created_at FROM evaluations ORDER BY created_at DESC LIMIT 4");
    if($r) while($row=$r->fetch_assoc()) $recentFeedback[]=$row;
    $r=$conn->query("SELECT name,status,date_time FROM activities ORDER BY created_at DESC LIMIT 5");
    if($r) while($row=$r->fetch_assoc()) $recentActivities[]=$row;
} catch(Throwable $e){}

function timeAgo($dt){
    if(!$dt) return '—';
    $diff=(new DateTime())->diff(new DateTime($dt));
    if($diff->y>0) return $diff->y.'y ago';
    if($diff->m>0) return $diff->m.'mo ago';
    if($diff->d>0) return $diff->d.'d ago';
    if($diff->h>0) return $diff->h.'h ago';
    if($diff->i>0) return $diff->i.'m ago';
    return 'Just now';
}

$iconColors=['team'=>'#3B82F6','beneficiaries'=>'#10B981','ongoing_projects'=>'#F59E0B',
'upcoming_activities'=>'#EF4444','programs'=>'#8B5CF6','projects'=>'#EC4899',
'activities'=>'#14B8A6','partners'=>'#F97316','resources'=>'#6366F1',
'locations'=>'#06B6D4','evaluations'=>'#A855F7','reports'=>'#6B7280',
'feedback'=>'#FFD60A','joins'=>'#32D74B'];

$gray=['900'=>'#212529','800'=>'#343A40','700'=>'#495057','600'=>'#6C757D'];
?>

<section id="home-section" class="space-y-6">

<!-- Welcome Banner -->
<div class="rounded-3xl p-6 md:p-8 relative overflow-hidden"
     style="background:linear-gradient(135deg,<?php echo $gray['900'];?> 0%,<?php echo $gray['800'];?> 100%);">
    <div class="absolute top-0 right-0 w-80 h-80 bg-white/5 rounded-full blur-3xl -mr-20 -mt-20"></div>
    <div class="relative z-10">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <div class="flex items-center gap-2 mb-2 flex-wrap">
                    <span class="text-xs text-white/80 font-bold uppercase tracking-wider bg-white/10 px-3 py-1.5 rounded-full border border-white/20">✦ Dashboard Overview</span>
                    <span class="px-3 py-1.5 bg-white/20 rounded-full text-xs text-white font-semibold flex items-center gap-1 border border-white/30">
                        <span class="w-2 h-2 bg-green-400 rounded-full"></span>Live Data
                    </span>
                </div>
                <h1 class="text-3xl md:text-4xl font-bold text-white">
                    Welcome back, <span class="text-white"><?php echo htmlspecialchars($_SESSION['fullname']??'Admin'); ?></span>
                </h1>
                <p class="text-white/60 text-sm mt-2">Here's your comprehensive community impact analytics.</p>
            </div>
            <div class="flex flex-col items-start md:items-end gap-2">
                <div class="flex items-center gap-2 bg-white/10 rounded-xl px-4 py-2 border border-white/20">
                    <i class="ri-calendar-line text-white"></i>
                    <span class="text-white text-sm font-medium"><?php echo date('l, F j, Y'); ?></span>
                </div>
                <div class="flex gap-2">
                    <a href="dashboard.php?section=reports" class="px-3 py-2 bg-white/10 hover:bg-white/20 rounded-lg text-white text-xs font-semibold flex items-center gap-1 border border-white/20 transition-all">
                        <i class="ri-download-line"></i> Reports
                    </a>
                    <button onclick="window.location.reload()" class="px-3 py-2 bg-white/20 hover:bg-white/30 rounded-lg text-white text-xs font-semibold flex items-center gap-1 border border-white/30 transition-all">
                        <i class="ri-refresh-line"></i> Refresh
                    </button>
                </div>
            </div>
        </div>

        <!-- Top 4 KPIs -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <?php
            $topKpis=[
                ['Team Members',        $stats['team'],                $stats['teaching'].' teaching · '.$stats['nonteaching'].' non-teaching', $iconColors['team'],                'staff',    'ri-team-line'],
                ['Beneficiaries',       $stats['beneficiaries'],       $stats['beneficiary_users'].' registered users',                         $iconColors['beneficiaries'],       'beneficiary','ri-user-heart-line'],
                ['Active Projects',     $stats['ongoing_projects'],    'of '.$stats['projects'].' total projects',                              $iconColors['ongoing_projects'],    'project',  'ri-folder-2-line'],
                ['Upcoming Activities', $stats['upcoming_activities'], 'of '.$stats['activities'].' total activities',                         $iconColors['upcoming_activities'], 'activity', 'ri-calendar-event-line'],
            ];
            foreach($topKpis as $k): ?>
            <a href="dashboard.php?section=<?php echo $k[4]; ?>" class="bg-white/5 rounded-xl p-4 border border-white/10 hover:bg-white/10 transition-all group block">
                <div class="w-9 h-9 rounded-lg flex items-center justify-center mb-2 group-hover:scale-110 transition-transform" style="background:<?php echo $k[3]; ?>20;">
                    <i class="<?php echo $k[5]; ?> text-xl" style="color:<?php echo $k[3]; ?>;"></i>
                </div>
                <p class="text-white/50 text-xs mb-1 font-medium"><?php echo $k[0]; ?></p>
                <p class="text-2xl font-bold text-white"><?php echo number_format($k[1]); ?></p>
                <p class="text-white/35 text-xs mt-1"><?php echo $k[2]; ?></p>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Main Stats Grid -->
<?php
$statCards=[
    ['beneficiary','ri-user-3-line',    'Total Beneficiaries', $stats['beneficiaries'],  $stats['beneficiaries'].' registered',                              $iconColors['beneficiaries']],
    ['program',    'ri-archive-line',   'Programs',            $stats['programs'],       $stats['programs'].' total',                                        $iconColors['programs']],
    ['project',    'ri-folder-2-line',  'Projects',            $stats['projects'],       $stats['ongoing_projects'].' active',                               $iconColors['projects']],
    ['activity',   'ri-calendar-line',  'Activities',          $stats['activities'],     $stats['upcoming_activities'].' upcoming',                          $iconColors['activities']],
    ['partner',    'ri-hand-heart-line','Partners & Donors',   $stats['partners'],       $stats['donors'].' donor accounts',                                 $iconColors['partners']],
    ['resource',   'ri-stack-line',     'Resources',           $stats['resources'],      $stats['resources'].' items',                                       $iconColors['resources']],
    ['location',   'ri-map-pin-line',   'Locations',           $stats['locations'],      $stats['locations'].' mapped sites',                                $iconColors['locations']],
    ['staff',      'ri-team-line',      'All Users',           $stats['team'],           $stats['students'].' students · '.$stats['coordinators'].' coord.', $iconColors['team']],
];
$grads=[
    'linear-gradient(135deg,#212529,#343A40)',
    'linear-gradient(135deg,#343A40,#495057)',
    'linear-gradient(135deg,#495057,#6C757D)',
    'linear-gradient(135deg,#6C757D,#ADB5BD)',
];
?>
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
<?php foreach($statCards as $i=>$card): ?>
<a href="dashboard.php?section=<?php echo $card[0]; ?>"
   class="rounded-2xl p-5 hover:scale-105 transition-all duration-300 group cursor-pointer block relative overflow-hidden"
   style="background:<?php echo $grads[$i%4]; ?>">
    <div class="absolute top-0 right-0 w-24 h-24 bg-white/5 rounded-full blur-xl"></div>
    <div class="relative z-10">
        <div class="flex items-start justify-between mb-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform"
                 style="background:<?php echo $card[5]; ?>20;border:1px solid <?php echo $card[5]; ?>40;">
                <i class="<?php echo $card[1]; ?> text-xl" style="color:<?php echo $card[5]; ?>;"></i>
            </div>
            <span class="text-xs px-2 py-1 rounded-full font-bold bg-white/10 text-white border border-white/20"><?php echo number_format($card[3]); ?></span>
        </div>
        <p class="text-white/60 text-xs font-medium mb-1"><?php echo $card[2]; ?></p>
        <p class="text-2xl font-bold text-white mb-2"><?php echo number_format($card[3]); ?></p>
        <span class="text-xs bg-white/10 text-white/60 px-2 py-1 rounded-full font-medium border border-white/10"><?php echo $card[4]; ?></span>
    </div>
</a>
<?php endforeach; ?>
</div>

<!-- Extra KPI row -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4">
    <a href="dashboard.php?section=feedback_log" class="bg-white/5 rounded-xl p-4 border border-white/10 hover:bg-white/10 transition-all group block">
        <div class="w-9 h-9 rounded-lg flex items-center justify-center mb-2 group-hover:scale-110 transition-transform" style="background:<?php echo $iconColors['feedback']; ?>20;">
            <i class="ri-feedback-line text-xl" style="color:<?php echo $iconColors['feedback']; ?>;"></i>
        </div>
        <p class="text-white/50 text-xs font-medium">Total Feedback</p>
        <p class="text-2xl font-bold text-white"><?php echo number_format($stats['evaluations']); ?></p>
        <p class="text-white/35 text-xs mt-1"><?php echo $stats['today_feedback']; ?> submitted today</p>
    </a>
    <a href="dashboard.php?section=participants" class="bg-white/5 rounded-xl p-4 border border-white/10 hover:bg-white/10 transition-all group block">
        <div class="w-9 h-9 rounded-lg flex items-center justify-center mb-2 group-hover:scale-110 transition-transform" style="background:<?php echo $iconColors['joins']; ?>20;">
            <i class="ri-user-add-line text-xl" style="color:<?php echo $iconColors['joins']; ?>;"></i>
        </div>
        <p class="text-white/50 text-xs font-medium">Total Joins</p>
        <p class="text-2xl font-bold text-white"><?php echo number_format($stats['total_joins']); ?></p>
        <p class="text-white/35 text-xs mt-1">Activities + Projects</p>
    </a>
    <a href="dashboard.php?section=evaluation" class="bg-white/5 rounded-xl p-4 border border-white/10 hover:bg-white/10 transition-all group block">
        <div class="w-9 h-9 rounded-lg flex items-center justify-center mb-2 group-hover:scale-110 transition-transform" style="background:<?php echo $iconColors['evaluations']; ?>20;">
            <i class="ri-survey-line text-xl" style="color:<?php echo $iconColors['evaluations']; ?>;"></i>
        </div>
        <p class="text-white/50 text-xs font-medium">Evaluations</p>
        <p class="text-2xl font-bold text-white"><?php echo number_format($stats['evaluations']); ?></p>
        <p class="text-white/35 text-xs mt-1">All submissions</p>
    </a>
    <a href="dashboard.php?section=reports" class="bg-white/5 rounded-xl p-4 border border-white/10 hover:bg-white/10 transition-all group block">
        <div class="w-9 h-9 rounded-lg flex items-center justify-center mb-2 group-hover:scale-110 transition-transform" style="background:<?php echo $iconColors['reports']; ?>20;">
            <i class="ri-bar-chart-2-line text-xl" style="color:<?php echo $iconColors['reports']; ?>;"></i>
        </div>
        <p class="text-white/50 text-xs font-medium">Reports</p>
        <p class="text-2xl font-bold text-white"><?php echo number_format($stats['reports']); ?></p>
        <p class="text-white/35 text-xs mt-1">Generated</p>
    </a>
</div>

<!-- Bottom 3 panels -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    <!-- Team Role Breakdown -->
    <div class="rounded-2xl p-5" style="background:linear-gradient(135deg,<?php echo $gray['900'];?>,<?php echo $gray['800'];?>);">
        <h2 class="text-base font-bold text-white mb-4 flex items-center gap-2">
            <span class="w-1 h-5 bg-white/30 rounded-full"></span>Team Breakdown
        </h2>
        <?php
        $devCount = 0;
        $dq = $conn->query("SELECT COUNT(*) as c FROM users WHERE role IN ('Developer','Director','Admin','admin')");
        if($dq) $devCount = (int)$dq->fetch_assoc()['c'];
        $roles=[
            ['Developer/Director', $devCount,                    '#0A84FF'],
            ['Dept. Coordinator',  $stats['coordinators'],       '#BF5AF2'],
            ['Teaching Staff',     $stats['teaching'],           '#32D74B'],
            ['Non-Teaching Staff', $stats['nonteaching'],        '#FF9F0A'],
            ['Donor/Partner',      $stats['donors'],             '#FF2D55'],
            ['Beneficiary',        $stats['beneficiary_users'],  '#14B8A6'],
            ['Student',            $stats['students'],           '#FFD60A'],
        ];
        $total=max($stats['team'],1);
        foreach($roles as $role):
            $pct=round(($role[1]/$total)*100);
        ?>
        <div class="mb-3">
            <div class="flex items-center justify-between mb-1">
                <div class="flex items-center gap-2">
                    <div class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background:<?php echo $role[2]; ?>"></div>
                    <span class="text-white/70 text-xs font-medium"><?php echo $role[0]; ?></span>
                </div>
                <div class="flex items-center gap-1">
                    <span class="text-white font-bold text-sm"><?php echo $role[1]; ?></span>
                    <span class="text-white/30 text-xs">(<?php echo $pct; ?>%)</span>
                </div>
            </div>
            <div class="h-2 bg-white/10 rounded-full overflow-hidden">
                <div class="h-full rounded-full" style="width:<?php echo $pct; ?>%;background:<?php echo $role[2]; ?>;opacity:0.8;"></div>
            </div>
        </div>
        <?php endforeach; ?>
        <div class="grid grid-cols-3 gap-2 mt-4 pt-3 border-t border-white/10">
            <div class="text-center bg-white/5 rounded-lg p-2">
                <p class="text-xl font-bold text-white"><?php echo $stats['team']; ?></p>
                <p class="text-xs text-white/40">Total</p>
            </div>
            <div class="text-center bg-white/5 rounded-lg p-2">
                <p class="text-xl font-bold text-white"><?php echo $stats['teaching']+$stats['nonteaching']; ?></p>
                <p class="text-xs text-white/40">Staff</p>
            </div>
            <div class="text-center bg-white/5 rounded-lg p-2">
                <p class="text-xl font-bold text-white"><?php echo $stats['students']; ?></p>
                <p class="text-xs text-white/40">Students</p>
            </div>
        </div>
    </div>

    <!-- Recent Feedback -->
    <div class="rounded-2xl p-5" style="background:linear-gradient(135deg,<?php echo $gray['800'];?>,<?php echo $gray['700'];?>);">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-bold text-white flex items-center gap-2">
                <span class="w-1 h-5 bg-white/30 rounded-full"></span>Recent Feedback
            </h2>
            <a href="dashboard.php?section=feedback_log" class="text-xs text-white/50 hover:text-white bg-white/10 px-2 py-1 rounded-lg border border-white/10 transition-all">View All →</a>
        </div>
        <?php if(empty($recentFeedback)): ?>
        <div class="text-center py-8 text-white/30">
            <i class="ri-feedback-line text-3xl block mb-2"></i>
            <p class="text-sm">No feedback yet.</p>
        </div>
        <?php else: foreach($recentFeedback as $fb): ?>
        <div class="flex items-start gap-3 p-3 rounded-xl bg-white/5 border border-white/5 mb-2 hover:bg-white/8 transition-all">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-xs flex-shrink-0"
                 style="background:linear-gradient(135deg,#BF5AF2,#0A84FF);color:white;">
                <?php echo strtoupper(substr($fb['evaluator']??'?',0,1)); ?>
            </div>
            <div class="flex-1 min-w-0">
                <div class="text-white text-xs font-semibold truncate"><?php echo htmlspecialchars($fb['evaluator']??'Unknown'); ?></div>
                <div class="text-white/40 text-xs truncate"><?php echo htmlspecialchars($fb['program']??'—'); ?></div>
                <div class="text-white/30 text-xs truncate"><?php echo htmlspecialchars(substr($fb['findings']??'',0,45)); ?></div>
            </div>
            <div class="text-white/30 text-xs flex-shrink-0"><?php echo timeAgo($fb['created_at']); ?></div>
        </div>
        <?php endforeach; endif; ?>
        <div class="grid grid-cols-2 gap-2 mt-3 pt-3 border-t border-white/10">
            <div class="bg-white/5 rounded-lg p-2 text-center">
                <p class="text-xl font-bold text-white"><?php echo $stats['evaluations']; ?></p>
                <p class="text-xs text-white/40">Total Feedback</p>
            </div>
            <div class="bg-white/5 rounded-lg p-2 text-center">
                <p class="text-xl font-bold text-white"><?php echo $stats['total_joins']; ?></p>
                <p class="text-xs text-white/40">Total Joins</p>
            </div>
        </div>
    </div>

    <!-- Recent Members + Activities -->
    <div class="rounded-2xl p-5" style="background:linear-gradient(135deg,<?php echo $gray['700'];?>,<?php echo $gray['600'];?>);">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-bold text-white flex items-center gap-2">
                <span class="w-1 h-5 bg-white/30 rounded-full"></span>Recent Members
            </h2>
            <a href="dashboard.php?section=staff" class="text-xs text-white/50 hover:text-white bg-white/10 px-2 py-1 rounded-lg border border-white/10 transition-all">View All →</a>
        </div>
        <?php foreach($recentMembers as $m): ?>
        <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-white/5 transition-all mb-1">
            <div class="w-7 h-7 rounded-lg flex items-center justify-center font-bold text-xs flex-shrink-0"
                 style="background:linear-gradient(135deg,#FF9F0A,#c97800);color:white;">
                <?php echo strtoupper(substr($m['fullname']??'?',0,1)); ?>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-white text-xs font-semibold truncate"><?php echo htmlspecialchars($m['fullname']??''); ?></p>
                <p class="text-white/40 text-xs"><?php echo htmlspecialchars($m['role']??''); ?></p>
            </div>
            <span class="text-white/30 text-xs flex-shrink-0"><?php echo timeAgo($m['created_at']); ?></span>
        </div>
        <?php endforeach; ?>
        <div class="mt-4 pt-3 border-t border-white/10">
            <div class="text-xs text-white/30 font-bold uppercase tracking-wider mb-2">Recent Activities</div>
            <?php
            $statusColors=['Ongoing'=>'#32D74B','Upcoming'=>'#0A84FF','Completed'=>'#FF9F0A','Cancelled'=>'#FF453A'];
            foreach($recentActivities as $a):
                $s=$a['status']??'';
                $c=$statusColors[$s]??'#6B7280';
            ?>
            <div class="flex items-center gap-2 py-1.5 border-b border-white/5">
                <div class="w-2 h-2 rounded-full flex-shrink-0" style="background:<?php echo $c; ?>"></div>
                <span class="text-white/70 text-xs flex-1 truncate"><?php echo htmlspecialchars($a['name']); ?></span>
                <span class="text-xs font-semibold flex-shrink-0" style="color:<?php echo $c; ?>"><?php echo $s; ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Footer summary -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4">
    <?php
    $footer=[
        ['ri-archive-line',  $iconColors['programs'],   $stats['programs'],   'Active Programs',   'program'],
        ['ri-folder-2-line', $iconColors['projects'],   $stats['projects'],   'Total Projects',    'project'],
        ['ri-calendar-line', $iconColors['activities'], $stats['activities'], 'Total Activities',  'activity'],
        ['ri-map-pin-line',  $iconColors['locations'],  $stats['locations'],  'Service Locations', 'location'],
    ];
    foreach($footer as $f): ?>
    <a href="dashboard.php?section=<?php echo $f[4]; ?>"
       class="bg-white/5 rounded-xl p-4 text-center border border-white/10 hover:bg-white/10 transition-all group block">
        <div class="w-9 h-9 rounded-lg mx-auto mb-2 flex items-center justify-center group-hover:scale-110 transition-transform"
             style="background:<?php echo $f[1]; ?>20;">
            <i class="<?php echo $f[0]; ?> text-xl" style="color:<?php echo $f[1]; ?>;"></i>
        </div>
        <p class="text-2xl font-bold text-white"><?php echo number_format($f[2]); ?></p>
        <p class="text-xs text-white/40 font-medium"><?php echo $f[3]; ?></p>
    </a>
    <?php endforeach; ?>
</div>

</section>