<?php 
// Include the Razorpay PHP library

// Add some basic UI styling
echo '<style>
    body { font-family: Arial, sans-serif; background: #f7f7f7; margin: 0; padding: 0; }
    .payment-container {
        max-width: 400px; margin: 60px auto; background: #fff; border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08); padding: 32px 24px; text-align: center;
    }
    .pay-btn {
        background: #738276; color: #fff; border: none; border-radius: 5px;
        padding: 12px 32px; font-size: 18px; cursor: pointer; margin-top: 24px;
        transition: background 0.2s;
    }
    .pay-btn:hover { background: #5a655c; }
    .logo { width: 64px; margin-bottom: 16px; }
    .order-amount { font-size: 22px; color: #333; margin: 16px 0 8px; }
    .order-desc { color: #666; margin-bottom: 24px; }
</style>
<div class="payment-container">
    <img class="logo" src="https://cdn.razorpay.com/logos/GhRQcyean79PqE_medium.png" alt="Logo" />
    <div class="order-amount">&#8377;99.00</div>
    <div class="order-desc">Pay securely for your subscription</div>
    <button class="pay-btn" onclick="startPayment()">Pay with Razorpay</button>
</div>
';
require('razorpay-php/Razorpay.php');
use Razorpay\Api\Api;

// Initialize Razorpay with your key and secret
$api_key = 'rzp_live_R78Z3uT9I5EQ0k';
$api_secret = 'lC21KSWINU0zdgrphmfMsH3m';



$api = new Api($api_key, $api_secret);
// Create an order
$order = $api->order->create([
    'amount' => 100, // amount in paise (100 paise = 1 rupee)
    'currency' => 'INR',
    'receipt' => 'order_receipt_12asa3'
]);
// Get the order ID
$order_id = $order->id;

// Set your callback URL
$callback_url = "";

// Include Razorpay Checkout.js library
echo '<script src="https://checkout.razorpay.com/v1/checkout.js"></script>';

// Create a payment button with Checkout.js
echo '<button onclick="startPayment()">Pay with Razorpay</button>';

// Add a script to handle the payment
echo '<script>
    function startPayment() {
        var options = {
            key: "' . $api_key . '",
            amount: ' . $order->amount . ',
            currency: "' . $order->currency . '",
            name: "Your Company Name",
            description: "Payment for your order",
            image: "https://cdn.razorpay.com/logos/GhRQcyean79PqE_medium.png",
            order_id: "' . $order_id . '",
            theme:
            {
                "color": "#738276"
            },
            callback_url: "' . $callback_url . '"
        };
        var rzp = new Razorpay(options);
        rzp.open();
    }
</script>';
?>
