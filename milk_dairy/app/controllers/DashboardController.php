<?php
require_once '../app/models/Customer.php';
require_once '../app/helpers/Auth.php';
class DashboardController extends Controller
{
    public function index()
    {
        Auth::check(); // If using auth
        $this->view('dashboard/index');
    }
        public function toggleDarkMode()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $user_id = isset($_SESSION['vendor']['id']) ? $_SESSION['vendor']['id'] : null;
        if (!$user_id) {
            echo json_encode(['status' => 'error', 'message' => 'Not logged in.']);
            exit;
        }
        require_once '../app/models/Setting.php';
        $settingModel = new Setting();
        $setting = $settingModel->getOrCreate($user_id);
        $new_mode = $setting['dark_mode'] ? 0 : 1;
        $settingModel->updateDarkMode($user_id, $new_mode);
        echo json_encode(['status' => 'success', 'dark_mode' => $new_mode]);
        exit;
    }
    
}
