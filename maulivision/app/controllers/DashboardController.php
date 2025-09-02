<?php
require_once '../app/helpers/Auth.php';
class DashboardController extends Controller
{
    public function index()
    {
        Auth::check(); // ✅ session check
        $dashboardModel = $this->model('Dashboard');
        $superadminCount = (int) $dashboardModel->countSuperAdmins();
        $status = method_exists($dashboardModel, 'connectionStatus') ? $dashboardModel->connectionStatus() : ['db'=>false,'db2'=>false];
        $this->view('dashboard/index', [
            'superadminCount' => $superadminCount,
            'dbStatus' => $status['db'],
            'db2Status' => $status['db2']
        ]);
    }

    public function vendors()
    {
    Auth::check();
    $this->view('dashboard/vendors');

    }

    // Lightweight health endpoint to verify DB connectivity
    public function health()
    {
        $dashboardModel = $this->model('Dashboard');
        $status = method_exists($dashboardModel, 'connectionStatus') ? $dashboardModel->connectionStatus() : ['db'=>false,'db2'=>false];
        header('Content-Type: application/json');
        echo json_encode([
            'ok' => ($status['db'] && $status['db2']),
            'db' => $status['db'],
            'db2' => $status['db2']
        ]);
        exit;
    }
}
