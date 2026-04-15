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

    // GET all staff
    if (isset($_GET['action']) && ($_GET['action'] === 'get' || $_GET['action'] === 'get_staff')) {
        $result = $conn->query("SELECT id, fullname, email, role, assignments, metrics, training, phone, join_date, status, performance, emergency_contact, emergency_phone, created_at FROM users ORDER BY created_at DESC");
        $rows = [];
        while ($row = $result->fetch_assoc()) $rows[] = $row;
        echo json_encode($rows);
        exit;
    }

    // INSERT new staff (no password — admin-added accounts)
    // Roles with admin access: Developer, Director
    if (isset($_POST['action']) && ($_POST['action'] === 'save' || $_POST['action'] === 'save_staff')) {
        $name      = trim($_POST['name']              ?? '');
        $email     = trim($_POST['email']             ?? '') ?: null;
        $role      = trim($_POST['role']              ?? '');
        $phone     = trim($_POST['phone']             ?? '');
        $join_date = !empty($_POST['join_date'])       ? $_POST['join_date'] : null;
        $status    = trim($_POST['status']            ?? 'Active');
        $assign    = trim($_POST['assignments']       ?? '');
        $metrics   = trim($_POST['metrics']           ?? '');
        $perf      = trim($_POST['performance']       ?? 'Medium');
        $training  = trim($_POST['training']          ?? '');
        $ec_name   = trim($_POST['emergency_contact'] ?? '');
        $ec_phone  = trim($_POST['emergency_phone']   ?? '');

        if ($name === '' || $role === '') { echo json_encode(['status'=>'error','message'=>'Name and role required']); exit; }

        // Check duplicate email only if provided
        if ($email) {
            $chk = $conn->prepare("SELECT id FROM users WHERE email=?");
            $chk->bind_param("s", $email);
            $chk->execute();
            $chk->store_result();
            if ($chk->num_rows > 0) { echo json_encode(['status'=>'error','message'=>'Email already exists']); exit; }
            $chk->close();
        }

        $stmt = $conn->prepare(
            "INSERT INTO users (fullname, email, password, role, phone, join_date, status, assignments, metrics, performance, training, emergency_contact, emergency_phone)
             VALUES (?, ?, '', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("ssssssssssss",
            $name, $email, $role, $phone, $join_date, $status,
            $assign, $metrics, $perf, $training, $ec_name, $ec_phone
        );
        $stmt->execute();
        echo json_encode(['status'=>'success','id'=>$conn->insert_id]);
        exit;
    }

    // UPDATE — role IS editable
    if (isset($_POST['action']) && ($_POST['action'] === 'update' || $_POST['action'] === 'update_staff')) {
        $id        = intval($_POST['id']              ?? 0);
        $name      = trim($_POST['name']              ?? '');
        $email     = trim($_POST['email']             ?? '') ?: null;
        $role      = trim($_POST['role']              ?? '');
        $phone     = trim($_POST['phone']             ?? '');
        $join_date = !empty($_POST['join_date'])       ? $_POST['join_date'] : null;
        $status    = trim($_POST['status']            ?? 'Active');
        $assign    = trim($_POST['assignments']       ?? '');
        $metrics   = trim($_POST['metrics']           ?? '');
        $perf      = trim($_POST['performance']       ?? 'Medium');
        $training  = trim($_POST['training']          ?? '');
        $ec_name   = trim($_POST['emergency_contact'] ?? '');
        $ec_phone  = trim($_POST['emergency_phone']   ?? '');

        if ($id === 0 || $name === '' || $role === '') { echo json_encode(['status'=>'error','message'=>'ID, name, and role required']); exit; }

        $stmt = $conn->prepare(
            "UPDATE users SET fullname=?, email=?, role=?, phone=?, join_date=?, status=?, assignments=?, metrics=?, performance=?, training=?, emergency_contact=?, emergency_phone=? WHERE id=?"
        );
        $stmt->bind_param("ssssssssssssi",
            $name, $email, $role, $phone, $join_date, $status,
            $assign, $metrics, $perf, $training, $ec_name, $ec_phone, $id
        );
        $stmt->execute();
        echo json_encode(['status'=>'success']);
        exit;
    }

    // DELETE
    if (isset($_POST['action']) && ($_POST['action'] === 'delete' || $_POST['action'] === 'delete_staff')) {
        $id = intval($_POST['id'] ?? 0);
        if ($id === 0) { echo json_encode(['status'=>'error','message'=>'ID required']); exit; }
        $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
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