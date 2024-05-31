<?php
include "../includes/connection.php"; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Function to sanitize inputs
    function sanitizeInput($data)
    {
        // Sanitize input to prevent SQL injection
        // You can implement more sophisticated sanitization as needed
        return htmlspecialchars(stripslashes(trim($data)));
    }

    function compressImage($source_path)
    {
        // Load image
        $image = imagecreatefromjpeg($source_path); // Assuming the images are JPEG

        // Compress image
        ob_start();
        imagejpeg($image, NULL, 10); // 10 is the compression quality (0-100)
        $compressed_image = ob_get_clean();

        // Free up memory
        imagedestroy($image);

        return $compressed_image;
    }

    // Reuse the existing database connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL queries to create tables if not exists
    $sql_add_property = "CREATE TABLE IF NOT EXISTS add_property (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        place VARCHAR(255) NOT NULL,
        phone VARCHAR(20) NOT NULL,
        email VARCHAR(255) NOT NULL,
        property_type varchar(30)
    )";

    $sql_property_images = "CREATE TABLE IF NOT EXISTS property_images (
        id INT AUTO_INCREMENT PRIMARY KEY,
        property_id INT,
        image_name VARCHAR(255) NOT NULL,
        image_data LONGBLOB,
        image_type VARCHAR(255),
        FOREIGN KEY (property_id) REFERENCES add_property(id) ON DELETE CASCADE
    )";

    // Execute queries to create tables
    if ($conn->query($sql_add_property) === TRUE && $conn->query($sql_property_images) === TRUE) {
        // Tables created successfully, no need to echo anything
    } else {
        die("Error creating tables: " . $conn->error);
    }

    // Check if email field is set
    if (!isset($_POST['email'])) {
        die("Email field is required.");
    }

    // Sanitize inputs
    $name = sanitizeInput($_POST['name']);
    $place = sanitizeInput($_POST['place']);
    $phone = sanitizeInput($_POST['phone']);
    $email = sanitizeInput($_POST['email']);
    $property_type = isset($_POST['sell']) ? 'sell' : 'rent';

    // Prepare SQL statement
    $sql = "INSERT INTO add_property (name, place, phone, email, property_type) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die('Error in preparing statement: ' . $conn->error);
    }

    // Bind parameters and execute statement
    if (!$stmt->bind_param("sssss", $name, $place, $phone, $email, $property_type)) {
        die('Error in binding parameters: ' . $stmt->error);
    }

    if (!$stmt->execute()) {
        die('Error in executing statement: ' . $stmt->error);
    }

    $property_id = $stmt->insert_id;
    $stmt->close();

    // Loop through uploaded images
    foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
        // Check if file was uploaded
        if ($_FILES['images']['error'][$key] !== UPLOAD_ERR_OK) {
            die("File upload error: Please select a file to upload.");
        }

        // Sanitize file name
        $image_name = sanitizeInput($_FILES['images']['name'][$key]);

        // Compress image
        $compressed_image = compressImage($tmp_name);

        // Prepare and execute SQL statement for image insertion
        // Prepare and execute SQL statement for image insertion
        $sql = "INSERT INTO property_images (property_id, image_name, image_data, image_type) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die('Error in preparing statement: ' . $conn->error);
        }

        // Use send_long_data to insert blob data
        $null = NULL;
        $stmt->bind_param("isbs", $property_id, $image_name, $null, $image_type);
        $stmt->send_long_data(2, $compressed_image);

        if (!$stmt->execute()) {
            die('Error in executing statement: ' . $stmt->error);
        }

        $stmt->close();
    }

    // Close database connection
    $conn->close();

    // Redirect to index.php after successful submission
    echo "<script>alert('Property Added Successfully'); window.location.href = '../index.php';</script>";
    exit;
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" />
    <link rel="stylesheet" href="../proptokart_css/add_property.css">
    <link rel="stylesheet" href="../proptokart_css/header.css">
    <title>Add property for FREE</title>
    <style>
        header .navbar-brand img {
            padding-top: 0 !important;
        }
    </style>
