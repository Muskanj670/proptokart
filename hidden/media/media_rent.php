<?php
// Database connection parameters
include "../../includes/connection.php";

// Retrieve user ID from the AJAX request
$userId = $_GET["userId"];

// Fetch images and videos associated with the user ID from the database
$sqlImages = "SELECT image_data, image_type FROM property_images_rent WHERE property_id = ?";
$stmtImages = $conn->prepare($sqlImages);
if (!$stmtImages) {
    die("SQL Error: " . $conn->error);
}
$stmtImages->bind_param("i", $userId);
$stmtImages->execute();
$stmtImages->store_result(); // Store the result for num_rows
$stmtImages->bind_result($imageData, $imageType);

echo "<div style='display: flex; flex-wrap: wrap;justify-content: center;'>";
if ($stmtImages->num_rows > 0) {
    while ($stmtImages->fetch()) {
        $base64_image_data = base64_encode($imageData);
        echo "<img src='data:image/" . $imageType . ";base64," . $base64_image_data . "' height='400px' width='400px' alt='Property Image' style='margin: 1%;'><br>";
    }
} else {
    echo "No images found";
}
echo "</div>";

// Close the statement
$stmtImages->close();

// Close the database connection
$conn->close();
