<?php 

session_start();
include('header.php'); 
?>

<?php if(isset($_SESSION['loggedin'])) { ?>
    <main class="main content-center" id="login-page">
        <section class="card" style="max-width:400px;">
            <p class="text-center">Your are logged in with account:</p>
            <h2 class="text-center"><span>@<?=$_SESSION['name']?></span></h2>
            <div>
                <a href="logout.php" class="btn-primary btn-lg">Logout</a>
            </div>
        </section>
    </main>

<?php } else { ?>
    <main class="main content-center" id="login-page">
        <section class="form card">
            <h1 class="form-heading">Log In ⚡</h1>
            <p class="input-error text-center" id="login_error">Invalid username or password.</p>

            <div class="alert-info">
                <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="20" height="20" viewBox="0 0 50 50">
                    <path d="M 25 2 C 12.309295 2 2 12.309295 2 25 C 2 37.690705 12.309295 48 25 48 C 37.690705 48 48 37.690705 48 25 C 48 12.309295 37.690705 2 25 2 z M 25 4 C 36.609824 4 46 13.390176 46 25 C 46 36.609824 36.609824 46 25 46 C 13.390176 46 4 36.609824 4 25 C 4 13.390176 13.390176 4 25 4 z M 25 11 A 3 3 0 0 0 22 14 A 3 3 0 0 0 25 17 A 3 3 0 0 0 28 14 A 3 3 0 0 0 25 11 z M 21 21 L 21 23 L 22 23 L 23 23 L 23 36 L 22 36 L 21 36 L 21 38 L 22 38 L 23 38 L 27 38 L 28 38 L 29 38 L 29 36 L 28 36 L 27 36 L 27 21 L 26 21 L 22 21 L 21 21 z"></path>
                </svg>
                <p>Username: <b>admin</b> Password: <b>123456</b></p>                
            </div>
            <form action="login.php" method="post" id="login-form">
                <div class="form-control">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" >
                    <small class="input-error" id="username_error">Please enter your username</small>
                    <small class="input-error" id="nameformat_error">Username is 3 or more characters</small>
                </div>
                <div class="form-control">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password">
                    <small class="input-error" id="password_error">Please enter your password</small>
                    <small class="input-error" id="passformat_error">Password is 6 or more characters</small>
                </div>
                <div class="form-buttons">
                    <button class="btn-primary btn-stretch">Log In</button>
                </div>
            </form>
        </section>
    </main>

<?php } ?>

<?php include('footer.php'); ?>

<script src="public/js/index.js"></script>