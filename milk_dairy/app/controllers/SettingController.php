<?php
require_once '../app/helpers/Auth.php';
class SettingController extends Controller
{
    public function index()
    {
        Auth::check();
        $model = $this->model('Setting');
        $settings = $model->getAll();
        $this->view('setting/index', ['settings' => $settings]);
    }

    public function update()
    {
        Auth::check();
        header('Content-Type: application/json');
        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            echo json_encode(['success'=>false,'message'=>'Invalid']);return;
        }
        $id = intval($_POST['id'] ?? 0);
        if(!$id){
            echo json_encode(['success'=>false,'message'=>'Missing id']);return;
        }
        $model = $this->model('Setting');
        $res = $model->setActive($id);
        if($res) echo json_encode(['success'=>true,'message'=>'Setting updated']); else echo json_encode(['success'=>false,'message'=>'Failed']);
    }
}
