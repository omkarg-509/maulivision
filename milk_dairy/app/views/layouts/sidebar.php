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
<div class="navbar-bg"></div>
      <nav class="navbar navbar-expand-lg main-navbar">
     <div class="form-inline me-auto">
          <ul class="navbar-nav navbar-left">
        
          <a href="<?=BASE_URL?>dashboard" 
              class="nav-link dropdown-toggle nav-link-lg nav-link-user">
              <img alt="image" src="<?=BASE_URL?>/assets/img/logo-1.png" class="">

            </a>
          
        </ul>
          
        </div>
        <ul class="navbar-nav me-3">
            
            <li><a href="#" data-bs-toggle="sidebar" class="nav-link nav-link-lg collapse-btn"><i
                  class="fas fa-bars"></i></a></li>
           
          </ul>
      </nav>
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
<!-- Floating translator button (site-wide) -->
<div id="gt_translate_wrapper" style="position:fixed;right:18px;bottom:18px;z-index:2000;">
  <button id="translateBtn" title="Translate page" type="button" class="btn btn-sm btn-outline-secondary" style="border-radius:50%;width:48px;height:48px;display:flex;align-items:center;justify-content:center;box-shadow:0 6px 18px rgba(0,0,0,.12);">
    <i class="fas fa-globe"></i>
  </button>
  <div id="google_translate_element" style="display:none;margin-top:8px;background:#fff;padding:8px;border-radius:8px;box-shadow:0 6px 18px rgba(0,0,0,.12);"></div>
</div>

<script>
  (function(){
    var inited = false;
    function loadGoogleTranslate(){
      if (inited) return;
      inited = true;
      var gt = document.createElement('script');
      gt.type = 'text/javascript';
      gt.src = '//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit';
      document.body.appendChild(gt);
      window.googleTranslateElementInit = function(){
        try{
          new google.translate.TranslateElement({
            pageLanguage: 'en',
            layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
            includedLanguages: 'hi,en,mt,mr'
          }, 'google_translate_element');
        }catch(e){
          console.warn('Google Translate init failed', e);
        }
      };
    }
    var btn = document.getElementById('translateBtn');
    var widget = document.getElementById('google_translate_element');
    if(btn){
      btn.addEventListener('click', function(){
        if(widget.style.display === 'none' || widget.style.display === ''){
          widget.style.display = 'block';
          loadGoogleTranslate();
        } else {
          widget.style.display = 'none';
        }
      });
    }
  })();
</script>