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

// Books query.
$books_count_query = "SELECT COUNT(*) AS count FROM books 
                      WHERE is_active = 1;";
$books_count_stmt = $db->prepare($books_count_query);
$books_count_stmt->execute(); 
$books_count = $books_count_stmt->fetch();

if($_GET && !empty($_GET['q'])) {
    $q = filter_input(INPUT_GET, 'q', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $books_query = "SELECT b.book_id, b.book_title, b.book_subtitle,
                        b.book_author, b.book_pagecount, g.genre_name 
                    FROM books b
                    JOIN books_genres g ON b.genre_id=g.genre_id 
                    WHERE b.is_active = 1 AND
                        (b.book_title LIKE '%$q%' OR b.book_subtitle LIKE '%$q%')
                    ORDER BY b.date_added DESC LIMIT 10";

    if(!empty($_GET['genre_id'])) {
        $genre_id = filter_input(INPUT_GET, 'genre_id', FILTER_SANITIZE_NUMBER_INT);
        $books_query = "SELECT b.book_id, b.book_title, b.book_subtitle,
        b.book_author, b.book_pagecount, g.genre_name 
                        FROM books b
                        JOIN books_genres g ON b.genre_id=g.genre_id 
                        WHERE b.is_active = 1 AND
                            b.book_title LIKE '%$q%' AND
                            b.genre_id = $genre_id 
                        ORDER BY b.date_added DESC LIMIT 10";
    }

} else {
    $books_query = "SELECT b.book_id, b.book_title, b.book_subtitle,
                        b.book_author, b.book_pagecount, g.genre_name 
                    FROM books b
                    JOIN books_genres g ON b.genre_id=g.genre_id 
                    WHERE b.is_active = 1
                    ORDER BY b.date_added DESC LIMIT 10";
}
$books_stmt = $db->prepare($books_query);
$books_stmt->execute(); 

$genres_query = "SELECT * FROM books_genres ORDER BY genre_name ASC";
$genres_stmt = $db->prepare($genres_query);
$genres_stmt->execute(); 

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

        <form action="books.php">            
            <div class="input-group my-3">
                <input type="text" style="max-width: 400px" id="bookSearch" name="q" placeholder="Enter word in book title/subtitle...">
                <select class="form-select" id="genreSelect" style="max-width: 260px" name="genre_id">
                    <option selected>All Genres</option>
                    <?php while($g = $genres_stmt->fetch()): ?>
                    <option value="<?=$g['genre_id']?>"><?=$g['genre_name']?></option>
                    <?php endwhile; ?>
                </select>
                <button class="btn btn-outline-primary" type="submit">Search book</button>
            </div>
        </form>
    </div>

    <section class="dashbooks container">
        <table class="table table-striped">
            <caption>All Books</caption>
            <thead class="table-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Title</th>
                    <th scope="col">Genre</th>
                    <th scope="col">Author</th>
                    <th scope="col">Page count</th>
                    <th scope="col">Links</th>
                </tr>
            </thead>
            <tbody>
                <?php if($books_stmt->rowCount() == 0) : ?>
                <tr>
                    <td colspan='6' class="text-center">*No books yet*</td>
                </tr>
                <?php exit; endif; ?>

                <?php while($books = $books_stmt->fetch()): ?>
                <tr>
                    <th scope="row"><?=$row_count?></th>
                    <td>
                        <h4 class="text-success"><?=$books['book_title']?></h4>
                        <p><?=$books['book_subtitle']?></p>
                        <small><a href="<?=BASE_URL.'dashboard/books-edit.php?id='.$books['book_id'] ?>" class="btn-link">Edit</a></small>
                    </td>
                    <td><?=$books['genre_name']?></td>
                    <td><?=$books['book_author']?></td>
                    <td><?=$books['book_pagecount']?></td>
                    <td>
                        <a href="<?=BASE_URL.'dashboard/notes-new.php?book_id='.$books['book_id']?>" class="btn-link">Add notes</a>                     </td>
                </tr>
                <?php $row_count++; endwhile; ?>
            </tbody>
        </table>
    </section>
</main>

<?php include(ROOT_PATH . 'footer.php'); ?>