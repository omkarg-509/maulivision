<?php
header('Content-Type: application/json');
session_start();
require_once __DIR__ . '/../app/models/User.php';

$userModel = new User();

// Prefer session vendor
if (isset($_SESSION['vendor']) && isset($_SESSION['vendor']['id'])) {
    $lang = $_SESSION['vendor']['lng'] ?? '';
    echo json_encode(['success' => true, 'lang' => $lang]);
    exit;
}

// If vendor_id provided via GET/POST, fetch from DB
$vendorId = $_GET['vendor_id'] ?? $_POST['vendor_id'] ?? null;
if ($vendorId) {
    $lang = $userModel->getVendorLang((int)$vendorId);
    echo json_encode(['success' => true, 'lang' => $lang]);
    exit;
}

// fallback
$lang = $_SESSION['maulivision_lang'] ?? '';
echo json_encode(['success' => true, 'lang' => $lang]);
