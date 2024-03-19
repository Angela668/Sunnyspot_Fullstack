<?php
// Author:Angela Fang
// Date:10/11/2023
// Purpose:developing a database system for Sunny Spot Holidays

// Start the session
session_start();

// Unset all session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect to the login page in the "admin" directory
header("Location: ../admin/login.php"); // Adjust the path to access login.php in the parent folder's "admin" directory
