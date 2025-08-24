<div id="app">
    <style>
        .auth-card { border-radius: 12px; overflow: hidden; }
        .auth-left { background: linear-gradient(180deg,#0d6efd,#0a58ca); color: #fff; }
        .auth-left img { filter: brightness(1.05); }
        .form-control:focus { box-shadow: 0 0 0 .12rem rgba(13,110,253,.12); }
        .btn-primary { background: linear-gradient(90deg,#0d6efd,#0069d9); border: none; }
        .small-note { color: #6c757d; font-size: .9rem; }
    </style>

    <section class="section">
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-12 col-md-10 col-lg-8 col-xl-6">
                    <div class="card shadow-sm auth-card">
                        <div class="row g-0">
                            <div class="col-md-5 d-none d-md-flex align-items-center justify-content-center auth-left p-4">
                                <div class="text-center">
                                    <img src="/public/assets/img/logo-1.png" alt="MilkDairy" style="height:64px" class="mb-3">
                                    <h5 class="mb-0">Reset password</h5>
                                    <small class="d-block small-note">Secure your account</small>
                                </div>
                            </div>

                            <div class="col-md-7">
                                <div class="card-body">
                                    <h5 class="card-title text-center mb-3">Forgot Password</h5>

                                    <div id="forgot-error" class="alert alert-danger d-none" role="alert"></div>
                                    <div id="forgot-success" class="alert alert-success d-none" role="alert"></div>

                                    <form id="forgotForm" novalidate>
                                        <div class="mb-3">
                                            <label for="email_or_number" class="form-label">Email or Mobile number</label>
                                            <input id="email_or_number" name="email_or_number" type="text" class="form-control" required autofocus placeholder="Email or phone">
                                            <div class="invalid-feedback">Enter your email address or mobile number.</div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="new_password" class="form-label">New password</label>
                                            <div class="input-group">
                                                <input id="new_password" name="new_password" type="password" class="form-control" required minlength="6">
                                                <button class="btn btn-outline-secondary" type="button" id="toggleNewPassword"><i class="bi bi-eye"></i></button>
                                            </div>
                                            <div class="form-text small-note">Choose a strong password (min 6 chars).</div>
                                            <div class="invalid-feedback">Provide a password with at least 6 characters.</div>
                                        </div>

                                        <div class="d-grid">
                                            <button id="forgotBtn" type="submit" class="btn btn-primary">
                                                <span id="forgotBtnText">Reset Password</span>
                                                <span id="forgotSpinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
                                            </button>
                                        </div>
                                    </form>

                                    <div class="text-center mt-3">
                                        <small class="small-note">Remembered? <a href="/public/auth/login">Login</a></small>
                                    </div>
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
    function showAlert(id, msg){ $('#'+id).removeClass('d-none').text(msg); }
    function hideAlert(id){ $('#'+id).addClass('d-none').text(''); }

    $('#toggleNewPassword').on('click', function(){
        const inp = $('#new_password');
        const type = inp.attr('type') === 'password' ? 'text' : 'password';
        inp.attr('type', type);
        $(this).find('i').toggleClass('bi-eye bi-eye-slash');
    });

    $('#forgotForm').on('submit', function(e){
        e.preventDefault();
        hideAlert('forgot-error'); hideAlert('forgot-success');

        // clear field state
        $('#email_or_number,#new_password').removeClass('is-invalid is-valid');

        const identifier = $('#email_or_number').val().trim();
        const pwd = $('#new_password').val() || '';
        let hasErr = false;
        if(!identifier){ $('#email_or_number').addClass('is-invalid'); hasErr = true; }
        if(pwd.length < 6){ $('#new_password').addClass('is-invalid'); showAlert('forgot-error', 'Password must be at least 6 characters long.'); hasErr = true; }
        if(hasErr) return;

        // show loading
        $('#forgotBtn').attr('disabled', true);
        $('#forgotBtnText').text('Updating...');
        $('#forgotSpinner').removeClass('d-none');

        $.ajax({
            url: '/public/auth/forgot',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json'
        }).done(function(response){
            if(response && response.status === 'success'){
                showAlert('forgot-success', response.message || 'Password updated. Redirecting...');
                setTimeout(function(){ window.location.href = response.redirect || '/public/auth/login'; }, 1000);
            } else {
                if(response && response.errors){
                    Object.keys(response.errors).forEach(function(field){ $('#'+field).addClass('is-invalid'); });
                    showAlert('forgot-error', response.message || 'Please review the highlighted fields.');
                } else {
                    showAlert('forgot-error', (response && response.message) ? response.message : 'Failed to update password.');
                }
            }
        }).fail(function(){
            showAlert('forgot-error', 'Something went wrong. Please try again.');
        }).always(function(){
            $('#forgotBtn').attr('disabled', false);
            $('#forgotBtnText').text('Reset Password');
            $('#forgotSpinner').addClass('d-none');
        });
    });
</script>
