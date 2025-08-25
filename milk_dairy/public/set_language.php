<?php
// Persist vendor language choice (session + DB)
header('Content-Type: application/json');
session_start();

$vendorId = isset($_POST['vendor_id']) ? trim($_POST['vendor_id']) : null;
$lang = isset($_POST['lang']) ? trim($_POST['lang']) : null;

// allowed language codes
$allowed = ['en','mr','hi','auto',''];
if ($lang === null) {
    echo json_encode(['success' => false, 'message' => 'Missing lang']);
    exit;
}

// normalize
if ($lang === 'auto') $storeVal = '';
else $storeVal = in_array($lang, $allowed) ? $lang : '';

// update session if vendor in session
if (isset($_SESSION['vendor'])) {
    $_SESSION['vendor']['lng'] = $storeVal;
}

// If vendor id present, update DB
if ($vendorId) {
    // require Database core
    $dbFile = __DIR__ . '/../core/Database.php';
    if (file_exists($dbFile)) {
        require_once $dbFile;
        try {
            $db = new Database();
            $conn = $db->getConnection();
            $stmt = $conn->prepare('UPDATE vendor SET lng = ? WHERE id = ?');
            if ($stmt) {
                $stmt->bind_param('si', $storeVal, $vendorId);
                $ok = $stmt->execute();
                if ($ok) {
                    echo json_encode(['success' => true, 'message' => 'Language saved']);
                    $stmt->close();
                    exit;
                } else {
                    echo json_encode(['success' => false, 'message' => 'DB update failed']);
                    $stmt->close();
                    exit;
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'DB prepare failed']);
                exit;
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'DB error: ' . $e->getMessage()]);
            exit;
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Database core not found']);
        exit;
    }
}

// fallback: session-only
$_SESSION['maulivision_lang'] = $storeVal;
echo json_encode(['success' => true, 'message' => 'Language saved to session']);
