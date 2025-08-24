<div id="app">
  <style>
    .login-card { border-radius: 12px; overflow: hidden; }
    .login-left { background: linear-gradient(180deg,#0d6efd,#0a58ca); color: #fff; }
    .login-left img { filter: brightness(1.05); }
    .form-control:focus { box-shadow: 0 0 0 .12rem rgba(13,110,253,.12); }
    .btn-primary { background: linear-gradient(90deg,#0d6efd,#0069d9); border: none; }
    .small-note { color: #6c757d; font-size: .9rem; }
  </style>

  <section class="section">
    <div class="container mt-5">
      <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8 col-xl-6">
             <div class="card shadow-sm login-card">

             
            <div class="row g-0">
              <div class="col-md-5 d-none d-md-flex align-items-center justify-content-center login-left p-4">
                <div class="text-center">
                  <img src="/public/assets/img/logo-1.png" alt="MilkDairy" style="height:64px" class="mb-3">
                  <h5 class="mb-0">Welcome Back</h5>
                  <small class="d-block small-note">Sign in to manage your dairy</small>
                </div>
              </div>

              <div class="col-md-7">
                <div class="card-body">
                  <h5 class="card-title text-center mb-3">Login</h5>

                  <div id="loginMessage" class="d-none"></div>

                  <form id="loginForm" novalidate>
                    <div class="mb-3">
                      <label for="email_or_number" class="form-label">Email or Mobile</label>
                      <input id="email_or_number" name="email_or_number" type="text" class="form-control" required autofocus placeholder="Email or phone">
                      <div class="invalid-feedback">Please enter your email or mobile number.</div>
                    </div>

                    <div class="mb-3">
                      <label for="password" class="form-label">Password</label>
                      <div class="input-group">
                        <input id="password" name="password" type="password" class="form-control" required minlength="6">
                        <button class="btn btn-outline-secondary" type="button" id="toggleLoginPassword" title="Show / hide password"><i class="bi bi-eye"></i></button>
                      </div>
                      <div class="invalid-feedback">Please enter your password.</div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                      <a href="/public/auth/forgot" class="small">Forgot Password?</a>
                      <small class="small-note">Need an account? <a href="/public/auth/register">Register</a></small>
                    </div>

                    <div class="d-grid mb-2">
                      <button id="loginBtn" type="submit" class="btn btn-primary">
                        <span id="loginBtnText">Login</span>
                        <span id="loginSpinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
                      </button>
                    </div>
                  </form>

                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </section>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<script>
  function showLoginAlert(type, msg){
    const el = $('#loginMessage');
    el.removeClass('d-none alert-danger alert-success').addClass('alert alert-' + (type === 'success' ? 'success' : 'danger'));
    el.text(msg);
  }
  function hideLoginAlert(){ $('#loginMessage').addClass('d-none').text(''); }

  $('#toggleLoginPassword').on('click', function(){
    const inp = $('#password');
    const type = inp.attr('type') === 'password' ? 'text' : 'password';
    inp.attr('type', type);
    $(this).find('i').toggleClass('bi-eye bi-eye-slash');
  });

  $('#loginForm').on('submit', function(e){
    e.preventDefault();
    hideLoginAlert();

    // clear previous states
    $('#email_or_number,#password').removeClass('is-invalid is-valid');

    const identifier = $('#email_or_number').val().trim();
    const pwd = $('#password').val();
    let hasErr = false;
    if(!identifier){ $('#email_or_number').addClass('is-invalid'); hasErr = true; }
    if(!pwd || pwd.length < 3){ $('#password').addClass('is-invalid'); hasErr = true; }
    if(hasErr) return;

    // show loading
    $('#loginBtn').attr('disabled', true);
    $('#loginBtnText').text('Signing in...');
    $('#loginSpinner').removeClass('d-none');

    $.ajax({
      url: '/public/auth/login',
      type: 'POST',
      data: $(this).serialize(),
      dataType: 'json'
    }).done(function(response){
      if(response && response.status === 'success'){
        showLoginAlert('success', response.message || 'Login successful. Redirecting...');
        setTimeout(function(){ window.location.href = response.redirect || '/public'; }, 800);
      } else {
        if(response && response.message) showLoginAlert('error', response.message);
        else showLoginAlert('error', 'Invalid credentials.');
        // map field errors if present
        if(response && response.errors){
          Object.keys(response.errors).forEach(function(field){
            const sel = '#'+field;
            $(sel).addClass('is-invalid');
          });
        }
      }
    }).fail(function(){
      showLoginAlert('error', 'Something went wrong. Please try again.');
    }).always(function(){
      $('#loginBtn').attr('disabled', false);
      $('#loginBtnText').text('Login');
      $('#loginSpinner').addClass('d-none');
    });
  });
</script>
