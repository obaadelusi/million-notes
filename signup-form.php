
<?php include('header.php'); ?>

<main class="main content-center" id="signup-page">
    <section class="form card">
        <form action="register.php" method="post" id="signup-form">
            <h1 class="form-heading">Sign up 🔮</h1>
            <p class="text-center">🔱 Join the exclusive Million Club.</p>
            <div class="form-control">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" minlength="3">
                <small class="input-error" id="username_error">Please enter a username</small>
                <small class="input-error" id="usernameformat_error">Letters, numbers, underscores only e.g. jane_bubu33</small>
            </div>
            <div class="form-control">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" minlength="6">
                <small class="input-error" id="email_error">Please enter your email address</small>
            </div>
            <div class="form-control">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" minlength="6">
                <small class="input-error" id="password_error">Password must be 6 characters or more</small>
            </div>
            <div class="form-control">
                <label for="confirmPassword">Confirm password</label>
                <input type="password" name="confirmPassword" id="confirmPassword" minlength="6">
                <small class="input-error" id="confirmPassword_error">Re-enter password</small>
            </div>
            <div class="form-buttons">
                <button class="btn-primary btn-stretch">Sign up</button>
            </div>
        </form>
    </section>
</main>

<?php include('footer.php'); ?>

<script src="public/js/index.js"></script>