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

    // ========== GET ==========
    if (isset($_GET['action']) && $_GET['action'] === 'get') {
        $result = $conn->query("SELECT * FROM programs ORDER BY created_at DESC");
        $rows = [];
        while ($row = $result->fetch_assoc()) $rows[] = $row;
        echo json_encode($rows);
        exit;
    }

    // ========== INSERT ==========
    if (isset($_POST['action']) && $_POST['action'] === 'save') {
        $name         = trim($_POST['name']         ?? '');
        $description  = trim($_POST['description']  ?? '');
        $goals        = trim($_POST['goals']        ?? '');
        $beneficiaries= trim($_POST['beneficiaries']?? '');
        $duration     = trim($_POST['duration']     ?? '');
        $start_date   = $_POST['start_date']  ?? null;
        $end_date     = $_POST['end_date']    ?? null;
        $budget       = floatval($_POST['budget']   ?? 0);
        $manager      = trim($_POST['manager']      ?? '');
        $status       = trim($_POST['status']       ?? 'Planning');

        if ($name === '') { echo json_encode(['status'=>'error','message'=>'Name is required']); exit; }

        $stmt = $conn->prepare(
            "INSERT INTO programs (name, description, goals, target_beneficiaries, duration, start_date, end_date, budget, manager, status)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("sssssssdss", $name, $description, $goals, $beneficiaries, $duration, $start_date, $end_date, $budget, $manager, $status);
        $stmt->execute();
        echo json_encode(['status' => 'success', 'id' => $conn->insert_id]);
        exit;
    }

    // ========== UPDATE ==========
    if (isset($_POST['action']) && $_POST['action'] === 'update') {
        $id           = intval($_POST['id']          ?? 0);
        $name         = trim($_POST['name']          ?? '');
        $description  = trim($_POST['description']   ?? '');
        $goals        = trim($_POST['goals']         ?? '');
        $beneficiaries= trim($_POST['beneficiaries'] ?? '');
        $duration     = trim($_POST['duration']      ?? '');
        $start_date   = $_POST['start_date']   ?? null;
        $end_date     = $_POST['end_date']     ?? null;
        $budget       = floatval($_POST['budget']    ?? 0);
        $manager      = trim($_POST['manager']       ?? '');
        $status       = trim($_POST['status']        ?? 'Planning');

        if ($id === 0 || $name === '') { echo json_encode(['status'=>'error','message'=>'ID and name required']); exit; }

        $stmt = $conn->prepare(
            "UPDATE programs SET name=?, description=?, goals=?, target_beneficiaries=?, duration=?, start_date=?, end_date=?, budget=?, manager=?, status=? WHERE id=?"
        );
        $stmt->bind_param("sssssssdssı", $name, $description, $goals, $beneficiaries, $duration, $start_date, $end_date, $budget, $manager, $status, $id);
        // fix bind — use i not ı
        $stmt->close();
        $stmt = $conn->prepare(
            "UPDATE programs SET name=?, description=?, goals=?, target_beneficiaries=?, duration=?, start_date=?, end_date=?, budget=?, manager=?, status=? WHERE id=?"
        );
        $stmt->bind_param("sssssssdssi", $name, $description, $goals, $beneficiaries, $duration, $start_date, $end_date, $budget, $manager, $status, $id);
        $stmt->execute();
        echo json_encode(['status' => 'success']);
        exit;
    }

    // ========== DELETE ==========
    if (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $id = intval($_POST['id'] ?? 0);
        if ($id === 0) { echo json_encode(['status'=>'error','message'=>'ID required']); exit; }
        $stmt = $conn->prepare("DELETE FROM programs WHERE id=?");
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