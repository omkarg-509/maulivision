<div id="app">
    <section class="section">
        <div class="container mt-5">
            <div class="row">
                <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                    <div class="card card-primary">
                        <div class="col-12 text-center mt-2">
                            <h5>Create an Account</h5>
                        </div>
                        <div class="card-body">

                            <div id="register-error" style="color:red; display:none;"></div>

                            <form id="registerForm">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input id="name" type="text" class="form-control" name="name" value='omkar' tabindex="1" required autofocus>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input id="email" type="email" class="form-control" name="email" value='omkar@example.com' tabindex="2" required>
                                </div>
                                <div class="form-group">
                                    <label for="password" class="control-label">Password</label>
                                    <input id="password" type="password" class="form-control" name="password" value='password123' tabindex="3" required>
                                </div>
                                <div class="form-group">
                                    <label for="password_confirm" class="control-label">Confirm Password</label>
                                    <input id="password_confirm" type="password" class="form-control" name="password_confirm" value='password123' tabindex="4" required>
                                </div>
                                <div class="form-group col-12 text-center">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="5">
                                        Register
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
$('#registerForm').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
                url: '/public/auth/register',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                        if (response.status === 'success') {
                                window.location.href = response.redirect;
                        } else {
                                $('#register-error').text(response.message).show();
                        }
                },
                error: function() {
                        $('#register-error').text('Something went wrong.').show();
                }
        });
});
</script>
