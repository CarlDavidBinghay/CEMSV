<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit();
}

include 'db.php';

// Get email - trim all whitespace including hidden chars
$email = trim(strip_tags($_POST['email'] ?? ''));
$email = filter_var($email, FILTER_SANITIZE_EMAIL);

if (empty($email)) {
    $_SESSION['recover_error'] = 'Please enter your email address.';
    $_SESSION['show_recover']  = true;
    header("Location: login.php");
    exit();
}

// Check if email exists in DB (case-insensitive)
$stmt = $conn->prepare("SELECT id, fullname, role FROM users WHERE LOWER(email) = LOWER(?)");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['recover_error'] = 'No account found with that email address. Please check and try again.';
    $_SESSION['show_recover']  = true;
    header("Location: login.php");
    exit();
}

$user = $result->fetch_assoc();

// Generate secure reset token
$token     = bin2hex(random_bytes(32));
$expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));

// Auto-create password_resets table if missing
$conn->query("CREATE TABLE IF NOT EXISTS password_resets (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    user_id    INT NOT NULL,
    token      VARCHAR(100) NOT NULL,
    expires_at DATETIME NOT NULL,
    used       TINYINT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_token (token),
    INDEX idx_user  (user_id)
)");

// Delete old tokens for this user
$del = $conn->prepare("DELETE FROM password_resets WHERE user_id = ?");
$del->bind_param("i", $user['id']);
$del->execute();

// Insert new token
$ins = $conn->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (?,?,?)");
$ins->bind_param("iss", $user['id'], $token, $expiresAt);
$ins->execute();

// Build reset link
$resetLink = "http://localhost/cems/reset_password.php?token=" . urlencode($token);

$_SESSION['recover_success'] = 'Reset link generated for <strong>' . htmlspecialchars($user['fullname']) . '</strong>. Click the link below:';
$_SESSION['recover_link']    = $resetLink;
$_SESSION['show_recover']    = true;

header("Location: login.php");
exit();