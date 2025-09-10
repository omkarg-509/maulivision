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
}
