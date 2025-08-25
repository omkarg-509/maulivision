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
?>
<style>
  .navbar-bg {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: auto; /* Or remove this line */
        z-index: 879; /* keep navbar above other elements */
    }
</style>
<div class="navbar-bg">
      <nav class="navbar navbar-expand-lg main-navbar fixed-top">
        <div class="container-fluid">
          <ul class="navbar-nav me-auto">
            <li class="nav-item">
              <a href="<?=BASE_URL?>dailyentry/" class="nav-link nav-link-lg nav-link-user">
                <img alt="logo" src="<?=BASE_URL?>assets/img/logo-1.png" style="height:40px;">
              </a>
            </li>
          </ul>
          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <a href="#" data-bs-toggle="sidebar" class="nav-link nav-link-lg collapse-btn">
                <i class="fas fa-bars"></i>
              </a>
            </li>
          </ul>
        </div>
      </nav>
      </div>
      <div class="main-sidebar sidebar-style-2">
        <aside id="sidebar-wrapper">
          <div class="sidebar-brand">
            <a href="index.html">
  <span class="logo-name"  style="font-size:12px !important">
    <?= htmlspecialchars($vendor['business_name']) ?>
</span>
            </a>
          
          </div>
          <ul class="sidebar-menu">
            <?php
            // Check if session language is 'mr' (Marathi)
            $lang = isset($vendor['lng']) ? $vendor['lng'] : 'en';
            echo $lang;
            ?>
            <li class="menu-header">
              <?= ($lang === 'mr') ? 'मुख्य' : 'Main' ?>
            </li>
             


            <li class="active">
                <?php
                // Use session language to display menu in Marathi or English
                $dashboardLabel = ($lang === 'mr') ? 'डॅशबोर्ड' : 'Dashboard';
                ?>
                <a href="<?=BASE_URL?>dashboard" class="nav-link "><i class="fas fa-home"></i><span><?= $dashboardLabel ?></span></a>
            </li>
            <li class="dropdown">
              <a href="#" class="nav-link has-dropdown"><i class="fas fa-users"></i><span>Customers</span></a>
              <ul class="dropdown-menu">
                <li class=""><a class="nav-link" href="<?=BASE_URL?>customer/create">Create Customers</a></li>
                <li><a class="nav-link" href="<?=BASE_URL?>customer/index">Customers Details</a></li>
                
              </ul>
            </li>
            <li>
              <a href="<?=BASE_URL?>dailyentry/history" class="nav-link"><i class="fas fa-history"></i><span>History</span></a>
            </li>
          
          <li class="">
              <a href="<?=BASE_URL?>auth/logout" class="nav-link"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a>
          </li>
          
             <!-- <li class="">
          <a href="/public/subscription" class="nav-link">
              <i class="fas fa-bell"></i><span>Subscription</span>
          </a>
             </li> -->
              </ul>
        </aside>
      </div>
  <?php // if ($showSubscriptionPopup) { include '../app/views/layouts/subscription_popup.php'; } ?>
