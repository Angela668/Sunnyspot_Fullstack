<!DOCTYPE html>
<!-- Author: Angela Fang -->
<!-- Date: 10/11/2023 -->
<!-- Purpose: Developing a database system for Sunny Spot Holidays -->
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Delete Cabin</title>
  <link href="https://fonts.googleapis.com/css?family=Quando&display=swap" rel="stylesheet">
  <link href="style.css" rel="stylesheet" type="text/css">

  <script>
    // JavaScript function to ask for confirmation before submitting the form
    function confirmDelete(cabinID) {
      var confirmDelete = confirm("Are you sure you want to delete this cabin?");
      if (confirmDelete) {
        // If user confirms, set the cabinID value and submit the form
        document.getElementById('deleteForm').cabinID.value = cabinID;
        document.getElementById('deleteForm').submit();
      } else {
        // If user cancels, return false to prevent form submission
        return false;
      }
    }
  </script>
</head>

<body>
  <?php
  // start a session
  session_start();

  // check if a success message is set in the session
  if (isset($_SESSION['delete_success'])) {
    // display the success message
    echo '<div class="delete-container">';
    echo '<h3>Success:</h3>';
    echo '<p>' . $_SESSION['delete_success'] . '</p>';
    echo '</div>';

    //unset the success message from the session so it wont display again
    unset($_SESSION['delete_success']);
  }

  // Connect to the MySQL database
  $conn = mysqli_connect("localhost", "root", "", "sunnyspot");

  // Check if the connection failed
  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }

  // If the delete button is clicked, delete the cabin and redirect the user to the list of cabins
  if (!empty($_POST['delete'])) {
    $cabinID = $_POST['cabinID']; // Retrieve the cabinID from the form

    mysqli_query($conn, "DELETE FROM Cabin WHERE cabinID=$cabinID");

    $_SESSION['delete_success'] = "Cabin deleted successfully";
    // Refresh
    header("Location:#");



    exit; // Terminate the script to ensure the header is sent correctly
  }

  // Get all the cabins to delete
  $sql = "SELECT * FROM Cabin";
  $result = mysqli_query($conn, $sql);

  // Display the cabins in a table
  if (mysqli_num_rows($result) > 0) {
    echo "<h1>Delete Cabin</h1>";
    echo "<button><a href='adminMenu.php' target='_blank'>Back to Admin Menu</a></button>";
    echo "<br>";
    echo '<button><a href="index.php" target="_blank">Back to Accommodation Page</a></button>';

    echo "<table>";
    echo "<tr><th>Cabin ID</th><th>Cabin Type</th><th>Description</th><th>Price per Night</th><th>Price per Week</th><th>Photo</th><th>Action</th></tr>";

    while ($row = mysqli_fetch_assoc($result)) {
      $cabinID = $row["cabinID"]; // Fetch cabinID from the current row
      echo "<tr>";
      echo "<td>" . $row["cabinID"] . "</td>";
      echo "<td>" . $row["cabinType"] . "</td>";
      echo "<td>" . $row["cabinDescription"] . "</td>";
      echo "<td>$" . $row["pricePerNight"] . "</td>";
      echo "<td>$" . $row["pricePerWeek"] . "</td>";
      echo "<td><img src='images/" . $row["photo"] . "' alt='" . $row["cabinType"] . "' width='150' height='100'></td>";
      echo "<td>";
      echo "<form id='deleteForm' action='' method='post'>";
      echo "<input type='hidden' name='cabinID' value='$cabinID'>"; // Add a hidden input for cabinID
      echo "<input class='delete' onclick='return confirmDelete($cabinID)' type='submit' name='delete' value='Delete'>";
      echo "</form>";
      echo "</td>";
      echo "</tr>";
    }

    echo "</table>";
  } else {
    echo "No cabins to delete";
  }

  // session_destroy();
  // Close the database connection
  mysqli_close($conn);
  ?>

</body>

</html>