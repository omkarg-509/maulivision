<?php
require_once '../app/helpers/Auth.php';

class VendorController extends Controller
{
    public function index()
    {
        Auth::check(); $admin=Auth::user();
        $vendorModel=$this->model('Vendor');
        $vendors=$vendorModel->listByAdmin($admin['id']);
        $this->view('vendor/index',['vendors'=>$vendors]);
    }

    public function create()
    {
        Auth::check();
        $this->view('vendor/create');
    }

    public function store()
    {
            Auth::check(); 
        $admin=Auth::user();
        $full = trim($_POST['full_name']??'');
        $phone = trim($_POST['phone']??'');
        $email = trim($_POST['email']??'');
        $username = trim($_POST['username']??'');
        $password = trim($_POST['password']??'');
        $bname = trim($_POST['business_name']??'');
        $brole = trim($_POST['business_role']??'');
        $bnum = trim($_POST['business_number']??'');
        $baddr= trim($_POST['business_address']??'');
        if($full==='' || $phone==='' || $bname===''){
            $_SESSION['error']='Required fields missing';
            header('Location: '.BASE_URL.'vendor/create'); return;
        }
        $password=password_hash($password,PASSWORD_DEFAULT);
        $vendorModel=$this->model('Vendor');
        $id=$vendorModel->create($admin['id'],$full,$phone,$email,$username,$password,$bname,$brole,$bnum,$baddr);
        header('Location: '.BASE_URL.'vendor/index');
    }

    public function toggle($id=null)
    {
        Auth::check(); header('Content-Type: application/json'); $admin=Auth::user(); $id=(int)$id; if($id<=0){ echo json_encode(['ok'=>false]); return; }
        $vendorModel=$this->model('Vendor'); $ok=$vendorModel->toggleStatus($id,$admin['id']); echo json_encode(['ok'=>(bool)$ok]);
    }
}
?>