<?php require_once("config.php");?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Million Notes Book Club</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Oswald:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="<?=BASE_URL?>public/css/main.css">
</head>

<body>    
    <header class="header">
        <div class="topbar"><?php if(isset($_SESSION['flash_message'])) echo $_SESSION['flash_message'];?>
        </div>

        <nav class="navbar">
            <div class="navbar-container container">
                <div class="logo">
                    <a href="index.php">
                        Million<span>Notes</span>
                    </a>
                </div>
                <ul class="navbar-list">
                <?php if(isset($_SESSION['loggedin'])) { ?>
                    <li><a href="books.php" class="nav-link">📚 Books</a></li>
                    <!-- <li><a href="reviews.php" class="nav-link">⭐ Reviews</a></li> -->
                    <li><a href="notes.php" class="nav-link">📝 Notes</a></li>
                    <!-- <li><a href="book-notes.php" class="nav-link">👤 Account</a></li> -->
                    <li>@<?=$_SESSION['user_name']?></li>
                    <li><a href="<?=BASE_URL?>logout.php" class="btn-link">Log out</a></li>
                <?php } else { ?>
                    <li><a href="index.php" class="nav-link">Home</a></li>
                    <li><a href="about.php" class="nav-link">About</a></li>
                    <!-- <li><a href="dashboard.php" class="nav-link">📚 Books</a></li> -->
                    <li><a href="login-form.php" class="btn-link">Log In</a></li>
                    <li><a href="signup-form.php" class="btn-outline-primary">Join Club</a></li>
                <?php } ?>
                </ul>
            </div>
        </nav>
    </header>

