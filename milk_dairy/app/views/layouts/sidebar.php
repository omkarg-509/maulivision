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
            <li class="nav-item d-flex align-items-center ms-2">
              <select id="siteLangSelect" class="form-select form-select-sm" style="width:auto; min-width:110px;">
                <option value="auto">Language: Auto</option>
                <option value="en">English</option>
                <option value="mr">Marathi</option>
                <option value="hi">Hindi</option>
              </select>
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
  <script>
    (function(){
      // key used in localStorage
      var LS_KEY = 'maulivision_lang';
      var select = document.getElementById('siteLangSelect');

      function setCookie(name, value, days) {
        var expires = '';
        if (days) {
          var date = new Date();
          date.setTime(date.getTime() + (days*24*60*60*1000));
          expires = '; expires=' + date.toUTCString();
        }
        var domain = location.hostname;
        document.cookie = name + '=' + value + expires + '; path=/';
      }

      function applyLang(lang, save) {
        // set document language attribute
        if (lang && lang !== 'auto') document.documentElement.lang = lang; else document.documentElement.removeAttribute('lang');
        // store selection
        if (save) localStorage.setItem(LS_KEY, lang);
        // set google translate cookie to instruct widget (common pattern)
        try {
          var val = '/auto/' + (lang === 'auto' ? '' : lang);
          // set cookie twice for compatibility
          setCookie('googtrans', val, 365);
          setCookie('__googtrans', val, 365);
        } catch(e) { console.warn('Could not set translate cookie', e); }
        // reload to let any translation widget pick up the change
        // For a smoother UX you can remove reload and integrate with the translate widget API if present
        window.location.reload();
      }

      // init select from localStorage
      try {
        var cur = localStorage.getItem(LS_KEY) || 'auto';
        if (select) {
          select.value = cur;
          select.addEventListener('change', function(){ applyLang(this.value, true); });
        }
        // apply initially without saving if present
        if (cur && cur !== 'auto') {
          // set cookie so translate widget applies across pages
          setCookie('googtrans', '/auto/' + cur, 365);
        }
      } catch(err) { console.warn(err); }
    })();
  </script>
