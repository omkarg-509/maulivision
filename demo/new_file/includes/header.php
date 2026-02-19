
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard - Door Maker App</title>
   <link rel="stylesheet" href="https://demo.maulivision.in/assets/css/style.css">

</head>
<body>
 <div class="topbar">
        <div class="title">Door Maker Billing</div>
        <div style="display:flex; gap:10px; align-items:center;">
            <div class="user">
                Hi, <?php echo htmlspecialchars($_SESSION['user_name'] ?? "User"); ?>
            </div>
            <a class="logout" href="logout">Logout</a>
        </div>
    </div>