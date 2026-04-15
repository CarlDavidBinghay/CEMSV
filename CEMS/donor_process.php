<?php
session_start();

// Suppress errors so they don't break JSON responses
error_reporting(0);
ini_set('display_errors', 0);

$is_ajax = isset($_POST['action']) && in_array($_POST['action'], ['fund_activity','fund_project','activity_feedback','project_feedback']);

if(!isset($_SESSION['user_id'])){
    if($is_ajax){ header('Content-Type: application/json'); echo json_encode(['status'=>'error','message'=>'Session expired. Please log in again.']); exit; }
    header('Location: login.php'); exit;
}

$allowed = ['Donor','Donor/Partner'];
if(!in_array($_SESSION['role'], $allowed)){
    if($is_ajax){ header('Content-Type: application/json'); echo json_encode(['status'=>'error','message'=>'Access denied.']); exit; }
    header('Location: login.php'); exit;
}

include_once 'db.php';

$action  = $_POST['action'] ?? '';
$uid     = $_SESSION['user_id'];
$uname   = $_SESSION['fullname'] ?? '';

// ── Create Profile (wizard) ──
if($action === 'create_profile') {
    $fullname = trim($_POST['fullname'] ?? '');
    $email    = trim($_POST['email']    ?? '');
    $phone    = trim($_POST['phone']    ?? '');
    $assign   = trim($_POST['assignments'] ?? '');
    $newpass  = $_POST['new_password']     ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    if($fullname === '' || $phone === '' || $assign === '') {
        $_SESSION['profile_error'] = 'Name, phone, and organization are required.';
        header('Location: donor.php?section=profile');
        exit;
    }

    if($newpass !== '' && $newpass !== $confirm) {
        $_SESSION['profile_error'] = 'Passwords do not match.';
        header('Location: donor.php?section=profile');
        exit;
    }

    if($newpass !== '') {
        $hashed = password_hash($newpass, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET fullname=?,email=?,phone=?,assignments=?,password=? WHERE id=?");
        $stmt->bind_param("sssssi",$fullname,$email,$phone,$assign,$hashed,$uid);
    } else {
        $stmt = $conn->prepare("UPDATE users SET fullname=?,email=?,phone=?,assignments=? WHERE id=?");
        $stmt->bind_param("ssssi",$fullname,$email,$phone,$assign,$uid);
    }

    if($stmt && $stmt->execute()) {
        $_SESSION['fullname']    = $fullname;
        $_SESSION['email']       = $email;
        $_SESSION['phone']       = $phone;
        $_SESSION['assignments'] = $assign;
        $_SESSION['profile_success'] = 'Profile created successfully! Welcome aboard 🎉';
        header('Location: donor.php?section=profile');
    } else {
        $_SESSION['profile_error'] = 'Database error: ' . $conn->error;
        header('Location: donor.php?section=profile');
    }
    exit;
}

// ── Update Profile ──
if($action === 'update_profile') {
    $fullname = trim($_POST['fullname'] ?? '');
    $email    = trim($_POST['email']    ?? '');
    $phone    = trim($_POST['phone']    ?? '');
    $assign   = trim($_POST['assignments'] ?? '');
    $newpass  = $_POST['new_password']     ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    if($fullname === '') {
        $_SESSION['profile_error'] = 'Name is required.';
        header('Location: donor.php?section=profile');
        exit;
    }

    if($newpass !== '' && $newpass !== $confirm) {
        $_SESSION['profile_error'] = 'Passwords do not match.';
        header('Location: donor.php?section=profile');
        exit;
    }

    if($newpass !== '') {
        $hashed = password_hash($newpass, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET fullname=?,email=?,phone=?,assignments=?,password=? WHERE id=?");
        $stmt->bind_param("sssssi",$fullname,$email,$phone,$assign,$hashed,$uid);
    } else {
        $stmt = $conn->prepare("UPDATE users SET fullname=?,email=?,phone=?,assignments=? WHERE id=?");
        $stmt->bind_param("ssssi",$fullname,$email,$phone,$assign,$uid);
    }

    if($stmt && $stmt->execute()) {
        $_SESSION['fullname']    = $fullname;
        $_SESSION['email']       = $email;
        $_SESSION['phone']       = $phone;
        $_SESSION['assignments'] = $assign;
        $_SESSION['profile_success'] = 'Profile updated successfully!';
        header('Location: donor.php?section=profile');
    } else {
        $_SESSION['profile_error'] = 'Database error: ' . $conn->error;
        header('Location: donor.php?section=profile');
    }
    exit;
}

// ── Fund Activity ──
if($action === 'fund_activity') {
    header('Content-Type: application/json');
    $activity_id = intval($_POST['activity_id'] ?? 0);
    $amount      = floatval($_POST['amount']     ?? 0);
    $note        = trim($_POST['note']           ?? '');

    if($activity_id === 0 || $amount <= 0) {
        echo json_encode(['status'=>'error','message'=>'Please enter a valid activity and amount.']);
        exit;
    }

    // Auto-create table if missing
    $conn->query("CREATE TABLE IF NOT EXISTS activity_sponsorships (
        id INT AUTO_INCREMENT PRIMARY KEY,
        activity_id INT NOT NULL,
        user_id INT NOT NULL,
        amount DECIMAL(15,2) NOT NULL DEFAULT 0,
        note TEXT DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $stmt = $conn->prepare("INSERT INTO activity_sponsorships (activity_id, user_id, amount, note) VALUES (?,?,?,?)");
    if($stmt) {
        $stmt->bind_param("iids", $activity_id, $uid, $amount, $note);
        $stmt->execute();
        echo json_encode(['status'=>'success','message'=>'Thank you! Your sponsorship of ₱' . number_format($amount, 2) . ' has been recorded. 💛']);
    } else {
        echo json_encode(['status'=>'error','message'=>'Database error: ' . $conn->error]);
    }
    exit;
}

// ── Fund Project ──
if($action === 'fund_project') {
    header('Content-Type: application/json');
    $project_id = intval($_POST['project_id'] ?? 0);
    $amount     = floatval($_POST['amount']    ?? 0);
    $note       = trim($_POST['note']          ?? '');

    if($project_id === 0 || $amount <= 0) {
        echo json_encode(['status'=>'error','message'=>'Please enter a valid project and amount.']);
        exit;
    }

    // Auto-create table if missing
    $conn->query("CREATE TABLE IF NOT EXISTS project_sponsorships (
        id INT AUTO_INCREMENT PRIMARY KEY,
        project_id INT NOT NULL,
        user_id INT NOT NULL,
        amount DECIMAL(15,2) NOT NULL DEFAULT 0,
        note TEXT DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $stmt = $conn->prepare("INSERT INTO project_sponsorships (project_id, user_id, amount, note) VALUES (?,?,?,?)");
    if($stmt) {
        $stmt->bind_param("iids", $project_id, $uid, $amount, $note);
        $stmt->execute();
        echo json_encode(['status'=>'success','message'=>'Thank you! Your sponsorship of ₱' . number_format($amount, 2) . ' has been recorded. 💛']);
    } else {
        echo json_encode(['status'=>'error','message'=>'Database error: ' . $conn->error]);
    }
    exit;
}

// ── Activity Feedback ──
if($action === 'activity_feedback') {
    header('Content-Type: application/json');
    $activity_id = intval($_POST['activity_id'] ?? 0);
    $feedback    = trim($_POST['feedback']      ?? '');

    if($activity_id === 0 || $feedback === '') {
        echo json_encode(['status'=>'error','message'=>'Please select an activity and enter feedback.']);
        exit;
    }

    $actName = 'Activity #' . $activity_id;
    $an = $conn->prepare("SELECT name FROM activities WHERE id=?");
    if($an){ $an->bind_param("i",$activity_id); $an->execute(); $r=$an->get_result()->fetch_assoc(); if($r) $actName=$r['name']; }

    $stmt = $conn->prepare("INSERT INTO evaluations (title, program, findings, evaluator, status, eval_date, created_at) VALUES (?,?,?,?,'Completed',CURDATE(),NOW())");
    if($stmt) {
        $title = 'Donor Activity Feedback';
        $stmt->bind_param("ssss",$title,$actName,$feedback,$uname);
        $stmt->execute();
        echo json_encode(['status'=>'success','message'=>'Feedback submitted successfully!']);
    } else {
        echo json_encode(['status'=>'error','message'=>'Database error: ' . $conn->error]);
    }
    exit;
}

// ── Project Feedback ──
if($action === 'project_feedback') {
    header('Content-Type: application/json');
    $project_id = intval($_POST['project_id'] ?? 0);
    $feedback   = trim($_POST['feedback']     ?? '');

    if($project_id === 0 || $feedback === '') {
        echo json_encode(['status'=>'error','message'=>'Please select a project and enter feedback.']);
        exit;
    }

    $projName = 'Project #' . $project_id;
    $pn = $conn->prepare("SELECT name FROM projects WHERE id=?");
    if($pn){ $pn->bind_param("i",$project_id); $pn->execute(); $r=$pn->get_result()->fetch_assoc(); if($r) $projName=$r['name']; }

    $stmt = $conn->prepare("INSERT INTO evaluations (title, program, findings, evaluator, status, eval_date, created_at) VALUES (?,?,?,?,'Completed',CURDATE(),NOW())");
    if($stmt) {
        $title = 'Donor Project Feedback';
        $stmt->bind_param("ssss",$title,$projName,$feedback,$uname);
        $stmt->execute();
        echo json_encode(['status'=>'success','message'=>'Feedback submitted successfully!']);
    } else {
        echo json_encode(['status'=>'error','message'=>'Database error: ' . $conn->error]);
    }
    exit;
}

// Fallback
header('Content-Type: application/json');
echo json_encode(['status'=>'error','message'=>'Unknown action: ' . htmlspecialchars($action)]);