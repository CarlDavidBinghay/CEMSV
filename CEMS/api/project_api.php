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

    // ========== GET: all projects ==========
    if (isset($_GET['action']) && $_GET['action'] === 'get') {
        $result = $conn->query("SELECT * FROM projects ORDER BY created_at DESC");
        $rows = [];
        while ($row = $result->fetch_assoc()) $rows[] = $row;
        echo json_encode($rows);
        exit;
    }

    // ========== GET: all programs (for dropdown) ==========
    if (isset($_GET['action']) && $_GET['action'] === 'get_programs') {
        $result = $conn->query("SELECT id, name FROM programs ORDER BY name ASC");
        $rows = [];
        while ($row = $result->fetch_assoc()) $rows[] = $row;
        echo json_encode($rows);
        exit;
    }

    // ========== POST: INSERT ==========
    if (isset($_POST['action']) && $_POST['action'] === 'save') {
        $name           = trim($_POST['name']           ?? '');
        $program_id     = intval($_POST['program_id']   ?? 0) ?: null;
        $objectives     = trim($_POST['objectives']     ?? '');
        $beneficiaries  = trim($_POST['beneficiaries']  ?? '');
        $timeline       = trim($_POST['timeline']       ?? '');
        $start_date     = $_POST['start_date']  ?: null;
        $end_date       = $_POST['end_date']    ?: null;
        $budget         = floatval($_POST['budget']     ?? 0);
        $coordinators   = trim($_POST['coordinators']   ?? '');
        $resources      = trim($_POST['resources']      ?? '');
        $status         = trim($_POST['status']         ?? 'Planned');
        $progress       = intval($_POST['progress']     ?? 0);
        $team           = trim($_POST['team']           ?? '');
        $priority       = trim($_POST['priority']       ?? 'Medium');

        if ($name === '') { echo json_encode(['status'=>'error','message'=>'Name is required']); exit; }

        $stmt = $conn->prepare(
            "INSERT INTO projects (name, program_id, objectives, target_beneficiaries, timeline, start_date, end_date, budget, coordinators, resources, status, progress, team, priority)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("siissssdssisis",
            $name, $program_id, $objectives, $beneficiaries,
            $timeline, $start_date, $end_date, $budget,
            $coordinators, $resources, $status, $progress, $team, $priority
        );
        $stmt->execute();
        echo json_encode(['status' => 'success', 'id' => $conn->insert_id]);
        exit;
    }

    // ========== POST: UPDATE ==========
    if (isset($_POST['action']) && $_POST['action'] === 'update') {
        $id             = intval($_POST['id']           ?? 0);
        $name           = trim($_POST['name']           ?? '');
        $program_id     = intval($_POST['program_id']   ?? 0) ?: null;
        $objectives     = trim($_POST['objectives']     ?? '');
        $beneficiaries  = trim($_POST['beneficiaries']  ?? '');
        $timeline       = trim($_POST['timeline']       ?? '');
        $start_date     = $_POST['start_date']  ?: null;
        $end_date       = $_POST['end_date']    ?: null;
        $budget         = floatval($_POST['budget']     ?? 0);
        $coordinators   = trim($_POST['coordinators']   ?? '');
        $resources      = trim($_POST['resources']      ?? '');
        $status         = trim($_POST['status']         ?? 'Planned');
        $progress       = intval($_POST['progress']     ?? 0);
        $team           = trim($_POST['team']           ?? '');
        $priority       = trim($_POST['priority']       ?? 'Medium');

        if ($id === 0 || $name === '') { echo json_encode(['status'=>'error','message'=>'ID and name required']); exit; }

        $stmt = $conn->prepare(
            "UPDATE projects SET name=?, program_id=?, objectives=?, target_beneficiaries=?, timeline=?, start_date=?, end_date=?, budget=?, coordinators=?, resources=?, status=?, progress=?, team=?, priority=? WHERE id=?"
        );
        $stmt->bind_param("siissssdssisisi",
            $name, $program_id, $objectives, $beneficiaries,
            $timeline, $start_date, $end_date, $budget,
            $coordinators, $resources, $status, $progress, $team, $priority, $id
        );
        $stmt->execute();
        echo json_encode(['status' => 'success']);
        exit;
    }

    // ========== POST: DELETE ==========
    if (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $id = intval($_POST['id'] ?? 0);
        if ($id === 0) { echo json_encode(['status'=>'error','message'=>'ID required']); exit; }
        $stmt = $conn->prepare("DELETE FROM projects WHERE id=?");
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