<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "railway";
$port = 3307;
$conn = new mysqli($servername, $username, $password, $dbname,$port);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM emergency";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emergency Page</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Navigation Bar -->
    <header>
        <h1>Indian Railways Administration System</h1>
        <nav>
            <a href="index.html">Home</a>
            <a href="emergency.php">Emergency</a>
            <a href="feedback.php">Feedback</a>
        </nav>
    </header>
    <!-- Main Content -->
    <div class="container">
        <h1>Emergency</h1>
        <table class="data-table">
            <thead>
                <tr>
                    
                    <th>Tweets</th>
                    
                </tr>
                <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr><td>" . $row["Tweets"]. "</td></tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No data found</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
            </thead>
        </table>
    </div>
    <footer>
        <p>&copy; 2024 Indian Railways Administration System</p>
    </footer>
</body>
</html>
