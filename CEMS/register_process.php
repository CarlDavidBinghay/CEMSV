<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit();
}

include 'db.php';

$fullname         = trim($_POST['fullname']         ?? '');
$email            = trim($_POST['email']            ?? '');
$password         = $_POST['password']              ?? '';
$confirm_password = $_POST['confirm_password']      ?? '';
$role             = trim($_POST['role']             ?? 'Beneficiary');

// Helper — go back to register with error
function backWithError($msg, $fullname, $email) {
    $_SESSION['register_error'] = $msg;
    $_SESSION['old_fullname']   = $fullname;
    $_SESSION['old_reg_email']  = $email;
    header("Location: register.php");
    exit();
}

// Validation
if (empty($fullname) || empty($email) || empty($password) || empty($confirm_password)) {
    backWithError('Please fill in all fields.', $fullname, $email);
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    backWithError('Please enter a valid email address.', $fullname, $email);
}
if (strlen($password) < 6) {
    backWithError('Password must be at least 6 characters.', $fullname, $email);
}
if ($password !== $confirm_password) {
    backWithError('Passwords do not match.', $fullname, $email);
}

// Only allow safe self-registration roles
$allowed_roles = ['Teaching Staff', 'Non-Teaching Staff', 'Student', 'Donor/Partner', 'Beneficiary'];
if (!in_array($role, $allowed_roles)) {
    $role = 'Beneficiary';
}

// Check duplicate email
$check = $conn->prepare("SELECT id FROM users WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$check->store_result();
if ($check->num_rows > 0) {
    backWithError('An account with that email already exists.', $fullname, $email);
}
$check->close();

// Insert new user
$hashed = password_hash($password, PASSWORD_DEFAULT);
$stmt   = $conn->prepare("INSERT INTO users (fullname, email, password, role) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $fullname, $email, $hashed, $role);

if ($stmt->execute()) {
    $_SESSION['login_success'] = 'Account created successfully! You can now log in.';
    header("Location: login.php");
} else {
    backWithError('Registration failed: ' . $conn->error, $fullname, $email);
}
exit();