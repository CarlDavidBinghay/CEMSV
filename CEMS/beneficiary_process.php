<?php
ob_start();
session_start();
error_reporting(0);
ini_set('display_errors',0);
error_reporting(0);
ini_set('display_errors',0);

$is_ajax = isset($_POST['action']) && in_array($_POST['action'],['join_project','project_feedback','activity_feedback']);

if(!isset($_SESSION['user_id'])){
    if($is_ajax){header('Content-Type: application/json');echo json_encode(['status'=>'error','message'=>'Session expired.']);exit;}
    header('Location: login.php');exit;
}
if($_SESSION['role'] !== 'Beneficiary'){
    if($is_ajax){header('Content-Type: application/json');echo json_encode(['status'=>'error','message'=>'Access denied.']);exit;}
    header('Location: login.php');exit;
}

include_once 'db.php';
$action = $_POST['action'] ?? '';
$uid    = $_SESSION['user_id'];
$uname  = $_SESSION['fullname'] ?? '';

// Create Profile
if($action === 'create_profile'){
    $fullname = trim($_POST['fullname'] ?? '');
    $email    = trim($_POST['email']    ?? '');
    $phone    = trim($_POST['phone']    ?? '');
    $assign   = trim($_POST['assignments'] ?? '');
    $newpass  = $_POST['new_password']  ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';
    if($fullname===''||$phone===''||$assign===''){$_SESSION['profile_error']='Name, phone, and community are required.';header('Location: beneficiary.php?section=profile');exit;}
    if($newpass!==''&&$newpass!==$confirm){$_SESSION['profile_error']='Passwords do not match.';header('Location: beneficiary.php?section=profile');exit;}
    if($newpass!==''){$hashed=password_hash($newpass,PASSWORD_DEFAULT);$stmt=$conn->prepare("UPDATE users SET fullname=?,email=?,phone=?,assignments=?,password=? WHERE id=?");$stmt->bind_param("sssssi",$fullname,$email,$phone,$assign,$hashed,$uid);}
    else{$stmt=$conn->prepare("UPDATE users SET fullname=?,email=?,phone=?,assignments=? WHERE id=?");$stmt->bind_param("ssssi",$fullname,$email,$phone,$assign,$uid);}
    if($stmt&&$stmt->execute()){$_SESSION['fullname']=$fullname;$_SESSION['email']=$email;$_SESSION['phone']=$phone;$_SESSION['assignments']=$assign;$_SESSION['profile_success']='Profile created! Welcome to CEMS 🎉';header('Location: beneficiary.php?section=profile');}
    else{$_SESSION['profile_error']='Database error: '.$conn->error;header('Location: beneficiary.php?section=profile');}
    exit;
}

// Update Profile
if($action === 'update_profile'){
    $fullname = trim($_POST['fullname'] ?? '');
    $email    = trim($_POST['email']    ?? '');
    $phone    = trim($_POST['phone']    ?? '');
    $assign   = trim($_POST['assignments'] ?? '');
    $newpass  = $_POST['new_password']  ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';
    if($fullname===''){$_SESSION['profile_error']='Name is required.';header('Location: beneficiary.php?section=profile');exit;}
    if($newpass!==''&&$newpass!==$confirm){$_SESSION['profile_error']='Passwords do not match.';header('Location: beneficiary.php?section=profile');exit;}
    if($newpass!==''){$hashed=password_hash($newpass,PASSWORD_DEFAULT);$stmt=$conn->prepare("UPDATE users SET fullname=?,email=?,phone=?,assignments=?,password=? WHERE id=?");$stmt->bind_param("sssssi",$fullname,$email,$phone,$assign,$hashed,$uid);}
    else{$stmt=$conn->prepare("UPDATE users SET fullname=?,email=?,phone=?,assignments=? WHERE id=?");$stmt->bind_param("ssssi",$fullname,$email,$phone,$assign,$uid);}
    if($stmt&&$stmt->execute()){$_SESSION['fullname']=$fullname;$_SESSION['email']=$email;$_SESSION['phone']=$phone;$_SESSION['assignments']=$assign;$_SESSION['profile_success']='Profile updated successfully!';header('Location: beneficiary.php?section=profile');}
    else{$_SESSION['profile_error']='Database error: '.$conn->error;header('Location: beneficiary.php?section=profile');}
    exit;
}

