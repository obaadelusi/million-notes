<?php

/*******w******** 
    
    Name: Obafunsho Adelusi
    Date: September 26
    Description: Assignment 3 - Blog

****************/

require('connect.php');
    
// SQL is written as a String.
$query = "SELECT * FROM blog ORDER BY date_posted DESC LIMIT 8";

// A PDO::Statement is prepared from the query.
$statement = $db->prepare($query);

// Execution on the DB server is delayed until we execute().
$statement->execute(); 

?>

<?php include('header.php'); ?>

<main class="main container">
    <section class="hero">
        <div class="hero-content">
            <small>⭐ <?=date("F")?> Read</small>
            <h1>Million Dollar Habits</h1>
            <p>Proven Power Practices to Double & Triple Your Income</p>
            <p><em>by</em>&ensp;Brian Tracy</p>

            <div class="hero-cta">
                <a href="m-notes.php?book_id=1" class="btn-primary btn-lg">Read BookNotes</a>
                <a href="#" class="btn-link">View Book</a>
            </div>
        </div>
        <div class="hero-image">
            <img src="uploads/million_dollar_habits_brian_tracy.png" alt="million dollar habit">
        </div>
    </section>
    
    <?php if($statement->rowCount() == 0) : ?>
        <div class="text-center py-1">
            <p>No blog entries yet!</p>
            <a href="#" class="button-primary">+ New Blog</a>
        </div>
    <?php exit; endif; ?>

    <!-- Fetch each table row in turn. Each $row is a table row hash.
    Fetch returns FALSE when out of rows, halting the loop. -->
    <?php while($row = $statement->fetch()): ?>
        <article class="blog-post">
            <h3 class="blog-post-title">
                <a href="show.php?id=<?=$row['id']?>"><?=$row['title']?></a>
            </h3>
            <small class="blog-post-date">
                Posted on <time datetime="<?=$row['date_posted']?>"><?=date_format(date_create($row['date_posted']), 'F j, Y G:i') ?><time>
                &ensp; <a href="edit.php?id=<?=$row['id']?>" class="blog-post-edit">edit</a>
            </small>
            <p class="blog-post-content">
                <?=substr(strip_tags(html_entity_decode($row['content'])), 0, 200)?>
                <?php if(strlen($row['content']) > 200) : ?>
                    ... <a href="show.php?id=<?=$row['id']?>">Read more</a>
                <?php endif ?>
            </p>
        </article>
    <?php endwhile; ?>

</main>

<?php include('footer.php'); ?>