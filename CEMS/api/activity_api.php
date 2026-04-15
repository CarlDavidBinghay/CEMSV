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

    // GET: all activities
    if (isset($_GET['action']) && $_GET['action'] === 'get') {
        $result = $conn->query("SELECT * FROM activities ORDER BY date_time DESC");
        $rows = [];
        while ($row = $result->fetch_assoc()) $rows[] = $row;
        echo json_encode($rows);
        exit;
    }

    // GET: projects for dropdown
    if (isset($_GET['action']) && $_GET['action'] === 'get_projects') {
        $result = $conn->query("SELECT id, name FROM projects ORDER BY name ASC");
        $rows = [];
        while ($row = $result->fetch_assoc()) $rows[] = $row;
        echo json_encode($rows);
        exit;
    }

    // POST: INSERT
    if (isset($_POST['action']) && $_POST['action'] === 'save') {
        $name         = trim($_POST['name']         ?? '');
        $project_id   = intval($_POST['project_id'] ?? 0) ?: null;
        $date_time    = !empty($_POST['date_time'])  ? $_POST['date_time'] : null;
        $location     = trim($_POST['location']     ?? '');
        $resources    = trim($_POST['resources']    ?? '');
        $output       = trim($_POST['output']       ?? '');
        $attendance   = trim($_POST['attendance']   ?? '');
        $status       = trim($_POST['status']       ?? 'Upcoming');
        $facilitators = trim($_POST['facilitators'] ?? '');
        $budget       = floatval($_POST['budget']   ?? 0);

        if ($name === '') { echo json_encode(['status'=>'error','message'=>'Name is required']); exit; }

        $stmt = $conn->prepare(
            "INSERT INTO activities (name, project_id, date_time, location, resources, expected_output, attendance, status, facilitators, budget) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("sisssssssd", $name, $project_id, $date_time, $location, $resources, $output, $attendance, $status, $facilitators, $budget);
        $stmt->execute();
        echo json_encode(['status' => 'success', 'id' => $conn->insert_id]);
        exit;
    }

    // POST: UPDATE
    if (isset($_POST['action']) && $_POST['action'] === 'update') {
        $id           = intval($_POST['id']          ?? 0);
        $name         = trim($_POST['name']          ?? '');
        $project_id   = intval($_POST['project_id']  ?? 0) ?: null;
        $date_time    = !empty($_POST['date_time'])   ? $_POST['date_time'] : null;
        $location     = trim($_POST['location']      ?? '');
        $resources    = trim($_POST['resources']     ?? '');
        $output       = trim($_POST['output']        ?? '');
        $attendance   = trim($_POST['attendance']    ?? '');
        $status       = trim($_POST['status']        ?? 'Upcoming');
        $facilitators = trim($_POST['facilitators']  ?? '');
        $budget       = floatval($_POST['budget']    ?? 0);

        if ($id === 0 || $name === '') { echo json_encode(['status'=>'error','message'=>'ID and name required']); exit; }

        $stmt = $conn->prepare(
            "UPDATE activities SET name=?, project_id=?, date_time=?, location=?, resources=?, expected_output=?, attendance=?, status=?, facilitators=?, budget=? WHERE id=?"
        );
        $stmt->bind_param("sisssssssdi", $name, $project_id, $date_time, $location, $resources, $output, $attendance, $status, $facilitators, $budget, $id);
        $stmt->execute();
        echo json_encode(['status' => 'success']);
        exit;
    }

    // POST: DELETE
    if (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $id = intval($_POST['id'] ?? 0);
        if ($id === 0) { echo json_encode(['status'=>'error','message'=>'ID required']); exit; }
        $stmt = $conn->prepare("DELETE FROM activities WHERE id=?");
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