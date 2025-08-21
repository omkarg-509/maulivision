<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>MilkDairy</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <style>
    .hero{background:#f8fafc}
    .feature-icon{font-size:2rem;color:#0d6efd}
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="#">
      <img src="/public/assets/img/logo-1.png" alt="MilkDairy" height="100">
      <span>MilkDairy</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample" aria-controls="navbarsExample" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarsExample">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
        <li class="nav-item"><a class="nav-link" href="#services">Services</a></li>
        <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
      </ul>
      <div class="d-flex gap-2">
        <a href="/public/auth/login" class="btn btn-outline-primary btn-sm">Login</a>
        <a href="/public/auth/register" class="btn btn-primary btn-sm">Get Started</a>
      </div>
    </div>
  </div>
</nav>

<section class="hero py-5">
  <div class="container">
    <div class="row align-items-center g-4">
      <div class="col-md-6">
        <h1 class="display-5 fw-bold">Smart Milk Dairy Management</h1>
        <p class="lead text-muted">Manage daily entries, customers, billing, and subscriptions with ease. Accessible from any device.</p>
        <a href="/public/auth/register" class="btn btn-primary btn-lg me-2">Start free</a>
        <a href="/public/auth/login" class="btn btn-outline-secondary btn-lg">Sign in</a>
      </div>
      <div class="col-md-6">
        <div class="ratio ratio-16x9 bg-light rounded shadow-sm"></div>
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
    <h2 class="mb-4">Services</h2>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="p-4 bg-white rounded shadow-sm h-100">
          <div class="feature-icon mb-2">🧾</div>
          <h5>Daily Milk Entry</h5>
          <p class="text-muted">Fast recording of quantities, rates, and customer-wise logs.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="p-4 bg-white rounded shadow-sm h-100">
          <div class="feature-icon mb-2">👥</div>
          <h5>Customer Management</h5>
          <p class="text-muted">Create, view, and manage your customer base and history.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="p-4 bg-white rounded shadow-sm h-100">
          <div class="feature-icon mb-2">💳</div>
          <h5>Billing & Reports</h5>
          <p class="text-muted">Generate bills, export PDFs, and track transactions easily.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<section id="contact" class="py-5">
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

<footer class="py-4 border-top bg-white">
  <div class="container text-center">
    <small class="text-muted">© <?php echo date('Y'); ?> MilkDairy</small>
  </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
