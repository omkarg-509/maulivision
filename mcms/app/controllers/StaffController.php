<?php
require_once '../app/helpers/Auth.php';
class StaffController extends Controller
{
    public function index()
    {
         Auth::check(); // 🔒 Protect the staff page
        $this->view('staff/index');
    }

   public function create()
   {
       Auth::check(); // 🔒 Protect the staff page
       $this->view('staff/create');
   }

   public function edit($id)
   {
       Auth::check(); // 🔒 Protect the staff page
       $this->view('staff/edit', ['id' => $id]);
   }

   public function delete($id)
   {
       Auth::check(); // 🔒 Protect the staff page
       $this->view('staff/delete', ['id' => $id]);
   }

}
