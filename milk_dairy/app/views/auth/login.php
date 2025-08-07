<!-- <!DOCTYPE html>
<html>
<head>
    <title>Login - Milk Dairy App</title>
</head>
<body>
    <h2>Login</h2>
 
    <form method="POST" action="/milk_dairy_app/public/auth/login">
        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>
        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>
        <button type="submit">Login</button>
    </form>
</body>
</html> -->

  <div id="app">
    <section class="section">
      <div class="container mt-5">
        <div class="row">
          <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
            <div class="card card-primary" >
              <div class="col-12 text-center mt-2">
                <h5>Welcome Back </h5>
              </div>
              <div class="card-body">
                   <?php if (!empty($data['error'])): ?>
        <p style="color:red;"><?= $data['error'] ?></p>
    <?php endif; ?>
                <form method="POST" action="/public/auth/login">
                  <div class="form-group">
                  <label for="email">email</label>
                  <input id="email" type="text" class="form-control" name="email" value="admin@example.com"  tabindex="1" required autofocus>
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
                  <button type="submit"  class="btn btn-primary btn-lg btn-block" tabindex="4">
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