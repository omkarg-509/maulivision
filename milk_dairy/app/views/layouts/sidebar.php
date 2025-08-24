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
      var LS_KEY = 'maulivision_lang';
      var select = document.getElementById('siteLangSelect');

      function setCookie(name, value, days) {
        var expires = '';
        if (days) {
          var date = new Date();
          date.setTime(date.getTime() + (days*24*60*60*1000));
          expires = '; expires=' + date.toUTCString();
        }
        document.cookie = name + '=' + value + expires + '; path=/';
      }

      function loadGoogleTranslate(cb) {
        if (window.google && window.google.translate) {
          return cb && cb();
        }
        // create hidden container if not present
        if (!document.getElementById('google_translate_element')) {
          var div = document.createElement('div');
          div.id = 'google_translate_element';
          div.style.display = 'none';
          document.body.appendChild(div);
        }
        window.__gtOnLoad = function(){
          try{
            new google.translate.TranslateElement({pageLanguage: 'en', layout: google.translate.TranslateElement.InlineLayout.SIMPLE, includedLanguages: 'hi,en,es,fr'}, 'google_translate_element');
          }catch(e){console.warn('GT init', e)}
          cb && setTimeout(cb, 200);
        };
        if (!document.getElementById('__google_translate_script')) {
          var s = document.createElement('script');
          s.id = '__google_translate_script';
          s.src = '//translate.google.com/translate_a/element.js?cb=__gtOnLoad';
          s.async = true;
          document.body.appendChild(s);
        }
      }

      function setWidgetLang(lang) {
        var sel = document.querySelector('#google_translate_element select');
        if (!sel) return false;
        // try to match by value or text
        var target = (lang === 'auto' ? '' : lang).toLowerCase();
        for(var i=0;i<sel.options.length;i++){
          var opt = sel.options[i];
          if ((opt.value || '').toLowerCase().indexOf(target) !== -1 || (opt.text || '').toLowerCase().indexOf(target) !== -1) {
            sel.selectedIndex = i;
            sel.dispatchEvent(new Event('change'));
            return true;
          }
        }
        return false;
      }

      function applyLang(lang, save) {
        try { if (lang && lang !== 'auto') document.documentElement.lang = lang; else document.documentElement.removeAttribute('lang'); } catch(e){}
        if (save) try{ localStorage.setItem(LS_KEY, lang); }catch(e){}
        var val = '/auto/' + (lang === 'auto' ? '' : lang);
        try{ setCookie('googtrans', val, 365); setCookie('__googtrans', val, 365); }catch(e){console.warn(e)}

        // try apply immediately via widget; if not available load it then apply
        if (setWidgetLang(lang)) return;
        loadGoogleTranslate(function(){
          // small delay to let widget build
          setTimeout(function(){ setWidgetLang(lang); }, 400);
        });
      }

      // init
      try {
        var cur = localStorage.getItem(LS_KEY) || 'auto';
        if (select) {
          select.value = cur;
          select.addEventListener('change', function(){ 
            var v = this.value;
            try { var vendorId = <?= json_encode($vendor['id'] ?? null) ?>; } catch(e) { var vendorId = null; }
            if (vendorId) {
              var xhr = new XMLHttpRequest();
              xhr.open('POST', '<?= BASE_URL ?>set_language.php', true);
              xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
              xhr.send('vendor_id=' + encodeURIComponent(vendorId) + '&lang=' + encodeURIComponent(v));
            }
            applyLang(v, true);
          });
        }
        // apply initial language silently
        if (cur && cur !== 'auto') {
          try{ setCookie('googtrans', '/auto/' + cur, 365); setCookie('__googtrans', '/auto/' + cur, 365); }catch(e){}
          // ensure widget exists and apply
          loadGoogleTranslate(function(){ setWidgetLang(cur); });
        }
      } catch(err) { console.warn(err); }
    })();
  </script>
