<?php
session_start();

if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']=true) {
    header('Location: dashboard');
}

require('connect.php');

// This month's read.
$this_month_query = "SELECT bus.suggestion_id, bus.book_id, bus.sugg_date
                , b.book_title, b.book_subtitle, b.book_author, b.book_image
                FROM books_users_suggestions bus
                JOIN books b ON bus.book_id = b.book_id 
                WHERE bus.is_selected = 1 AND
                    MONTH(bus.sugg_date) = 11 AND 
                    YEAR(bus.sugg_date) = 2023";

$this_month_stmt = $db->prepare($this_month_query);
$this_month_stmt->execute(); 
$this_month = $this_month_stmt->fetch();

// Next month's suggestion
$next_month_query = "SELECT * 
                    FROM books_users_suggestions
                    WHERE sugg_date > CURDATE()";

$next_month_stmt = $db->prepare($next_month_query);
$next_month_stmt->execute();
$next_month = $next_month_stmt->fetch();

?>

<?php include('header.php'); ?>

<main class="main" id="home-page">
    <section class="hero">
        <div class="container hero-container">
            <div class="hero-content">
                <small>📚 Book Club</small>
                <h1>Get & Make Summaries <br>of Your Favourite Books.</h1>
                <p>🖊 Read. Review. Make notes. Monthly.</p>

                <div class="hero-cta">
                    <a href="signup-form.php" class="btn-primary btn-lg">Join Club</a>
                    <span class="fs-small text-body-secondary">You're invited</span>
                </div>
            </div>
            <div class="hero-image">
                <img src="images/hero_books_image.png" alt="million dollar habit">
            </div>
        </div>
    </section>

    <section class="monthread container py-2 my-4">
        <div class="monthread-card ">
            <div class="monthread-content">
                <small>⭐ This Month's Read</small>
                <h3><?=$this_month['book_title']?></h3>
                <p><?=$this_month['book_subtitle']?></p>
                <p><em>by</em>&ensp;<?=$this_month['book_author']?></p>
                <div class="monthread-cta">
                    <a href="dashboard/notes?book_id=<?=$this_month['book_id']?>" class="btn-outline-primary">Read BookNotes -></a>
                </div>
            </div>
            <div class="monthread-image">
                <img src="uploads/<?=$this_month['book_image']?>" alt="<?=$this_month['book_title']?>">
            </div>
        </div>
        <div class="monthread-sugesstioncard">
            <div class="monthread-content">
                <small>⏭ Next Month - <?=(new \DateTime('next month'))->format('F Y')?></small>
                <h3><?=$next_month_stmt->rowCount();?> Book Suggestions</h3>
                <p>Top Suggestion: 🥇 Becoming <em>by</em>&ensp;Michelle Obama</p>
                <p></p>
                <div class="monthread-cta">
                    <a href="<?=BASE_URL . 'dashboard/books.php'?>" class="btn-link">View Books -></a>
                </div>
            </div>
        </div>
    </section>

    <section class="indexcta py-4">
        <h2>📩 Get Invited</h2>
        <form action="signup-form.php" class="form" style="max-width: 380px;">
            <div class="form-control">
                <label for="email">Email</label>
                <input type="email" name="email" id="email">
            </div>
            <button class="btn-primary">Join Club</button>
        </form>
    </section>

</main>

<?php include('footer.php'); ?>