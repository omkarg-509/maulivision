<div id="app">
  <section class="section">
    <div class="container mt-5">
      <div class="row">
        <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
          <div class="card card-primary">
            <div class="col-12 text-center mt-2">
              <h5>Welcome Back</h5>
            </div>
            <div class="card-body">

              <div id="login-error" style="color:red; display:none;"></div>

              <form id="loginForm">
                <div class="form-group">
                  <label for="email">Email</label>
                  <input id="email" type="text" class="form-control" name="email" value="admin@example.com" tabindex="1" required autofocus>
                </div>
                <div class="form-group">
                  <label for="password" class="control-label">Password</label>
                  <input id="password" type="password" class="form-control" name="password" value="cls" tabindex="2" required>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$('#loginForm').on('submit', function(e) {
    e.preventDefault();

    $.ajax({
        url: '/public/auth/login',
        type: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                window.location.href = response.redirect;
            } else {
                $('#loginMessage').text(response.message);
            }
        },
        error: function() {
            $('#loginMessage').text('Something went wrong.');
        }
    });
});
</script>
