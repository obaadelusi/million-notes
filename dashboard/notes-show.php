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

    $query = "SELECT n.note_id, u.user_name, n.content, n.date_created, 
                    b.book_title, b.book_author, b.book_image
              FROM books_users_notes n
              JOIN books b ON n.book_id = b.book_id
              JOIN users u ON n.user_id = u.user_id
              WHERE n.note_id = :id";
    $statement = $db->prepare($query);
    $statement->bindValue(':id', $id, PDO::PARAM_INT);
    
    // Execute the SELECT and fetch the single row returned.
    $statement->execute();
    $note = $statement->fetch();
} else {
    $id = false;
}

include(ROOT_PATH . 'header.php'); 

?>

<main class="main" id="dashnotes-show">
    <?php if ($id): ?>
    <div class="section-header">
        <div class="container">
            <span>Your notes on: </span>
            <h1><?=$note['book_title']?></h1>
            <small>
                Posted on
                <time datetime="<?=$note['date_created']?>"><?=date_format(date_create($note['date_created']), 'F j, Y H:ia') ?><time>
                &ensp; <a href="<?=BASE_URL.'dashboard/notes-edit.php?id='.$note['note_id']?>">edit</a>
            </small>
        </div>
    </div>
    <div class="container mt-5" id="content">
        <?=htmlspecialchars_decode($note['content'])?> 
    </div>
    <?php else: ?>
    <p>No note selected.</p>
    <?php endif ?>
</main>

<?php include(ROOT_PATH . 'footer.php'); ?>