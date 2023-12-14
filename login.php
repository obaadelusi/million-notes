 <?php

session_start();

require 'connect.php';

if (!isset($_POST['username'], $_POST['password'])) {
	// Could not get the data that should have been sent.
	exit('Please fill both the username and password fields!');
}

// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
if ($stmt = $db->prepare('SELECT user_id, user_pass FROM users WHERE user_name = :username')) {
  $inputUsername = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

  $stmt->bindValue(':username', $inputUsername);
  $stmt->execute();
  // Store the result so we can check if the user exists in the database.
  $user = $stmt->fetch();

  if ($stmt->rowCount() > 0) {
    // Account exists, now we verify the password.
    $inputPassword = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    // Note: remember to use password_hash in your registration file to store the hashed passwords.
    if (password_verify($inputPassword, $user['user_pass'])) {
      // Verification success! User has logged-in!
      // Create sessions, so we know the user is logged in, they basically act like cookies but remember the data on the server.
      session_regenerate_id();
      $_SESSION['loggedin'] = TRUE;
      $_SESSION['user_name'] = $inputUsername;
      $_SESSION['user_id'] = $user['user_id'];
      $_SESSION['user_role'] = $user['role_id'];
      $_SESSION['flash_message'] = '🍀 Welcome @' . $inputUsername;

      header('Location: dashboard/index.php');
    } else {
      // Incorrect password
      echo 'Incorrect username and/or password!';
    }
  } else {
    // Incorrect username
    echo 'Incorrect username and/or password! <a href="login-form.php">Login again</a>';
  }
}

?>