// Join Project
if($action === 'join_project'){
    header('Content-Type: application/json');
    $project_id = intval($_POST['project_id'] ?? 0);
    if($project_id===0){echo json_encode(['status'=>'error','message'=>'Invalid project.']);exit;}
    $conn->query("CREATE TABLE IF NOT EXISTS project_participants (id INT AUTO_INCREMENT PRIMARY KEY, project_id INT NOT NULL, user_id INT NOT NULL, joined_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, UNIQUE KEY unique_join (project_id,user_id))");
    $chk=$conn->prepare("SELECT id FROM project_participants WHERE project_id=? AND user_id=?");
    if($chk){$chk->bind_param("ii",$project_id,$uid);$chk->execute();$chk->store_result();if($chk->num_rows>0){echo json_encode(['status'=>'error','message'=>'You have already joined this project.']);exit;}$chk->close();}
    $stmt=$conn->prepare("INSERT INTO project_participants (project_id,user_id) VALUES (?,?)");
    if($stmt){$stmt->bind_param("ii",$project_id,$uid);$stmt->execute();echo json_encode(['status'=>'success','message'=>'Successfully joined the project! 🎉']);}
    else echo json_encode(['status'=>'error','message'=>'Database error: '.$conn->error]);
    exit;
}

// Activity Feedback
if($action === 'activity_feedback'){
    ob_clean();
    header('Content-Type: application/json');
    $activity_id = intval($_POST['activity_id'] ?? 0);
    $feedback    = trim($_POST['feedback']      ?? '');
    if($activity_id===0||$feedback===''){echo json_encode(['status'=>'error','message'=>'Please select an activity and enter feedback.']);exit;}
    $actName='Activity #'.$activity_id;
    $an=$conn->prepare("SELECT name FROM activities WHERE id=?");
    if($an){$an->bind_param("i",$activity_id);$an->execute();$r=$an->get_result()->fetch_assoc();if($r)$actName=$r['name'];}
    $stmt=$conn->prepare("INSERT INTO evaluations (title,program,findings,evaluator,status,eval_date,created_at) VALUES (?,?,?,?,'Completed',CURDATE(),NOW())");
    if($stmt){
        $title='Beneficiary Activity Feedback';
        $stmt->bind_param("ssss",$title,$actName,$feedback,$uname);
        $stmt->execute();
        $devs=$conn->query("SELECT id FROM users WHERE role IN ('Developer','Director')");
        if($devs) while($dev=$devs->fetch_assoc()){
            $ns=$conn->prepare("INSERT INTO notifications (user_id,type,title,message,link) VALUES (?,?,?,?,?)");
            if($ns){$nt='feedback';$nth='New Feedback Submitted';$nm=$uname.' submitted feedback';$nl='dashboard.php?section=feedback_log';$ns->bind_param("issss",$dev['id'],$nt,$nth,$nm,$nl);$ns->execute();}
        }
        echo json_encode(['status'=>'success','message'=>'Feedback submitted successfully! ✅']);
    } else echo json_encode(['status'=>'error','message'=>'Database error: '.$conn->error]);
    exit;
}

// Project Feedback — returns JSON
if($action === 'project_feedback'){
    ob_clean();
    header('Content-Type: application/json');
    $project_id = intval($_POST['project_id'] ?? 0);
    $feedback   = trim($_POST['feedback']     ?? '');
    if($project_id===0||$feedback===''){echo json_encode(['status'=>'error','message'=>'Please select a project and enter feedback.']);exit;}
    $projName='Project #'.$project_id;
    $pn=$conn->prepare("SELECT name FROM projects WHERE id=?");
    if($pn){$pn->bind_param("i",$project_id);$pn->execute();$r=$pn->get_result()->fetch_assoc();if($r)$projName=$r['name'];}
    $stmt=$conn->prepare("INSERT INTO evaluations (title,program,findings,evaluator,status,eval_date,created_at) VALUES (?,?,?,?,'Completed',CURDATE(),NOW())");
    if($stmt){
        $title='Beneficiary Feedback';
        $stmt->bind_param("ssss",$title,$projName,$feedback,$uname);
        $stmt->execute();
        $devs=$conn->query("SELECT id FROM users WHERE role IN ('Developer','Director')");
        if($devs) while($dev=$devs->fetch_assoc()){
            $ns=$conn->prepare("INSERT INTO notifications (user_id,type,title,message,link) VALUES (?,?,?,?,?)");
            if($ns){$nt='feedback';$nth='New Feedback Submitted';$nm=$uname.' submitted feedback';$nl='dashboard.php?section=feedback_log';$ns->bind_param("issss",$dev['id'],$nt,$nth,$nm,$nl);$ns->execute();}
        }
        echo json_encode(['status'=>'success','message'=>'Feedback submitted successfully! ✅']);
    } else echo json_encode(['status'=>'error','message'=>'Database error: '.$conn->error]);
    exit;
}

// Fallback
header('Content-Type: application/json');
echo json_encode(['status'=>'error','message'=>'Unknown action: '.htmlspecialchars($action)]);