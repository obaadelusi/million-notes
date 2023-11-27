<?php

/*******w******** 
    
    Name: Obafunsho Adelusi
    Date: September 27
    Description: Assignment 3 - Blog

****************/

require('connect.php');

if ($_POST && isset($_POST['title']) && isset($_POST['content']) && isset($_POST['id'])
    && !empty($_POST['title']) && !empty($_POST['content'])) {
    // Sanitize user input to escape HTML entities and filter out dangerous characters.
    $title  = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $id      = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    
    // Build the parameterized SQL query and bind to the above sanitized values.
    if(isset($_POST['command']) && $_POST['command']=='Delete') {
        $query = "DELETE FROM blog WHERE id = :id";
        $statement = $db->prepare($query);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $location = "index.php";
    } else {
        $query = "UPDATE blog SET title = :title, content = :content WHERE id = :id";
        $statement = $db->prepare($query);
        $statement->bindValue(':title', $title);        
        $statement->bindValue(':content', $content);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $location = "show.php?id={$id}";
    }

    $statement->execute();

    // Redirect after edit.
    header("Location: {$location}");
    exit;

} else if (isset($_GET['id'])) { // Retrieve quote to be edited, if id GET parameter is in URL.
    
    // Sanitize the id. Like above but this time from INPUT_GET.
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    
    // Build the parametrized SQL query using the filtered id.
    $query = "SELECT * FROM blog WHERE id = :id";
    $statement = $db->prepare($query);
    $statement->bindValue(':id', $id, PDO::PARAM_INT);
    
    // Execute the SELECT and fetch the single row returned.
    $statement->execute();
    $blog = $statement->fetch();
} else {
    $id = false; // False if we are not UPDATING or SELECTING.
};

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <title>Edit Post - CarZoo Blog</title>

    <?php require('authenticate.php'); ?>
    
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.2/classic/ckeditor.js"></script>
</head>
<body>
    <header class="header">
        <div class="text-center">
            <h1>CarZoo Blog</h1>
        </div>
    </header>

    <?php include('nav.php'); ?>

    <!-- Remember that alternative syntax is good and html inside php is bad -->
    <main class="container py-1" id="create-post">
    <?php if ($id): ?>
        <form action="edit.php" method="POST">
            <h2>Edit this post!</h2>
        
            <input type="hidden" name="id" value="<?=$blog['id']?>">

            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" value="<?=$blog['title']?>" minlength="1" required>
            </div>
        
            <div class="form-group">
                <label for="content">Content</label>
                <textarea name="content" id="editor" cols="30" rows="10"><?=$blog['content']?></textarea>
            </div>
        
            <input type="submit" class="button-primary" name="command" value="Update Blog">
            <input type="submit" class="button-primary-outline" name="command" value="Delete" onclick="return confirm('Are you sure you want to delete this post?')">
        </form>
    <?php else: ?>
        <p>No blog selected. <a href="post.php">Create new post</a>.</p>
    <?php endif ?>
    </main>

    <?php include('footer.php'); ?>

    <script>
        ClassicEditor
            .create( document.querySelector( '#editor' ) )
            .catch( error => {
                console.error( error );
            } );
    </script>

</body>
</html>