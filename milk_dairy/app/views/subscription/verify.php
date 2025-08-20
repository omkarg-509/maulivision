<?php
// Include the Razorpay PHP library
require('razorpay-php/Razorpay.php');

use Razorpay\Api\Api;

// Initialize Razorpay with your key and secret
// $api_key = 'rzp_test_Y2wy8t1wD1AFaA';
// $api_secret = 'zSqRMpIa2ljBBpkieFYGmfLa';
$api_key = 'rzp_live_R78Z3uT9I5EQ0k';
$api_secret = 'lC21KSWINU0zdgrphmfMsH';
$api = new Api($api_key, $api_secret);

// Check if payment is successful
$success = true;

$error = null;

// Get the payment ID and the signature from the callback
$payment_id = $_POST['razorpay_payment_id'];
$razorpay_signature = $_POST['razorpay_signature'];

try {
    // Verify the payment
    $attributes = array(
        'razorpay_order_id' => $_POST['razorpay_order_id'],
        'razorpay_payment_id' => $payment_id,
        'razorpay_signature' => $razorpay_signature
    );
    $api->utility->verifyPaymentSignature($attributes);
} catch (\Razorpay\Api\Errors\SignatureVerificationError $e) {
    $success = false;
    $error = 'Razorpay Signature Verification Failed';
}

if ($success) {
    // Payment is successful, update your database or perform other actions

    // Fetch the payment details
    $payment = $api->payment->fetch($payment_id);

    // You can access payment details like $payment->amount, $payment->status, etc.
    $amount_paid = $payment->amount / 100; // Convert amount from paise to rupees

    // Get vendor_id from session or context
    session_start();
    $vendor_id = isset($_SESSION['vendor']['id']) ? $_SESSION['vendor']['id'] : null;

    // Prepare transaction data
    $transaction_id = $payment_id;
    $transaction_date = date('Y-m-d H:i:s');
    $transaction_type = 'subscription';
    $status = $payment->status; // 'captured' for success
    $reference = $_POST['razorpay_order_id'];
    $remarks = 'Razorpay payment';
    $created_at = $transaction_date;
    $updated_at = $transaction_date;

    // Insert into vendor_transactions table
    require_once '../core/Database.php';
    $db = (new Database())->db;
    $stmt = $db->prepare("INSERT INTO vendor_transactions (transaction_id, vendor_id, amount, transaction_date, transaction_type, status, reference, remarks, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
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
    $stmt->execute();

    echo "Payment Successful! Amount: $amount_paid INR";
} else {
    // Payment failed, handle accordingly
    echo "Payment Failed! Error: $error";
}
?>
