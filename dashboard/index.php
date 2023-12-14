<?php

session_start();

require_once("../config.php");

$login_page = BASE_URL . 'login-form.php';
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin']==false) {
    header("Location: $login_page");
    exit;
}

require_once(ROOT_PATH . 'connect.php');

include(ROOT_PATH . 'header.php'); 

$user_id = $_SESSION['user_id'];

// Books query.
$books_count_query = "SELECT COUNT(*) AS count FROM books;";
$books_count_stmt = $db->prepare($books_count_query);
$books_count_stmt->execute(); 
$books_count = $books_count_stmt->fetch();

$books_sugg_query = "SELECT COUNT(*) AS count FROM books_users_suggestions  
                     WHERE user_id = $user_id";
$books_sugg_stmt = $db->prepare($books_sugg_query);
$books_sugg_stmt->execute(); 
$books_sugg = $books_sugg_stmt->fetch();

$books_notes_query = "SELECT COUNT(*) AS total_count, 
                      (SELECT COUNT(*) FROM books_users_notes  
                      WHERE user_id = $user_id) AS user_notes_count
                      FROM books_users_notes";
$books_notes_stmt = $db->prepare($books_notes_query);
$books_notes_stmt->execute(); 
$books_notes = $books_notes_stmt->fetch();

?>

<main class="main dash" id="dashboard-index">
    <div class="section-header">
        <h1 class="container">Dashboard</h1>
    </div>

    <section class="container py-2 dash-cards">
        <div class="card dash-books">
            <h2>Books</h2>
            <ul>
                <li>Number of books: <?=$books_count['count']?></li>
                <li>Books suggested: <?=$books_sugg['count']?></li>
            </ul>
            <a href="<?=BASE_URL.'dashboard/books.php'?>" class="btn-link">View all books -></a>
        </div>

        <div class="card dash-notes">
            <h2>Your booknotes</h2>
            <ul>
                <li>Notes: <?=$books_notes['total_count']?></li>
                <li>Notes by you: <?=$books_notes['user_notes_count']?></li>
            </ul>
            <a href="<?=BASE_URL.'dashboard/notes.php'?>" class="btn-link">View your notes -></a>
        </div>

        <div class="card dash-buttons">
            <a href="<?=BASE_URL.'dashboard/notes-new.php'?>" class="btn-outline-primary">Add booknotes +</a>
            <a href="<?=BASE_URL.'dashboard/books-new.php'?>" class="btn-link">New book +</a>
        </div>

        <div class="card dash-account" style="max-width:360px;">
            <div class="dash-account-image">
                <img src="<?=BASE_URL.'images/avatar.jpg'?>" alt="avatar">
            </div>
            <p>@<?=$_SESSION['user_name']?></p>
            <!-- <a href="#" class="btn-link">Edit account</a> -->
        </div>
    </section>
</main>

<?php include(ROOT_PATH . 'footer.php'); ?>