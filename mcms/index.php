<?php
session_start();
require_once 'config/config.php'; // Ensure this file is included to set up the Database class
if (!isset($_SESSION['vendor_id'])) {
    header('Location: login.php');
    exit();
}
?>
<?=(new Header())->render();?>
<?=(new Sidebar())->render();?>
<div class="main-content">
  <div class="loader"></div>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">
        <section class="section">
            <div class="section-body">
                <div class="row mt-5">
                
                     <div class="col-6 col-md-3 col-lg-3">
                        <div class="card  btn btn-default">
                            <a class="card-body  text-center mt-4 mb-2 " href="../Customers?d=true"><i class="fas fa-users " style="font-size:30px ;"></i> </a><a>Customers</a>
                        </div>
                    </div>
                </div>
             </div>
             
        </section>
        
    </div>
    
  </div>
 </div>
  </div>

 <?=(new Footer())->render();?>