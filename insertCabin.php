<?php
// Author:Angela Fang
// Date:10/11/2023
// Purpose:developing a database system for Sunny Spot Holidays

session_start();

// Initialize an empty array to store error messages
$errors = array();

// check if the form is submitted by POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate cabinType and cabinDescription
  $cabinType = trim($_POST["cabinType"]);
  $cabinDescription = trim($_POST["cabinDescription"]);

  if (empty($cabinType) || strlen($cabinType) < 4) {
    $errors[] = "Cabin type must be at least 4 characters.";
  }

  if (empty($cabinDescription) || strlen($cabinDescription) < 10) {
    $errors[] = "Cabin description must be at least 10 characters.";
  }

  // Validate pricePerNight and pricePerWeek
  $pricePerNight = $_POST["pricePerNight"];
  $pricePerWeek = $_POST["pricePerWeek"];

  if (!is_numeric($pricePerNight) || $pricePerNight < 0) {
    $errors[] = "Invalid price per night.";
  }

  if (!is_numeric($pricePerWeek) || $pricePerWeek < 0) {
    $errors[] = "Invalid price per week.";
  }

  if ($pricePerWeek > 5 * $pricePerNight) {
    $errors[] = "Price per week must be less than or equal to 5 times the price per night.";
  }

  // Connect to the database
  $conn = mysqli_connect("localhost", "root", "", "sunnyspot");

  // Check connection
  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }

  // Process uploading photo 
  $photoName = null; // Default to NULL

  if (!empty($_FILES["photo"]["name"])) {
    $photoName = $_FILES["photo"]["name"];
    $photoTempName = $_FILES["photo"]["tmp_name"];
    $filePath = "images/" . $photoName;
    $imageFileType = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
    $allowedExtensions = array("jpg", "jpeg", "png", "gif");

    if (!in_array($imageFileType, $allowedExtensions)) {
      $errors[] = "Invalid file format. Only JPG, JPEG, PNG, and GIF files are allowed.";
    } elseif (move_uploaded_file($photoTempName, $filePath)) {
      $photoName = mysqli_real_escape_string($conn, $photoName);
    } else {
      $errors[] = "Error uploading image.";
    }
  } else {
    // No photo uploaded, set a default image file name
    $photoName = "testCabin.jpg";
  }

  if (empty($errors)) {
    // No errors, proceed with the database insert
    try {
      if ($photoName !== null) {
        // if a photo is uploaded, insert the record with photo name and photo path and include it in the database insert query

        $sql = "INSERT INTO Cabin (cabinType, cabinDescription, pricePerNight, pricePerWeek, photo) VALUES ('$cabinType', '$cabinDescription', $pricePerNight, $pricePerWeek, '$photoName')";
      } else {
        $sql = "INSERT INTO Cabin (cabinType, cabinDescription, pricePerNight, pricePerWeek) VALUES ('$cabinType', '$cabinDescription', $pricePerNight, $pricePerWeek)";
      }

      if (mysqli_query($conn, $sql)) {
        echo "<div style='text-align: center;'>Cabin inserted successfully</div>";
      } else {
        throw new Exception("Error inserting cabin.");
      }
    } catch (Exception $e) {
      $errors[] = "Error: " . $e->getMessage();
    }
  }

  // Close the database connection
  mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Insert Cabin</title>
  <link href="https://fonts.googleapis.com/css?family=Quando&display=swap" rel="stylesheet">
  <link href="style.css" rel="stylesheet" type="text/css">
</head>

<body>
  <div class="insert-cabin">
    <h1>Insert Cabin</h1>

    <!-- Display errors if there are any -->
    <?php
    if (!empty($errors)) {
      echo "<div style='text-align: center; color: red;'>";
      foreach ($errors as $error) {
        echo $error . "<br>";
      }
      echo "</div>";
    }
    ?>

    <form action="insertCabin.php" method="post" enctype="multipart/form-data">
      <label for="cabinType">Cabin Type:</label>
      <input type="text" id="cabinType" name="cabinType" required>

      <label for="cabinDescription">Cabin Description:</label>
      <textarea id="cabinDescription" name="cabinDescription" rows="5" required></textarea>

      <label for="pricePerNight">Price per Night: $</label>
      <input type="number" id="pricePerNight" name="pricePerNight" min="0" step="0.1" required>

      <label for="pricePerWeek">Price per Week: $</label>
      <input type="number" id="pricePerWeek" name="pricePerWeek" min="0" step="0.1" required>

      <label for="photo">Cabin Photo:</label>
      <input type="file" id="photo" name="photo" accept="image/*">

      <button type="submit" name="submit">Submit</button>
      <button><a href="adminMenu.php" target="_blank">Back</a></button>
    </form>
  </div>
</body>

</html>