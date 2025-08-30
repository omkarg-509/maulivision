
<?php
// if (session_status() === PHP_SESSION_NONE) {
  //  session_start();
 //}
$vendor = isset($_SESSION['vendor']) ?    
  $_SESSION['vendor'] : null;
// // Determine if subscription popup should show ONLY on dashboard and only once per session (unless page reload after cookie expires)
// $showSubscriptionPopup = false;
// $path = $_GET['url'] ?? '';
// $isDashboard = ($path === '' || $path === 'dashboard');
// $hideUntil = $_COOKIE['hide_sub_popup_until'] ?? '';
// if ($hideUntil && strtotime($hideUntil) > time()) {
//   // User snoozed popup
//   $snoozed = true;
// } else {
//   $snoozed = false;
// }
// if ($vendor && $isDashboard && !$snoozed) {
//   if (empty($_SESSION['has_active_subscription'])) {
//     if (empty($_SESSION['subscription_popup_shown'])) {
//       $showSubscriptionPopup = true;
//       $_SESSION['subscription_popup_shown'] = true; // prevent multi-page spam
//     }
//   }
// }

?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  
  <!-- General CSS Files -->
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/app.min.css">
  <!-- Template CSS -->
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/components.css">
  <!-- Custom style CSS -->
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/custom.css">
  <link rel='shortcut icon' type='image/x-icon' href='<?= BASE_URL ?>assets/img/logo-1.png' />
  <title> <?= htmlspecialchars($vendor['business_name']) ?></title>
</head>


<body>
