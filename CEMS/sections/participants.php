<?php
// sections/participants.php — Join Log & Participants Tracker
// Visible to: Developer, Director, Admin
include_once 'db.php';

// Auto-create tables if missing
$conn->query("CREATE TABLE IF NOT EXISTS activity_participants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    activity_id INT NOT NULL,
    user_id INT NOT NULL,
    joined_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_join (activity_id, user_id)
)");
$conn->query("CREATE TABLE IF NOT EXISTS project_participants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_id INT NOT NULL,
    user_id INT NOT NULL,
    joined_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_join (project_id, user_id)
)");

// ── Stats ──
$totalActJoins  = $conn->query("SELECT COUNT(*) as c FROM activity_participants")->fetch_assoc()['c'] ?? 0;
$totalProjJoins = $conn->query("SELECT COUNT(*) as c FROM project_participants")->fetch_assoc()['c'] ?? 0;
$totalUniqueAct = $conn->query("SELECT COUNT(DISTINCT user_id) as c FROM activity_participants")->fetch_assoc()['c'] ?? 0;
$totalUniqueProj= $conn->query("SELECT COUNT(DISTINCT user_id) as c FROM project_participants")->fetch_assoc()['c'] ?? 0;
$todayAct  = $conn->query("SELECT COUNT(*) as c FROM activity_participants WHERE DATE(joined_at)=CURDATE()")->fetch_assoc()['c'] ?? 0;
$todayProj = $conn->query("SELECT COUNT(*) as c FROM project_participants WHERE DATE(joined_at)=CURDATE()")->fetch_assoc()['c'] ?? 0;
$totalToday = $todayAct + $todayProj;
$totalAll   = $totalActJoins + $totalProjJoins;
$totalUnique= $conn->query("SELECT COUNT(DISTINCT user_id) as c FROM (SELECT user_id FROM activity_participants UNION SELECT user_id FROM project_participants) t")->fetch_assoc()['c'] ?? 0;

