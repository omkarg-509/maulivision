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
                                    <label for="full_name">Full Name</label>
                                    <input id="full_name" type="text" class="form-control" name="full_name"  tabindex="1" required autofocus>
                                </div>
                              
                            
                                <div class="form-group">
                                    <label for="phone">Phone</label>
                                    <input id="phone" type="text" class="form-control" name="phone"  tabindex="3" required>
                                </div>
                                <div class="form-group">
                                    <label for="email" class="control-label">Email</label>
                                    <input id="email" type="email" class="form-control" name="email"  tabindex="2" required>
                                </div>
                                <div class="form-group">
                                    <label for="bussines_name" class="control-label">Business Name</label>
                                    <input id="bussines_name" type="text" class="form-control" name="bussines_name"  tabindex="3" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="username" class="control-label">Username</label>
                                    <input id="username" type="text" class="form-control" name="username"  tabindex="3" required>
                                </div>
                                <div class="form-group">
                                    <label for="password_confirm" class="control-label">Confirm Password</label>
                                    <input id="password_confirm" type="password" class="form-control" name="password_confirm"  tabindex="4" required>
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
