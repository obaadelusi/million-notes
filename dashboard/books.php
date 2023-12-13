<?php

session_start();

if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin']==false) {
    header('Location: login-form.php');
    exit;
}

require_once("../config.php");
require_once(ROOT_PATH . 'connect.php');

$row_count = 1;

// Books query.
$books_count_query = "SELECT COUNT(*) AS count FROM books 
                      WHERE is_active = 1;";
$books_count_stmt = $db->prepare($books_count_query);
$books_count_stmt->execute(); 
$books_count = $books_count_stmt->fetch();

$books_query = "SELECT b.book_id, b.book_title, b.book_subtitle,
                       b.book_author, b.book_pagecount, g.genre_name 
                FROM books b
                JOIN books_genres g ON b.genre_id=g.genre_id 
                WHERE b.is_active = 1
                ORDER BY b.date_added DESC";
$books_stmt = $db->prepare($books_query);
$books_stmt->execute(); 
// $books = $books_stmt->fetch();

include(ROOT_PATH . 'header.php'); 

?>

<main class="main dash" id="dashboard-books">
    <div class="section-header">
        <div class="container">
            <h1>📚 Books</h1>
            <p>There are currently <?=$books_count['count']?> books.</p>
        </div>
    </div>

    <div class="container py-4">
        <a href="<?=BASE_URL.'dashboard/books-new.php'?>" class="btn-primary">New Book +</a>
    </div>

    <section class="dashbooks container">
        <table class="table table-striped">
            <caption>All Books</caption>
            <thead class="table-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Title</th>
                    <th scope="col">Genres</th>
                    <th scope="col">Author</th>
                    <th scope="col">Page count</th>
                    <th scope="col">Links</th>
                </tr>
            </thead>
            <tbody>
                <?php if($books_stmt->rowCount() == 0) : ?>
                <tr>
                    <td colspan='4'>No books yet</td>
                </tr>
                <?php exit; endif; ?>

                <?php while($books = $books_stmt->fetch()): ?>
                <tr>
                    <th scope="row"><?=$row_count?></th>
                    <td>
                        <span class="text-success"><?=$books['book_title']?></span><br/>
                        <small><?=$books['book_subtitle']?></small><br/>
                        <small><a href="<?=BASE_URL.'dashboard/books-edit.php?id='.$books['book_id'] ?>" class="btn-link">Edit</a></small>
                    </td>
                    <td><?=$books['genre_name']?></td>
                    <td><?=$books['book_author']?></td>
                    <td><?=$books['book_pagecount']?></td>
                    <td>
                        <a href="#" class="btn-link">Add notes</a> / 
                        <a href="#" class="btn-link">Review</a>
                    </td>
                </tr>
                <?php $row_count++; endwhile; ?>
            </tbody>
        </table>
    </section>
</main>

<?php include(ROOT_PATH . 'footer.php'); ?>