// ── Activity join log ──
$actLog = [];
$aq = $conn->query("
    SELECT ap.id, ap.joined_at,
           u.fullname, u.email, u.role,
           a.name as activity_name, a.status as activity_status, a.date_time
    FROM activity_participants ap
    LEFT JOIN users u ON ap.user_id = u.id
    LEFT JOIN activities a ON ap.activity_id = a.id
    ORDER BY ap.joined_at DESC
    LIMIT 200
");
if($aq) while($r=$aq->fetch_assoc()) $actLog[]=$r;

// ── Project join log ──
$projLog = [];
$pq = $conn->query("
    SELECT pp.id, pp.joined_at,
           u.fullname, u.email, u.role,
           p.name as project_name, p.status as project_status, p.start_date
    FROM project_participants pp
    LEFT JOIN users u ON pp.user_id = u.id
    LEFT JOIN projects p ON pp.project_id = p.id
    ORDER BY pp.joined_at DESC
    LIMIT 200
");
if($pq) while($r=$pq->fetch_assoc()) $projLog[]=$r;

// ── Per-activity summary ──
$actSummary = [];
$as = $conn->query("
    SELECT a.name, a.status, a.date_time, COUNT(ap.id) as total_joined,
           MIN(ap.joined_at) as first_join, MAX(ap.joined_at) as last_join
    FROM activities a
    LEFT JOIN activity_participants ap ON a.id = ap.activity_id
    GROUP BY a.id ORDER BY total_joined DESC
");
if($as) while($r=$as->fetch_assoc()) $actSummary[]=$r;

// ── Per-project summary ──
$projSummary = [];
$ps = $conn->query("
    SELECT p.name, p.status, p.start_date, COUNT(pp.id) as total_joined,
           MIN(pp.joined_at) as first_join, MAX(pp.joined_at) as last_join
    FROM projects p
    LEFT JOIN project_participants pp ON p.id = pp.project_id
    GROUP BY p.id ORDER BY total_joined DESC
");
if($ps) while($r=$ps->fetch_assoc()) $projSummary[]=$r;

$isDevDir = in_array($_SESSION['role'], ['Developer','Director']);
?>

<style>
.pt-stat { background:rgba(255,255,255,0.04); border:1px solid rgba(255,255,255,0.08); border-radius:14px; padding:1.1rem 1.25rem; display:flex; align-items:flex-start; justify-content:space-between; position:relative; overflow:hidden; }
.pt-stat::before { content:''; position:absolute; top:0; right:0; width:80px; height:80px; border-radius:50%; filter:blur(28px); }
.pt-stat.blue::before   { background:rgba(10,132,255,0.18); }
.pt-stat.green::before  { background:rgba(50,215,75,0.15); }
.pt-stat.purple::before { background:rgba(191,90,242,0.15); }
.pt-stat.orange::before { background:rgba(255,159,10,0.15); }
.pt-stat.teal::before   { background:rgba(0,180,160,0.15); }
.pt-stat.red::before    { background:rgba(255,69,58,0.15); }
.pt-label { font-size:0.62rem; font-weight:700; text-transform:uppercase; letter-spacing:0.1em; color:rgba(255,255,255,0.38); margin-bottom:0.3rem; }
.pt-value { font-size:1.75rem; font-weight:800; color:white; line-height:1; }
.pt-sub   { font-size:0.62rem; color:rgba(255,255,255,0.32); margin-top:4px; }
.pt-icon  { width:36px; height:36px; border-radius:9px; display:flex; align-items:center; justify-content:center; font-size:1.1rem; color:white; flex-shrink:0; }
.ptab-btn { padding:0.45rem 1.1rem; border-radius:8px; font-size:0.78rem; font-weight:600; cursor:pointer; border:1px solid rgba(255,255,255,0.1); background:transparent; color:rgba(255,255,255,0.4); font-family:inherit; transition:all 0.2s; display:flex; align-items:center; gap:5px; }
.ptab-btn.active { background:rgba(10,132,255,0.18); border-color:rgba(10,132,255,0.35); color:white; }
.ptab-btn:hover:not(.active) { background:rgba(255,255,255,0.06); color:rgba(255,255,255,0.8); }
.pt-table { width:100%; border-collapse:collapse; }
.pt-table thead tr { background:rgba(0,0,0,0.25); }
.pt-table th { padding:0.6rem 0.9rem; font-size:0.62rem; font-weight:700; text-transform:uppercase; letter-spacing:0.1em; color:rgba(255,255,255,0.38); text-align:left; white-space:nowrap; }
.pt-table td { padding:0.7rem 0.9rem; font-size:0.8rem; color:rgba(255,255,255,0.72); border-bottom:1px solid rgba(255,255,255,0.04); }
.pt-table tr:hover td { background:rgba(255,255,255,0.025); }
.badge { display:inline-flex; align-items:center; padding:0.18rem 0.6rem; border-radius:50px; font-size:0.65rem; font-weight:700; }
.badge-act  { background:rgba(10,132,255,0.15); color:#60a5fa; border:1px solid rgba(10,132,255,0.3); }
.badge-proj { background:rgba(191,90,242,0.15); color:#c084fc; border:1px solid rgba(191,90,242,0.3); }
.badge-green{ background:rgba(50,215,75,0.15); color:#4ade80; border:1px solid rgba(50,215,75,0.3); }
.badge-gray { background:rgba(255,255,255,0.06); color:rgba(255,255,255,0.4); border:1px solid rgba(255,255,255,0.1); }
.badge-role { background:rgba(255,159,10,0.12); color:#fbbf24; border:1px solid rgba(255,159,10,0.25); font-size:0.6rem; }
.pt-search { background:rgba(0,0,0,0.25); border:1px solid rgba(255,255,255,0.08); border-radius:8px; padding:0.5rem 0.9rem 0.5rem 2.1rem; font-size:0.8rem; color:white; outline:none; font-family:inherit; transition:all 0.2s; width:100%; }
.pt-search::placeholder { color:rgba(255,255,255,0.22); }
.pt-search:focus { border-color:#0A84FF; box-shadow:0 0 0 2px rgba(10,132,255,0.12); }
.export-btn { background:rgba(50,215,75,0.12); border:1px solid rgba(50,215,75,0.25); border-radius:8px; padding:6px 14px; color:#4ade80; font-size:0.75rem; font-weight:600; cursor:pointer; display:flex; align-items:center; gap:5px; transition:all 0.2s; font-family:inherit; }
.export-btn:hover { background:rgba(50,215,75,0.22); }
.section-heading { font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:0.12em; color:rgba(255,255,255,0.25); margin-bottom:0.75rem; display:flex; align-items:center; gap:6px; }
</style>

<!-- Page Header -->
<div style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:1rem;margin-bottom:1.5rem;">
    <div>
        <div style="display:inline-flex;align-items:center;gap:6px;background:rgba(10,132,255,0.12);border:1px solid rgba(10,132,255,0.25);border-radius:50px;padding:3px 12px;font-size:0.65rem;font-weight:700;color:rgba(255,255,255,0.6);letter-spacing:0.08em;text-transform:uppercase;margin-bottom:0.5rem;">
            <i class="ri-team-line"></i> PARTICIPANTS &nbsp;•&nbsp; <span id="totalBadge"><?php echo $totalAll; ?> Total</span>
        </div>
        <div style="font-size:1.75rem;font-weight:800;color:white;letter-spacing:-0.5px;display:flex;align-items:center;gap:0.5rem;">
            Participants <span style="color:rgba(255,255,255,0.35);font-weight:400;font-size:1.1rem;">/ Join Log</span>
        </div>
        <div style="font-size:0.75rem;color:rgba(255,255,255,0.38);margin-top:4px;">
            <i class="ri-sparkling-line" style="color:#0A84FF"></i>
            Track who joined activities &amp; projects, when, and how many
        </div>
    </div>
    <div style="display:flex;gap:8px;flex-wrap:wrap;">
        <button class="export-btn" onclick="exportCSV('activity')"><i class="ri-download-line"></i> Export Activity CSV</button>
        <button class="export-btn" style="background:rgba(191,90,242,0.12);border-color:rgba(191,90,242,0.25);color:#c084fc;" onclick="exportCSV('project')"><i class="ri-download-line"></i> Export Project CSV</button>
    </div>
</div>

<!-- Stats Grid -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:0.75rem;margin-bottom:1.5rem;">
    <div class="pt-stat blue">
        <div><div class="pt-label">Total Joins</div><div class="pt-value"><?php echo $totalAll; ?></div><div class="pt-sub">All time</div></div>
        <div class="pt-icon" style="background:linear-gradient(135deg,#0A84FF,#0060c7)"><i class="ri-user-add-line"></i></div>
    </div>
    <div class="pt-stat green">
        <div><div class="pt-label">Today's Joins</div><div class="pt-value"><?php echo $totalToday; ?></div><div class="pt-sub"><?php echo date('M j, Y'); ?></div></div>
        <div class="pt-icon" style="background:linear-gradient(135deg,#32D74B,#22a336)"><i class="ri-calendar-check-line"></i></div>
    </div>
    <div class="pt-stat purple">
        <div><div class="pt-label">Unique Participants</div><div class="pt-value"><?php echo $totalUnique; ?></div><div class="pt-sub">Distinct users</div></div>
        <div class="pt-icon" style="background:linear-gradient(135deg,#BF5AF2,#9030d0)"><i class="ri-group-line"></i></div>
    </div>
    <div class="pt-stat orange">
        <div><div class="pt-label">Activity Joins</div><div class="pt-value"><?php echo $totalActJoins; ?></div><div class="pt-sub"><?php echo $uniqueAct ?? $totalUniqueAct; ?> unique users</div></div>
        <div class="pt-icon" style="background:linear-gradient(135deg,#FF9F0A,#c97800)"><i class="ri-calendar-event-line"></i></div>
    </div>
    <div class="pt-stat teal">
        <div><div class="pt-label">Project Joins</div><div class="pt-value"><?php echo $totalProjJoins; ?></div><div class="pt-sub"><?php echo $totalUniqueProj; ?> unique users</div></div>
        <div class="pt-icon" style="background:linear-gradient(135deg,#00B4A0,#007a6e)"><i class="ri-folder-add-line"></i></div>
    </div>
    <div class="pt-stat red">
        <div><div class="pt-label">Today Activities</div><div class="pt-value"><?php echo $todayAct; ?></div><div class="pt-sub"><?php echo $todayProj; ?> project joins today</div></div>
        <div class="pt-icon" style="background:linear-gradient(135deg,#FF453A,#c42e24)"><i class="ri-fire-line"></i></div>
    </div>
</div>

<!-- Tab switcher -->
<div style="display:flex;gap:0.5rem;margin-bottom:1.25rem;flex-wrap:wrap;">
    <button class="ptab-btn active" id="tab-actlog"     onclick="switchTab('actlog')"><i class="ri-calendar-event-line"></i> Activity Join Log</button>
    <button class="ptab-btn"        id="tab-projlog"    onclick="switchTab('projlog')"><i class="ri-folder-2-line"></i> Project Join Log</button>
    <button class="ptab-btn"        id="tab-actsum"     onclick="switchTab('actsum')"><i class="ri-bar-chart-line"></i> Activity Summary</button>
    <button class="ptab-btn"        id="tab-projsum"    onclick="switchTab('projsum')"><i class="ri-pie-chart-line"></i> Project Summary</button>
</div>

<!-- ── ACTIVITY JOIN LOG ── -->
<div id="pane-actlog">
    <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:0.75rem;flex-wrap:wrap;">
        <div style="position:relative;flex:1;min-width:200px;">
            <i class="ri-search-line" style="position:absolute;left:0.7rem;top:50%;transform:translateY(-50%);color:rgba(255,255,255,0.3);font-size:0.9rem;"></i>
            <input type="text" id="actSearch" class="pt-search" placeholder="Search by name, activity, role..." oninput="filterTable('actLogTable',this.value)">
        </div>
        <span style="font-size:0.72rem;color:rgba(255,255,255,0.3);">
            <?php echo count($actLog); ?> records
        </span>
    </div>
    <?php if(empty($actLog)): ?>
    <div style="text-align:center;padding:3rem 0;">
        <i class="ri-calendar-event-line" style="font-size:3rem;color:rgba(255,255,255,0.08);"></i>
        <p style="color:rgba(255,255,255,0.3);margin-top:0.75rem;font-size:0.875rem;">No activity joins recorded yet.</p>
    </div>
    <?php else: ?>
    <div style="overflow-x:auto;">
    <table class="pt-table" id="actLogTable">
        <thead><tr>
            <th>#</th>
            <th>Participant</th>
            <th>Role</th>
            <th>Activity</th>
            <th>Status</th>
            <th>Activity Date</th>
            <th>Joined At</th>
            <th>Date Joined</th>
        </tr></thead>
        <tbody>
        <?php foreach($actLog as $i=>$r): ?>
        <tr>
            <td style="color:rgba(255,255,255,0.3);font-size:0.72rem;"><?php echo $i+1; ?></td>
            <td>
                <div style="display:flex;align-items:center;gap:8px;">
                    <div style="width:30px;height:30px;border-radius:8px;background:linear-gradient(135deg,#0A84FF,#BF5AF2);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:0.75rem;color:white;flex-shrink:0;">
                        <?php echo strtoupper(substr($r['fullname']??'?',0,1)); ?>
                    </div>
                    <div>
                        <div style="font-weight:600;color:white;font-size:0.82rem;"><?php echo htmlspecialchars($r['fullname']??'Unknown'); ?></div>
                        <div style="font-size:0.68rem;color:rgba(255,255,255,0.35);"><?php echo htmlspecialchars($r['email']??'—'); ?></div>
                    </div>
                </div>
            </td>
            <td><span class="badge badge-role"><?php echo htmlspecialchars($r['role']??'—'); ?></span></td>
            <td style="font-weight:600;color:white;"><?php echo htmlspecialchars($r['activity_name']??'—'); ?></td>
            <td>
                <?php $s=$r['activity_status']??'';
                $bc=$s==='Completed'?'badge-green':($s==='Ongoing'?'badge-act':'badge-gray'); ?>
                <span class="badge <?php echo $bc; ?>"><?php echo htmlspecialchars($s); ?></span>
            </td>
            <td style="color:rgba(255,255,255,0.45);font-size:0.75rem;"><?php echo $r['date_time']?date('M j, Y g:i A',strtotime($r['date_time'])):'—'; ?></td>
            <td style="color:#60a5fa;font-size:0.75rem;font-variant-numeric:tabular-nums;"><?php echo $r['joined_at']?date('g:i A',strtotime($r['joined_at'])):'—'; ?></td>
            <td style="color:rgba(255,255,255,0.45);font-size:0.75rem;"><?php echo $r['joined_at']?date('M j, Y',strtotime($r['joined_at'])):'—'; ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    <div style="margin-top:0.75rem;font-size:0.7rem;color:rgba(255,255,255,0.28);">
        Showing <?php echo count($actLog); ?> most recent activity joins
    </div>
    <?php endif; ?>
</div>

<!-- ── PROJECT JOIN LOG ── -->
<div id="pane-projlog" style="display:none;">
    <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:0.75rem;flex-wrap:wrap;">
        <div style="position:relative;flex:1;min-width:200px;">
            <i class="ri-search-line" style="position:absolute;left:0.7rem;top:50%;transform:translateY(-50%);color:rgba(255,255,255,0.3);font-size:0.9rem;"></i>
            <input type="text" id="projSearch" class="pt-search" placeholder="Search by name, project, role..." oninput="filterTable('projLogTable',this.value)">
        </div>
        <span style="font-size:0.72rem;color:rgba(255,255,255,0.3);"><?php echo count($projLog); ?> records</span>
    </div>
    <?php if(empty($projLog)): ?>
    <div style="text-align:center;padding:3rem 0;">
        <i class="ri-folder-2-line" style="font-size:3rem;color:rgba(255,255,255,0.08);"></i>
        <p style="color:rgba(255,255,255,0.3);margin-top:0.75rem;font-size:0.875rem;">No project joins recorded yet.</p>
    </div>
    <?php else: ?>
    <div style="overflow-x:auto;">
    <table class="pt-table" id="projLogTable">
        <thead><tr>
            <th>#</th>
            <th>Participant</th>
            <th>Role</th>
            <th>Project</th>
            <th>Status</th>
            <th>Project Start</th>
            <th>Joined At</th>
            <th>Date Joined</th>
        </tr></thead>
        <tbody>
        <?php foreach($projLog as $i=>$r): ?>
        <tr>
            <td style="color:rgba(255,255,255,0.3);font-size:0.72rem;"><?php echo $i+1; ?></td>
            <td>
                <div style="display:flex;align-items:center;gap:8px;">
                    <div style="width:30px;height:30px;border-radius:8px;background:linear-gradient(135deg,#BF5AF2,#FF9F0A);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:0.75rem;color:white;flex-shrink:0;">
                        <?php echo strtoupper(substr($r['fullname']??'?',0,1)); ?>
                    </div>
                    <div>
                        <div style="font-weight:600;color:white;font-size:0.82rem;"><?php echo htmlspecialchars($r['fullname']??'Unknown'); ?></div>
                        <div style="font-size:0.68rem;color:rgba(255,255,255,0.35);"><?php echo htmlspecialchars($r['email']??'—'); ?></div>
                    </div>
                </div>
            </td>
            <td><span class="badge badge-role"><?php echo htmlspecialchars($r['role']??'—'); ?></span></td>
            <td style="font-weight:600;color:white;"><?php echo htmlspecialchars($r['project_name']??'—'); ?></td>
            <td>
                <?php $s=$r['project_status']??'';
                $bc=$s==='Completed'?'badge-green':($s==='Active'?'badge-proj':'badge-gray'); ?>
                <span class="badge <?php echo $bc; ?>"><?php echo htmlspecialchars($s); ?></span>
            </td>
            <td style="color:rgba(255,255,255,0.45);font-size:0.75rem;"><?php echo $r['start_date']?date('M j, Y',strtotime($r['start_date'])):'—'; ?></td>
            <td style="color:#c084fc;font-size:0.75rem;font-variant-numeric:tabular-nums;"><?php echo $r['joined_at']?date('g:i A',strtotime($r['joined_at'])):'—'; ?></td>
            <td style="color:rgba(255,255,255,0.45);font-size:0.75rem;"><?php echo $r['joined_at']?date('M j, Y',strtotime($r['joined_at'])):'—'; ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    <div style="margin-top:0.75rem;font-size:0.7rem;color:rgba(255,255,255,0.28);">
        Showing <?php echo count($projLog); ?> most recent project joins
    </div>
    <?php endif; ?>
</div>

<!-- ── ACTIVITY SUMMARY ── -->
<div id="pane-actsum" style="display:none;">
    <div class="section-heading"><i class="ri-bar-chart-line" style="color:#0A84FF"></i> Activity Participation Summary</div>
    <?php if(empty($actSummary)): ?>
    <div style="text-align:center;padding:3rem 0;color:rgba(255,255,255,0.3);">No activities found.</div>
    <?php else: ?>
    <div style="overflow-x:auto;">
    <table class="pt-table">
        <thead><tr>
            <th>Activity Name</th>
            <th>Status</th>
            <th>Activity Date</th>
            <th>Total Joined</th>
            <th>First Join</th>
            <th>Last Join</th>
            <th>Trend</th>
        </tr></thead>
        <tbody>
        <?php
        $maxJoined = max(array_column($actSummary,'total_joined') ?: [1]);
        foreach($actSummary as $r):
            $pct = $maxJoined > 0 ? round(($r['total_joined']/$maxJoined)*100) : 0;
        ?>
        <tr>
            <td style="font-weight:600;color:white;"><?php echo htmlspecialchars($r['name']??'—'); ?></td>
            <td>
                <?php $s=$r['status']??'';
                $bc=$s==='Completed'?'badge-green':($s==='Ongoing'?'badge-act':'badge-gray'); ?>
                <span class="badge <?php echo $bc; ?>"><?php echo htmlspecialchars($s); ?></span>
            </td>
            <td style="color:rgba(255,255,255,0.45);font-size:0.75rem;"><?php echo $r['date_time']?date('M j, Y',strtotime($r['date_time'])):'—'; ?></td>
            <td>
                <span style="font-size:1.2rem;font-weight:800;color:white;"><?php echo $r['total_joined']; ?></span>
                <span style="font-size:0.68rem;color:rgba(255,255,255,0.35);margin-left:4px;">participant<?php echo $r['total_joined']!=1?'s':''; ?></span>
            </td>
            <td style="color:rgba(255,255,255,0.45);font-size:0.75rem;"><?php echo $r['first_join']?date('M j, Y g:i A',strtotime($r['first_join'])):'—'; ?></td>
            <td style="color:rgba(255,255,255,0.45);font-size:0.75rem;"><?php echo $r['last_join']?date('M j, Y g:i A',strtotime($r['last_join'])):'—'; ?></td>
            <td style="min-width:120px;">
                <div style="background:rgba(255,255,255,0.06);border-radius:20px;height:8px;overflow:hidden;">
                    <div style="background:linear-gradient(90deg,#0A84FF,#BF5AF2);height:100%;width:<?php echo $pct; ?>%;border-radius:20px;transition:width 0.5s;"></div>
                </div>
                <div style="font-size:0.62rem;color:rgba(255,255,255,0.3);margin-top:3px;text-align:right;"><?php echo $pct; ?>%</div>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    <?php endif; ?>
</div>

<!-- ── PROJECT SUMMARY ── -->
<div id="pane-projsum" style="display:none;">
    <div class="section-heading"><i class="ri-pie-chart-line" style="color:#BF5AF2"></i> Project Participation Summary</div>
    <?php if(empty($projSummary)): ?>
    <div style="text-align:center;padding:3rem 0;color:rgba(255,255,255,0.3);">No projects found.</div>
    <?php else: ?>
    <div style="overflow-x:auto;">
    <table class="pt-table">
        <thead><tr>
            <th>Project Name</th>
            <th>Status</th>
            <th>Start Date</th>
            <th>Total Joined</th>
            <th>First Join</th>
            <th>Last Join</th>
            <th>Trend</th>
        </tr></thead>
        <tbody>
        <?php
        $maxJoined = max(array_column($projSummary,'total_joined') ?: [1]);
        foreach($projSummary as $r):
            $pct = $maxJoined > 0 ? round(($r['total_joined']/$maxJoined)*100) : 0;
        ?>
        <tr>
            <td style="font-weight:600;color:white;"><?php echo htmlspecialchars($r['name']??'—'); ?></td>
            <td>
                <?php $s=$r['status']??'';
                $bc=$s==='Completed'?'badge-green':($s==='Active'?'badge-proj':'badge-gray'); ?>
                <span class="badge <?php echo $bc; ?>"><?php echo htmlspecialchars($s); ?></span>
            </td>
            <td style="color:rgba(255,255,255,0.45);font-size:0.75rem;"><?php echo $r['start_date']?date('M j, Y',strtotime($r['start_date'])):'—'; ?></td>
            <td>
                <span style="font-size:1.2rem;font-weight:800;color:white;"><?php echo $r['total_joined']; ?></span>
                <span style="font-size:0.68rem;color:rgba(255,255,255,0.35);margin-left:4px;">participant<?php echo $r['total_joined']!=1?'s':''; ?></span>
            </td>
            <td style="color:rgba(255,255,255,0.45);font-size:0.75rem;"><?php echo $r['first_join']?date('M j, Y g:i A',strtotime($r['first_join'])):'—'; ?></td>
            <td style="color:rgba(255,255,255,0.45);font-size:0.75rem;"><?php echo $r['last_join']?date('M j, Y g:i A',strtotime($r['last_join'])):'—'; ?></td>
            <td style="min-width:120px;">
                <div style="background:rgba(255,255,255,0.06);border-radius:20px;height:8px;overflow:hidden;">
                    <div style="background:linear-gradient(90deg,#BF5AF2,#FF9F0A);height:100%;width:<?php echo $pct; ?>%;border-radius:20px;transition:width 0.5s;"></div>
                </div>
                <div style="font-size:0.62rem;color:rgba(255,255,255,0.3);margin-top:3px;text-align:right;"><?php echo $pct; ?>%</div>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    <?php endif; ?>
</div>

<script>
function switchTab(tab) {
    ['actlog','projlog','actsum','projsum'].forEach(t => {
        document.getElementById('pane-'+t).style.display = t===tab ? 'block' : 'none';
        document.getElementById('tab-'+t).classList.toggle('active', t===tab);
    });
}
function filterTable(tableId, q) {
    const t = document.getElementById(tableId);
    if(!t) return;
    const rows = t.querySelectorAll('tbody tr');
    const lq = q.toLowerCase();
    rows.forEach(r => {
        r.style.display = r.textContent.toLowerCase().includes(lq) ? '' : 'none';
    });
}
function exportCSV(type) {
    const tableId = type === 'activity' ? 'actLogTable' : 'projLogTable';
    const table = document.getElementById(tableId);
    if(!table) { alert('Switch to the ' + type + ' log tab first.'); return; }
    let csv = [];
    table.querySelectorAll('tr').forEach(row => {
        const cols = [...row.querySelectorAll('th,td')].map(c => '"' + c.innerText.replace(/"/g,'""').trim() + '"');
        csv.push(cols.join(','));
    });
    const blob = new Blob([csv.join('\n')], {type:'text/csv'});
    const a = document.createElement('a');
    a.href = URL.createObjectURL(blob);
    a.download = type + '_participants_<?php echo date("Y-m-d"); ?>.csv';
    a.click();
}
</script>