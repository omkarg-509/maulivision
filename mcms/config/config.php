<?php

class Database {
    private $host = 'localhost';
    private $db_name = 'u367009900_mcms';
    private $username = 'u367009900_mcms';
    private $password = '!NK6N*17p';
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name}",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}

// Usage example:
$connection = (new Database())->getConnection();


class Header {
    public function render() {
      ?>
      <!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  
  <!-- General CSS Files -->
  <link rel="stylesheet" href="../assets/css/app.min.css">
  <!-- Template CSS -->
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="../assets/css/components.css">
  <!-- Custom style CSS -->
  <link rel="stylesheet" href="../assets/css/custom.css">
  <link rel='shortcut icon' type='image/x-icon' href='../assets/img/favicon.ico' />
</head>


<body>

      <?php
    }
}
class Sidebar{
    public function render(){
        ?>
        <aside class="sidebar">
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="settings.php">Settings</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </aside>
        <?php
    }
}
class Footer {
    public function render() {
       ?>
       
</body>
    <script src="../js/script.js"></script>

   <!-- General JS Scripts -->
  <script src="../assets/js/app.min.js"></script>
  <!-- JS Libraies -->
  <script src="../assets/bundles/chartjs/chart.min.js"></script>
  <script src="../assets/bundles/apexcharts/apexcharts.min.js"></script>
  <!-- Page Specific JS File -->
  <script src="../assets/js/page/index.js"></script>
  <!-- Template JS File -->
  <script src="../assets/js/scripts.js"></script>
  <!-- Custom JS File -->
  <script src="../assets/js/custom.js"></script>
</body>
</html>

       <?php
}
}?>



