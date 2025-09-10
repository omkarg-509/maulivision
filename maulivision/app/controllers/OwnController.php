<?php
require_once '../app/helpers/Auth.php';
class OwnController extends Controller
{
    public function index()
    {
        Auth::check();
        $admin = Auth::user();
        $todoModel = $this->model('Todo');
        $todos = $todoModel->allByAdmin($admin['id']);
        $this->view('own/index', ['todos' => $todos]);
    }

    public function add()
    {
        Auth::check();
        header('Content-Type: application/json');
        $admin = Auth::user();
        $title = trim($_POST['title'] ?? '');
        if ($title === '') {
            echo json_encode(['ok' => false, 'error' => 'Empty title']);
            return;
        }
        $todoModel = $this->model('Todo');
        $id = $todoModel->create($admin['id'], $title);
        echo json_encode([
            'ok' => $id > 0,
            'id' => $id,
            'title' => htmlspecialchars($title, ENT_QUOTES, 'UTF-8')
        ]);
    }

    public function toggle($id = null)
    {
        Auth::check();
        header('Content-Type: application/json');
        $admin = Auth::user();
        $id = (int)$id;
        if ($id <= 0) {
            echo json_encode(['ok' => false]);
            return;
        }
        $todoModel = $this->model('Todo');
        $ok = $todoModel->toggle($id, $admin['id']);
        echo json_encode(['ok' => (bool)$ok]);
    }

    public function delete($id = null)
    {
        Auth::check();
        header('Content-Type: application/json');
        $admin = Auth::user();
        $id = (int)$id;
        if ($id <= 0) {
            echo json_encode(['ok' => false]);
            return;
        }
        $todoModel = $this->model('Todo');
        $ok = $todoModel->delete($id, $admin['id']);
        echo json_encode(['ok' => (bool)$ok]);
    }

    public function stats()
    {
        Auth::check();
        header('Content-Type: application/json');
        $admin = Auth::user();
        $from = isset($_GET['from']) ? $_GET['from'] : null;
        $to = isset($_GET['to']) ? $_GET['to'] : null;
        $todoModel = $this->model('Todo');
        $rows = $todoModel->getDailyStats($admin['id'], $from, $to);
        echo json_encode(['ok' => true, 'data' => $rows]);
    }

    /* Finance Section */
    public function finance()
    {
        Auth::check();
        $admin = Auth::user();
        $financeModel = $this->model('Finance');
        $entries = $financeModel->allByAdmin($admin['id']);
        $this->view('own/finance', ['entries' => $entries]);
    }

    public function financeAdd()
    {
        Auth::check();
        header('Content-Type: application/json');
        $admin = Auth::user();
        $type = $_POST['type'] ?? '';
        $method = $_POST['method'] ?? 'cash';
        $amount = isset($_POST['amount']) ? (float)$_POST['amount'] : 0;
        $note = trim($_POST['note'] ?? '');
        $date = $_POST['entry_date'] ?? date('Y-m-d');
        $validTypes = ['income','expense','borrow','repay'];
        $validMethods = ['cash','online'];
        if(!in_array($type,$validTypes) || !in_array($method,$validMethods) || $amount <= 0){
            echo json_encode(['ok'=>false,'error'=>'Invalid data']); return; }
        $financeModel = $this->model('Finance');
        $id = $financeModel->create($admin['id'],$type,$method,$amount,$note,$date);
        echo json_encode(['ok'=>$id>0,'id'=>$id,'type'=>$type,'method'=>$method,'amount'=>$amount,'note'=>htmlspecialchars($note,ENT_QUOTES,'UTF-8'),'entry_date'=>$date]);
    }

    public function financeDelete($id=null)
    {
        Auth::check();
        header('Content-Type: application/json');
        $admin = Auth::user();
        $id = (int)$id; if($id<=0){ echo json_encode(['ok'=>false]); return; }
        $financeModel = $this->model('Finance');
        $ok = $financeModel->delete($id,$admin['id']);
        echo json_encode(['ok'=>(bool)$ok]);
    }

    public function financeStats()
    {
        Auth::check();
        header('Content-Type: application/json');
        $admin = Auth::user();
        $from = $_GET['from'] ?? null;
        $to = $_GET['to'] ?? null;
        $financeModel = $this->model('Finance');
        $daily = $financeModel->dailyStats($admin['id'],$from,$to);
        $summary = $financeModel->summaryTotals($admin['id'],$from,$to);
        echo json_encode(['ok'=>true,'daily'=>$daily,'summary'=>$summary]);
    }
}
