<?php
// Author:Angela Fang
// Date:10/11/2023
// Purpose:developing a database system for Sunny Spot Holidays

// start a session to manage the user login status
session_start();

// Check if the user is not logged in as an admin
if (!isset($_SESSION['admin'])) {

  // redirect to the admin login page if not logged in
  header("Location: ./admin/login.php");
  exit(); // Terminate the script to ensure the header is sent correctly
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Administrative Menu</title>
  <link href="https://fonts.googleapis.com/css?family=Quando&display=swap" rel="stylesheet">
  <link href="style.css" rel="stylesheet" type="text/css">
</head>

<body class="admin-page">

  <h1>Administrative Menu</h1>

  <ul>
    <!-- Navigation links for admin tasks -->
    <li><a href="insertCabin.php">Insert a New Cabin</a></li>
    <li><a href="updateCabin.php">Update a Cabin</a></li>
    <li><a href="deleteCabin.php">Delete a Cabin</a></li>
    <li><a href="allCabins.php">All Cabins</a></li>

    <!-- Logout button to end the admin session -->
    <button><a href="admin/logout.php">Logout</a></button>

    <!-- Button to go back to the main Accommodation Page -->
    <button><a href="index.php">Back to Accommodation Page</a></button>

  </ul>

</body>

</html>