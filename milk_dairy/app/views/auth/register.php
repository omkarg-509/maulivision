<div id="app">
    <style>
        /* Small, self-contained styles for the register card */
        .register-card { border-radius: 12px; overflow: hidden; }
        .register-left { background: linear-gradient(180deg,#0d6efd,#0a58ca); }
        .register-left img { filter: brightness(1.1); }
        .form-control:focus { box-shadow: 0 0 0 .12rem rgba(13,110,253,.12); }
        .btn-primary { background: linear-gradient(90deg,#0d6efd,#0069d9); border: none; }
        .input-hint { font-size: .85rem; color: #6c757d; }
        @media (max-width:767px) { .register-left { border-radius: 0 0 .5rem .5rem; padding: 1.25rem 0; } }
    </style>
    <section class="section">
        <div class="container mt-5">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-end mb-3">
                        <a href="/public/auth/login" class="btn btn-outline-primary">Login</a>
                    </div>
                </div>

                <div class="col-12 col-sm-10 offset-sm-1 col-md-8 offset-md-2 col-lg-6 offset-lg-3">
                      <div class="card shadow-sm register-card">
                        <div class="row g-0">
                            <div class="col-md-5 d-none d-md-flex align-items-center justify-content-center bg-primary text-white p-4 rounded-start">
                                <div class="text-center">
                                    <img src="/public/assets/img/logo-1.png" alt="MilkDairy" style="height:64px" class="mb-3">
                                    <h5 class="mb-0">Welcome</h5>
                                    <small class="d-block">Create your MilkDairy account</small>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="card-body">
                                    <h5 class="card-title text-center">Create an Account</h5>

                                      <div id="register-error" class="alert alert-danger d-none" role="alert"></div>
                                      <div id="register-success" class="alert alert-success d-none" role="alert"></div>

                                    <form id="registerForm" novalidate>
                                                            <div class="mb-3">
                                                                <label for="name" class="form-label">Full name</label>
                                                                <input id="name" name="name" type="text" class="form-control" required autocomplete="name" autofocus>
                                                                <div class="invalid-feedback">Please enter your full name.</div>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="mobile_number" class="form-label">Mobile number</label>
                                                                <input id="mobile_number" name="mobile_number" type="tel" inputmode="numeric" pattern="[0-9+\- ]{6,}" class="form-control" required placeholder="e.g. +91 9876543210">
                                                                <div class="invalid-feedback">Enter a valid mobile number.</div>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="email" class="form-label">Email</label>
                                                                <input id="email" name="email" type="email" class="form-control" required placeholder="you@company.com" autocomplete="email">
                                                                <div class="invalid-feedback">Provide a valid email address.</div>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="business_name" class="form-label">Business name</label>
                                                                <input id="business_name" name="business_name" type="text" class="form-control" required>
                                                                <div class="invalid-feedback">Enter your business name.</div>
                                                            </div>

                                                            <div class="mb-3">
                                                                <input id="business_number" name="business_number" type="tel" inputmode="numeric" pattern="[0-9+\- ]{6,}" class="form-control" required placeholder="e.g. 9876543210">
                                                                <div class="invalid-feedback">Enter a valid business contact number.</div>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="business_address" class="form-label">Business address</label>
                                                                <textarea id="business_address" name="business_address" class="form-control" rows="2" required></textarea>
                                                                <div class="invalid-feedback">Please provide the business address.</div>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="form-label">Password</label>
                                                                <div class="input-group">
                                                                    <input id="password" name="password" type="password" class="form-control" required minlength="6" aria-describedby="showPwd">
                                                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword" title="Show / hide password"><i class="bi bi-eye"></i></button>
                                                                </div>
                                                                <div class="form-text input-hint">Minimum 6 characters.</div>
                                                                <div class="invalid-feedback">Password must be at least 6 characters.</div>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="form-label">Confirm password</label>
                                                                <div class="input-group">
                                                                    <input id="password_confirm" name="password_confirm" type="password" class="form-control" required minlength="6">
                                                                    <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm" title="Show / hide password"><i class="bi bi-eye"></i></button>
                                                                </div>
                                                                <div class="invalid-feedback">Passwords do not match.</div>
                                                            </div>

                                        <div class="d-grid">
                                            <button id="registerBtn" type="submit" class="btn btn-primary btn-block">
                                                <span id="btnText">Register</span>
                                                <span id="btnSpinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
                                            </button>
                                        </div>
                                    </form>

                                    <div class="text-center mt-3">
                                        <small>Already have an account? <a href="/public/auth/login">Login</a></small>
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
    // helper: show bootstrap alert
    function showAlert(id, message) {
        $('#'+id).removeClass('d-none').text(message);
    }
    function hideAlert(id) {
        $('#'+id).addClass('d-none').text('');
    }

    // helper: field state
    function setFieldInvalid(selector, message) {
        const el = $(selector);
        el.addClass('is-invalid');
        if(message) el.siblings('.invalid-feedback').text(message);
    }
    function setFieldValid(selector) {
        $(selector).removeClass('is-invalid').addClass('is-valid');
    }
    function clearFieldState(selector) {
        $(selector).removeClass('is-invalid is-valid');
    }

    // password toggle
    $('#togglePassword').on('click', function(){
        const inp = $('#password');
        const type = inp.attr('type') === 'password' ? 'text' : 'password';
        inp.attr('type', type);
        $(this).find('i').toggleClass('bi-eye bi-eye-slash');
    });
    $('#togglePasswordConfirm').on('click', function(){
        const inp = $('#password_confirm');
        const type = inp.attr('type') === 'password' ? 'text' : 'password';
        inp.attr('type', type);
        $(this).find('i').toggleClass('bi-eye bi-eye-slash');
    });

    // form submission with client-side validation and loading state
    $('#registerForm').on('submit', function(e){
        e.preventDefault();
        hideAlert('register-error'); hideAlert('register-success');

        // clear previous field states
        ['#name','#mobile_number','#email','#business_name','#business_number','#business_address','#password','#password_confirm'].forEach(clearFieldState);

        // simple client-side checks
        const pwd = $('#password').val() || '';
        const pwd2 = $('#password_confirm').val() || '';
        let hasError = false;
        if(!$('#name').val().trim()) { setFieldInvalid('#name'); hasError = true; }
        const mobileVal = $('#mobile_number').val().trim();
        if(!mobileVal || !/^[0-9+\- ]{6,}$/.test(mobileVal)) { setFieldInvalid('#mobile_number'); hasError = true; }
        const emailVal = $('#email').val().trim();
        if(!emailVal || !/^\S+@\S+\.\S+$/.test(emailVal)) { setFieldInvalid('#email'); hasError = true; }
        if(!$('#business_name').val().trim()) { setFieldInvalid('#business_name'); hasError = true; }
        if(!$('#business_number').val().trim()) { setFieldInvalid('#business_number'); hasError = true; }
        if(!$('#business_address').val().trim()) { setFieldInvalid('#business_address'); hasError = true; }
        if(pwd.length < 6) { setFieldInvalid('#password'); showAlert('register-error','Password must be at least 6 characters long.'); hasError = true; }
        if(pwd !== pwd2) { setFieldInvalid('#password_confirm'); showAlert('register-error','Passwords do not match.'); hasError = true; }
        if(hasError) return;

        // mark fields valid
        ['#name','#mobile_number','#email','#business_name','#business_number','#business_address','#password','#password_confirm'].forEach(setFieldValid);

        // disable button + spinner
        $('#registerBtn').attr('disabled', true);
        $('#btnText').text('Creating...');
        $('#btnSpinner').removeClass('d-none');

        $.ajax({
            url: '/public/auth/register',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json'
        }).done(function(response){
            if(response && response.status === 'success'){
                showAlert('register-success', response.message || 'Account created — redirecting...');
                setTimeout(function(){ window.location.href = response.redirect || '/public/auth/login'; }, 1000);
            } else {
                // if server returned field-specific errors, map them
                if(response && response.errors && typeof response.errors === 'object'){
                    Object.keys(response.errors).forEach(function(field){
                        const sel = '#'+field;
                        setFieldInvalid(sel, response.errors[field]);
                    });
                    showAlert('register-error', response.message || 'Please review the highlighted fields.');
                } else {
                    showAlert('register-error', (response && response.message) ? response.message : 'Registration failed.');
                }
            }
        }).fail(function(){
            showAlert('register-error', 'Something went wrong. Please try again.');
        }).always(function(){
            $('#registerBtn').attr('disabled', false);
            $('#btnText').text('Register');
            $('#btnSpinner').addClass('d-none');
        });
    });
</script>