</head>

<body>
    <header>
        <?php include '../includes/config.php'; ?>
        <?php include INCLUDES_PATH . 'header.php'; ?>
    </header>
    <div class="backgroun-gradiant" style="top:50%; right:0"></div>
    <div class="backgroun-gradiant" style="top:0%; left:-8rem"></div>
    <h1 style="padding-bottom: 50px;margin-top: 5%">Add property for FREE</h1>
    <div class="form-container btn-search">
        <div class="login-container" id="login-container">
            <form class="login" method="post" enctype="multipart/form-data">

                <div class="login__cta">
                    <div class="login__form">
                        <h2>To SELL</h2>
                        <input type="hidden" name="sell" value="sell">

                        <div class="input-container">
                            <label class="login__label login__label--sm" for="name">Owner Name</label>
                            <input class="login__input-text" id="OwnerName" type="text" name="name">
                        </div>
                        <div class="input-container">
                            <label class="login__label login__label--sm" for="phone">Phone No.</label>
                            <input class="login__input-text" id="phoneNo" type="tel" name="phone">
                        </div>
                        <div class="input-container">
                            <label class="login__label login__label--sm" for="email_sell">Email</label>
                            <input class="login__input-text" id="email_sell" type="email" name="email">
                        </div>
                        <div class="input-container">
                            <label class="login__label login__label--sm" for="place">Address</label>
                            <input class="login__input-text" id="email" type="address" name="place">
                        </div>
                        <div class="input-container">
                            <input class="login__input-text" id="images_sell" type="file" name="images[]" multiple accept="image/*" required>
                        </div>
                        <button class="login__button" type="submit" data-login name="sell">Submit</button>
                        <div class="signup-text">Don't want to sell your property
                            <a id="signup-form-toggler">Rent it</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="placeholder-banner" id="banner">
            <img src="https://source.unsplash.com/358x488/?home" alt="Add Your Property for Free" class="banner" />
        </div>
        <div class="signup-container" id="signup-container">
            <form class="login" method="post" enctype="multipart/form-data">
                <div class="login__cta">
                    <div class="login__form">
                        <h2>To RENT</h2>

                        <input type="hidden" name="rent" value="rent">

                        <div class="input-container">
                            <label class="login__label login__label--sm" for="name">Owner Name</label>
                            <input class="login__input-text" id="OwnerName" type="text" name="name">
                        </div>
                        <div class="input-container">
                            <label class="login__label login__label--sm" for="phone">Phone No.</label>
                            <input class="login__input-text" id="phoneNo" type="tel" name="phone">
                        </div>
                        <div class="input-container">
                            <label class="login__label login__label--sm" for="email_rent">Email</label>
                            <input class="login__input-text" id="email_rent" type="email" name="email">
                        </div>
                        <div class="input-container">
                            <label class="login__label login__label--sm" for="place">Address</label>
                            <input class="login__input-text" id="email" type="address" name="place">
                        </div>
                        <div class="input-container">
                            <input class="login__input-text" id="images_rent" type="file" name="images[]" multiple accept="image/*" required>
                        </div>
                        <button class="login__button" type="submit" data-login name="rent">Submit</button>
                        <div class="signup-text">Want to sell your property
                            <a id="login-form-toggler">Sell it</a>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
    <script>
        const banner = document.getElementById("banner");
        const loginContainer = document.getElementById("login-container");
        const signupContainer = document.getElementById("signup-container");
        const loginToggle = document.getElementById("login-form-toggler");
        const signupToggle = document.getElementById("signup-form-toggler");

        signupToggle.addEventListener("click", () => {
            banner.style.transform = "translateX(-100%)";
            loginContainer.style.transform = "scale(0)";
            signupContainer.style.transform = "scale(1)";
        });
        loginToggle.addEventListener("click", () => {
            banner.style.transform = "translateX(0%)";
            signupContainer.style.transform = "scale(0)";
            loginContainer.style.transform = "scale(1)";
        });
    </script>
</body>

</html>