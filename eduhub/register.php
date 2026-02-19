<?php
session_start();

// जर user आधीच login असेल तर dashboard ला पाठव
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$msg = "";
if (isset($_SESSION['msg'])) {
    $msg = $_SESSION['msg'];
    unset($_SESSION['msg']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <style>
        body{
            font-family: Arial, sans-serif;
            background:#f4f6f9;
            display:flex;
            justify-content:center;
            align-items:center;
            height:100vh;
            margin:0;
        }
        .box{
            width:380px;
            background:#fff;
            padding:25px;
            border-radius:12px;
            box-shadow:0 8px 20px rgba(0,0,0,0.1);
        }
        h2{
            margin:0 0 15px 0;
            text-align:center;
        }
        input{
            width:100%;
            padding:12px;
            margin:8px 0;
            border:1px solid #ccc;
            border-radius:8px;
            font-size:15px;
        }
        button{
            width:100%;
            padding:12px;
            margin-top:10px;
            background:#007bff;
            border:none;
            color:white;
            font-size:16px;
            border-radius:8px;
            cursor:pointer;
        }
        button:hover{
            background:#0056b3;
        }
        .msg{
            padding:10px;
            margin-bottom:10px;
            border-radius:8px;
            font-size:14px;
            text-align:center;
        }
        .error{ background:#ffe5e5; color:#b30000; }
        .success{ background:#e6ffed; color:#006b1b; }
        .link{
            text-align:center;
            margin-top:12px;
            font-size:14px;
        }
        .link a{
            text-decoration:none;
            color:#007bff;
        }
    </style>
</head>
<body>

<div class="box">
    <h2>Create Account</h2>

    <?php if($msg != ""): ?>
        <div class="msg <?php echo (str_contains($msg, 'Success')) ? 'success' : 'error'; ?>">
            <?php echo $msg; ?>
        </div>
    <?php endif; ?>

    <form action="register_save.php" method="POST">
        <input type="text" name="full_name" placeholder="Full Name" required>

        <input type="text" name="mobile" placeholder="Mobile (Optional)">

        <input type="email" name="email" placeholder="Email" required>

        <input type="password" name="password" placeholder="Password" required>

        <input type="password" name="confirm_password" placeholder="Confirm Password" required>

        <button type="submit" name="register_btn">Register</button>
    </form>

    <div class="link">
        Already have an account? <a href="login.php">Login</a>
    </div>
</div>

</body>
</html>
