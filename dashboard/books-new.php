<?php

session_start();

require_once("../config.php");

$login_page = BASE_URL . 'login-form.php';
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin']==false) {
    header("Location: $login_page");
    exit;
}

require_once(ROOT_PATH . 'connect.php');

function file_upload_path($original_filename, $upload_subfolder_name = 'uploads') {
    // $current_folder = dirname(__FILE__);
    $current_folder = ROOT_PATH;
    
    $path_segments = [$current_folder, $upload_subfolder_name, basename($original_filename)];
    
    return join(DIRECTORY_SEPARATOR, $path_segments);
    // return BASE_URL . basename($original_filename);
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
    && !empty($_POST['book-title']) && !empty($_POST['book-author'])) {
    $title  = filter_input(INPUT_POST, 'book-title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $author = filter_input(INPUT_POST, 'book-author', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $genre_id = filter_input(INPUT_POST, 'book-genre', FILTER_SANITIZE_NUMBER_INT);
    $subtitle = filter_input(INPUT_POST, 'book-subtitle', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $pagecount = filter_input(INPUT_POST, 'book-pagecount', FILTER_SANITIZE_NUMBER_INT);

    if ($file_upload_detected) { 
        $file_name        = $_FILES['bookImage']['name'];
        $temporary_file_path  = $_FILES['bookImage']['tmp_name'];
        $new_file_path        = file_upload_path($file_name);
        
        if (file_is_an_image($temporary_file_path, $new_file_path)) {
            move_uploaded_file($temporary_file_path, $new_file_path);
        };

        $query = "INSERT INTO books (book_title, book_author, genre_id, book_image, book_subtitle, book_pagecount) VALUES (:book_title, :book_author, :genre_id, :book_image, :book_subtitle, :book_pagecount)";

        $stmt = $db->prepare($query);
        $stmt->bindValue(':book_title', $title);        
        $stmt->bindValue(':book_author', $author);
        $stmt->bindValue(':genre_id', $genre_id, PDO::PARAM_INT);
        $stmt->bindValue(':book_subtitle', $subtitle);
        $stmt->bindValue(':book_pagecount', $pagecount, PDO::PARAM_INT);
        $stmt->bindValue(':book_image', $file_name);

    } else {              
        $query = "INSERT INTO books (book_title, book_author, genre_id, book_subtitle, book_pagecount) VALUES (:book_title, :book_author, :genre_id, :book_subtitle, :book_pagecount)";
  
        $stmt = $db->prepare($query);
        $stmt->bindValue(':book_title', $title);        
        $stmt->bindValue(':book_author', $author);
        $stmt->bindValue(':genre_id', $genre_id, PDO::PARAM_INT);
        $stmt->bindValue(':book_subtitle', $subtitle);
        $stmt->bindValue(':book_pagecount', $pagecount, PDO::PARAM_INT);
        // $location = "show.php?id={$id}";            
    }

    $url = BASE_URL . 'dashboard/books.php';
    if($stmt->execute()) {
        header("Location: $url");
    }
}

// Genres query.
$genres_query = "SELECT * FROM books_genres ORDER BY genre_name ASC;";
$genres_stmt = $db->prepare($genres_query);
$genres_stmt->execute(); 
// $genres = $genres_stmt->fetch();

include(ROOT_PATH . 'header.php'); 

?>

<main class="main" id="newbook">
    <div class="container py-4">
        <h1>Add new book</h1>
    </div>

    <section class="content-center">
        <div class="form card" style="max-width: 460px;">
            <form action="new-book.php" method="POST" enctype='multipart/form-data'>
                <h2 class="form-heading">New book</h2>

                <div class="form-control">
                    <label for="book-title">Book title</label>
                    <input type="text" id="book-title" name="book-title">
                </div>

                <div class="form-control">
                    <label for="book-author">Author</label>
                    <input type="text" id="book-author" name="book-author">
                </div>

                <div class="form-control">
                    <label>Genre</label>
                    <select class="form-select" name="book-genre">
                        <option selected disabled>Select genre</option>
                        <?php while($genres = $genres_stmt->fetch()): ?>
                        <option value="<?=$genres['genre_id']?>"><?=$genres['genre_name']?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-control">
                    <label for="bookImage">Book cover image</label>
                    <input type="file" accept=".jpg,.jpeg,.png,.gif" id="bookImage" name="bookImage">
                </div>

                <div class="form-control">
                    <label for="book-subtitle">Subtitle</label>
                    <input type="text" id="book-subtitle" name="book-subtitle">
                </div>

                <div class="form-control">
                    <label for="book-pagecount">Page count</label>
                    <input type="number" id="book-pagecount" name="book-pagecount">
                </div>

                <div class="form-buttons">
                    <button type="reset" class="btn-link">Reset</button>
                    <button type="submit" class="btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </section>
</main>

<?php include(ROOT_PATH . 'footer.php'); ?>