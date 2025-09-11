<?php require_once '../app/views/layouts/sidebar.php'; ?>
<div class="main-content mt-5 pt-4">
  <section class="section">
    <div class="section-header d-flex justify-content-between align-items-center flex-wrap gap-2">
      <h1 class="mb-0">Create Vendor</h1>
      <a href="<?= BASE_URL ?>vendor/index" class="btn btn-outline-secondary btn-sm">Back</a>
    </div>
    <?php if(!empty($_SESSION['error'])): ?><div class="alert alert-danger py-2 px-3 small mb-3"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div><?php endif; ?>
    <div class="card">
      <div class="card-body">
        <form method="post" action="<?= BASE_URL ?>vendor/store" class="row g-3" autocomplete="off">
          <div class="col-md-4"><label class="form-label small">Full Name *</label><input name="full_name" class="form-control" required></div>
          <div class="col-md-4"><label class="form-label small">Phone *</label><input name="phone" class="form-control" required></div>
          <div class="col-md-4"><label class="form-label small">Email</label><input type="email" name="email" class="form-control"></div>

          <div class="col-md-4"><label class="form-label small">Business Name *</label><input name="business_name" class="form-control" required></div>
          <div class="col-md-4"><label class="form-label small">Business Role</label><input name="business_role" class="form-control"></div>
          <div class="col-md-4"><label class="form-label small">Business Number</label><input name="business_number" class="form-control"></div>
          <div class="col-md-4"><label class="form-label small">Business Address</label><textarea name="business_address" class="form-control" rows="2"></textarea></div>
          <div class="col-md-4"><label class="form-label small">Username</label><input name="username" class="form-control" required></div>
          <div class="col-md-4">
            <label class="form-label small">Password *</label>
            <div class="input-group">
              <input type="password" name="password" class="form-control" id="passwordInput" required>
              <button class="btn btn-outline-secondary" type="button" id="togglePassword" tabindex="-1">
                <i class="bi bi-eye" id="togglePasswordIcon"></i>
              </button>
            </div>
          </div>
          <script>
            document.addEventListener('DOMContentLoaded', function() {
              const passwordInput = document.getElementById('passwordInput');
              const togglePassword = document.getElementById('togglePassword');
              const togglePasswordIcon = document.getElementById('togglePasswordIcon');
              togglePassword.addEventListener('click', function () {
                const type = passwordInput.type === 'password' ? 'text' : 'password';
                passwordInput.type = type;
                togglePasswordIcon.classList.toggle('bi-eye');
                togglePasswordIcon.classList.toggle('bi-eye-slash');
              });
            });
          </script>
          <div class="col-12 d-flex justify-content-end"><button class="btn btn-primary">Save Vendor</button></div>
        </form>
      </div>
    </div>
  </section>
</div>