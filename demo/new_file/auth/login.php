<?php 
// include'../config/auth.php'; 
include'../includes/header.php'; 

?>


<div class="login-card">

        <div class="app-title">Door Maker Billing</div>
        <div class="app-subtitle">Login to continue</div>

        <?php if ($error !== "") { ?>
            <div class="error-box"><?php echo htmlspecialchars($error); ?></div>
        <?php } ?>

        <form method="POST" action="login_check" autocomplete="off">

            <label>Username / Mobile</label>
            <input type="text" name="username" placeholder="Enter username or mobile"
                   value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required />

            <label>Password</label>
            <input type="password" name="password" placeholder="Enter password" required />

            <button type="submit" class="btn">Login</button>

        </form>

        <div class="hint">
            Demo Login: <b>admin</b> | Password: <b>12345</b>
        </div>

        <div class="footer">© 2026 Door Maker App</div>

    </div>

<?php  include'includes/footer.php'; ?>