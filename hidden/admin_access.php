<?php
session_start();

$valid_username = "PROPTOKART";
$valid_password_hash = password_hash("Proptokart123@", PASSWORD_DEFAULT);

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Set the duration of the block in seconds (24 hours)
$block_duration = 5;

// Check if the block duration has elapsed since the last failed attempt
if (isset($_SESSION['last_failed_attempt_time']) && time() - $_SESSION['last_failed_attempt_time'] < $block_duration) {
    $time_remaining = $block_duration - (time() - $_SESSION['last_failed_attempt_time']);
    $error_message = "Too many login attempts. Please try again later. You can try again after " . gmdate("H:i:s", $time_remaining) . ".";
    $_SESSION['error'] = $error_message;
    echo "<script>alert('$error_message'); window.location.href = 'admin_login.php';</script>";
    exit;
}

// Check if there have been 3 or more failed attempts
if (isset($_SESSION['login_attempts']) && $_SESSION['login_attempts'] >= 3) {
    // If there have been 3 or more failed attempts, start timing
    $_SESSION['last_failed_attempt_time'] = time();
    $_SESSION['login_attempts'] = 0; // Reset login attempts counter
}

if (empty($username) || empty($password)) {
    // Handle empty username or password
    $_SESSION['error'] = "Username and password are required.";
    echo "<script>alert('Username and password are required.'); window.location.href = 'admin_login.php';</script>";
    exit;
}

// Simulate a delay to mitigate timing attacks
usleep(500000);

if (hash_equals($valid_username, $username) && password_verify($password, $valid_password_hash)) {
    // Authentication successful
    $_SESSION['authenticated'] = true;
    unset($_SESSION['login_attempts']);
} else {
    // Authentication failed
    $_SESSION['error'] = "Incorrect username or password.";
    echo "<script>alert('Incorrect username or password.'); window.location.href = 'admin_login.php';</script>";
    $_SESSION['last_failed_attempt_time'] = time(); // Update the timestamp of the last failed attempt
    // Increment login attempts counter
    $_SESSION['login_attempts'] = isset($_SESSION['login_attempts']) ? $_SESSION['login_attempts'] + 1 : 1;
    exit;
}
?>

<?php include "./../includes/connection.php"; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
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
    <style>
        #loading {
            display: none;
            text-align: center;
            margin-top: 20px;
            /* Adjust as needed */
        }

        #mediaContainer h2 {
            font-size: 220%;
            /* font-weight: bold; */
            margin: 2%;
            font-family: emoji;
        }

        .email-link {
            color: black;
        }

        .email-link:hover {
            color: blue;
        }

        a {
            text-decoration: none;
            color: white;
        }

        .form-section {
            display: none;
        }

        .active {
            display: block;
        }

        body {
            background-color: #264553;
        }

        main {
            /*font-family: cursive;*/
            /* display: flex; */
            align-items: center;
            text-align: center;
            justify-content: center;
            margin: 0 auto;
        }

        .selection-buttons {
            display: flex;
            flex-direction: row;
            justify-content: center;
        }

        .selection-buttons button {
            padding: 1% 4%;
            /* margin: 1%; */
            /* border: none; */
            background-color: rgb(255 255 255 / 10%);
            border-radius: 9px;
            border: 1px solid black;
            font-family: monospace;
            font-size: 144%;
        }

        .selection-buttons button:hover {
            background-color: rgb(255 255 255 / 20%);
        }

        .form-container {
            margin: 2% auto;
            /*font-family: cursive;*/
            color: black;
            background-color: white;
            border-radius: 45px;
            padding: 2% 2%;
            min-width: 90%;
            max-width: fit-content;
        }

        h1 {
            margin: 0px;
        }

        h2 {
            font-size: 310%;
            font-weight: bold;
            margin: 0 0 10% 0;
        }

        input {
            padding: 2% 1% 2% 3%;
            background-color: #f0e7e7;
            border: none;
            font-size: 94%;
            border-bottom: 2px solid black;
            border-radius: 10px;
        }

        main button {
            color: white;
            background-color: #14b2a2;
            border: 3px double #2de095;
            border-radius: 10px;
            padding: 2% 5%;
            font-weight: bold;
            font-size: 115%;
        }

        main button {
            background-color: #1c8c81;
            color: #ffffffd9;
        }
    </style>
