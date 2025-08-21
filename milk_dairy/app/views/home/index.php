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
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #fff;
    }
    .navbar {
      transition: background-color 0.5s ease, padding 0.5s ease;
    }
    .navbar-scrolled {
      background-color: #fff !important;
      box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15)!important;
    }
    .hero {
      background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('https://images.unsplash.com/photo-1620451121352-c1faf3545557?q=80&w=2070&auto=format&fit=crop') no-repeat center center;
      background-size: cover;
      color: white;
      height: 100vh;
      display: flex;
      align-items: center;
      text-align: center;
    }
    .feature-icon {
      font-size: 3rem;
      color: var(--bs-primary);
    }
    .service-card {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .service-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 1rem 3rem rgba(0,0,0,.175)!important;
    }
    .testimonial-card {
      background-color: #f8f9fa;
    }
    .testimonial-card img {
      width: 80px;
      height: 80px;
      object-fit: cover;
    }
    .footer {
      background-color: #0a1828;
      color: #adb5bd;
    }
    .footer a {
      color: #adb5bd;
      text-decoration: none;
      transition: color 0.3s;
    }
    .footer a:hover {
      color: #fff;
    }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-transparent fixed-top">
  <div class="container">
    <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="#">
      <img src="/public/assets/img/logo-1.png" alt="MilkDairy" height="40">
      <span>MilkDairy</span>
    </a>

    <!-- Desktop Links (Visible on lg and up) -->
    <div class="d-none d-lg-flex align-items-center gap-4">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
        <li class="nav-item"><a class="nav-link" href="#services">Services</a></li>
        <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
      </ul>
      <div class="d-flex gap-2">
        <a href="/public/auth/login" class="btn btn-outline-light btn-sm">Login</a>
        <a href="/public/auth/register" class="btn btn-light btn-sm">Get Started</a>
      </div>
    </div>

    <!-- Mobile Toggle (Visible on md and down) -->
    <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
  </div>
</nav>

<!-- Off-canvas Mobile Menu -->
<div class="offcanvas offcanvas-end d-lg-none" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
  <div class="offcanvas-header border-bottom">
    <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Menu</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <ul class="navbar-nav justify-content-end flex-grow-1 pe-3 mb-3">
      <li class="nav-item"><a class="nav-link fs-5" href="#about">About</a></li>
      <li class="nav-item"><a class="nav-link fs-5" href="#services">Services</a></li>
      <li class="nav-item"><a class="nav-link fs-5" href="#contact">Contact</a></li>
    </ul>
    <hr>
    <div class="d-grid gap-2">
      <a href="/public/auth/login" class="btn btn-outline-primary">Login</a>
      <a href="/public/auth/register" class="btn btn-primary">Get Started</a>
    </div>
  </div>
</div>

<section class="hero">
  <div class="container">
    <div class="row">
      <div class="col-lg-8 col-md-10 mx-auto" data-aos="fade-up">
        <img src="/public/assets/img/logo-1.png" alt="MilkDairy" height="80" class="mb-4">
        <h1 class="display-4 fw-bold">Smart Milk Dairy Management</h1>
        <p class="lead">Manage daily entries, customers, billing, and subscriptions with ease. Accessible from any device, anywhere.</p>
        <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
          <a href="/public/auth/register" class="btn btn-primary btn-lg px-4 gap-3">Start for Free</a>
          <a href="/public/auth/login" class="btn btn-outline-light btn-lg px-4">Sign In</a>
        </div>
      </div>
    </div>
  </div>
</section>

<section id="about" class="py-5">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-6">
        <h2>About MilkDairy</h2>
        <p class="text-muted">MilkDairy is a lightweight web app to streamline your dairy operations: record milk entries, manage customers, track payments, and generate reports.</p>
      </div>
      <div class="col-lg-6">
        <div class="row g-3">
          <div class="col-6"><div class="p-3 border rounded">Daily Entries</div></div>
          <div class="col-6"><div class="p-3 border rounded">Customer CRM</div></div>
          <div class="col-6"><div class="p-3 border rounded">Billing & PDF</div></div>
          <div class="col-6"><div class="p-3 border rounded">Subscriptions</div></div>
        </div>
      </div>
    </div>
  </div>
</section>

<section id="services" class="py-5 bg-light">
  <div class="container">
    <div class="text-center mb-5" data-aos="fade-up">
      <h2 class="fw-bold">Our Services</h2>
      <p class="lead text-muted">Everything you need to run your dairy business efficiently.</p>
    </div>
    <div class="row g-4">
      <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
        <div class="card h-100 text-center p-4 service-card shadow-sm">
          <div class="feature-icon mx-auto mb-3"><i class="bi bi-journal-check"></i></div>
          <h5 class="card-title">Daily Milk Entry</h5>
          <p class="card-text text-muted">Fast recording of quantities, rates, and customer-wise logs.</p>
        </div>
      </div>
      <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
        <div class="card h-100 text-center p-4 service-card shadow-sm">
          <div class="feature-icon mx-auto mb-3"><i class="bi bi-people-fill"></i></div>
          <h5 class="card-title">Customer Management</h5>
          <p class="card-text text-muted">Create, view, and manage your customer base and history.</p>
        </div>
      </div>
      <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
        <div class="card h-100 text-center p-4 service-card shadow-sm">
          <div class="feature-icon mx-auto mb-3"><i class="bi bi-receipt-cutoff"></i></div>
          <h5 class="card-title">Billing & Reports</h5>
          <p class="card-text text-muted">Generate bills, export PDFs, and track transactions easily.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<section id="testimonials" class="py-5">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="fw-bold">What Our Users Say</h2>
      <p class="lead text-muted">Trusted by dairy farmers and distributors.</p>
    </div>
    <div class="row">
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
