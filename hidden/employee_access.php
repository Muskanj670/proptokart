<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Establish database connection
    include "../includes/connection.php";

    // Retrieve username and password from the form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Sanitize inputs to prevent SQL injection
    $username = mysqli_real_escape_string($conn, $username);
    // No need to sanitize password as it will be hashed

    // Prepare SQL statement to retrieve hashed password based on username
    $sql = "SELECT password FROM employee WHERE username=?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        // Handle error
        $_SESSION['error'] = "Error preparing SQL statement: " . $conn->error;
        header("Location: employee_login.php"); // Redirect to login page
        exit;
    }

    // Bind parameters
    $stmt->bind_param("s", $username);

    // Execute the statement
    if (!$stmt->execute()) {
        // Handle error
        $_SESSION['error'] = "Error executing SQL query: " . $stmt->error;
        header("Location: employee_login.php"); // Redirect to login page
        exit;
    }

    // Bind result variables
    $stmt->bind_result($hashed_password);

    // Fetch result
    $stmt->fetch();

    // Close statement
    $stmt->close();

    // Verify password
    if (password_verify($password, $hashed_password)) {
        // Set username in session
        $_SESSION['username'] = $username;
        // Redirect to modify data page
    } else {
        // Incorrect username or password
        $_SESSION['error'] = "Incorrect username or password";
        header("Location: employee_login.php"); // Redirect to login page
        exit;
    }

    // Close connection (moved to after all operations)
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" href="../images/proptokart-black.png" type="image/x-icon">
    <meta charset="UTF-8" />
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-RSVDBB94BF"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-RSVDBB94BF');
    </script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Access or Modify Data</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(to left, #fc0, #F86F03);
        }

        main {
            display: flex;
            justify-content: center;
            align-items: center;
            height: calc(100vh - 80px);
            /* Adjust if you have a different header height */
        }

        .container {
            text-align: center;
        }

        .heading {
            font-size: 24px;
            margin-bottom: 20px;
        }

        /* .buttons {
            display: flex;
            justify-content: center;
        } */

        button{
            display: inline-block;
            text-decoration: none;
            color: #fff;
            background-color: #333;
            padding: 10px 20px;
            border-radius: 5px;
            margin: 0 10px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #555;
        }
    </style>
</head>

<body>
    <header>
        <?php include '../includes/config.php'; ?>
        <?php include INCLUDES_PATH . 'header.php'; ?>
    </header>
    <main>
        <div class="container">
            <div class="heading">Access or Modify Data to</div>
            <div class="buttons">
                <form action="emp_fetch.php" method="get">
                    <button type="submit" name="property" value="rent">Rent</button>
                    <button type="submit" name="property" value="buy">Sell</button>
                </form>
            </div>
        </div>
    </main>
</body>

</html>