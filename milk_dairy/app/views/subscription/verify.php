<?php
session_start();

// Include the Razorpay PHP library
require('razorpay-php/Razorpay.php');
use Razorpay\Api\Api;

// Use environment variables or config for keys in production!
$api_key = 'rzp_live_R78Z3uT9I5EQ0k';
$api_secret = 'lC21KSWINU0zdgrphmfMsH';
$api = new Api($api_key, $api_secret);

$success = true;
$error = null;

// Check required POST variables
if (
    !isset($_POST['razorpay_payment_id'], $_POST['razorpay_signature'], $_POST['razorpay_order_id'])
) {
    echo "Invalid request.";
    exit;
}

$payment_id = $_POST['razorpay_payment_id'];
$razorpay_signature = $_POST['razorpay_signature'];

try {
    $attributes = [
        'razorpay_order_id' => $_POST['razorpay_order_id'],
        'razorpay_payment_id' => $payment_id,
        'razorpay_signature' => $razorpay_signature
    ];
    $api->utility->verifyPaymentSignature($attributes);
} catch (\Razorpay\Api\Errors\SignatureVerificationError $e) {
    $success = false;
    $error = 'Razorpay Signature Verification Failed';
}

if ($success) {
    // Fetch payment details
    $payment = $api->payment->fetch($payment_id);
    $amount_paid = $payment->amount / 100;

    $vendor_id = isset($_SESSION['vendor']['id']) ? $_SESSION['vendor']['id'] : null;
    if (!$vendor_id) {
        echo "Vendor not found in session.";
        exit;
    }

    $transaction_id = $payment_id;
    $transaction_date = date('Y-m-d H:i:s');
    $transaction_type = 'subscription';
    $status = $payment->status;
    $reference = $_POST['razorpay_order_id'];
    $remarks = 'Razorpay payment';
    $created_at = $transaction_date;
    $updated_at = $transaction_date;

    require_once '../core/Database.php';
    $database = new Database();
    $db = $database->getConnection(); // Use the correct method to get the database connection

    if ($db->connect_errno) {
        echo "Database connection failed: " . $db->connect_error;
        exit;
    }

    $stmt = $db->prepare("INSERT INTO vendor_transactions (transaction_id, vendor_id, amount, transaction_date, transaction_type, status, reference, remarks, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        echo "Prepare failed: " . $db->error;
        exit;
    }
    $stmt->bind_param(
        "sidsssssss",
        $transaction_id,
        $vendor_id,
        $amount_paid,
        $transaction_date,
        $transaction_type,
        $status,
        $reference,
        $remarks,
        $created_at,
        $updated_at
    );
    if ($stmt->execute()) {
        echo "Payment Successful! Amount: $amount_paid INR";
    } else {
        echo "Database insert failed: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "Payment Failed! Error: $error";
}
?>
