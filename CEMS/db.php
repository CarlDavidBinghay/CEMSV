<?php
$host   = 'localhost';
$dbname = 'cems_db';
$user   = 'root';
$pass   = '';   // default XAMPP has no password

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    // For API endpoints (JSON responses), check if we should return JSON
    if (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {
        header('Content-Type: application/json');
        die(json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $conn->connect_error]));
    }
    die("Database connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");