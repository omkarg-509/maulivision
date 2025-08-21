<?php
// Razorpay configuration (placeholder keys; replace via env or secure storage in production)
if (!defined('RAZORPAY_KEY_ID')) {
    define('RAZORPAY_KEY_ID', 'rzp_test_yourkey');
}
if (!defined('RAZORPAY_KEY_SECRET')) {
    define('RAZORPAY_KEY_SECRET', 'your_secret_here');
}
// Plan catalog (id => attributes). Amount in paise.
if (!defined('SUBSCRIPTION_PLANS')) {
    define('SUBSCRIPTION_PLANS', json_encode([
        'basic_monthly' => [
            'plan_name' => 'Basic Monthly',
            'amount_paise' => 69900,
            'duration_days' => 30,
        ],
        'trial_week' => [
            'plan_name' => 'Trial 7 Days',
            'amount_paise' => 0,
            'duration_days' => 7,
        ],
    ]));
}
