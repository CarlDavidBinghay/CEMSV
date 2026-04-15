<?php
// sections/feedback_log.php — All Users Feedback Log
// Visible to: Developer, Director only
include_once 'db.php';

// Auto-create notifications table
$conn->query("CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    type VARCHAR(50) DEFAULT 'feedback',
    title VARCHAR(255) NOT NULL,
    message TEXT,
    is_read TINYINT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user (user_id),
    INDEX idx_read (is_read)
)");

// Stats
$totalFeedback  = $conn->query("SELECT COUNT(*) as c FROM evaluations")->fetch_assoc()['c'] ?? 0;
$todayFeedback  = $conn->query("SELECT COUNT(*) as c FROM evaluations WHERE DATE(created_at)=CURDATE()")->fetch_assoc()['c'] ?? 0;
$completedCount = $conn->query("SELECT COUNT(*) as c FROM evaluations WHERE status='Completed'")->fetch_assoc()['c'] ?? 0;
$uniqueEval     = $conn->query("SELECT COUNT(DISTINCT evaluator) as c FROM evaluations WHERE evaluator IS NOT NULL AND evaluator != ''")->fetch_assoc()['c'] ?? 0;

// All feedback with user info
$feedbacks = [];
$fq = $conn->query("
    SELECT e.*,
           u.role as user_role, u.email as user_email, u.assignments as user_dept
    FROM evaluations e
    LEFT JOIN users u ON u.fullname = e.evaluator
    ORDER BY e.created_at DESC
    LIMIT 500
");
if($fq) while($r=$fq->fetch_assoc()) $feedbacks[]=$r;

// Per-user feedback count
$userCounts = [];
$uq = $conn->query("SELECT evaluator, COUNT(*) as cnt FROM evaluations WHERE evaluator IS NOT NULL AND evaluator != '' GROUP BY evaluator ORDER BY cnt DESC LIMIT 20");
if($uq) while($r=$uq->fetch_assoc()) $userCounts[]=$r;
?>

<style>
.fb-stat{background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:14px;padding:1.1rem 1.25rem;display:flex;align-items:flex-start;justify-content:space-between;position:relative;overflow:hidden;}
.fb-stat::before{content:'';position:absolute;top:0;right:0;width:80px;height:80px;border-radius:50%;filter:blur(28px);}
.fb-stat.blue::before{background:rgba(10,132,255,0.18);}
.fb-stat.green::before{background:rgba(50,215,75,0.15);}
.fb-stat.purple::before{background:rgba(191,90,242,0.15);}
.fb-stat.orange::before{background:rgba(255,159,10,0.15);}
.fb-label{font-size:0.62rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:rgba(255,255,255,0.38);margin-bottom:0.3rem;}
.fb-value{font-size:1.75rem;font-weight:800;color:white;line-height:1;}
.fb-sub{font-size:0.62rem;color:rgba(255,255,255,0.32);margin-top:4px;}
.fb-icon{width:36px;height:36px;border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;color:white;flex-shrink:0;}
.fbtab-btn{padding:0.45rem 1.1rem;border-radius:8px;font-size:0.78rem;font-weight:600;cursor:pointer;border:1px solid rgba(255,255,255,0.1);background:transparent;color:rgba(255,255,255,0.4);font-family:inherit;transition:all 0.2s;display:flex;align-items:center;gap:5px;}
.fbtab-btn.active{background:rgba(10,132,255,0.18);border-color:rgba(10,132,255,0.35);color:white;}
.fbtab-btn:hover:not(.active){background:rgba(255,255,255,0.06);color:rgba(255,255,255,0.8);}
.fb-table{width:100%;border-collapse:collapse;}
.fb-table thead tr{background:rgba(0,0,0,0.25);}
.fb-table th{padding:0.6rem 0.9rem;font-size:0.62rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:rgba(255,255,255,0.38);text-align:left;white-space:nowrap;}
.fb-table td{padding:0.7rem 0.9rem;font-size:0.8rem;color:rgba(255,255,255,0.72);border-bottom:1px solid rgba(255,255,255,0.04);}
.fb-table tr:hover td{background:rgba(255,255,255,0.025);}
.badge{display:inline-flex;align-items:center;padding:0.18rem 0.6rem;border-radius:50px;font-size:0.65rem;font-weight:700;}
.badge-blue{background:rgba(10,132,255,0.15);color:#60a5fa;border:1px solid rgba(10,132,255,0.3);}
.badge-green{background:rgba(50,215,75,0.15);color:#4ade80;border:1px solid rgba(50,215,75,0.3);}
.badge-gray{background:rgba(255,255,255,0.06);color:rgba(255,255,255,0.4);border:1px solid rgba(255,255,255,0.1);}
.badge-role{background:rgba(255,159,10,0.12);color:#fbbf24;border:1px solid rgba(255,159,10,0.25);font-size:0.6rem;}
.fb-search{background:rgba(0,0,0,0.25);border:1px solid rgba(255,255,255,0.08);border-radius:8px;padding:0.5rem 0.9rem 0.5rem 2.1rem;font-size:0.8rem;color:white;outline:none;font-family:inherit;transition:all 0.2s;width:100%;}
.fb-search:focus{border-color:#0A84FF;box-shadow:0 0 0 2px rgba(10,132,255,0.12);}
.fb-search::placeholder{color:rgba(255,255,255,0.22);}
.export-btn{background:rgba(50,215,75,0.12);border:1px solid rgba(50,215,75,0.25);border-radius:8px;padding:6px 14px;color:#4ade80;font-size:0.75rem;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:5px;transition:all 0.2s;font-family:inherit;}
.export-btn:hover{background:rgba(50,215,75,0.22);}
</style>

<div style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:1rem;margin-bottom:1.5rem;">
    <div>
        <div style="display:inline-flex;align-items:center;gap:6px;background:rgba(10,132,255,0.12);border:1px solid rgba(10,132,255,0.25);border-radius:50px;padding:3px 12px;font-size:0.65rem;font-weight:700;color:rgba(255,255,255,0.6);letter-spacing:0.08em;text-transform:uppercase;margin-bottom:0.5rem;">
            <i class="ri-feedback-line"></i> FEEDBACK LOG &nbsp;•&nbsp; <?php echo $totalFeedback; ?> Total
        </div>
        <div style="font-size:1.75rem;font-weight:800;color:white;letter-spacing:-0.5px;">
            All Users Feedback <span style="color:rgba(255,255,255,0.35);font-weight:400;font-size:1.1rem;">/ Log</span>
        </div>
        <div style="font-size:0.75rem;color:rgba(255,255,255,0.38);margin-top:4px;"><i class="ri-sparkling-line" style="color:#0A84FF"></i> Complete feedback from all roles — auto-saved in real time</div>
    </div>
    <button class="export-btn" onclick="exportFeedbackCSV()"><i class="ri-download-line"></i> Export CSV</button>
</div>

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:0.75rem;margin-bottom:1.5rem;">
    <div class="fb-stat blue"><div><div class="fb-label">Total Feedback</div><div class="fb-value"><?php echo $totalFeedback; ?></div><div class="fb-sub">All time</div></div><div class="fb-icon" style="background:linear-gradient(135deg,#0A84FF,#0060c7)"><i class="ri-feedback-line"></i></div></div>
    <div class="fb-stat green"><div><div class="fb-label">Today</div><div class="fb-value"><?php echo $todayFeedback; ?></div><div class="fb-sub"><?php echo date('M j'); ?></div></div><div class="fb-icon" style="background:linear-gradient(135deg,#32D74B,#22a336)"><i class="ri-calendar-check-line"></i></div></div>
    <div class="fb-stat purple"><div><div class="fb-label">Unique Users</div><div class="fb-value"><?php echo $uniqueEval; ?></div><div class="fb-sub">Distinct submitters</div></div><div class="fb-icon" style="background:linear-gradient(135deg,#BF5AF2,#9030d0)"><i class="ri-group-line"></i></div></div>
    <div class="fb-stat orange"><div><div class="fb-label">Completed</div><div class="fb-value"><?php echo $completedCount; ?></div><div class="fb-sub">Status: Completed</div></div><div class="fb-icon" style="background:linear-gradient(135deg,#FF9F0A,#c97800)"><i class="ri-checkbox-circle-line"></i></div></div>
</div>

<div style="display:flex;gap:0.5rem;margin-bottom:1.25rem;flex-wrap:wrap;">
    <button class="fbtab-btn active" id="fbtab-all"  onclick="fbTab('all')"><i class="ri-list-check"></i> All Feedback</button>
    <button class="fbtab-btn"        id="fbtab-users" onclick="fbTab('users')"><i class="ri-user-line"></i> By User</button>
</div>

<!-- ALL FEEDBACK -->
<div id="fbpane-all">
    <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:0.75rem;flex-wrap:wrap;">
        <div style="position:relative;flex:1;min-width:200px;">
            <i class="ri-search-line" style="position:absolute;left:0.7rem;top:50%;transform:translateY(-50%);color:rgba(255,255,255,0.3);font-size:0.9rem;"></i>
            <input type="text" class="fb-search" placeholder="Search by user, program, title, findings..." oninput="filterFbTable(this.value)">
        </div>
        <span style="font-size:0.72rem;color:rgba(255,255,255,0.3);"><?php echo count($feedbacks); ?> records</span>
    </div>
    <?php if(empty($feedbacks)): ?>
    <div style="text-align:center;padding:3rem 0;">
        <i class="ri-feedback-line" style="font-size:3rem;color:rgba(255,255,255,0.08);"></i>
        <p style="color:rgba(255,255,255,0.3);margin-top:0.75rem;font-size:0.875rem;">No feedback submitted yet.</p>
    </div>
    <?php else: ?>
    <div style="overflow-x:auto;">
    <table class="fb-table" id="fbTable">
        <thead><tr>
            <th>#</th><th>Submitted By</th><th>Role</th><th>Program/Activity</th>
            <th>Title</th><th>Findings</th><th>Status</th><th>Date</th>
        </tr></thead>
        <tbody>
        <?php foreach($feedbacks as $i=>$r): ?>
        <tr>
            <td style="color:rgba(255,255,255,0.3);font-size:0.72rem;"><?php echo $i+1; ?></td>
            <td>
                <div style="display:flex;align-items:center;gap:8px;">
                    <div style="width:30px;height:30px;border-radius:8px;background:linear-gradient(135deg,#0A84FF,#BF5AF2);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:0.75rem;color:white;flex-shrink:0;">
                        <?php echo strtoupper(substr($r['evaluator']??'?',0,1)); ?>
                    </div>
                    <div>
                        <div style="font-weight:600;color:white;font-size:0.82rem;"><?php echo htmlspecialchars($r['evaluator']??'Unknown'); ?></div>
                        <div style="font-size:0.68rem;color:rgba(255,255,255,0.35);"><?php echo htmlspecialchars($r['user_email']??'—'); ?></div>
                    </div>
                </div>
            </td>
            <td><?php if($r['user_role']): ?><span class="badge badge-role"><?php echo htmlspecialchars($r['user_role']); ?></span><?php else: ?><span style="color:rgba(255,255,255,0.3);">—</span><?php endif; ?></td>
            <td style="font-weight:600;color:white;max-width:140px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?php echo htmlspecialchars($r['program']??'—'); ?></td>
            <td style="color:rgba(255,255,255,0.7);"><?php echo htmlspecialchars($r['title']??'—'); ?></td>
            <td style="max-width:200px;color:rgba(255,255,255,0.5);"><?php echo htmlspecialchars(substr($r['findings']??'',0,60)).(strlen($r['findings']??'')>60?'...':''); ?></td>
            <td><?php $s=$r['status']??''; echo '<span class="badge '.($s==='Completed'?'badge-green':'badge-gray').'">'.htmlspecialchars($s).'</span>'; ?></td>
            <td style="color:rgba(255,255,255,0.4);font-size:0.72rem;white-space:nowrap;"><?php echo $r['created_at']?date('M j, Y g:i A',strtotime($r['created_at'])):'—'; ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    <?php endif; ?>
</div>

<!-- BY USER -->
<div id="fbpane-users" style="display:none;">
    <div style="font-size:0.7rem;font-weight:700;text-transform:uppercase;letter-spacing:0.12em;color:rgba(255,255,255,0.25);margin-bottom:0.75rem;">Top Feedback Contributors</div>
    <?php if(empty($userCounts)): ?>
    <p style="color:rgba(255,255,255,0.3);">No data yet.</p>
    <?php else: ?>
    <?php $maxCount = max(array_column($userCounts,'cnt')); ?>
    <div style="display:flex;flex-direction:column;gap:0.6rem;">
    <?php foreach($userCounts as $uc): ?>
    <div style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);border-radius:10px;padding:0.75rem 1rem;display:flex;align-items:center;gap:12px;">
        <div style="width:36px;height:36px;border-radius:9px;background:linear-gradient(135deg,#0A84FF,#BF5AF2);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:0.875rem;color:white;flex-shrink:0;">
            <?php echo strtoupper(substr($uc['evaluator'],0,1)); ?>
        </div>
        <div style="flex:1;min-width:0;">
            <div style="font-weight:700;color:white;font-size:0.85rem;"><?php echo htmlspecialchars($uc['evaluator']); ?></div>
            <div style="background:rgba(255,255,255,0.06);border-radius:20px;height:6px;overflow:hidden;margin-top:5px;">
                <div style="background:linear-gradient(90deg,#0A84FF,#BF5AF2);height:100%;width:<?php echo round(($uc['cnt']/$maxCount)*100); ?>%;border-radius:20px;transition:width 0.5s;"></div>
            </div>
        </div>
        <div style="text-align:right;flex-shrink:0;">
            <div style="font-size:1.1rem;font-weight:800;color:white;"><?php echo $uc['cnt']; ?></div>
            <div style="font-size:0.62rem;color:rgba(255,255,255,0.35);">submission<?php echo $uc['cnt']!=1?'s':''; ?></div>
        </div>
    </div>
    <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<script>
function fbTab(tab){
    ['all','users'].forEach(t=>{
        document.getElementById('fbpane-'+t).style.display=t===tab?'block':'none';
        document.getElementById('fbtab-'+t).classList.toggle('active',t===tab);
    });
}
function filterFbTable(q){
    const t=document.getElementById('fbTable');if(!t)return;
    const lq=q.toLowerCase();
    t.querySelectorAll('tbody tr').forEach(r=>{r.style.display=r.textContent.toLowerCase().includes(lq)?'':'none';});
}
function exportFeedbackCSV(){
    const t=document.getElementById('fbTable');if(!t){alert('No data.');return;}
    let csv=[];
    t.querySelectorAll('tr').forEach(r=>{
        const cols=[...r.querySelectorAll('th,td')].map(c=>'"'+c.innerText.replace(/"/g,'""').trim()+'"');
        csv.push(cols.join(','));
    });
    const a=document.createElement('a');
    a.href=URL.createObjectURL(new Blob([csv.join('\n')],{type:'text/csv'}));
    a.download='feedback_log_<?php echo date("Y-m-d"); ?>.csv';
    a.click();
}
</script>