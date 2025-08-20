<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$vendor = isset($_SESSION['vendor']) ? $_SESSION['vendor'] : null;
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
              <span class="logo-name"><?= htmlspecialchars($vendor['business_name']) ?></span>
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
          
          <li class="">
              <a href="<?=BASE_URL?>auth/logout" class="nav-link"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a>
          </li>
          <li class="">
            <a href="#" class="nav-link" id="toggle-theme-btn">
              <i class="fas fa-adjust"></i><span>Dark/Light Mode</span>
            </a>
          </li>
          <script>
          document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('toggle-theme-btn');
            btn.addEventListener('click', function(e) {
              e.preventDefault();
              document.body.classList.toggle('dark-mode');
              // Optionally, save preference to localStorage
              if(document.body.classList.contains('dark-mode')) {
                localStorage.setItem('theme', 'dark');
              } else {
                localStorage.setItem('theme', 'light');
              }
            });
            // On load, set theme from localStorage
            if(localStorage.getItem('theme') === 'dark') {
              document.body.classList.add('dark-mode');
            }
          });
          </script>
          <style>
          /* Example dark mode styles */
          .dark-mode {
            background: #222 !important;
            color: #eee !important;
          }
          .dark-mode .main-sidebar,
          .dark-mode .navbar,
          .dark-mode .sidebar-menu,
          .dark-mode .sidebar-brand {
            background: #23272b !important;
            color: #eee !important;
          }
          .dark-mode a, .dark-mode .nav-link, .dark-mode .sidebar-menu li a {
            color: #eee !important;
          }
          </style>
             <li class="">
          <a href="/public/subscription" class="nav-link">
              <i class="fas fa-bell"></i><span>Subscription</span>
          </a>
             </li>
              </ul>
        </aside>
      </div>