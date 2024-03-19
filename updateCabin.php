<?php
// Author:Angela Fang
// Date:10/11/2023
// Purpose:developing a database system for Sunny Spot Holidays

// start a session
session_start();

// check if a success message is set in the session
if (isset($_SESSION['update_success'])) {
  // display the success message
  echo '<div class="success-container">';
  echo '<h3>Success:</h3>';
  echo '<p>' . $_SESSION['update_success'] . '</p>';
  echo '</div>';

  //unset the success message from the session so it wont display again
  unset($_SESSION['update_success']);
}

// Initialize an empty array to store error messages
$errors = array();

// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "sunnyspot");

// Check if the connection failed
if (!$conn) {
  // display an error message and exit the script if connection fails.
  die("Connection failed: " . mysqli_connect_error());
}

// Check if the form is submitted
if (isset($_POST['update'])) {
  // Check if form inputs exist
  if (isset($_POST['cabinID'], $_POST['cabinType'], $_POST['cabinDescription'], $_POST['pricePerNight'], $_POST['pricePerWeek'])) {
    $cabinID = $_POST['cabinID'];
    $cabinType = $_POST['cabinType'];
    $cabinDescription = $_POST['cabinDescription'];
    $pricePerNight = $_POST['pricePerNight'];
    $pricePerWeek = $_POST['pricePerWeek'];

    // Validate cabinType and cabinDescription
    if (empty($cabinType) || strlen($cabinType) < 4) {
      $errors[] = "Cabin type must be at least 4 characters.";
    }

    if (empty($cabinDescription) || strlen($cabinDescription) < 10) {
      $errors[] = "Cabin description must be at least 10 characters.";
    }

    // Validate pricePerNight and pricePerWeek
    if (!is_numeric($pricePerNight) || $pricePerNight < 0) {
      $errors[] = "Invalid price per night.";
    }

    if (!is_numeric($pricePerWeek) || $pricePerWeek < 0) {
      $errors[] = "Invalid price per week.";
    }

    if ($pricePerWeek > 5 * $pricePerNight) {
      $errors[] = "Price per week must be less than or equal to 5 times the price per night.";
    }

    // Process uploading photo
    $photoName = null; // Default to NULL

    if (!empty($_FILES["photo"]["name"])) {
      $photoName = $_FILES["photo"]["name"];
      $photoTempName = $_FILES["photo"]["tmp_name"];
      $filePath = "images/" . $photoName;
      $imageFileType = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
      $allowedExtensions = array("jpg", "jpeg", "png", "gif");

      // Validate file format
      if (!in_array($imageFileType, $allowedExtensions)) {
        $errors[] = "Invalid file format. Only JPG, JPEG, PNG, and GIF files are allowed.";
      } elseif (move_uploaded_file($photoTempName, $filePath)) {
        $photoName = mysqli_real_escape_string($conn, $photoName);
      } else {
        $errors[] = "Error uploading image.";
      }
    } else {
      // No new photo uploaded, keep the existing photo
      $sql = "SELECT photo FROM Cabin WHERE cabinID = $cabinID";
      $result = mysqli_query($conn, $sql);
      $row = mysqli_fetch_assoc($result);
      $photoName = $row['photo'];
    }
    if (empty($errors)) {
      // No errors, proceed with the database update
      $sql = "UPDATE Cabin SET cabinType = '$cabinType', cabinDescription = '$cabinDescription', pricePerNight = $pricePerNight, pricePerWeek = $pricePerWeek, photo = '$photoName' WHERE cabinID = $cabinID";

      try {
        if (mysqli_query($conn, $sql)) {
          // set success message in session and redirect to the update page
          $_SESSION['update_success'] = "Cabin updated successfully";

          echo "<script>window.location = 'updateCabin.php';</script>";
        } else {
          $errors[] = "Error updating cabin: " . mysqli_error($conn);
        }
      } catch (Exception $e) {
        $errors[] = "Error: " . $e->getMessage();
      }
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Update Cabins</title>
  <link href="https://fonts.googleapis.com/css?family=Quando&display=swap" rel="stylesheet">
  <link href="style.css" rel="stylesheet" type="text/css">
  <script>
    function previewImage(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
          document.getElementById('image-preview').src = e.target.result;
        };

        reader.readAsDataURL(input.files[0]);
      }
    }
  </script>
</head>

<body>


  <?php


  // Check if there are any errors to display
  if (!empty($errors)) {
    echo '<div class="error-container">';
    echo '<h3>Error(s):</h3>';
    echo '<ul>';
    foreach ($errors as $error) {
      echo "<li>$error</li>";
    }
    echo '</ul>';
    echo '</div>';
  }

  // Connect to the database
  $conn = mysqli_connect("localhost", "root", "", "sunnyspot");

  // Check if the connection failed
  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }

  // Check if a cabin was selected for update
  if (isset($_GET['cabinID'])) {
    $cabinID = $_GET['cabinID'];

    // Query the database to retrieve cabin details
    $sql = "SELECT * FROM Cabin WHERE cabinID = $cabinID";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_assoc($result);

      // Display a form to update the cabin
      echo "<form action='updateCabin.php?cabinID=$cabinID' method='post' enctype='multipart/form-data'>";
      echo "<h1>Update Cabins</h1>";
      echo "<input type='hidden' name='cabinID' value='$cabinID'>";
      echo "<label for='cabinType'>Cabin Type:</label>";
      echo "<input type='text' id='cabinType' name='cabinType' value='" . $row['cabinType'] . "' required>";
      echo "<label for='cabinDescription'>Cabin Description:</label>";
      echo "<textarea id='cabinDescription' name='cabinDescription' rows='5' required>" . $row['cabinDescription'] . "</textarea>";
      echo "<label for='pricePerNight'>Price per Night:</label>";
      echo "<input type='number' id='pricePerNight' name='pricePerNight' min='0' step='0.1' value='" . $row['pricePerNight'] . "' required>";
      echo "<label for='pricePerWeek'>Price per Week:</label>";
      echo "<input type='number' id='pricePerWeek' name='pricePerWeek' min='0' step='0.1' value='" . $row['pricePerWeek'] . "' required>";
      echo "<label for='photo'>Cabin Photo:</label>";
      echo "<input type='file' id='photo' name='photo' accept='image/*' onchange='previewImage(this)'>";
      echo "<p>Current Photo: <img src='images/" . $row['photo'] . "' alt='" . $row['cabinType'] . "' width='150' height='100'></p>";
      echo "<p>Selected Photo Preview: <img id='image-preview' src='images/testCabin.jpg' alt='preview Photo' width='150' height='100'></p>";
      echo "<button type='submit' name='update'>Update</button>";
      echo "</form>";
    } else {
      echo "Cabin not found";
    }
  }

  // Get all the cabins to display in a list
  $sql = "SELECT * FROM Cabin";
  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) > 0) {
    echo "<h2>List of Cabins:</h2>";
    echo "<button><a href='adminMenu.php' target='_blank'>Back to Admin Menu</a></button>";
    echo "<br>";
    echo '<button><a href="index.php" target="_blank">Back to Accommodation Page</a></button>';
    echo "<table>";
    echo "<tr><th>Cabin ID</th><th>Cabin Type</th><th>Description</th><th>Price per Night</th><th>Price per Week</th><th>Photo</th><th>Action</th></tr>";

    while ($row = mysqli_fetch_assoc($result)) {
      $cabinID = $row["cabinID"];
      echo "<tr>";
      echo "<td>" . $cabinID . "</td>";
      echo "<td>" . $row["cabinType"] . "</td>";
      echo "<td>" . $row["cabinDescription"] . "</td>";
      echo "<td>$" . $row["pricePerNight"] . "</td>";
      echo "<td>$" . $row["pricePerWeek"] . "</td>";
      echo "<td><img src='images/" . $row["photo"] . "' alt='" . $row["cabinType"] . "' width='150' height='100'></td>";
      echo "<td>";
      echo "<form action='updateCabin.php?cabinID=$cabinID' method='post'>";
      echo "<input class='button' type='submit' name='update' value='Update'>";
      echo "</form>";
      echo "</td>";
      echo "</tr>";
    }

    echo "</table>";
  } else {
    echo "No cabins available";
  }

  // Close the database connection
  mysqli_close($conn);
  ?>

</body>

</html>