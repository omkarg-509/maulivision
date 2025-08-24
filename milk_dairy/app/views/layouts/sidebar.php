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
            <li class="menu-header">Main</li>
             


            <li class="active">
              <a href="<?=BASE_URL?>dashboard" class="nav-link "><i class="fas fa-home"></i><span>Dashboard</span></a>
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
  <!-- Language Switcher Button -->
  <div style="position: fixed; bottom: 20px; left: 20px; z-index: 999;">
    <form method="post" action="" id="lang-switch-form">
      <select name="lang" id="lang-select" class="form-select form-select-sm">
      <option value="en" <?= (isset($_SESSION['lang']) && $_SESSION['lang'] === 'en') ? 'selected' : '' ?>>English</option>
      <option value="hi" <?= (isset($_SESSION['lang']) && $_SESSION['lang'] === 'hi') ? 'selected' : '' ?>>हिन्दी</option>
      <option value="mr" <?= (isset($_SESSION['lang']) && $_SESSION['lang'] === 'mr') ? 'selected' : '' ?>>मराठी</option>
      <!-- Add more languages as needed -->
      </select>
    </form>
    <div id="google_translate_element" style="display:none;"></div>
    <script type="text/javascript">
      function googleTranslateElementInit() {
      new google.translate.TranslateElement({
        pageLanguage: 'en',
        includedLanguages: 'en,hi,mr',
        layout: google.translate.TranslateElement.InlineLayout.SIMPLE
      }, 'google_translate_element');
      }

      // Load Google Translate script
      (function() {
      var gt = document.createElement('script');
      gt.type = 'text/javascript';
      gt.async = true;
      gt.src = '//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit';
      var s = document.getElementsByTagName('script')[0];
      s.parentNode.insertBefore(gt, s);
      })();

      // Map your language codes to Google Translate codes
      const langMap = {
      'en': 'en',
      'hi': 'hi',
      'mr': 'mr'
      };

      document.getElementById('lang-select').addEventListener('change', function() {
      var lang = this.value;
      var googleLang = langMap[lang] || 'en';
      // Set Google Translate cookie and reload
      document.cookie = 'googtrans=/en/' + googleLang + ';path=/';
      location.reload();
      });

      // On page load, set Google Translate language if session lang is set
      <?php if (isset($_SESSION['lang'])): ?>
      (function() {
      var googleLang = langMap['<?= $_SESSION['lang'] ?>'] || 'en';
      if (googleLang !== 'en') {
        document.cookie = 'googtrans=/en/' + googleLang + ';path=/';
      }
      })();
      <?php endif; ?>
    </script>
  </div>
