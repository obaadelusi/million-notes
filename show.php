<?php 

require('connect.php');

if (isset($_GET['id'])) { // Retrieve blog to be edited, if id GET parameter is in URL.
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
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <title><?="{$blog['title']}"?> - CarZoo Blog</title>
</head>
<body>
    <header class="header">
        <div class="text-center">
            <h1>CarZoo Blog</h1>
        </div>
    </header>

    <?php include('nav.php'); ?>

    <main class="container py-1">
    <?php if ($id): ?>
        <h1 class="blog-post-title"><?=$blog['title']?></h1>
        <small class="blog-post-date">
            Posted on 
            <time datetime="<?=$blog['date_posted']?>"><?=date_format(date_create($blog['date_posted']), 'F j, Y G:i') ?><time> 
            &ensp; <a href="edit.php?id=<?=$blog['id']?>" class="blog-post-edit">edit</a>
        </small>
        <p class="blog-post-content">
            <?=htmlspecialchars_decode($blog['content'])?> 
        </p>
    <?php else: ?>
        <p>No blog selected. <a href="?id=1">Try this link</a>.</p>
    <?php endif ?>
    </main>

    <?php include('footer.php') ?>

</body>
</html>