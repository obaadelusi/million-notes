<?php
session_start();
$_SESSION = array();
session_destroy();

// Redirect to the index page:
header('Location: login-form.php');
exit;
?>