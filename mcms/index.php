<?php
session_start();
require_once 'config/config.php'; // Ensure this file is included to set up the Database class
require_once 'db.php'; // Make sure this file sets up $pdo as a PDO instance

// Legacy MySQLi connection (not used in this script, but included as requested)




// Redirect to dashboard if already logged in
if (isset($_SESSION['vendor_id'])) {
    header('Location: dashboard.php');
    exit;
}

// Handle login form submission
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Check credentials against vendor table using MySQLi ($conn)
    $stmt = $conn->prepare('SELECT id, password FROM vendor WHERE username = ? LIMIT 1');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->bind_result($id, $hashed_password);
    if ($stmt->fetch() && password_verify($password, $hashed_password)) {
        $_SESSION['vendor_id'] = $id;
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Invalid username or password.';
    }
    $stmt->close();
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