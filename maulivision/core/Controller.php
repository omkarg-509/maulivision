<?php

class Controller
{
    public function model($model)
    {
        require_once '../app/models/' . $model . '.php';
        return new $model();
    }

    public function view($view, $data = [])
{
    require_once "../app/views/layouts/header.php";
    require_once "../app/views/layouts/sidebar.php";
    require_once '../app/views/' . $view . '.php';
    require_once "../app/views/layouts/footer.php";
    
}

    public function home($home , $data = [])
{
       require_once '../app/views/' . $home . '.php';
}

}
