<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit();
}

include 'db.php';

$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    $_SESSION['login_error'] = 'Please enter your email and password.';
    $_SESSION['old_email']   = $email;
    header("Location: login.php");
    exit();
}

$stmt = $conn->prepare("SELECT id, fullname, password, role, email, phone, assignments FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['login_error'] = 'No account found with that email.';
    $_SESSION['old_email']   = $email;
    header("Location: login.php");
    exit();
}

$user = $result->fetch_assoc();

if (!password_verify($password, $user['password'])) {
    $_SESSION['login_error'] = 'Incorrect password. Please try again.';
    $_SESSION['old_email']   = $email;
    header("Location: login.php");
    exit();
}

// Store session
$_SESSION['user_id']     = $user['id'];
$_SESSION['fullname']    = $user['fullname'];
$_SESSION['role']        = $user['role'];
$_SESSION['email']       = $user['email']       ?? '';
$_SESSION['phone']       = $user['phone']       ?? '';
$_SESSION['assignments'] = $user['assignments'] ?? '';

// Route by exact DB role values
$role = $user['role'];

if ($role === 'Developer' || $role === 'Director' || $role === 'Admin' || $role === 'admin') {
    header("Location: dashboard.php");
    exit();
}

if ($role === 'Dept. Coordinator') {
    header("Location: coordinator.php");
    exit();
}

if ($role === 'Teaching Staff') {
    header("Location: teaching_staff.php");
    exit();
}

if ($role === 'Non-Teaching Staff') {
    header("Location: non_teaching_staff.php");
    exit();
}

// Donor / Partner
if ($role === 'Donor/Partner' || $role === 'Donor') {
    header("Location: donor.php");
    exit();
}

// Beneficiary
if ($role === 'Beneficiary') {
    header("Location: beneficiary.php");
    exit();
}

// Student
if ($role === 'Student') {
    header("Location: student.php");
    exit();
}

// Role not mapped — show exact role for debugging
$_SESSION['login_error'] = 'Access denied. Role "' . htmlspecialchars($role) . '" has no portal assigned yet.';
$_SESSION['old_email']   = $email;
header("Location: login.php");
exit();