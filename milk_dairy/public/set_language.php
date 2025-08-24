<?php
// Simple endpoint to update vendor language in session (and optionally DB later)
session_start();
header('Content-Type: application/json');
$vendorId = $_POST['vendor_id'] ?? null;
$lang = $_POST['lang'] ?? null;
if (!$lang) {
    echo json_encode(['success' => false, 'message' => 'Missing lang']);
    exit;
}
// If vendor exists in session, update it
if (isset($_SESSION['vendor'])) {
    $_SESSION['vendor']['lang'] = $lang;
    // TODO: update DB if you have vendor table and model
    echo json_encode(['success' => true, 'message' => 'Language saved']);
    exit;
}
// Fallback: store in session under temp key
$_SESSION['maulivision_lang'] = $lang;
echo json_encode(['success' => true, 'message' => 'Language saved to session']);
