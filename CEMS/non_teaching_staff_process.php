<?php
ob_start();
session_start();
error_reporting(0);
ini_set('display_errors',0);

// For AJAX requests, always return JSON even on auth failure
$is_ajax = isset($_POST['action']) && in_array($_POST['action'], ['join_activity','join_project','activity_feedback','project_feedback']);

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Non-Teaching Staff'){
    if($is_ajax){
        header('Content-Type: application/json');
        echo json_encode(['status'=>'error','message'=>'Session expired. Please log in again.']);
        exit;
    }
    header('Location: login.php');
    exit;
}

// Suppress errors from outputting before JSON
error_reporting(0);
ini_set('display_errors', 0);

include 'db.php';

$action = $_POST['action'] ?? '';
$uid    = $_SESSION['user_id'] ?? 0;

// ── Create Profile (wizard) ──
if($action === 'create_profile') {
    $fullname = trim($_POST['fullname']      ?? '');
    $email    = trim($_POST['email']         ?? '');
    $phone    = trim($_POST['phone']         ?? '');
    $assign   = trim($_POST['assignments']   ?? '');
    $newpass  = $_POST['new_password']       ?? '';
    $confirm  = $_POST['confirm_password']   ?? '';

    if($fullname === '' || $phone === '' || $assign === '') {
        $_SESSION['profile_error'] = 'Name, phone, and department are required.';
        header('Location: non_teaching_staff.php?section=profile');
        exit;
    }
    if($newpass !== '' && $newpass !== $confirm) {
        $_SESSION['profile_error'] = 'Passwords do not match.';
        header('Location: non_teaching_staff.php?section=profile');
        exit;
    }
    if($newpass !== '') {
        $hashed = password_hash($newpass, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET fullname=?,email=?,phone=?,assignments=?,password=? WHERE id=?");
        $stmt->bind_param("sssssi", $fullname, $email, $phone, $assign, $hashed, $uid);
    } else {
        $stmt = $conn->prepare("UPDATE users SET fullname=?,email=?,phone=?,assignments=? WHERE id=?");
        $stmt->bind_param("ssssi", $fullname, $email, $phone, $assign, $uid);
    }
    if($stmt && $stmt->execute()) {
        $_SESSION['fullname']    = $fullname;
        $_SESSION['email']       = $email;
        $_SESSION['phone']       = $phone;
        $_SESSION['assignments'] = $assign;
        $_SESSION['profile_success'] = 'Profile created successfully! All features are now unlocked. 🎉';
        header('Location: non_teaching_staff.php?section=profile');
    } else {
        $_SESSION['profile_error'] = 'Database error: ' . $conn->error;
        header('Location: non_teaching_staff.php?section=profile');
    }
    exit;
}

// ── Update Profile ──
if($action === 'update_profile') {
    $id       = $_SESSION['user_id'];
    $fullname = trim($_POST['fullname'] ?? '');
    $email    = trim($_POST['email']    ?? '');
    $phone    = trim($_POST['phone']    ?? '');
    $assign   = trim($_POST['assignments'] ?? '');
    $newpass  = $_POST['new_password']    ?? '';
    $confirm  = $_POST['confirm_password']?? '';

    if($fullname === '') {
        $_SESSION['profile_error'] = 'Name is required.';
        header("Location: non_teaching_staff.php?section=profile");
        exit;
    }

    if($newpass !== '') {
        if($newpass !== $confirm) {
            $_SESSION['profile_error'] = 'Passwords do not match.';
            header("Location: non_teaching_staff.php?section=profile");
            exit;
        }
        $hashed = password_hash($newpass, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET fullname=?,email=?,phone=?,assignments=?,password=? WHERE id=?");
        $stmt->bind_param("sssssi",$fullname,$email,$phone,$assign,$hashed,$id);
    } else {
        $stmt = $conn->prepare("UPDATE users SET fullname=?,email=?,phone=?,assignments=? WHERE id=?");
        $stmt->bind_param("ssssi",$fullname,$email,$phone,$assign,$id);
    }
    $stmt->execute();

    $_SESSION['fullname']    = $fullname;
    $_SESSION['email']       = $email;
    $_SESSION['phone']       = $phone;
    $_SESSION['assignments'] = $assign;
    $_SESSION['profile_success'] = 'Profile updated successfully!';
    header("Location: non_teaching_staff.php?section=profile");
    exit;
}

// ── Join Activity ──
if($action === 'join_activity') {
    header('Content-Type: application/json');
    $activity_id = intval($_POST['activity_id'] ?? 0);
    $user_id     = $_SESSION['user_id'];
    $username    = $_SESSION['fullname'] ?? '';

    if($activity_id === 0) {
        echo json_encode(['status'=>'error','message'=>'Invalid activity ID.']);
        exit;
    }

    // Try to create participants table if not exists
    $conn->query("CREATE TABLE IF NOT EXISTS activity_participants (
        id INT AUTO_INCREMENT PRIMARY KEY,
        activity_id INT NOT NULL,
        user_id INT NOT NULL,
        joined_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY unique_join (activity_id, user_id)
    )");

    // Check if already joined
    $chk = $conn->prepare("SELECT id FROM activity_participants WHERE activity_id=? AND user_id=?");
    if($chk) {
        $chk->bind_param("ii", $activity_id, $user_id);
        $chk->execute();
        $chk->store_result();
        if($chk->num_rows > 0) {
            echo json_encode(['status'=>'error','message'=>'You have already joined this activity.']);
            exit;
        }
        $chk->close();
    }

    $stmt = $conn->prepare("INSERT INTO activity_participants (activity_id, user_id) VALUES (?,?)");
    if($stmt) {
        $stmt->bind_param("ii", $activity_id, $user_id);
        $stmt->execute();
        echo json_encode(['status'=>'success','message'=>'Successfully joined the activity! 🎉']);
    } else {
        echo json_encode(['status'=>'error','message'=>'Database error: '.$conn->error]);
    }
    exit;
}

// ── Join Project ──
if($action === 'join_project') {
    header('Content-Type: application/json');
    $project_id = intval($_POST['project_id'] ?? 0);
    $user_id    = $_SESSION['user_id'];

    if($project_id === 0) {
        echo json_encode(['status'=>'error','message'=>'Invalid project ID.']);
        exit;
    }

    // Try to create participants table if not exists
    $conn->query("CREATE TABLE IF NOT EXISTS project_participants (
        id INT AUTO_INCREMENT PRIMARY KEY,
        project_id INT NOT NULL,
        user_id INT NOT NULL,
        joined_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY unique_join (project_id, user_id)
    )");

    // Check if already joined
    $chk = $conn->prepare("SELECT id FROM project_participants WHERE project_id=? AND user_id=?");
    if($chk) {
        $chk->bind_param("ii", $project_id, $user_id);
        $chk->execute();
        $chk->store_result();
        if($chk->num_rows > 0) {
            echo json_encode(['status'=>'error','message'=>'You have already joined this project.']);
            exit;
        }
        $chk->close();
    }

    $stmt = $conn->prepare("INSERT INTO project_participants (project_id, user_id) VALUES (?,?)");
    if($stmt) {
        $stmt->bind_param("ii", $project_id, $user_id);
        $stmt->execute();
        echo json_encode(['status'=>'success','message'=>'Successfully joined the project! 🎉']);
    } else {
        echo json_encode(['status'=>'error','message'=>'Database error: '.$conn->error]);
    }
    exit;
}

// ── Activity Feedback ──
if($action === 'activity_feedback') {
    header('Content-Type: application/json');
    $activity_id = intval($_POST['activity_id'] ?? 0);
    $feedback    = trim($_POST['feedback']      ?? '');
    $username    = $_SESSION['fullname']        ?? '';

    if($activity_id === 0 || $feedback === '') {
        echo json_encode(['status'=>'error','message'=>'Please select an activity and enter feedback.']);
        exit;
    }

    $actName = 'Activity #'.$activity_id;
    $an = $conn->prepare("SELECT name FROM activities WHERE id=?");
    if($an){ $an->bind_param("i",$activity_id); $an->execute(); $r=$an->get_result()->fetch_assoc(); if($r) $actName=$r['name']; }

    $stmt = $conn->prepare("INSERT INTO evaluations (title, program, findings, evaluator, status, eval_date, created_at) VALUES (?,?,?,?,'Completed',CURDATE(),NOW())");
    if($stmt) {
        $title = 'Activity Feedback';
        $stmt->bind_param("ssss", $title, $actName, $feedback, $username);
        $stmt->execute();
        
    // Push notification to Developer/Director users
    $devs = $conn->query("SELECT id FROM users WHERE role IN ('Developer','Director')");
    if($devs) while($dev=$devs->fetch_assoc()){
        $nstmt=$conn->prepare("INSERT INTO notifications (user_id,type,title,message,link) VALUES (?,?,?,?,?)");
        if($nstmt){
            $ntype='feedback'; $ntitle='New Feedback Submitted';
            $nmsg=htmlspecialchars($uname).' submitted feedback';
            $nlink='dashboard.php?section=feedback_log';
            $nstmt->bind_param("issss",$dev['id'],$ntype,$ntitle,$nmsg,$nlink);
            $nstmt->execute();
        }
    }

    echo json_encode(['status'=>'success','message'=>'Activity feedback submitted successfully!']);
    } else {
        echo json_encode(['status'=>'error','message'=>'Could not save feedback: '.$conn->error]);
    }
    exit;
}

// ── Project Feedback ──
if($action === 'project_feedback') {
    header('Content-Type: application/json');
    $project_id = intval($_POST['project_id'] ?? 0);
    $feedback   = trim($_POST['feedback']     ?? '');
    $username   = $_SESSION['fullname']       ?? '';

    if($project_id === 0 || $feedback === '') {
        echo json_encode(['status'=>'error','message'=>'Please select a project and enter feedback.']);
        exit;
    }

    $projName = 'Project #'.$project_id;
    $pn = $conn->prepare("SELECT name FROM projects WHERE id=?");
    if($pn){ $pn->bind_param("i",$project_id); $pn->execute(); $r=$pn->get_result()->fetch_assoc(); if($r) $projName=$r['name']; }

    $stmt = $conn->prepare("INSERT INTO evaluations (title, program, findings, evaluator, status, eval_date, created_at) VALUES (?,?,?,?,'Completed',CURDATE(),NOW())");
    if($stmt) {
        $title = 'Project Feedback';
        $stmt->bind_param("ssss", $title, $projName, $feedback, $username);
        $stmt->execute();
        
    // Push notification to Developer/Director users
    $devs = $conn->query("SELECT id FROM users WHERE role IN ('Developer','Director')");
    if($devs) while($dev=$devs->fetch_assoc()){
        $nstmt=$conn->prepare("INSERT INTO notifications (user_id,type,title,message,link) VALUES (?,?,?,?,?)");
        if($nstmt){
            $ntype='feedback'; $ntitle='New Feedback Submitted';
            $nmsg=htmlspecialchars($uname).' submitted feedback';
            $nlink='dashboard.php?section=feedback_log';
            $nstmt->bind_param("issss",$dev['id'],$ntype,$ntitle,$nmsg,$nlink);
            $nstmt->execute();
        }
    }

    echo json_encode(['status'=>'success','message'=>'Project feedback submitted successfully!']);
    } else {
        echo json_encode(['status'=>'error','message'=>'Could not save feedback: '.$conn->error]);
    }
    exit;
}

// Fallback
header('Content-Type: application/json');
echo json_encode(['status'=>'error','message'=>'Invalid action: '.$action]);