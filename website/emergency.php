<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "password";
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
    <style>
        body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Login Page Styles */
.login-container {
    width: 100%;
    max-width: 400px;
    margin: 50px auto;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 10px;
    background-color: #f9f9f9;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.login-container h1 {
    font-size: 1.8em;
    margin-bottom: 20px;
}

.login-container .header-image {
    width: 100%;
    height: auto;
    margin-bottom: 20px;
}

.login-container h2 {
    text-align: center;
}

.login-container form {
    display: flex;
    flex-direction: column;
}

.login-container label {
    margin-bottom: 5px;
}

.login-container input {
    margin-bottom: 15px;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.login-container button {
    padding: 10px;
    border: none;
    border-radius: 5px;
    background-color: #5cb85c;
    color: white;
    cursor: pointer;
    font-size: 16px;
}

.login-container button:hover {
    background-color: #4cae4c;
}

.login-container p {
    text-align: center;
}

.login-container a {
    color: white
    text-decoration: none;
}

.login-container a:hover {
    text-decoration: underline;
}

footer {
    text-align: center;
    height:20px;
    padding: 10px;
    background-color: #f1f1f1;
    position: fixed;
    width: 100%;
    bottom:0;
    margin:5px;
    
}

/* Home Page Styles */
nav {
    background-color: #333;
    color: white;
    padding: 10px;
    text-align: center;
}

nav ul {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    justify-content: center;
}

nav ul li {
    margin: 0 15px;
}

nav ul li a {
    color: white;
    
}

nav ul li a:hover {
    text-decoration: underline;
}
a, a:hover, a:active, a:visited { color: white; }
.content {
    height: 100vh;
    background: url('https://www.indiaspend.com/h-upload/2023/04/15/945591-updated-indian-railways-data-1500.webp') no-repeat center center/cover;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    color: white;
    text-align: center;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);
}

.content h1 {
    font-size: 3em;
    margin: 0;
}

.content p {
    font-size: 1.5em;
}
.container {
            max-width: 950px;
            margin: 50px 30px;
            background: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        /* Table Styles */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 40px auto;
            padding: 20px;
            border-radius: 8px;
        }

        .data-table th, .data-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .data-table th {
            background-color: #f4f4f4;
        }
        .data-table tbody td {
            max-width: 250px; /* Limiting width for better presentation */
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .data-table tr:nth-child(even) {
            background-color: #f9f9f9;
            width: 80%;
        }
        .reply-box {
    width: 270px;
    height:200px;
    max-height: 800px; /* Maximum height before scrolling */
    padding: 10px;
    border: 1px solid #0000FF;
    border-radius: 5px;
    position: fixed;
    top: 340px; /* Adjust top position as needed */
    right: 20px; /* Adjust right position as needed */
    overflow-y: auto; /* Enable vertical scrolling */
    z-index: 999; /* Ensure the box is above other elements */
    
}
.tweet-box {
    width: 270px;
    height:200px;
    max-height: 800px; /* Maximum height before scrolling */
    padding: 10px;
    
    border-radius: 5px;
    position: fixed;
    top: 150px; /* Adjust top position as needed */
    right: 20px; /* Adjust right position as needed */
    overflow-y: auto; /* Enable vertical scrolling */
    z-index: 999; /* Ensure the box is above other elements */
    
            word-wrap: break-word;
}
.tweet-input {
            width: 270px;
            height:150px; /* Adjust width as needed */
            padding: 10px;
            border: 1px solid #0000FF;
            border-radius: 5px;
            
            position: fixed;
    top: 150px; /* Adjust top position as needed */
    right: 20px; /* Adjust right position as needed */
    
    z-index: 999; /
        }

        .reply-input {
            width: 238px;
            height:130px; /* Adjust width as needed */
            padding: 10px;
            border: 1px solid #0000FF;
            border-radius: 5px;
            margin-right: 10px;
        }

        .reply-button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
            font-size: 16px;
        }

        .reply-button:hover {
            background-color: #0056b3;
        }
    </style>
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
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Get all table cells
            var cells = document.querySelectorAll(".data-table td");

            // Add click event to each cell
            cells.forEach(function(cell) {
                cell.addEventListener("click", function() {
                    var cellText = this.textContent.trim(); // Get text content of the clicked cell
                    var replyBox = document.getElementById("tweet-box");
                    replyBox.value = cellText; // Set the text content to the reply box
                });
            });
        });
    </script>
   <div class="tweet-box">
   <textarea id="tweet-box" class="tweet-input" placeholder="Tweet" rows="4"></textarea>
      
    </div>
    <div  class="reply-box" >
        <input type="text"  class="reply-input" placeholder="Reply...">
        <button class="reply-button">Send</button>
    </div>
    
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
        <p>&copy; 2024 Indian Railways Administration System<br> </p>
        
        
    </footer>
    
</body>
</html>
