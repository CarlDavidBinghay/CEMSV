<?php
// Suppress PHP warnings from corrupting JSON output
error_reporting(0);
ini_set('display_errors', 0);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// ── Try both common db.php locations ──────────────────────────────────────────
$db_paths = [
    __DIR__ . '/../db.php',   // db.php is one folder UP  (e.g. /project/db.php, api is /project/api/)
    __DIR__ . '/db.php',      // db.php is in SAME folder
    __DIR__ . '/../../db.php' // db.php is two folders UP
];

$db_loaded = false;
foreach ($db_paths as $path) {
    if (file_exists($path)) {
        include_once $path;
        $db_loaded = true;
        break;
    }
}

if (!$db_loaded) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'db.php not found. Check your file structure.']);
    exit();
}

// ── Verify $conn exists ───────────────────────────────────────────────────────
if (!isset($conn)) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database connection variable $conn not found in db.php.']);
    exit();
}

// ── Run the SQL migration if columns are missing ──────────────────────────────
$columns = ['region', 'lat', 'lng', 'notes'];
$existing = [];
$result = $conn->query("SHOW COLUMNS FROM locations");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $existing[] = $row['Field'];
    }
}
foreach ($columns as $col) {
    if (!in_array($col, $existing)) {
        if ($col === 'region')    $conn->query("ALTER TABLE locations ADD COLUMN region varchar(100) DEFAULT NULL AFTER address");
        if ($col === 'lat')       $conn->query("ALTER TABLE locations ADD COLUMN lat decimal(10,6) DEFAULT NULL");
        if ($col === 'lng')       $conn->query("ALTER TABLE locations ADD COLUMN lng decimal(10,6) DEFAULT NULL");
        if ($col === 'notes')     $conn->query("ALTER TABLE locations ADD COLUMN notes text DEFAULT NULL");
    }
}

// ── Handle request ────────────────────────────────────────────────────────────
$method = $_SERVER['REQUEST_METHOD'];
$input  = json_decode(file_get_contents('php://input'), true) ?? [];

switch ($method) {

    // GET — fetch all locations
    case 'GET':
        $result = $conn->query("SELECT * FROM locations ORDER BY created_at DESC");
        if (!$result) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => $conn->error]);
            exit();
        }
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        echo json_encode(['success' => true, 'data' => $rows]);
        break;

    // POST — create new location
    case 'POST':
        $name       = trim($input['name']       ?? '');
        $address    = trim($input['address']    ?? '');
        $region     = trim($input['region']     ?? '');
        $facilities = trim($input['facilities'] ?? '');
        $lat        = $input['lat']  !== '' ? $input['lat']  : null;
        $lng        = $input['lng']  !== '' ? $input['lng']  : null;
        $notes      = trim($input['notes']      ?? '');

        if (empty($name) || empty($address)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Name and address are required.']);
            exit();
        }

        $stmt = $conn->prepare(
            "INSERT INTO locations (name, address, region, facilities, lat, lng, notes)
             VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param('sssssss', $name, $address, $region, $facilities, $lat, $lng, $notes);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'id' => $conn->insert_id, 'message' => 'Location created.']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => $stmt->error]);
        }
        $stmt->close();
        break;

    // PUT — update existing location
    case 'PUT':
        $id         = intval($input['id']       ?? 0);
        $name       = trim($input['name']       ?? '');
        $address    = trim($input['address']    ?? '');
        $region     = trim($input['region']     ?? '');
        $facilities = trim($input['facilities'] ?? '');
        $lat        = $input['lat']  !== '' ? $input['lat']  : null;
        $lng        = $input['lng']  !== '' ? $input['lng']  : null;
        $notes      = trim($input['notes']      ?? '');

        if (!$id) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Invalid location ID.']);
            exit();
        }
        if (empty($name) || empty($address)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Name and address are required.']);
            exit();
        }

        $stmt = $conn->prepare(
            "UPDATE locations
             SET name=?, address=?, region=?, facilities=?, lat=?, lng=?, notes=?
             WHERE id=?"
        );
        $stmt->bind_param('sssssssi', $name, $address, $region, $facilities, $lat, $lng, $notes, $id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Location updated.']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => $stmt->error]);
        }
        $stmt->close();
        break;

    // DELETE — remove location
    case 'DELETE':
        $id = intval($input['id'] ?? $_GET['id'] ?? 0);

        if (!$id) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Invalid location ID.']);
            exit();
        }

        $stmt = $conn->prepare("DELETE FROM locations WHERE id = ?");
        $stmt->bind_param('i', $id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Location deleted.']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => $stmt->error]);
        }
        $stmt->close();
        break;

    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'error' => 'Method not allowed.']);
        break;
}

$conn->close();
?>