<div id="app">
    <section class="section">
        <div class="container mt-5">
            <div class="row">
                <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                    <div class="card card-primary">
                        <div class="col-12 text-center mt-2">
                            <h5>Forgot Password</h5>
                        </div>
                        <div class="card-body">
                            <div id="forgot-error" style="color:red; display:none;"></div>
                            <form id="forgotForm">
                                <div class="form-group">
                                    <label for="email_or_number">Email or Mobile Number</label>
                                    <input id="email_or_number" type="text" class="form-control" name="email_or_number" required autofocus>
                                </div>
                                <div class="form-group">
                                    <label for="new_password">New Password</label>
                                    <input id="new_password" type="password" class="form-control" name="new_password" required>
                                </div>
                                <div class="form-group col-12 text-center">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                                        Reset Password
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
$('#forgotForm').on('submit', function(e) {
    e.preventDefault();
    $.ajax({
        url: '/public/auth/forgot',
        type: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                window.location.href = response.redirect;
            } else {
                $('#forgot-error').text(response.message).show();
            }
        },
        error: function() {
            $('#forgot-error').text('Something went wrong.').show();
        }
    });
});
</script>
