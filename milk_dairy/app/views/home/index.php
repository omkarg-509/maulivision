<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
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

  <!-- NAVBAR -->
  <nav class="navbar navbar-expand-lg navbar-transparent fixed-top py-3">
    <div class="container position-relative">
      <a class="navbar-brand d-flex align-items-center" href="/public">
        <img src="/public/assets/img/logo-1.png" alt="MilkDairy" class="nav-brand-img me-2">
        <span class="fw-bold text-dark">MilkDairy</span>
      </a>

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
      <button class="navbar-toggler d-lg-none border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu">
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

  <!-- TESTIMONIALS -->
  <section id="testimonials" class="py-5">
    <div class="container" data-aos="fade-up">
      <div class="text-center mb-4">
        <h3 class="fw-bold">Trusted by users</h3>
        <p class="text-muted">Real feedback from dairy owners</p>
      </div>
      <div class="row g-4">
        <div class="col-md-4">
          <div class="card p-4 testimonial-card h-100 text-center">
            <img src="https://randomuser.me/api/portraits/men/32.jpg" class="rounded-circle mb-3" alt="user">
            <p class="fst-italic">"Simple and effective—reduces my paperwork by half."</p>
            <strong>Ramesh K.</strong>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card p-4 testimonial-card h-100 text-center">
            <img src="https://randomuser.me/api/portraits/women/44.jpg" class="rounded-circle mb-3" alt="user">
            <p class="fst-italic">"Great for billing and daily tracking."</p>
            <strong>Sunita P.</strong>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card p-4 testimonial-card h-100 text-center">
            <img src="https://randomuser.me/api/portraits/men/56.jpg" class="rounded-circle mb-3" alt="user">
            <p class="fst-italic">"Nice UI, easy to use for our staff."</p>
            <strong>Ajay M.</strong>
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
      <div class="col-md-4">
        <div class="card testimonial-card p-4 text-center">
          <img src="https://randomuser.me/api/portraits/men/32.jpg" class="rounded-circle mx-auto mb-3" alt="User">
          <p class="fst-italic">"This app has simplified my daily records. Highly recommended!"</p>
          <h6 class="fw-bold">- John Doe</h6>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card testimonial-card p-4 text-center">
          <img src="https://randomuser.me/api/portraits/women/44.jpg" class="rounded-circle mx-auto mb-3" alt="User">
          <p class="fst-italic">"Managing customers and billing is now a breeze. A must-have tool."</p>
          <h6 class="fw-bold">- Jane Smith</h6>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card testimonial-card p-4 text-center">
          <img src="https://randomuser.me/api/portraits/men/56.jpg" class="rounded-circle mx-auto mb-3" alt="User">
          <p class="fst-italic">"The best dairy management app I have used. Simple and effective."</p>
          <h6 class="fw-bold">- Sam Wilson</h6>
        </div>
      </div>
    </div>
  </div>
</section>

<section id="contact" class="py-5 bg-light">
  <div class="container">
    <h2 class="mb-4">Contact</h2>
    <div class="row g-4">
      <div class="col-md-6">
        <form action="#" method="post" onsubmit="event.preventDefault(); alert('Thanks! We will contact you.');">
          <div class="mb-3"><label class="form-label">Name</label><input type="text" class="form-control" required></div>
          <div class="mb-3"><label class="form-label">Email</label><input type="email" class="form-control" required></div>
          <div class="mb-3"><label class="form-label">Message</label><textarea class="form-control" rows="4" required></textarea></div>
          <button class="btn btn-primary">Send</button>
        </form>
      </div>
      <div class="col-md-6">
        <div class="p-4 bg-light rounded h-100">
          <h5>About the App</h5>
          <p class="text-muted">This app is built with a simple MVC structure. Navigate to Dashboard after login to manage entries, customers, and more.</p>
          <ul class="mb-0">
            <li>Daily Entry: record milk data</li>
            <li>Customers: manage profiles</li>
            <li>History: review past entries</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>

<footer class="footer py-5">
  <div class="container text-center">
    <img src="/public/assets/img/logo-1.png" alt="MilkDairy" height="50" class="mb-3">
    <p>Smart Dairy Management</p>
    <div class="d-flex justify-content-center gap-3">
      <a href="#"><i class="bi bi-twitter"></i></a>
      <a href="#"><i class="bi bi-facebook"></i></a>
      <a href="#"><i class="bi bi-instagram"></i></a>
    </div>
    <hr class="my-4">
    <small>© <?php echo date('Y'); ?> MilkDairy. All Rights Reserved.</small>
  </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init({
    duration: 800,
    once: true
  });

  const navbar = document.querySelector('.navbar');
  window.onscroll = () => {
    if (window.scrollY > 50) {
      navbar.classList.add('navbar-scrolled');
    } else {
      navbar.classList.remove('navbar-scrolled');
    }
  };
</script>
</body>
</html>
