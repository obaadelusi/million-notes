<?php include('header.php') ?>

<main class="error-page">
    <div class="error-page-container">
        <h1>🚨 Page Not Found</h1>
        <h2>Error 404</h2>
        <p><?=$_SERVER['REQUEST_URI']?> does not exist.</p>
    </div>
</main>

<?php include('footer.php') ?>