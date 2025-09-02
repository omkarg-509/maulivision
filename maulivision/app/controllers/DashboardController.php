<?php
class DashboardController extends Controller
{
    public function index()
    {

        // Fetch vendor count from db2
        $db2 = new Database('db2'); // Assuming you have a Database class that accepts a connection name
        $pdo = $db2->getConnection(); // Assuming your Database class has a getConnection() method that returns the PDO instance
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM vendors");
        $stmt->execute();
        $vendorCount = $stmt->fetch()['count'];

        $this->view('dashboard/index', ['vendorCount' => $vendorCount]);
    
    }

    public function vendors()
    {

        $this->view('dashboard/vendors');

    }
}
