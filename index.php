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

<main class="main">
    <section class="hero">
        <div class="container hero-container">
            <div class="hero-content">
                <small>📖 Book Club</small>
                <h1>Get & Make Summaries <br>of Your Favourite Books.</h1>
                <p>🖊 Read. Review. Make notes. Monthly.</p>

                <div class="hero-cta">
                    <a href="m-notes.php?book_id=1" class="btn-primary btn-lg">Join Club</a>
                    <a href="#" class="btn-link">View Monthly Reads</a>
                </div>
            </div>
            <div class="hero-image">
                <img src="images/hero_books_image.png" alt="million dollar habit">
            </div>
        </div>
    </section>

    <section class="monthread container section-padding">
        <div class="monthread-card ">
            <div class="monthread-content">
                <small>⭐ This Month's Read</small>
                <h3>Million Dollar Habits</h3>
                <p>Proven Power Practices to Double & Triple Your Income</p>
                <p><em>by</em>&ensp;Brian Tracy</p>
                <div class="monthread-cta">
                    <a href="m-notes.php?book_id=1" class="btn-outline-primary">Read BookNotes -></a>
                </div>
            </div>
            <div class="monthread-image">
                <img src="uploads/million_dollar_habits_brian_tracy.png" alt="million dollar habit">
            </div>
        </div>
        <div class="monthread-sugesstioncard">
            <div class="monthread-content">
                <small>⏭ Next Month - <?=(new \DateTime('next month'))->format('F Y')?></small>
                <h3>25 Book Suggestions</h3>
                <p>Top Suggestion: 🥇 Becoming <em>by</em>&ensp;Michelle Obama</p>
                <p></p>
                <div class="monthread-cta">
                    <a href="m-notes.php?book_id=1" class="btn-link">View Suggestions -></a>
                </div>
            </div>
        </div>
    </section>

    <section class="indexcta section-padding">
        <h2>📩 Get Invited</h2>
        <form action="#" class="form">
            <div class="form-control">
                <label for="email">Email</label>
                <input type="email" name="email" id="email">
            </div>
            <button class="btn-primary">Join Club</button>
        </form>
    </section>

</main>

<?php include('footer.php'); ?>