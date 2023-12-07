<?php

session_start();

if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin']==false) {
    header('Location: login-form.php');
    exit;
}

require_once("../config.php");
require_once(ROOT_PATH . 'connect.php');

include(ROOT_PATH . 'header.php'); 
?>

<main class="main dash" id="dashboard-page">
    <div class="section-header">
        <h1 class="container">Dashboard</h1>
    </div>

    <section class="container py-2 dash-cards">
        <div class="card dash-books">
            <h2>Books</h2>
            <ul>
                <li>Number of books:</li>
                <li>Books read this month:</li>
            </ul>
            <a href="#" class="btn-link">View all books -></a>
        </div>
        <div class="card dash-reviews">
            <h2>Your reviews</h2>
            <ul>
                <li>Number of books:</li>
                <li>Books reviewed this month:</li>
            </ul>
            <a href="#" class="btn-link">View your reviews -></a>
        </div>
        <div class="card dash-notes">
            <h2>Your booknotes</h2>
            <ul>
                <li>Number of notes:</li>
                <li>This month notes:</li>
            </ul>
            <a href="#" class="btn-link">View your notes -></a>
        </div>
        <div class="card dash-buttons">
            <a href="#" class="btn-outline-primary">Add booknotes +</a>
            <a href="#" class="btn-link">Review a book</a>
            <a href="#" class="btn-link">Add a new book</a>
        </div>
        <div class="card dash-account" style="max-width:360px;">
            <div class="dash-account-image">
                <img src="<?=BASE_URL.'images/avatar.jpg'?>" alt="avatar">
            </div>
            <p>@<?=$_SESSION['user_name']?></p>
            <a href="#" class="btn-link">Edit account</a>
        </div>
    </section>
</main>

<?php include(ROOT_PATH . 'footer.php'); ?>