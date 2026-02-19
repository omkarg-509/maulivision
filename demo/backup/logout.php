<?php
session_start();

// सर्व session data remove
session_unset();
session_destroy();

// login page ला redirect
header("Location: login.php");
exit;
