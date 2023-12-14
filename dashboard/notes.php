<?php

session_start();

$login_url = BASE_URL . 'login-form.php';
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin']==false) {
    header("Location: $login_url");
    exit;
}

require_once("../config.php");
require_once(ROOT_PATH . 'connect.php');

$row_count = 1;

// notes query.
$notes_count_query = "SELECT COUNT(*) AS total_count
                      FROM books_users_notes";
$notes_count_stmt = $db->prepare($notes_count_query);
$notes_count_stmt->execute(); 
$notes_count = $notes_count_stmt->fetch();

$notes_query = "SELECT n.note_id, u.user_name, n.content, n.date_created, 
                       b.book_title, b.book_author, b.book_image
                FROM books_users_notes n
                JOIN books b ON n.book_id = b.book_id
                JOIN users u ON n.user_id = u.user_id
                WHERE n.is_active = 1
                ORDER BY n.date_created DESC";
$notes_stmt = $db->prepare($notes_query);
$notes_stmt->execute(); 

include(ROOT_PATH . 'header.php'); 

?>

<main class="main dash" id="dashboard-books">
    <div class="section-header">
        <div class="container">
            <h1>📝 Notes</h1>
            <p>There are currently <?=$notes_count['total_count']?> notes.</p>
        </div>
    </div>

    <div class="container py-4">
        <a href="<?=BASE_URL.'dashboard/notes-new.php'?>" class="btn-primary">New note +</a>
    </div>

    <section class="dashnotes container">
        <?php if($notes_stmt->rowCount() == 0) : ?>
        <div class="content-center">
            <p>No notes yet</p>
        </div>
        <?php exit; endif; ?>

        <?php while($notes = $notes_stmt->fetch()): ?>
        <div class="card <?=$notes['user_name'] == $_SESSION['user_name']?'border-success': ''?>" style="max-width: 800px;">
            <div class="d-flex align-items-center">
                <div class="dashnotes-userimage">
                    <img src="<?=BASE_URL?>images/avatar.jpg" alt="avatar">
                </div>
                <p class="m-0">@<?=$notes['user_name']?></p>
                <small class="ms-auto">Posted <?=(new \DateTime($notes['date_created']))->format('F d, Y H:ia')?></small>
            </div>

            <?php if($notes['user_name'] == $_SESSION['user_name']): ?>
            <small class="ms-auto">
                <a href="<?=BASE_URL.'dashboard/notes-edit.php?id='.$notes['note_id']?>">Edit</a>
            </small>
            <?php endif; ?> 

            <p>📌 on <b><?=$notes['book_title']?> </b><span><em>by</em> <?=$notes['book_author']?></span></p>            
            
            <article class="mt-2">
            <?=substr(strip_tags(html_entity_decode($notes['content'])), 0, 200)?>
            <?php if(strlen($notes['content']) > 200) : ?>
                ... <a href="<?=BASE_URL.'dashboard/notes-show?id='.$notes['note_id']?>">Read more</a>
            <?php endif ?>
            </article>
        </div>
        <?php $row_count++; endwhile; ?>
    </section>
</main>

<?php include(ROOT_PATH . 'footer.php'); ?>