<div id="app">
  <section class="section">
    <div class="container mt-5">
      <div class="row">
        <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
          <div class="card card-primary">
            <div class="col-12 text-center mt-2">
              <h5>Welcome Back </h5>
            </div>
            <div class="card-body">
              <div id="errorMsg" style="color:red;"></div>
              <form id="loginForm" method="POST" action="/public/auth/login">
                <div class="form-group">
                  <label for="email">email</label>
                  <input id="email" type="text" class="form-control" name="email" value="admin@example.com" tabindex="1" required autofocus>
                  <div class="invalid-feedback">
                    Please fill in your email
                  </div>
                </div>
                <div class="form-group">
                  <div class="d-block">
                    <label for="password" class="control-label">Password</label>
                  </div>
                  <input id="password" type="password" class="form-control" name="password" value="admin123" tabindex="2" required>
                  <div class="invalid-feedback">
                    Please fill in your password
                  </div>
                </div>
                <div class="form-group col-12 text-center">
                  <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                    Login
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<script>
document.getElementById('loginForm').addEventListener('submit', function(e) {
  e.preventDefault();
  const form = e.target;
  const data = new FormData(form);

  fetch(form.action, {
    method: 'POST',
    body: data,
    headers: {
      'X-Requested-With': 'XMLHttpRequest'
    }
  })
  .then(response => response.json())
  .then(result => {
    if (result.success) {
      window.location.href = result.redirect || '/'; // Redirect on success
    } else {
      document.getElementById('errorMsg').textContent = result.error || 'Login failed';
    }
  })
  .catch(() => {
    document.getElementById('errorMsg').textContent = 'An error occurred. Please try again.';
  });
});
</script>