<?php
include "../includes/connection.php";

// Start session
session_start();

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: employee_login.php");
    exit;
}
$username = $_SESSION['username'];
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Check if the property value is set
    if (isset($_GET['property'])) {
        // Set session variable with the property value
        $_SESSION["property"] = ($_GET['property'] === 'rent') ? 'rent' : 'buy';
    }
}
$property = $_SESSION["property"];

// Prepare SQL statement based on property type
$sql_fetch_data = "SELECT id, date, customer_id, name, phone, state, city, postal_code, email, address, propertyType, resi_com_agri_type, rooms, baths, parking, property_furnish, shopNo, carpet_area, carpet_area_unit, price, price_unit, per, advance_payment, advance_price_unit, payment_method, youtube_link FROM employee_data WHERE emp_id IN (SELECT id FROM employee WHERE username=?) AND property = ? order by id desc";

$stmt_fetch_data = $conn->prepare($sql_fetch_data);
$stmt_fetch_data->bind_param("ss", $username, $property);

if (!$stmt_fetch_data->execute()) {
    $_SESSION['error'] = "Error executing SQL query: " . $stmt_fetch_data->error;
    exit;
}

// Get result set
$result = $stmt_fetch_data->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="icon" href="../images/proptokart-black.png" type="image/x-icon">
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Collected by You</title>
    <style>
        /* Your CSS styles here */
        main {
            padding-top: 5%;
            overflow: auto;
            height: 100vh;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        .button {
            position: fixed;
            bottom: 0;
            right: 0;
            margin: 20px;
            padding: 10px 20px;
            background-color: #3a3ad4;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .button:hover {
            background-color: #0505ab;
        }

        a {
            text-decoration: none;
            color: white;
        }
    </style>
</head>

<body>
    <header>
        <?php include '../includes/config.php'; ?>
        <?php include INCLUDES_PATH . 'header.php'; ?>
    </header>
    <main>
        <h1>Data Collected by <?php echo htmlspecialchars($username); ?></h1>
        <table>
            <thead>
                <tr>
                    <th>Update</th>
                    <th>ID</th>
                    <th>Date</th>
                    <th>Customer ID</th>
                    <th>Name</th>
                    <th>Phone No.</th>
                    <th>State</th>
                    <th>City/Village</th>
                    <th>Postal code</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Property Type</th>
                    <th>Resi / Comm Type</th>
                    <th>Rooms</th>
                    <th>Baths</th>
                    <th>Parking</th>
                    <th>Property Furnish</th>
                    <th>shopNo</th>
                    <th>Carpet Area</th>
                    <!-- <th>Carpet Area Unit</th> -->
                    <th>Price</th>
                    <!-- <th>Price Unit</th> -->
                    <!-- <th>Per</th> -->
                    <th>Advance Payment</th>
                    <!-- <th>Advance Price Unit</th> -->
                    <th>Payment Method</th>
                    <th>Youtube link</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <tr>
                        <td><a href="update.php?id=<?php echo htmlspecialchars($row['id']); ?>" style="display: inline-block; background: gray; padding: 9px 5px; color: white; text-decoration: none;">Update</a></td>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['date']); ?></td>
                        <td><?php echo htmlspecialchars($row['customer_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['phone']); ?></td>
                        <td><?php echo htmlspecialchars($row['state']); ?></td>
                        <td><?php echo htmlspecialchars($row['city']); ?></td>
                        <td><?php echo htmlspecialchars($row['postal_code']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['address']); ?></td>
                        <td><?php echo htmlspecialchars($row['propertyType']); ?></td>
                        <td><?php echo htmlspecialchars($row['resi_com_agri_type']); ?></td>
                        <td><?php echo htmlspecialchars($row['rooms']); ?></td>
                        <td><?php echo htmlspecialchars($row['baths']); ?></td>
                        <td><?php echo htmlspecialchars($row['parking']); ?></td>
                        <td><?php echo htmlspecialchars($row['property_furnish']); ?></td>
                        <td><?php echo htmlspecialchars($row['shopNo']); ?></td>
                        <td><?php echo htmlspecialchars($row['carpet_area'])." ". htmlspecialchars($row['carpet_area_unit']); ?></td>
                        <td><?php echo htmlspecialchars($row['price'])." ". htmlspecialchars($row['price_unit'])."/".htmlspecialchars($row['per']); ?></td>
                        <td><?php echo htmlspecialchars($row['advance_payment'])." ".htmlspecialchars($row['advance_price_unit']); ?></td>
                        <td><?php echo htmlspecialchars($row['payment_method']); ?></td>
                        <td><?php echo htmlspecialchars($row['youtube_link']); ?></td>
                        <td><button onclick="showMedia4(<?php echo htmlspecialchars($row['id']); ?>)">Show Media</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <div id="loading4" style="display: none;">Loading...</div>
        <div id="mediaContainer4"></div>
    </main>
    <button class="button" type="submit" name="property" value=""> <a href="./modify.php">Add Data</a></button>


    <!-- Your JavaScript imports and scripts here -->
    <script src="js/admin_access.js"></script>
</body>

</html>

<?php
// Close statement and connection
$stmt_fetch_data->close();
$conn->close();
?>