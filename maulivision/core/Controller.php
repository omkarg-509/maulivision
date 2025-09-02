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
    // Make passed data available as individual variables in views
    if (is_array($data) && !empty($data)) {
        extract($data, EXTR_SKIP);
    }
    require_once "../app/views/layouts/header.php";
    require_once '../app/views/' . $view . '.php';
    require_once "../app/views/layouts/footer.php";
    
}

    public function home($home , $data = [])
{
       require_once '../app/views/' . $home . '.php';
}

}
