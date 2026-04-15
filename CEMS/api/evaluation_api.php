<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
header('Content-Type: application/json');
ob_start();

try {
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $dbPath = __DIR__ . '/../db.php';
    if (!file_exists($dbPath)) $dbPath = __DIR__ . '/db.php';
    if (!file_exists($dbPath)) { ob_end_clean(); echo json_encode(['status'=>'error','message'=>'db.php not found']); exit; }
    include $dbPath;
    ob_clean();

    // GET all
    if (isset($_GET['action']) && $_GET['action'] === 'get') {
        $result = $conn->query("SELECT * FROM evaluations ORDER BY created_at DESC");
        $rows = [];
        while ($row = $result->fetch_assoc()) $rows[] = $row;
        echo json_encode($rows);
        exit;
    }

    // GET programs list from programs table (for dropdown)
    if (isset($_GET['action']) && $_GET['action'] === 'get_programs') {
        $result = $conn->query("SELECT id, name FROM programs ORDER BY name ASC");
        $rows = [];
        while ($row = $result->fetch_assoc()) $rows[] = $row;
        echo json_encode($rows);
        exit;
    }

    // INSERT
    if (isset($_POST['action']) && $_POST['action'] === 'save') {
        $title    = trim($_POST['title']            ?? '');
        $program  = trim($_POST['program']          ?? '');
        $type     = trim($_POST['type']             ?? '');
        $findings = trim($_POST['findings']         ?? '');
        $progress = trim($_POST['progress_notes']   ?? '');
        $pct      = intval($_POST['progress_percent'] ?? 0);
        $status   = trim($_POST['status']           ?? 'Not Started');
        $date     = !empty($_POST['eval_date'])      ? $_POST['eval_date'] : null;
        $eval_by  = trim($_POST['evaluator']        ?? '');
        $parts    = trim($_POST['participants']      ?? '');

        if ($title === '') { echo json_encode(['status'=>'error','message'=>'Title is required']); exit; }

        $stmt = $conn->prepare(
            "INSERT INTO evaluations (title, program, type, findings, progress_notes, progress_percent, status, eval_date, evaluator, participants)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("sssssissss",
            $title, $program, $type, $findings, $progress, $pct, $status, $date, $eval_by, $parts
        );
        $stmt->execute();
        echo json_encode(['status'=>'success','id'=>$conn->insert_id]);
        exit;
    }

    // UPDATE
    if (isset($_POST['action']) && $_POST['action'] === 'update') {
        $id       = intval($_POST['id']             ?? 0);
        $title    = trim($_POST['title']            ?? '');
        $program  = trim($_POST['program']          ?? '');
        $type     = trim($_POST['type']             ?? '');
        $findings = trim($_POST['findings']         ?? '');
        $progress = trim($_POST['progress_notes']   ?? '');
        $pct      = intval($_POST['progress_percent'] ?? 0);
        $status   = trim($_POST['status']           ?? 'Not Started');
        $date     = !empty($_POST['eval_date'])      ? $_POST['eval_date'] : null;
        $eval_by  = trim($_POST['evaluator']        ?? '');
        $parts    = trim($_POST['participants']      ?? '');

        if ($id === 0 || $title === '') { echo json_encode(['status'=>'error','message'=>'ID and title required']); exit; }

        $stmt = $conn->prepare(
            "UPDATE evaluations SET title=?, program=?, type=?, findings=?, progress_notes=?, progress_percent=?, status=?, eval_date=?, evaluator=?, participants=? WHERE id=?"
        );
        $stmt->bind_param("sssssissssi",
            $title, $program, $type, $findings, $progress, $pct, $status, $date, $eval_by, $parts, $id
        );
        $stmt->execute();
        echo json_encode(['status'=>'success']);
        exit;
    }

    // DELETE
    if (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $id = intval($_POST['id'] ?? 0);
        if ($id === 0) { echo json_encode(['status'=>'error','message'=>'ID required']); exit; }
        $stmt = $conn->prepare("DELETE FROM evaluations WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        echo json_encode(['status'=>'success']);
        exit;
    }

    echo json_encode(['status'=>'error','message'=>'Invalid action']);

} catch (Throwable $e) {
    ob_end_clean();
    echo json_encode(['status'=>'error','message'=>$e->getMessage()]);
    exit;
}
ob_end_flush();