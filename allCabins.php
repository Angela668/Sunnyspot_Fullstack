<!DOCTYPE html>
<!-- Author:Angela Fang -->
<!-- Date:10/11/2023 -->
<!-- Purpose:developing a database system for Sunny Spot Holidays -->
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>All Cabins</title>
  <link href="https://fonts.googleapis.com/css?family=Quando&display=swap" rel="stylesheet">
  <link href="style.css" rel="stylesheet" type="text/css">
</head>

<body>
  <h1>All Cabins</h1>

  <?php
  // Connect to the database
  $conn = mysqli_connect("localhost", "root", "", "sunnyspot");

  // Check connection
  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }

  // SQL query to retrieve all cabin information
  $sql = "SELECT * FROM Cabin";
  $result = mysqli_query($conn, $sql);

  // Display cabin information in a table
  if (mysqli_num_rows($result) > 0) {
    echo "<table>";
    echo "<tr><th>Cabin Type</th><th>Description</th><th>Price per Night</th><th>Price per Week</th><th>Photo</th></tr>";

    while ($row = mysqli_fetch_assoc($result)) {
      echo "<tr>";
      echo "<td>" . $row["cabinType"] . "</td>";
      echo "<td>" . $row["cabinDescription"] . "</td>";
      echo "<td>" . $row["pricePerNight"] . "</td>";
      echo "<td>" . $row["pricePerWeek"] . "</td>";
      echo "<td><img src='images/" . $row["photo"] . "' alt='" . $row["cabinType"] . "' width='150' height='100'></td>";
      echo "</tr>";
    }

    echo "</table>";
  } else {
    echo "No cabins found";
  }

  // Close the database connection
  mysqli_close($conn);
  ?>

</body>

</html>