<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
header('Content-Type: application/json');
ob_start();

try {
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    $dbPath = __DIR__ . '/../db.php';
    if (!file_exists($dbPath)) $dbPath = __DIR__ . '/db.php';
    if (!file_exists($dbPath)) {
        ob_end_clean();
        echo json_encode(['status' => 'error', 'message' => 'db.php not found']);
        exit;
    }
    include $dbPath;
    ob_clean();

    // ========== GET: list all beneficiaries ==========
    if (isset($_GET['action']) && $_GET['action'] === 'get') {
        $result = $conn->query("SELECT * FROM beneficiaries ORDER BY created_at DESC");
        $rows = [];
        while ($row = $result->fetch_assoc()) $rows[] = $row;
        echo json_encode($rows);
        exit;
    }

    // ========== POST: save (insert) ==========
    if (isset($_POST['action']) && $_POST['action'] === 'save') {
        $name       = trim($_POST['name']       ?? '');
        $age        = intval($_POST['age']       ?? 0);
        $gender     = trim($_POST['gender']     ?? '');
        $occupation = trim($_POST['occupation'] ?? '');

        if ($name === '') {
            echo json_encode(['status' => 'error', 'message' => 'Name is required']);
            exit;
        }

        $stmt = $conn->prepare("INSERT INTO beneficiaries (name, age, gender, occupation) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("siss", $name, $age, $gender, $occupation);
        $stmt->execute();
        echo json_encode(['status' => 'success', 'id' => $conn->insert_id]);
        exit;
    }

    // ========== POST: update ==========
    if (isset($_POST['action']) && $_POST['action'] === 'update') {
        $id         = intval($_POST['id']        ?? 0);
        $name       = trim($_POST['name']        ?? '');
        $age        = intval($_POST['age']       ?? 0);
        $gender     = trim($_POST['gender']      ?? '');
        $occupation = trim($_POST['occupation']  ?? '');

        if ($id === 0 || $name === '') {
            echo json_encode(['status' => 'error', 'message' => 'ID and name are required']);
            exit;
        }

        $stmt = $conn->prepare("UPDATE beneficiaries SET name=?, age=?, gender=?, occupation=? WHERE id=?");
        $stmt->bind_param("sissi", $name, $age, $gender, $occupation, $id);
        $stmt->execute();
        echo json_encode(['status' => 'success']);
        exit;
    }

    // ========== POST: delete ==========
    if (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $id = intval($_POST['id'] ?? 0);
        if ($id === 0) {
            echo json_encode(['status' => 'error', 'message' => 'ID is required']);
            exit;
        }
        $stmt = $conn->prepare("DELETE FROM beneficiaries WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        echo json_encode(['status' => 'success']);
        exit;
    }

    echo json_encode(['status' => 'error', 'message' => 'Invalid action']);

} catch (Throwable $e) {
    ob_end_clean();
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    exit;
}
ob_end_flush();