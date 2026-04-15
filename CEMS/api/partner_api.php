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
        $result = $conn->query("SELECT * FROM partners ORDER BY created_at DESC");
        $rows = [];
        while ($row = $result->fetch_assoc()) $rows[] = $row;
        echo json_encode($rows);
        exit;
    }

    // INSERT
    if (isset($_POST['action']) && $_POST['action'] === 'save') {
        $name        = trim($_POST['name']              ?? '');
        $type        = trim($_POST['type']              ?? '');
        $email       = trim($_POST['email']             ?? '');
        $phone       = trim($_POST['phone']             ?? '');
        $address     = trim($_POST['address']           ?? '');
        $roles       = trim($_POST['roles']             ?? '');
        $since       = trim($_POST['since']             ?? '');
        $cont_type   = trim($_POST['contribution_type'] ?? '');
        $financial   = floatval($_POST['financial_amount'] ?? 0);
        $inkind      = floatval($_POST['inkind_value']  ?? 0);
        $cont_desc   = trim($_POST['contributions']     ?? '');
        $status      = trim($_POST['status']            ?? 'Active');
        $priority    = trim($_POST['priority']          ?? 'Medium');

        if ($name === '') { echo json_encode(['status'=>'error','message'=>'Name is required']); exit; }

        $stmt = $conn->prepare(
            "INSERT INTO partners (name, type, email, phone, address, roles, partner_since, contribution_type, financial_amount, inkind_value, contribution_desc, status, priority, contact)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $contact = $email . ($phone ? ' | ' . $phone : '');
        $stmt->bind_param("ssssssssddssss", $name, $type, $email, $phone, $address, $roles, $since, $cont_type, $financial, $inkind, $cont_desc, $status, $priority, $contact);
        $stmt->execute();
        echo json_encode(['status'=>'success','id'=>$conn->insert_id]);
        exit;
    }

    // UPDATE
    if (isset($_POST['action']) && $_POST['action'] === 'update') {
        $id          = intval($_POST['id']              ?? 0);
        $name        = trim($_POST['name']              ?? '');
        $type        = trim($_POST['type']              ?? '');
        $email       = trim($_POST['email']             ?? '');
        $phone       = trim($_POST['phone']             ?? '');
        $address     = trim($_POST['address']           ?? '');
        $roles       = trim($_POST['roles']             ?? '');
        $since       = trim($_POST['since']             ?? '');
        $cont_type   = trim($_POST['contribution_type'] ?? '');
        $financial   = floatval($_POST['financial_amount'] ?? 0);
        $inkind      = floatval($_POST['inkind_value']  ?? 0);
        $cont_desc   = trim($_POST['contributions']     ?? '');
        $status      = trim($_POST['status']            ?? 'Active');
        $priority    = trim($_POST['priority']          ?? 'Medium');

        if ($id === 0 || $name === '') { echo json_encode(['status'=>'error','message'=>'ID and name required']); exit; }

        $contact = $email . ($phone ? ' | ' . $phone : '');
        $stmt = $conn->prepare(
            "UPDATE partners SET name=?, type=?, email=?, phone=?, address=?, roles=?, partner_since=?, contribution_type=?, financial_amount=?, inkind_value=?, contribution_desc=?, status=?, priority=?, contact=? WHERE id=?"
        );
        $stmt->bind_param("ssssssssddssssi", $name, $type, $email, $phone, $address, $roles, $since, $cont_type, $financial, $inkind, $cont_desc, $status, $priority, $contact, $id);
        $stmt->execute();
        echo json_encode(['status'=>'success']);
        exit;
    }

    // DELETE
    if (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $id = intval($_POST['id'] ?? 0);
        if ($id === 0) { echo json_encode(['status'=>'error','message'=>'ID required']); exit; }
        $stmt = $conn->prepare("DELETE FROM partners WHERE id=?");
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