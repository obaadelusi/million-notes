<?php

session_start();

require_once("../config.php");

$login_page = BASE_URL . 'login-form.php';
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin']==false) {
    header("Location: $login_page");
    exit;
}

require_once(ROOT_PATH . 'connect.php');

if ($_GET && isset($_GET['id'])) { 
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    $query = "SELECT * 
              FROM books b
              JOIN books_genres g ON b.genre_id = g.genre_id
              WHERE b.book_id = :id";
    $statement = $db->prepare($query);
    $statement->bindValue(':id', $id, PDO::PARAM_INT);
    
    // Execute the SELECT and fetch the single row returned.
    $statement->execute();
    $book = $statement->fetch();
} else {
    $id = false;
}

include(ROOT_PATH . 'header.php'); 

?>

<main class="main" id="dashbooks-show">
    <?php if ($id): ?>
    <div class="section-header">
        <div class="container d-flex gap-3 flex-wrap">
            <div class="dashbooks-img">
                <?php if($book['book_image']) { ?>
                    <img src="<?=BASE_URL . 'uploads/' . $book['book_image']?>" alt="<?=$book['book_title']?>">
                <?php } ?>
            </div>
            <div>
                <h1><?=$book['book_title']?></h1>
                <p><?=$book['book_subtitle']?></p>
                <p><?=$book['genre_name']?> • <?=$book['book_pagecount']?> pages • <em>Published on <?=$book['date_pub']?> • </em></p>
                <small>
                    Added on
                    <time datetime="<?=$book['date_added']?>"><?=date_format(date_create($book['date_added']), 'F j, Y g:ia') ?><time>
                    &ensp;
                </small>
                <small><a href="<?=BASE_URL.'dashboard/books-edit.php?id='.$book['book_id']?>">edit</a></small>
            </div>
        </div>
    </div>

    <div class="container my-5" id="content">
        <h3>Subjects:</h3>
        <p><?=$book['book_subjects']?></p>

        <h3>Description:</h3>
        <p><?=htmlspecialchars_decode($book['book_desc'])?> </p>

        <h3></h3>
    </div>
    <?php else: ?>
    <p>No book selected.</p>
    <?php endif ?>
</main>

<?php include(ROOT_PATH . 'footer.php'); ?>