<div id="app">
  <style>
    :root {
      /* Brand colors (edit these two to quickly change the theme) */
      --brand-1: #ff7b00ff; /* primary */
      --brand-2: #010101b7; /* darker */
      --brand-focus: rgba(25, 135, 84, 0.18);
    }

    .login-card { border-radius: 12px; overflow: hidden; }
    .login-left { background: linear-gradient(180deg, var(--brand-1), var(--brand-2)); color: #fff; }
    .login-left img { filter: brightness(1.05); }
    .form-control:focus { box-shadow: 0 0 0 .12rem var(--brand-focus); }
    .btn-primary { background: linear-gradient(90deg, var(--brand-1), var(--brand-2)); border: none; }
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
                  <img src="<?= BASE_URL ?>assets/favicon-1.png" alt="MilkDairy" style="height:64px" class="mb-3">
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
