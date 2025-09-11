<?php require_once '../layouts/header.php'; ?>
<style>
  body.login-bg {
    min-height:100vh;
    background: radial-gradient(circle at 20% 20%, #4f46e5, #312e81 60%, #111827);
    display:flex;align-items:center;justify-content:center;
    font-family: 'Urbanist', sans-serif;
  }
  .login-wrapper { width:100%; max-width:430px; padding:10px; }
  .glass-card {
    background:rgba(255,255,255,0.08)!important;
    backdrop-filter: blur(8px) saturate(160%);
    -webkit-backdrop-filter: blur(8px) saturate(160%);
    border:1px solid rgba(255,255,255,0.15);
    border-radius:22px;
    box-shadow:0 10px 30px -5px rgba(0,0,0,0.55),0 2px 6px -2px rgba(0,0,0,0.4);
    overflow:hidden;
  }
  .glass-card .card-body { padding:2rem 2.2rem 2.4rem; }
  .brand-logo img { filter: drop-shadow(0 4px 6px rgba(0,0,0,.35)); }
  .brand-title { font-weight:600; letter-spacing:.5px; font-size:1.05rem; color:#e0e7ff; text-transform:uppercase; }
  label { font-size:.75rem; letter-spacing:.6px; font-weight:600; text-transform:uppercase; color:#cbd5e1; }
  .form-control { background:rgba(255,255,255,0.12); border:1px solid rgba(255,255,255,0.25); color:#f1f5f9; }
  .form-control:focus { background:rgba(255,255,255,0.18); color:#fff; box-shadow:0 0 0 2px rgba(99,102,241,0.35); border-color:#6366f1; }
  .toggle-password { cursor:pointer; position:absolute; top:50%; right:12px; transform:translateY(-50%); color:#94a3b8; }
  .toggle-password:hover { color:#fff; }
  .btn-gradient { background:linear-gradient(135deg,#6366f1,#8b5cf6); border:none; }
  .btn-gradient:hover { background:linear-gradient(135deg,#4f46e5,#7c3aed); }
  .status-msg { font-size:.8rem; margin-top:.75rem; }
  .status-msg.success { color:#34d399; }
  .status-msg.error { color:#f87171; }
  .small-link { font-size:.7rem; text-transform:uppercase; letter-spacing:.7px; color:#a5b4fc; }
  .small-link:hover { color:#fff; text-decoration:none; }
  .fade-in { animation: fade .6s ease; }
  @keyframes fade { from { opacity:0; transform:translateY(6px);} to { opacity:1; transform:translateY(0);} }
  ::placeholder { color:#94a3b8 !important; font-size:.85rem; }
  .copyright { font-size:.65rem; letter-spacing:1px; color:#64748b; }
  .spinner-border { width:1rem; height:1rem; border-width:2px; }
</style>
<body class="login-bg">
  <div class="login-wrapper fade-in">
    <div class="card glass-card">
      <div class="card-body">
        <div class="text-center mb-4 brand-logo">
          <img src="<?= BASE_URL ?>assets/img/logo-1.png" alt="Logo" style="max-width:120px;" />
          <div class="brand-title mt-3">Massage Center Admin</div>
        </div>

        <div id="loginMessage" class="status-msg"></div>

        <form id="loginForm" autocomplete="off" novalidate>
          <div class="form-group mb-3">
            <label for="email">Email</label>
            <input id="email" type="email" class="form-control" name="email" placeholder="name@example.com" required autofocus>
          </div>
          <div class="form-group mb-2 position-relative">
            <label for="password">Password</label>
            <input id="password" type="password" class="form-control" name="password" placeholder="••••••••" required>
            <span class="toggle-password" id="togglePassword" title="Show/Hide"><i class="fa fa-eye"></i></span>
          </div>
          <div class="d-flex justify-content-between mb-3">
            <a href="#" class="small-link" tabindex="-1">Forgot password?</a>
          </div>
          <button type="submit" class="btn btn-gradient text-white w-100 py-2 fw-semibold" id="loginBtn">
            <span class="btn-text">Login</span>
            <span class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
          </button>
        </form>
        <div class="text-center mt-4">
          <div class="copyright">&copy; <span id="year"></span> Massage Center. All rights reserved.</div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    document.getElementById('year').textContent = new Date().getFullYear();

    const loginForm = $('#loginForm');
    const msg = $('#loginMessage');
    const btn = $('#loginBtn');
    const spinner = btn.find('.spinner-border');
    const btnText = btn.find('.btn-text');

    $('#togglePassword').on('click', function(){
      const pwd = $('#password');
      const type = pwd.attr('type') === 'password' ? 'text' : 'password';
      pwd.attr('type', type);
      $(this).find('i').toggleClass('fa-eye fa-eye-slash');
    });

    loginForm.on('submit', function(e){
      e.preventDefault();
      msg.removeClass('success error').text('');
      btn.attr('disabled', true);
      spinner.removeClass('d-none');
      btnText.text('Authenticating...');

      $.ajax({
        url: '<?= BASE_URL ?>auth/login',
        method: 'POST',
        data: loginForm.serialize(),
        dataType: 'json'
      }).done(function(res){
        if(res.status === 'success') {
          msg.addClass('success').text('Login successful. Redirecting...');
          setTimeout(()=> window.location.href = res.redirect, 600);
        } else {
          msg.addClass('error').text(res.message || 'Invalid credentials.');
        }
      }).fail(function(){
        msg.addClass('error').text('Network error. Please retry.');
      }).always(function(){
        btn.attr('disabled', false);
        spinner.addClass('d-none');
        btnText.text('Login');
      });
    });
  </script>
</body>
<?php require_once '../layouts/footer.php'; ?>
