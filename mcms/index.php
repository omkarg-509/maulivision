<?php
session_start();
require_once 'config/config.php'; // Ensure this file is included to set up the Database class

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
    <div class="login-container">
        <h2>Login</h2>
        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="post" action="">
            <input type="text" name="username" placeholder="Username" required autofocus value="<?= isset($username) ? htmlspecialchars($username) : '' ?>">
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" value="Login">
        </form>
    </div>
 <?=(new Footer())->render();?>