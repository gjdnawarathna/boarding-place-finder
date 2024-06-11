<?php
session_start(); // Start the session if it's not already started

// Unset all of the session variables
$_SESSION = array();

// Destroy the session.
session_destroy();

// Redirect to the login page or another appropriate page
header('Location: login.php'); // Replace 'login.php' with the path to your login page
exit();
?>