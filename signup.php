<?php 
require 'connect.php';
// require 'authenticate.php';
    
// If the data was submitted, isset() function will check if the data exists.
if (!isset($_POST['username'], $_POST['password'], $_POST['confirmPassword'], $_POST['email'])) {
    exit('Please complete the registration form!');
}

// Make sure the submitted registration values are not empty.
if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['confirmPassword']) || empty($_POST['email'])) {
    exit('Please complete the registration form');
}

if ($stmt = $db->prepare('SELECT user_id, user_pass FROM users WHERE user_name = :user')) {
    $user = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $stmt->bindValue(':user', $user);
    $stmt->execute();
    // Store the result so we can check if the user exists in the database.
    $stmt->fetch();

	if ($stmt->rowCount() > 0) {
		// Username already exists
		echo '<h3>Username already exists, please choose another! <a href="signup-form.php">Sign up</a></h3>';
	} else {
		// Username doesn't exists, insert new account
        $pass = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $confirm_pass = filter_input(INPUT_POST, 'confirmPassword', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if(trim($pass) != $confirm_pass) exit("Please make sure your passwords match!");

        if ($stmt = $db->prepare('INSERT INTO users (user_name, user_email, user_pass, role_id) VALUES (:user, :email, :pass, 2)')) {
            // We do not want to expose passwords in our database, so hash the password and use password_verify when a user logs in.
            $hash_pass = password_hash($pass, PASSWORD_DEFAULT);

            $stmt->bindValue(':user', $user);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':pass', $hash_pass);
            $stmt->execute();

            // echo 'You have successfully registered! You can now login!';
            header("Location: login-form.php");
            exit;
        } else {
            // Something is wrong with the SQL statement, so you must check to make sure your accounts table exists with all three fields.
            echo 'Could not prepare statement!';
        }
	}
} else {
    echo 'Could not prepare statement';
}

$db=null;

?>