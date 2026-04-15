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
        $result = $conn->query("SELECT * FROM resources ORDER BY created_at DESC");
        $rows = [];
        while ($row = $result->fetch_assoc()) $rows[] = $row;
        echo json_encode($rows);
        exit;
    }

    // INSERT
    if (isset($_POST['action']) && $_POST['action'] === 'save') {
        $name       = trim($_POST['name']             ?? '');
        $type       = trim($_POST['type']             ?? '');
        $quantity   = intval($_POST['quantity']       ?? 0);
        $location   = trim($_POST['location']         ?? '');
        $status     = trim($_POST['status']           ?? 'Available');
        $condition  = trim($_POST['condition']        ?? 'Good');
        $maint_date = !empty($_POST['maintenance_date']) ? $_POST['maintenance_date'] : null;
        $category   = trim($_POST['category']         ?? '');
        $notes      = trim($_POST['notes']            ?? '');

        if ($name === '') { echo json_encode(['status'=>'error','message'=>'Name is required']); exit; }

        $stmt = $conn->prepare(
            "INSERT INTO resources (name, type, quantity, location, status, condition_status, maintenance_date, category, notes)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("ssissssss", $name, $type, $quantity, $location, $status, $condition, $maint_date, $category, $notes);
        $stmt->execute();
        echo json_encode(['status'=>'success','id'=>$conn->insert_id]);
        exit;
    }

    // UPDATE
    if (isset($_POST['action']) && $_POST['action'] === 'update') {
        $id         = intval($_POST['id']             ?? 0);
        $name       = trim($_POST['name']             ?? '');
        $type       = trim($_POST['type']             ?? '');
        $quantity   = intval($_POST['quantity']       ?? 0);
        $location   = trim($_POST['location']         ?? '');
        $status     = trim($_POST['status']           ?? 'Available');
        $condition  = trim($_POST['condition']        ?? 'Good');
        $maint_date = !empty($_POST['maintenance_date']) ? $_POST['maintenance_date'] : null;
        $category   = trim($_POST['category']         ?? '');
        $notes      = trim($_POST['notes']            ?? '');

        if ($id === 0 || $name === '') { echo json_encode(['status'=>'error','message'=>'ID and name required']); exit; }

        $stmt = $conn->prepare(
            "UPDATE resources SET name=?, type=?, quantity=?, location=?, status=?, condition_status=?, maintenance_date=?, category=?, notes=? WHERE id=?"
        );
        $stmt->bind_param("ssissssssi", $name, $type, $quantity, $location, $status, $condition, $maint_date, $category, $notes, $id);
        $stmt->execute();
        echo json_encode(['status'=>'success']);
        exit;
    }

    // DELETE
    if (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $id = intval($_POST['id'] ?? 0);
        if ($id === 0) { echo json_encode(['status'=>'error','message'=>'ID required']); exit; }
        $stmt = $conn->prepare("DELETE FROM resources WHERE id=?");
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