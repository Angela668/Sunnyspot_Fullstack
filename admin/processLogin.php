<?php
// Start the session
session_start();

// Check if username and password are set in the POST request
if (isset($_POST['username']) && isset($_POST['password'])) {
  // Retrieve username and password from the POST request
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Validate the credentials
  if ($username == "admin" && $password == "secure") {
    // Login successful;// Set a session variable to indicate admin login
    $_SESSION['admin'] = true;
    // Redirect to the admin menu page
    header("Location: ../adminMenu.php");
    // Terminate the script to ensure the header is sent correctly
    exit;
  } else {
    // Invalid credentials
    echo "<script>alert('Invalid username or password')</script>";
    // Redirect back to the login page
    echo "<script>window.location = 'login.php';</script>";
    exit;
  }
}