</head>

<body>
    <main>
        <div class="selection-buttons">
            <button class="section-btn" data-section="user" onclick="opentab('user')">User</button>
            <button class="section-btn" data-section="employee" onclick="opentab('employee')">Employee</button>
            <button class="section-btn" data-section="admin" onclick="opentab('admin')">FL for SELL</button>
            <button class="section-btn" data-section="rent" onclick="opentab('rent')">FL for RENT</button>
            <button class="section-btn" data-section="updated" onclick="opentab('updated')">Updated Data</button>
        </div>

        <!-- <div class="form-section user "> -->
        <div id="user" class="form-section user type-content">
            <div class="form-container">
                <h1>User's data From SignUp form</h1>
                <table border="5" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Contact Number</th>
                            <th>Email</th>
                            <th>Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT id, name,phone,email,address FROM User_data ORDER by id desc";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row["id"] . "</td>";
                                echo "<td>" . $row["name"] . "</td>";
                                echo "<td><a href='tel:" . $row['phone'] . "' class='email-link'>" . $row["phone"] . "</a></td>";
                                echo "<td><a href='mailto:" . $row['email'] . "' class='email-link'>" . $row["email"] . "</a></td>";
                                echo "<td>" . $row["address"] . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>No users found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- <div class="form-section employee"> -->
        <div id="employee" class="form-section employee type-content">
            <div class="form-container">
                <h1>Employee Lists</h1>
                <table border="5" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT id,name,username,password FROM employee ORDER by id desc";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row["id"] . "</td>";
                                echo "<td>" . $row["name"] . "</td>";
                                echo "<td>" . $row["username"] . "</td>";
                                echo "<td>
                                <form action='employee_data_access_by_admin.php' method='post' enctype='multipart/form-data'>
                                    <input type='hidden' name='username' value='" . $row["username"] . "'>
                                    <button type='submit'>Show Data</button>
                                </form>
                              </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>No users found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <button onclick="window.location.href='employee_remove.php'" style="position: fixed; bottom: 50px; right: 20px; background-color: #2fc595; color: white; padding: .5% 1%; border: 4px double black; font-weight: bold; font-family: monospace; font-size: 100%;width: 9vw;">Remove
                    Emp </button>
                <button onclick="window.location.href='employee_signup.php'" style="position: fixed; bottom: 8px; right: 20px; background-color: #2fc595; color: white; padding: .5% 1%; border: 4px double black; font-weight: bold; font-family: monospace; font-size: 100%;width: 9vw;">Add
                    Employee </button>
            </div>

        </div>

        <!-- <div class="form-section admin active"> -->
        <div id="admin" class="form-section admin active type-content">
            <div class="form-container">
                <h1>Property Listings for SELL</h1>
                <table border="5" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Address of the Property</th>
                            <th>Contact Number</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT id, name, place, phone,email FROM add_property where property_type = 'sell' ORDER by id desc";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row["id"] . "</td>";
                                echo "<td>" . $row["name"] . "</td>";
                                echo "<td>" . $row["place"] . "</td>";
                                echo "<td><a href='tel:" . $row['phone'] . "' class='email-link'>" . $row["phone"] . "</a></td>";
                                echo "<td><a href='mailto:" . $row['email'] . "' class='email-link'>" . $row["email"] . "</a></td>";
                                echo "<td><button onclick='showMedia1(" . $row["id"] . ")'>Show Media</button></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>No users found</td></tr>";
                        }
                        // $conn->close();
                        ?>
                    </tbody>
                </table>
                <div id="loading1" style="display: none;">Loading...</div>
                <div id="mediaContainer1"></div>
            </div>
        </div>

        <!-- <div class="form-section rent"> -->
        <div id="rent" class="form-section rent type-content">
            <div class="form-container">
                <h1>Property Listings for RENT</h1>
                <table border="5" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Address of the Property</th>
                            <th>Contact Number</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT id, name, place, phone,email FROM add_property where property_type = 'rent' ORDER by id desc";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row["id"] . "</td>";
                                echo "<td>" . $row["name"] . "</td>";
                                echo "<td>" . $row["place"] . "</td>";
                                echo "<td><a href='tel:" . $row['phone'] . "' class='email-link'>" . $row["phone"] . "</a></td>";
                                echo "<td><a href='mailto:" . $row['email'] . "' class='email-link'>" . $row["email"] . "</a></td>";
                                echo "<td><button onclick='showMedia2(" . $row["id"] . ")'>Show Media</button></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>No users found</td></tr>";
                        }
                        // $conn->close();
                        ?>
                    </tbody>
                </table>
                <div id="loading2" style="display: none;">Loading...</div>
                <div id="mediaContainer2"></div>
            </div>
        </div>

        <!-- <div class="form-section updated"> -->
        <div id="updated" class="form-section updated type-content">
            <div class="form-container" style="width: fit-content;">
                <h1>Updated Data by Employees</h1>
                <table border="5" style="width: -content;">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Emp ID</th>
                            <th>Customer ID</th>
                            <th>Name</th>
                            <th>Phone No.</th>
                            <th>State</th>
                            <th>City/Village</th>
                            <th>Postal code</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>Property</th>
                            <th>Property Type</th>
                            <th>Residential Type</th>
                            <th>Property Specification</th>
                            <th>Other Specification</th>
                            <th>Property Furnish</th>
                            <th>Other Furnish</th>
                            <th>Carpet Area</th>
                            <th>Price Min</th>
                            <th>Price Max</th>
                            <th>Advance Payment</th>
                            <th>Payment Method</th>
                            <!-- <th>Note</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // $table_name = 'before_updation'; // Assuming table name is same as username
                        $sql_fetch_data = "SELECT id, emp_id, customer_id, name, phone, state,city,postal_code,email,address,property,property_type,residential_type,property_specification,other_specification,property_furnish,other_furnish,carpet_area,price_min,price_max,advance_payment,payment_method FROM before_updation order by id desc";
                        $data_result = $conn->query($sql_fetch_data);

                        if ($data_result->num_rows > 0) {
                            while ($row = $data_result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row["id"] . "</td>";
                                echo "<td>" . $row["emp_id"] . "</td>";
                                echo "<td>" . $row["customer_id"] . "</td>";
                                echo "<td>" . $row["name"] . "</td>";
                                echo "<td>" . $row["phone"] . "</td>";
                                echo "<td>" . $row["state"] . "</td>";
                                echo "<td>" . $row["city"] . "</td>";
                                echo "<td>" . $row["postal_code"] . "</td>";
                                echo "<td>" . $row["email"] . "</td>";
                                echo "<td>" . $row["address"] . "</td>";
                                echo "<td>" . $row["property"] . "</td>";
                                echo "<td>" . $row["property_type"] . "</td>";
                                echo "<td>" . $row["residential_type"] . "</td>";
                                echo "<td>" . $row["property_specification"] . "</td>";
                                echo "<td>" . $row["other_specification"] . "</td>";
                                echo "<td>" . $row["property_furnish"] . "</td>";
                                echo "<td>" . $row["other_furnish"] . "</td>";
                                echo "<td>" . $row["carpet_area"] . "</td>";
                                echo "<td>" . $row["price_min"] . "</td>";
                                echo "<td>" . $row["price_max"] . "</td>";
                                echo "<td>" . $row["advance_payment"] . "</td>";
                                echo "<td>" . $row["payment_method"] . "</td>";
                                // echo "<td>" . $row["note"] . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='22'>No updations occurred Yet</td></tr>";
                        }
                        ?>

                    </tbody>
                </table>
            </div>
        </div>

    </main>
    <script src="js/admin_access.js"></script>

</html>