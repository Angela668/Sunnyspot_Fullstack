<!DOCTYPE html>
<!-- Author:Angela Fang -->
<!-- Date:10/11/2023 -->
<!-- Purpose:developing a database system for Sunny Spot Holidays -->
<html lang="en">

<head>
  <title>Admin Login</title>
  <link href="https://fonts.googleapis.com/css?family=Quando&display=swap" rel="stylesheet">
  <link href="../style.css" rel="stylesheet" type="text/css">
</head>

<body>
  <!-- login form for admin -->
  <!-- Form to submit admin login credentials to processLogin.php -->
  <form action="processLogin.php" method="POST">
    <h1>Admin Login</h1>
    <!-- input username -->
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>
    <!-- input password -->
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>
    <!-- Submit button to submit the admin login form -->
    <input type="submit" value="Login">
  </form>
</body>

</html>