<?php

/*******w******** 
    
    Name: Obafunsho Adelusi
    Date: September 26, 2023
    Description: Assignment 3 - Blog

****************/

require('authenticate.php');
require('connect.php');

if ($_POST && !empty($_POST['title']) && !empty($_POST['content'])) {
    //  Sanitize user input to escape HTML entities and filter out dangerous characters.
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
    //  Build the parameterized SQL query and bind to the above sanitized values.
    $query = "INSERT INTO blog (title, content) VALUES (:title, :content)";
    $statement = $db->prepare($query);
    
    //  Bind values to the parameters
    $statement->bindValue(':title', $title);
    $statement->bindValue(':content', $content);

    
    //  Execute the INSERT.
    //  execute() will check for possible SQL injection and remove if necessary
    if($statement->execute()) {
        echo "Success";
    }

    // Change to the show.php?{$id}
    header("Location: index.php?{$id}");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <title>Post a New Blog - CarZoo Blog</title>

    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.2/classic/ckeditor.js"></script>
</head>
<body>
    <header class="header">
        <div class="text-center">
            <h1>CarZoo Blog</h1>
        </div>
    </header>

    <?php include('nav.php'); ?>

    <main class="container py-1" id="create-post">
        <form action="post.php" method="POST">
            <h2>New Post</h2>
        
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" minlength="1" required>
            </div>
        
            <div class="form-group">
                <label for="content">Content</label>
                <textarea name="content" id="editor" cols="30" rows="10"></textarea>
            </div>

            <button type="submit" class="button-primary">Submit Blog</button>
        </form>
    </main>

<?php include('footer.php'); ?>

    <script>
        ClassicEditor
            .create( document.querySelector( '#editor' ) )
            .then( editor => {
                console.log(editor);
            } )
            .catch( error => {
                console.error( error );
            } );
    </script>
</body>
</html>