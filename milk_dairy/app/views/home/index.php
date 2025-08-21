<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>MilkDairy - Smart Dairy Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <style>
    :root{--primary:#0d6efd;--muted:#6c757d}
    body {font-family: 'Poppins', sans-serif; background:#fff;}
    /* Navbar */
    .navbar {transition: background-color .35s ease, box-shadow .35s ease;}
    .nav-brand-img{height:44px}
    .nav-center{position:absolute; left:50%; transform:translateX(-50%);} 
    .nav-link{font-weight:500;color:rgba(0,0,0,.7)!important}
    .navbar-scrolled{background:#fff!important;box-shadow:0 6px 25px rgba(13,38,59,.08);}
    .navbar-transparent{background:transparent}

    /* Hero */
    .hero{background:linear-gradient(to bottom, rgba(9,30,63,.55), rgba(9,30,63,.25)), url('/public/assets/img/image-gallery/hero.jpg'); background-size:cover; background-position:center; color:#fff; min-height:80vh; display:flex; align-items:center}
    .hero .lead{color:rgba(255,255,255,.9)}

    /* Services */
    .service-card{border-radius:12px}

    /* Footer */
    .footer{background:#071022;color:#9aa4af;padding:48px 0}

    /* Responsive tweaks */
    @media(max-width:991px){
      .nav-center{position:static;transform:none;margin-left:0}
    }
  </style>
</head>
<body>
 <!-- Team modals -->
    <div class="modal fade" id="bio-omkar" tabindex="-1" aria-labelledby="bioOmkarLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="bioOmkarLabel">Omkar Vivek Gaikwad — PHP Developer &amp; Product Lead</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p>Omkar leads product strategy and backend development for MilkDairy. He focuses on product vision, UX, and making the platform reliable and easy to use for dairy businesses. He also handles design and overall project direction.</p>
            <p class="mb-0"><strong>Skills:</strong> PHP, MySQL, Bootstrap, UX Design, Product Management</p>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="bio-uttkarsha" tabindex="-1" aria-labelledby="bioUttkarshaLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="bioUttkarshaLabel">Uttkarsha Gundalkar — Frontend Developer</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p>Uttkarsha designs and implements user interfaces for MilkDairy, focusing on usability and a seamless experience. She ensures the platform is visually appealing and easy to navigate for all users.</p>
            <p class="mb-0"><strong>Skills:</strong> HTML, CSS, JavaScript, Bootstrap, UI/UX Design</p>
          </div>
        </div>
      </div>
    </div>
<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-transparent fixed-top py-3">
  <div class="container position-relative">
    <a class="navbar-brand d-flex align-items-center" href="/public">
      <img src="/public/assets/img/logo-1.png" alt="MilkDairy" class="nav-brand-img me-2 animate-logo" id="heartbeat-logo">
      <span class="fw-bold text-dark animate-name" id="animated-name"></span>
    </a>
    <style>
      /* Heartbeat animation for logo */
      @keyframes heartbeat {
        0%, 100% { transform: scale(1);}
        10%, 30%, 50%, 70%, 90% { transform: scale(1.12);}
        20%, 40%, 60%, 80% { transform: scale(0.95);}
      }
      .animate-logo {
        transition: transform 0.5s cubic-bezier(.68,-0.55,.27,1.55);
        animation: heartbeat 1.4s infinite;
      }
      .animate-logo:hover {
        transform: rotate(-10deg) scale(1.12);
        animation: none;
      }
      .animate-name {
        display: inline-block;
        transition: color 0.4s, letter-spacing 0.4s;
        min-width: 1em;
      }
      .navbar-brand:hover .animate-name {
        color: var(--primary);
        letter-spacing: 2px;
      }
    </style>
    <script>
      // Typewriter effect for brand name
      document.addEventListener('DOMContentLoaded', function() {
        const name = "MilkDairy";
        const el = document.getElementById('animated-name');
        let i = 0;
        function type() {
          if (i <= name.length) {
            el.textContent = name.slice(0, i);
            i++;
            setTimeout(type, 90);
          }
        }
        type();
      });
    </script>

    <!-- center links (desktop) -->
    <div class="nav-center d-none d-lg-block">
      <ul class="navbar-nav d-flex flex-row gap-3">
        <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
        <li class="nav-item"><a class="nav-link" href="#services">Services</a></li>
        <li class="nav-item"><a class="nav-link" href="#testimonials">Testimonials</a></li>
        <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
      </ul>
    </div>

    <!-- right CTAs -->
    <div class="d-none d-lg-flex ms-auto align-items-center gap-2">
      <a href="/public/auth/login" class="btn btn-sm btn-outline-primary">Login</a>
      <a href="/public/auth/register" class="btn btn-sm btn-primary">Get Started</a>
    </div>

    <!-- mobile toggle -->
    <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" style="border:none; box-shadow:none;">
      <i class="bi bi-list" style="font-size:1.25rem;color:var(--primary)"></i>
    </button>
  </div>
</nav>

<!-- MOBILE OFFCANVAS -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="mobileMenu">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title">Menu</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body">
    <ul class="list-unstyled mb-4">
      <li class="mb-2"><a class="text-decoration-none fs-5" href="#about">About</a></li>
      <li class="mb-2"><a class="text-decoration-none fs-5" href="#services">Services</a></li>
      <li class="mb-2"><a class="text-decoration-none fs-5" href="#testimonials">Testimonials</a></li>
      <li class="mb-2"><a class="text-decoration-none fs-5" href="#contact">Contact</a></li>
    </ul>
    <div class="d-grid gap-2">
      <a href="/public/auth/login" class="btn btn-outline-primary">Login</a>
      <a href="/public/auth/register" class="btn btn-primary">Get Started</a>
    </div>
    <hr class="my-4">
    <div class="small text-muted">Contact: <a href="mailto:support@maulivision.in">support@maulivision.in</a></div>
  </div>
</div>

<!-- HERO -->
<section class="hero">
  <div class="container text-center" data-aos="fade-up">
    <img src="/public/assets/img/logo-1.png" alt="MilkDairy" height="90" class="mb-3">
    <h1 class="display-5 fw-bold">Manage Your Dairy — Smarter</h1>
    <p class="lead mb-4">Record daily milk entries, manage customers, generate bills and run subscriptions from one place.</p>
    <div class="d-flex justify-content-center gap-3">
      <a class="btn btn-lg btn-primary px-4" href="/public/auth/register">Get Started</a>
      <a class="btn btn-lg btn-outline-light px-4" href="/public/auth/login">Sign In</a>
    </div>
  </div>
</section>

<!-- ABOUT -->
<section id="about" class="py-5">
  <div class="container" data-aos="fade-up">
    <div class="row align-items-center">
      <div class="col-lg-6">
        <h2 class="fw-bold">About MilkDairy</h2>
        <p class="text-muted">MilkDairy helps small and medium dairy businesses streamline daily operations. Capture entries, track customers, bill easily, and export reports.</p>
        <ul class="text-muted">
          <li>Quick daily entry workflow</li>
          <li>Customer management and history</li>
          <li>Billing, invoices and PDF export</li>
        </ul>
      </div>
      <div class="col-lg-6">
        <img src="/public/assets/img/image-gallery/hero-2.jpg" class="img-fluid rounded shadow-sm" alt="About">
      </div>
    </div>
  </div>
</section>

<!-- SERVICES -->
<section id="services" class="py-5 bg-light">
  <div class="container" data-aos="fade-up">
    <div class="text-center mb-4">
      <h3 class="fw-bold">Features</h3>
      <p class="text-muted">Tools built specifically for the dairy business</p>
    </div>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="card p-4 service-card h-100">
          <div class="mb-3 text-primary h1"><i class="bi bi-journal-check"></i></div>
          <h5>Daily Entry</h5>
          <p class="text-muted">Fast input for quantities, fat, SNF and rates per customer.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card p-4 service-card h-100">
          <div class="mb-3 text-primary h1"><i class="bi bi-people-fill"></i></div>
          <h5>Customer Management</h5>
          <p class="text-muted">Maintain customer profiles, contact and billing history.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card p-4 service-card h-100">
          <div class="mb-3 text-primary h1"><i class="bi bi-receipt-cutoff"></i></div>
          <h5>Billing & Reports</h5>
          <p class="text-muted">Generate bills, view transactions and export PDFs.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- OUR TEAM -->
<section id="team" class="py-5">
  <div class="container" data-aos="fade-up">
    <div class="text-center mb-4">
      <h3 class="fw-bold">Our Team</h3>
      <p class="text-muted">Small team, big impact — people who build and support MilkDairy.</p>
    </div>

    <style>
      .team-card{border-radius:14px;transition:transform .28s ease,box-shadow .28s ease}
      .team-card:hover{transform:translateY(-6px);box-shadow:0 12px 30px rgba(10,40,80,.12)}
      .team-photo{width:110px;height:110px;object-fit:cover;border-radius:50%;border:6px solid #fff;margin-top:-66px;box-shadow:0 6px 20px rgba(10,40,80,.08)}
      .team-role{font-size:.9rem;color:var(--muted)}
      .social-icons a{color:var(--primary);margin:0 .35rem}
      .team-card .card-body{padding-top:4rem}
    </style>

    <div class="row g-4 justify-content-center">
      <!-- Omkar -->
      <div class="col-sm-8 col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="50">
        <div class="card text-center team-card h-100">
          <div class="card-body position-relative">
            <img src="/public/assets/img/users/omkar.png" alt="Omkar Gaikwad" class="team-photo mx-auto d-block">
            <h5 class="mt-3 mb-1">Omkar Gaikwad</h5>
            <div class="team-role mb-2">PHP Developer &amp; Product Lead</div>
            <p class="text-muted small">Leads backend development and product direction — manages vision, context and overall delivery for MilkDairy.</p>
            <div class="social-icons mt-3">
              <a href="#" aria-label="Omkar - twitter"><i class="bi bi-twitter"></i></a>
              <a href="#" aria-label="Omkar - linkedin"><i class="bi bi-linkedin"></i></a>
              <a href="#" aria-label="Omkar - github"><i class="bi bi-github"></i></a>
            </div>
            <div class="mt-3">
              <a href="#" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#bio-omkar">View bio</a>
            </div>
          </div>
        </div>
      </div>

     

  

    <!-- Uttkarsha -->
    <div class="col-sm-8 col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="170">
      <div class="card text-center team-card h-100">
        <div class="card-body position-relative">
        <img src="/public/assets/img/users/uttkarsha.png" alt="Uttkarsha Gundalkar" class="team-photo mx-auto d-block">
        <h5 class="mt-3 mb-1">Uttkarsha Gundalkar</h5>
        <div class="team-role mb-2">Frontend Developer</div>
        <p class="text-muted small">Designs and implements user interfaces, ensuring a seamless and intuitive experience for all users.</p>
        <div class="social-icons mt-3">
          <a href="#" aria-label="Uttkarsha - twitter"><i class="bi bi-twitter"></i></a>
          <a href="#" aria-label="Uttkarsha - linkedin"><i class="bi bi-linkedin"></i></a>
          <a href="#" aria-label="Uttkarsha - github"><i class="bi bi-github"></i></a>
        </div>
        <div class="mt-3">
          <a href="#" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#bio-uttkarsha">View bio</a>
        </div>
        </div>
      </div>
    </div>

  
    </div>

   

  </div>
</section>

<!-- CONTACT -->
<section id="contact" class="py-5 bg-light">
  <div class="container" data-aos="fade-up">
    <div class="row">
      <div class="col-lg-6">
        <h4>Contact Us</h4>
        <p class="text-muted">Need help or want a demo? Reach out and we'll get back to you.</p>
        <p class="small text-muted">Email: <a href="mailto:support@maulivision.in">support@maulivision.in</a></p>
      </div>
      <div class="col-lg-6">
        <form onsubmit="event.preventDefault(); alert('Thanks — we will contact you shortly');">
          <div class="mb-3"><input class="form-control" placeholder="Your name" required></div>
          <div class="mb-3"><input type="email" class="form-control" placeholder="Email" required></div>
          <div class="mb-3"><textarea class="form-control" rows="3" placeholder="Message" required></textarea></div>
          <div class="d-grid"><button class="btn btn-primary">Send message</button></div>
        </form>
      </div>
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer class="footer">
  <div class="container text-center">
    <img src="/public/assets/img/logo-1.png" alt="MilkDairy" height="48" class="mb-3">
    <p class="mb-1">Smart Dairy Management</p>
    <small class="text-muted">© <?php echo date('Y'); ?> MilkDairy</small>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init({duration:700,once:true});
  // navbar scroll behavior
  const nav = document.querySelector('.navbar');
  window.addEventListener('scroll', ()=>{
    if(window.scrollY>50) nav.classList.add('navbar-scrolled'); else nav.classList.remove('navbar-scrolled');
  });
</script>
</body>
</html>
