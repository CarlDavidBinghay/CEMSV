<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);
header('Content-Type: application/json');

// Clean any accidental output before JSON
ob_start();

try {
    $dbPath = __DIR__ . '/../db.php';
    if (!file_exists($dbPath)) $dbPath = __DIR__ . '/db.php';
    if (!file_exists($dbPath)) {
        ob_end_clean();
        echo json_encode(['status'=>'error','message'=>'db.php not found']);
        exit;
    }

    include $dbPath;
    ob_clean(); // clear anything db.php may have echoed

    if (!isset($conn) || !$conn) {
        echo json_encode(['status'=>'error','message'=>'No DB connection']);
        exit;
    }

    // Auto-create table so it never fails on missing table
    $conn->query("CREATE TABLE IF NOT EXISTS `reports` (
        `id`          int(11) NOT NULL AUTO_INCREMENT,
        `name`        varchar(255) NOT NULL,
        `category`    varchar(150) DEFAULT NULL,
        `description` text DEFAULT NULL,
        `report_date` date DEFAULT NULL,
        `format`      varchar(50) DEFAULT 'PDF',
        `status`      varchar(50) DEFAULT 'Generated',
        `time_period` varchar(50) DEFAULT 'all',
        `data_source` varchar(100) DEFAULT 'all',
        `schedule`    varchar(50) DEFAULT 'none',
        `recipients`  varchar(255) DEFAULT NULL,
        `created_at`  timestamp NOT NULL DEFAULT current_timestamp(),
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $action = $_GET['action'] ?? $_POST['action'] ?? '';

    // ── GET ALL ──────────────────────────────────────────────
    if ($action === 'get') {
        $res  = $conn->query("SELECT * FROM reports ORDER BY created_at DESC");
        $rows = [];
        while ($row = $res->fetch_assoc()) $rows[] = $row;
        ob_end_clean();
        echo json_encode($rows);
        exit;
    }

    // ── GET STATS ────────────────────────────────────────────
    if ($action === 'get_stats') {
        $stats  = [];
        $tables = [
            'beneficiaries'=>'beneficiaries','programs'=>'programs',
            'projects'=>'projects','activities'=>'activities',
            'partners'=>'partners','resources'=>'resources',
            'staff'=>'users','evaluations'=>'evaluations','reports'=>'reports'
        ];
        foreach ($tables as $key => $tbl) {
            try {
                $r = $conn->query("SELECT COUNT(*) AS cnt FROM `$tbl`");
                $stats[$key] = $r ? (int)$r->fetch_assoc()['cnt'] : 0;
            } catch (Throwable $e) { $stats[$key] = 0; }
        }
        try {
            $r = $conn->query("SELECT COALESCE(SUM(financial_amount),0)+COALESCE(SUM(inkind_value),0) AS t FROM partners");
            $stats['total_contributions'] = $r ? (float)$r->fetch_assoc()['t'] : 0;
        } catch (Throwable $e) { $stats['total_contributions'] = 0; }
        ob_end_clean();
        echo json_encode($stats);
        exit;
    }

    // ── SAVE ─────────────────────────────────────────────────
    if ($action === 'save') {
        $name       = trim($_POST['name']        ?? '');
        $category   = trim($_POST['category']    ?? '');
        $desc       = trim($_POST['description'] ?? '');
        $date       = !empty($_POST['report_date']) ? $_POST['report_date'] : date('Y-m-d');
        $format     = trim($_POST['format']      ?? 'PDF');
        $status     = 'Generated';
        $period     = trim($_POST['time_period'] ?? 'all');
        $source     = trim($_POST['data_source'] ?? 'all');
        $schedule   = trim($_POST['schedule']    ?? 'none');
        $recipients = trim($_POST['recipients']  ?? '');

        if ($name === '') {
            ob_end_clean();
            echo json_encode(['status'=>'error','message'=>'Name is required']);
            exit;
        }

        $sql  = "INSERT INTO reports (name, category, description, report_date, format, status, time_period, data_source, schedule, recipients)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            ob_end_clean();
            echo json_encode(['status'=>'error','message'=>'Prepare failed: '.$conn->error]);
            exit;
        }
        $stmt->bind_param("ssssssssss",
            $name, $category, $desc, $date, $format, $status, $period, $source, $schedule, $recipients);
        $stmt->execute();
        $newId = $conn->insert_id;
        $stmt->close();
        ob_end_clean();
        echo json_encode(['status'=>'success','id'=>$newId]);
        exit;
    }

    // ── UPDATE ───────────────────────────────────────────────
    if ($action === 'update') {
        $id         = intval($_POST['id']        ?? 0);
        $name       = trim($_POST['name']        ?? '');
        $category   = trim($_POST['category']    ?? '');
        $desc       = trim($_POST['description'] ?? '');
        $date       = !empty($_POST['report_date']) ? $_POST['report_date'] : date('Y-m-d');
        $format     = trim($_POST['format']      ?? 'PDF');
        $period     = trim($_POST['time_period'] ?? 'all');
        $source     = trim($_POST['data_source'] ?? 'all');
        $schedule   = trim($_POST['schedule']    ?? 'none');
        $recipients = trim($_POST['recipients']  ?? '');

        if ($id === 0 || $name === '') {
            ob_end_clean();
            echo json_encode(['status'=>'error','message'=>'ID and name required']);
            exit;
        }

        $sql  = "UPDATE reports SET name=?, category=?, description=?, report_date=?, format=?,
                 time_period=?, data_source=?, schedule=?, recipients=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            ob_end_clean();
            echo json_encode(['status'=>'error','message'=>'Prepare failed: '.$conn->error]);
            exit;
        }
        $stmt->bind_param("sssssssssi",
            $name, $category, $desc, $date, $format, $period, $source, $schedule, $recipients, $id);
        $stmt->execute();
        $stmt->close();
        ob_end_clean();
        echo json_encode(['status'=>'success']);
        exit;
    }

    // ── DELETE ───────────────────────────────────────────────
    if ($action === 'delete') {
        $id = intval($_POST['id'] ?? 0);
        if ($id === 0) {
            ob_end_clean();
            echo json_encode(['status'=>'error','message'=>'ID required']);
            exit;
        }
        $stmt = $conn->prepare("DELETE FROM reports WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        ob_end_clean();
        echo json_encode(['status'=>'success']);
        exit;
    }

    ob_end_clean();
    echo json_encode(['status'=>'error','message'=>'Invalid action: '.$action]);

} catch (Throwable $e) {
    ob_end_clean();
    echo json_encode(['status'=>'error','message'=>$e->getMessage()]);
    exit;
}