<!DOCTYPE html>
<!-- Author:Angela Fang -->
<!-- Date:10/11/2023 -->
<!-- Purpose:developing a database system for Sunny Spot Holidays -->
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sunnyspot Accommodation</title>
    <link href="https://fonts.googleapis.com/css?family=Quando&display=swap" rel="stylesheet" />
    <link href="style.css" rel="stylesheet" type="text/css" />
</head>

<body>
    <!-- header section -->
    <header>
        <div class="left-section">
            <!-- logo section -->
            <div class="logo">
                <img src="images/accommodation.png" alt="Accommodation" />

            </div>
            <!-- heading section -->
            <h1>Sunnyspot Accommodation</h1>
        </div>
        <nav>
            <!-- Navigation menu  -->
            <ul class="nav">
                <li><a href="index.php">Home</a></li>
                <li><a href="index.php">Accommodation</a></li>
                <li><a href="aboutus.html">About Us</a></li>

            </ul>
        </nav>
    </header>
    <!-- main content section  -->

    <h2>Accommodation</h2>
    <section>
        <?php
        // connect to database
        $conn = mysqli_connect("localhost", "root", "", "sunnyspot");

        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // query database for cabins 
        $sql = "SELECT * FROM Cabin";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<article>';
                echo '<h3>' . $row['cabinType'] . '</h3>';
                echo '<img src="images/' . $row['photo'] . '" alt="' . $row['cabinType'] . '" />';
                echo '<p><span>Details: </span>' . $row['cabinDescription'] . '</p>';
                echo '<p><span>Price per night: </span>$' . $row['pricePerNight'] . '</p>';
                echo '<p><span>Price per week: </span>$' . $row['pricePerWeek'] . '</p>';
                echo '</article>';
            }
        } else {
            echo "No cabins available.";
        }

        // close database connection
        mysqli_close($conn);
        ?>
    </section>
    <!-- footer section  -->

    <footer>
        <a href="adminMenu.php" target="_blank">Admin</a>
    </footer>
</body>

</html>