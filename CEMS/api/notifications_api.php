<?php
session_start();
error_reporting(0);
ini_set('display_errors',0);
header('Content-Type: application/json');

if(!isset($_SESSION['user_id'])){ echo json_encode(['status'=>'error','message'=>'Not logged in']); exit; }

include_once '../db.php';

// Auto-create table
$conn->query("CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    type VARCHAR(50) DEFAULT 'info',
    title VARCHAR(255) NOT NULL,
    message TEXT,
    link VARCHAR(255) DEFAULT NULL,
    is_read TINYINT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user (user_id),
    INDEX idx_read (is_read)
)");

$action  = $_GET['action'] ?? $_POST['action'] ?? 'get';
$uid     = $_SESSION['user_id'];
$role    = $_SESSION['role'] ?? '';

// ── GET notifications ──
if($action === 'get'){
    $limit = intval($_GET['limit'] ?? 20);
    $notifs = [];

    // Role-based notifications
    if(in_array($role, ['Developer','Director','Admin','admin'])){
        // Admin: new feedback, new joins today
        $newFeedback = $conn->query("SELECT COUNT(*) as c FROM evaluations WHERE DATE(created_at)=CURDATE()")->fetch_assoc()['c'] ?? 0;
        $newJoins    = $conn->query("SELECT COUNT(*) as c FROM activity_participants WHERE DATE(joined_at)=CURDATE()")->fetch_assoc()['c'] ?? 0;
        $newPJoins   = $conn->query("SELECT COUNT(*) as c FROM project_participants WHERE DATE(joined_at)=CURDATE()")->fetch_assoc()['c'] ?? 0;
        $newUsers    = $conn->query("SELECT COUNT(*) as c FROM users WHERE DATE(created_at)=CURDATE()")->fetch_assoc()['c'] ?? 0;

        if($newFeedback > 0) $notifs[] = ['id'=>'f'.date('Ymd'),'type'=>'feedback','title'=>'New Feedback Today','message'=>$newFeedback.' feedback submission'.($newFeedback!=1?'s':'').' received today','link'=>'dashboard.php?section=feedback_log','time'=>date('g:i A'),'read'=>false];
        if($newJoins+$newPJoins > 0) $notifs[] = ['id'=>'j'.date('Ymd'),'type'=>'join','title'=>'New Participants Today','message'=>($newJoins+$newPJoins).' user'.($newJoins+$newPJoins!=1?'s':'').' joined activities/projects','link'=>'dashboard.php?section=participants','time'=>date('g:i A'),'read'=>false];
        if($newUsers > 0) $notifs[] = ['id'=>'u'.date('Ymd'),'type'=>'user','title'=>'New Registrations','message'=>$newUsers.' new user'.($newUsers!=1?'s':'').' registered today','link'=>'dashboard.php?section=staff','time'=>date('g:i A'),'read'=>false];
    }

    // All users: upcoming activities
    $upcoming = $conn->query("SELECT name, date_time FROM activities WHERE status='Upcoming' AND date_time >= NOW() ORDER BY date_time ASC LIMIT 3");
    if($upcoming) while($r=$upcoming->fetch_assoc()){
        $notifs[] = ['id'=>'act'.$r['name'],'type'=>'activity','title'=>'Upcoming Activity','message'=>htmlspecialchars($r['name']).' — '.date('M j, g:i A',strtotime($r['date_time'])),'link'=>'','time'=>'','read'=>false];
    }

    // Personal: from DB notifications table
    $stmt = $conn->prepare("SELECT * FROM notifications WHERE user_id=? ORDER BY created_at DESC LIMIT ?");
    $stmt->bind_param("ii",$uid,$limit);
    $stmt->execute();
    $res=$stmt->get_result();
    while($r=$res->fetch_assoc()){
        $notifs[] = ['id'=>$r['id'],'type'=>$r['type'],'title'=>$r['title'],'message'=>$r['message'],'link'=>$r['link']??'','time'=>date('g:i A',strtotime($r['created_at'])),'read'=>(bool)$r['is_read']];
    }

    $unread = count(array_filter($notifs, fn($n)=>!$n['read']));
    echo json_encode(['status'=>'success','notifications'=>$notifs,'unread'=>$unread]);
    exit;
}

// ── MARK ALL READ ──
if($action === 'mark_read'){
    $conn->prepare("UPDATE notifications SET is_read=1 WHERE user_id=?")->bind_param("i",$uid) && $conn->prepare("UPDATE notifications SET is_read=1 WHERE user_id=?")->execute();
    $stmt=$conn->prepare("UPDATE notifications SET is_read=1 WHERE user_id=?");
    $stmt->bind_param("i",$uid);
    $stmt->execute();
    echo json_encode(['status'=>'success']);
    exit;
}

// ── PUSH notification (internal use) ──
if($action === 'push'){
    $target_uid = intval($_POST['user_id'] ?? 0);
    $type    = $_POST['type']    ?? 'info';
    $title   = $_POST['title']   ?? '';
    $message = $_POST['message'] ?? '';
    $link    = $_POST['link']    ?? '';
    if(!$title){ echo json_encode(['status'=>'error','message'=>'Title required']); exit; }
    $stmt=$conn->prepare("INSERT INTO notifications (user_id,type,title,message,link) VALUES (?,?,?,?,?)");
    $stmt->bind_param("issss",$target_uid,$type,$title,$message,$link);
    $stmt->execute();
    echo json_encode(['status'=>'success','id'=>$conn->insert_id]);
    exit;
}

echo json_encode(['status'=>'error','message'=>'Unknown action']);