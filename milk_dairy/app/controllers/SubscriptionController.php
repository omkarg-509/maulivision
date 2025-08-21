<?php
// In your router logic
if ($url[0] == 'subscription') {
    $controller = new SubscriptionController();
    $controller->index();
}

class SubscriptionController extends Controller
{
    public function index()
    {
        $this->view('subscription/index');
    }

    // Create razorpay order via AJAX
    public function createOrder()
    {
        Auth::check();
        header('Content-Type: application/json');
        require_once '../config/payment.php';
        $planId = $_POST['plan_id'] ?? 'basic_monthly';
        $plans = json_decode(SUBSCRIPTION_PLANS, true);
        if (!isset($plans[$planId])) {
            echo json_encode(['success' => false, 'message' => 'Invalid plan']);
            return;
        }
        $plan = $plans[$planId];
        require 'razorpay-php/Razorpay.php';
        $api = new Razorpay\Api\Api(RAZORPAY_KEY_ID, RAZORPAY_KEY_SECRET);
        try {
            $order = $api->order->create([
                'amount' => $plan['amount_paise'],
                'currency' => 'INR',
                'receipt' => 'sub_' . time() . '_' . rand(1000,9999),
            ]);
            echo json_encode(['success' => true, 'order' => $order, 'key' => RAZORPAY_KEY_ID, 'plan' => $plan]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // Verify payment & create subscription
    public function verify()
    {
        Auth::check();
        header('Content-Type: application/json');
        require_once '../config/payment.php';
        $vendor = $_SESSION['vendor'];
        $required = ['razorpay_payment_id','razorpay_order_id','razorpay_signature','plan_id'];
        foreach ($required as $f) {
            if (empty($_POST[$f])) { echo json_encode(['success'=>false,'message'=>'Missing field '.$f]); return; }
        }
        $planId = $_POST['plan_id'];
        $plans = json_decode(SUBSCRIPTION_PLANS, true);
        if (!isset($plans[$planId])) { echo json_encode(['success'=>false,'message'=>'Invalid plan']); return; }
        $plan = $plans[$planId];
        // Signature check
        $body = $_POST['razorpay_order_id'] . '|' . $_POST['razorpay_payment_id'];
        $expected = hash_hmac('sha256', $body, RAZORPAY_KEY_SECRET);
        if ($expected !== $_POST['razorpay_signature']) {
            echo json_encode(['success'=>false,'message'=>'Signature mismatch']); return;
        }
        // Store transaction & subscription
        $txnModel = $this->model('VendorTransaction');
        $subModel = $this->model('Subscription');
        $amountInRupees = $plan['amount_paise'] / 100.0;
        $txnModel->create([
            'transaction_id' => $_POST['razorpay_payment_id'],
            'vendor_id' => $vendor['id'],
            'amount' => $amountInRupees,
            'transaction_date' => date('Y-m-d H:i:s'),
            'transaction_type' => 'subscription',
            'status' => 'success',
            'reference' => $_POST['razorpay_order_id'],
            'remarks' => 'Plan '.$plan['plan_name'],
        ]);
        $start = date('Y-m-d');
        $end = date('Y-m-d', strtotime('+'.$plan['duration_days'].' days -1 day'));
        $subModel->create([
            'vendor_id' => $vendor['id'],
            'plan_name' => $plan['plan_name'],
            'start_date' => $start,
            'end_date' => $end,
            'status' => 'active',
            'amount' => $amountInRupees,
            'auto_renew' => 0,
        ]);
        $_SESSION['has_active_subscription'] = true;
        echo json_encode(['success'=>true,'message'=>'Subscription activated','end_date'=>$end]);
    }
}