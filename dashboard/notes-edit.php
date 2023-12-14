<?php

session_start();

$login_url = BASE_URL . 'login-form.php';
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin']==false) {
    header("Location: $login_url");
    exit;
}

require_once("../config.php");
require_once(ROOT_PATH . 'connect.php');

if($_POST && isset($_POST['book_id']) && isset($_POST['content']) 
    && !empty($_POST['book_id']) && !empty($_POST['content'])) {
    $id = filter_input(INPUT_POST, 'note_id', FILTER_SANITIZE_NUMBER_INT);
    $user_id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT);
    $book_id  = filter_input(INPUT_POST, 'book_id', FILTER_SANITIZE_NUMBER_INT);
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if (isset($_POST['command']) && $_POST['command']=='Delete') {
        print_r("xxxxxxx Post command");
        $query = "UPDATE books_users_notes SET is_active=0 WHERE note_id=:id";

        $stmt = $db->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    } else {
        $query = "UPDATE books_users_notes SET user_id=:user_id, book_id=:book_id, content=:content WHERE note_id=:id";
    
        $stmt = $db->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);       
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);        
        $stmt->bindValue(':book_id', $book_id, PDO::PARAM_INT);
        $stmt->bindValue(':content', $content);
    }        

    $url = BASE_URL . 'dashboard/notes.php';
    if($stmt->execute()) {
        header("Location: $url");
    }
}

// notes query.
if($_GET && isset($_GET['id'])) {
    $note_id = $_GET['id'];

    $note_query = "SELECT n.content, n.book_id, n.user_id, 
                          b.book_title, b.book_image
                   FROM books_users_notes n
                   JOIN books b ON n.book_id = b.book_id
                   WHERE n.note_id = $note_id;";
    $note_stmt = $db->prepare($note_query);
    $note_stmt->execute(); 
    $note = $note_stmt->fetch(); 
}

include(ROOT_PATH . 'header.php'); 

?>
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.2/classic/ckeditor.js"></script>

<main class="main" id="dashnotes-edit">
    <div class="container py-4">
        <h1>Edit</h1>
    </div>

    <section class="content-center">
        <div class="form card" style="max-width: 1200px;">
            <form action="notes-edit.php" method="POST">
                <h2>Edit note on</h2>

                <input type="hidden" name="note_id" value="<?=$_GET['id']?>">
                <input type="hidden" name="user_id" value="<?=$note['user_id']?>">
                <input type="hidden" name="book_id" value="<?=$note['book_id']?>">

                <div class="dashnotes-image ms-auto">
                    <img src="<?=BASE_URL.'uploads/'.$note['book_image']?>" alt="<?=$note['book_image']?>">
                </div>

                <div class="form-control">
                    <label>📙 Book</label>
                    <input type="text" value="<?=$note['book_title']?>" style="max-width: 400px;" disabled>
                </div>

                <div class="form-group">
                    <label for="content">Content</label>
                    <textarea name="content" id="editor" cols="60" rows="10" placeholder="What's your opinion on this book..."><?=$note['content']?></textarea>
                </div>

                <div class="form-buttons mt-4" style="max-width:400px;">
                    <input type="submit" name="command" value="Update Note" class="btn-primary">
                    <input type="submit" name="command" value="Delete" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete this note?')">
                </div>
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