<?php

session_start();

if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin']==false) {
    header('Location: login-form.php');
    exit;
}

require_once("../config.php");
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
        <div class="container">
            <h1><?=$book['book_title']?></h1>
            <p><?=$book['book_title']?></p>
            <small>
                Added on
                <time datetime="<?=$book['date_added']?>"><?=date_format(date_create($book['date_added']), 'F j, Y g:ia') ?><time>
                &ensp; 
            </small>
            <span><a href="<?=BASE_URL.'dashboard/books-edit.php?id='.$book['book_id']?>">edit</a></span>
        </div>
    </div>
    <div class="container my-5" id="content">
        <?=htmlspecialchars_decode($book['content'])?> 
    </div>
    <?php else: ?>
    <p>No book selected.</p>
    <?php endif ?>
</main>

<?php include(ROOT_PATH . 'footer.php'); ?>