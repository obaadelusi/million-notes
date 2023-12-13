<?php

session_start();

if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin']==false) {
    header('Location: login-form.php');
    exit;
}

require_once("../config.php");
require_once(ROOT_PATH . 'connect.php');

$url = BASE_URL . 'dashboard/books.php';

function file_upload_path($original_filename, $upload_subfolder_name = 'uploads') {
    $current_folder = dirname(__FILE__);
    
    // Build an array of paths segment names to be joins using OS specific slashes.
    $path_segments = [$current_folder, $upload_subfolder_name, basename($original_filename)];
    
    // The DIRECTORY_SEPARATOR constant is OS specific.
    // return join(DIRECTORY_SEPARATOR, $path_segments);
    return BASE_URL . basename($original_filename);
 }

 function file_is_an_image($temporary_path, $new_path) {
     $allowed_mime_types      = ['image/gif', 'image/jpeg', 'image/png'];
     $allowed_file_extensions = ['gif', 'jpg', 'jpeg', 'png'];
     
     $actual_file_extension   = strtolower(pathinfo($new_path, PATHINFO_EXTENSION));
     $actual_mime_type        = mime_content_type($temporary_path);
     
     $file_extension_is_valid = in_array($actual_file_extension, $allowed_file_extensions);
     $mime_type_is_valid      = in_array($actual_mime_type, $allowed_mime_types);
     
     return $file_extension_is_valid && $mime_type_is_valid;
 }
 
 $file_upload_detected = isset($_FILES['bookImage']) && ($_FILES['bookImage']['error'] === 0);
 $upload_error_detected = isset($_FILES['bookImage']) && ($_FILES['bookImage']['error'] > 0);

if($_POST && isset($_POST['book-title']) && isset($_POST['book-author']) 
    && isset($_POST['id']) 
    && !empty($_POST['book-title']) && !empty($_POST['book-author'])) {
    $id = filter_input(INPUT_POST, 'book_id', FILTER_SANITIZE_NUMBER_INT);
    $title  = filter_input(INPUT_POST, 'book-title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $author = filter_input(INPUT_POST, 'book-author', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $genre_id = filter_input(INPUT_POST, 'book-genre', FILTER_SANITIZE_NUMBER_INT);
    $subtitle = filter_input(INPUT_POST, 'book-subtitle', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $pagecount = filter_input(INPUT_POST, 'book-pagecount', FILTER_SANITIZE_NUMBER_INT);
    
    if (isset($_POST['command']) && $_POST['command']=='Delete') {
        $query = "UPDATE books SET is_active=0 WHERE book_id=:id";

        $stmt = $db->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    }
    elseif ($file_upload_detected) { 
        $file_name        = $_FILES['bookImage']['name'];
        $temporary_file_path  = $_FILES['bookImage']['tmp_name'];
        $new_file_path        = file_upload_path($file_name);
        
        $query = "UPDATE books SET book_title=:book_title, book_author=:book_author, genre_id=:genre_id, book_image=:book_image, book_subtitle=:book_subtitle, book_pagecount=:book_pagecount) WHERE book_id=:id";

        $stmt = $db->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':book_title', $title);        
        $stmt->bindValue(':book_author', $author);
        $stmt->bindValue(':genre_id', $genre_id, PDO::PARAM_INT);
        $stmt->bindValue(':book_subtitle', $subtitle);
        $stmt->bindValue(':book_pagecount', $pagecount, PDO::PARAM_INT);
        $stmt->bindValue(':book_image', $file_name);

        if (file_is_an_image($temporary_file_path, $new_file_path)) {
            move_uploaded_file($temporary_file_path, $new_file_path);
        }

    } else {              
        $query = "UPDATE books SET book_title=:book_title, book_author=:book_author, genre_id=:genre_id, book_subtitle=:book_subtitle, book_pagecount=:book_pagecount WHERE book_id=:book_id";
  
        $stmt = $db->prepare($query);
        $stmt->bindValue(':book_id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':book_title', $title);        
        $stmt->bindValue(':book_author', $author);
        $stmt->bindValue(':genre_id', $genre_id, PDO::PARAM_INT);
        $stmt->bindValue(':book_subtitle', $subtitle);
        $stmt->bindValue(':book_pagecount', $pagecount, PDO::PARAM_INT);         
    }

    if($stmt->execute()) {
        header("Location: $url");
    }
}

// Genres query.
$genres_query = "SELECT * FROM books_genres;";
$genres_stmt = $db->prepare($genres_query);
$genres_stmt->execute(); 


if($_GET && isset($_GET['id'])) {
    $book_id = $_GET['id'];
    // Book query.
    $book_query = "SELECT book_id, book_title, book_author, genre_id, book_image, book_subtitle, book_pagecount FROM books WHERE book_id=$book_id;";
    $book_stmt = $db->prepare($book_query);
    $book_stmt->execute(); 
    $book = $book_stmt->fetch();
} else {
    header("Location: $url");
    exit;
}

include(ROOT_PATH . 'header.php'); 

?>

<main class="main" id="newbook">
    <div class="py-4 section-header">
        <h1 class="ms-5">Edit book</h1>
    </div>

    <section class="content-center">
        <div class="form card" style="max-width: 460px;">
            <form action="edit-book.php" method="POST" enctype='multipart/form-data'>
                <h2 class="form-heading">Edit: <?=$book['book_title']?></h2>

                <input type="hidden" name="book_id" value="<?=$book['book_id']?>">

                <div class="form-control">
                    <label for="book-title">Book title</label>
                    <input type="text" id="book-title" name="book-title" value="<?=$book['book_title']?>">
                </div>

                <div class="form-control">
                    <label for="book-author">Author</label>
                    <input type="text" id="book-author" name="book-author" value="<?=$book['book_author']?>">
                </div>

                <div class="form-control">
                    <label>Genre</label>
                    <select class="form-select" name="book-genre">
                        <?php while($genres = $genres_stmt->fetch()): ?>
                        <option value="<?=$genres['genre_id']?>" <?=($book['genre_id']==$genres['genre_id'])?'selected':'' ?> ><?=$genres['genre_name']?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <?php if($book['book_image']) {
                    $image = $book['book_image'] ?>
                    <img src="<?=BASE_URL . 'uploads/' . $book['book_image']?>" alt="<?=$book['book_title']?>">
                <?php } ?>

                <div class="form-control">
                    <label for="bookImage">Upload book cover image</label>
                    <input type="file" accept=".jpg,.jpeg,.png,.gif" id="bookImage" name="bookImage">
                </div>

                <div class="form-control">
                    <label for="book-subtitle">Subtitle</label>
                    <input type="text" id="book-subtitle" name="book-subtitle" value="<?=$book['book_subtitle']?>">
                </div>

                <div class="form-control">
                    <label for="book-pagecount">Page count</label>
                    <input type="number" id="book-pagecount" name="book-pagecount" value="<?=$book['book_pagecount']?>">
                </div>

                <div class="form-buttons">
                    <input type="submit" name="command" value="Update Book" class="btn-primary">
                    <input type="submit" name="command" value="Delete" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete this book?')">
                </div>
            </form>
        </div>
    </section>
</main>

<?php include(ROOT_PATH . 'footer.php'); ?>