<?php

session_start();

if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin']==false) {
    header('Location: login-form.php');
    exit;
}

require_once("../config.php");
require_once(ROOT_PATH . 'connect.php');

if($_POST && isset($_POST['book_id']) && isset($_POST['content']) 
    && !empty($_POST['book_id']) && !empty($_POST['content'])) {
    $user_id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT);
    $book_id  = filter_input(INPUT_POST, 'book_id', FILTER_SANITIZE_NUMBER_INT);
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
             
    $query = "INSERT INTO books_users_notes (user_id, book_id, content) VALUES (:user_id, :book_id, :content)";

    $stmt = $db->prepare($query);
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);        
    $stmt->bindValue(':book_id', $book_id, PDO::PARAM_INT);
    $stmt->bindValue(':content', $content);
        

    $url = BASE_URL . 'dashboard/notes.php';
    if($stmt->execute()) {
        header("Location: $url");
    }
}

// books query.
$books_query = "SELECT book_id, book_title FROM books;";
$books_stmt = $db->prepare($books_query);
$books_stmt->execute(); 

include(ROOT_PATH . 'header.php'); 

?>
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.2/classic/ckeditor.js"></script>

<main class="main" id="dashnotes-new">
    <div class="container py-4">
        <h1>Add new note</h1>
    </div>

    <section class="content-center">
        <div class="form card" style="max-width: 1200px;">
            <form action="notes-new.php" method="POST" enctype='multipart/form-data'>
                <h2>New note</h2>

                <input type="hidden" name="user_id" value="<?=$_SESSION['user_id']?>">

                <div class="form-control">
                    <label>📙 Book</label>
                    <select class="form-select" name="book_id" style="max-width: 400px;">
                        <option selected disabled>Select a book</option>
                        <?php while($books = $books_stmt->fetch()): ?>
                        <option value="<?=$books['book_id']?>"><?=$books['book_title']?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="content">Content</label>
                    <textarea name="content" id="editor" cols="60" rows="10" placeholder="What's your opinion on this book..."></textarea>
                </div>

                <button type="submit" class="btn-primary btn-lg mt-4">Post</button>
            </form>
        </div>
    </section>
</main>

<script>
    ClassicEditor
        .create( document.querySelector( '#editor' ) )
        .then()
        .catch( error => {
            console.error( error );
        } );
</script>

<?php include(ROOT_PATH . 'footer.php'); ?>