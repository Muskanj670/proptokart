<?php
$id = $_GET['id'];
// Start session
session_start();

// Include database connection
include "../includes/connection.php";

function convertHeicToJpeg($heicFile)
{
    $imagick = new \Imagick();
    $imagick->readImage($heicFile);
    $imagick->setImageFormat('jpeg');
    $jpegFile = tempnam(sys_get_temp_dir(), 'jpeg');
    $imagick->writeImage($jpegFile);
    return $jpegFile;
}

function correctImageOrientation($imagePath)
{
    $exif = exif_read_data($imagePath);
    if (!empty($exif['Orientation'])) {
        $image = imagecreatefromstring(file_get_contents($imagePath));
        switch ($exif['Orientation']) {
            case 3:
                $image = imagerotate($image, 180, 0);
                break;
            case 6:
                $image = imagerotate($image, -90, 0);
                break;
            case 8:
                $image = imagerotate($image, 90, 0);
                break;
        }
        imagejpeg($image, $imagePath); // overwrite the original image
        imagedestroy($image);
    }
}

function compressImage($source_path)
{
    $image = imagecreatefromjpeg($source_path); // Assuming the images are JPEG
    ob_start();
    imagejpeg($image, NULL, 20); // 20 is the compression quality (0-100)
    $compressed_image = ob_get_clean();
    imagedestroy($image);
    return $compressed_image;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_update'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $state = $_POST['state'];
    $city = $_POST['city'];
    $postal_code = $_POST['postal_code'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $propertyType = $_POST['propertyType'];
    $resi_com_agri_type = $_POST['resi_com_agri_type'];
    $rooms = $_POST['rooms'];
    $baths = $_POST['baths'];
    $parking = $_POST['parking'];
    $property_furnish = $_POST['property_furnish'];
    $shopNo = $_POST['shopNo'];
    $carpet_area = $_POST['carpet_area'];
    $carpet_area_unit = $_POST['carpet_area_unit'];
    $price = $_POST['price'];
    $price_unit = $_POST['price_unit'];
    $per = $_POST['per'];
    $advance_payment = $_POST['advance_payment'];
    $advance_price_unit = $_POST['advance_price_unit'];
    $payment_method = $_POST['payment_method'];
    $note = $_POST['note'];
    $youtube_link = $_POST['youtube_link'];

    // Update employee data
    $sql_update_data = "UPDATE employee_data SET name=?, phone=?, state=?, city=?, postal_code=?, email=?, address=?, propertyType=?, resi_com_agri_type=?, rooms=?, baths=?, parking=?, property_furnish=?, shopNo=?, carpet_area=?, carpet_area_unit=?, price=?, price_unit=?, per=?, advance_payment=?, advance_price_unit=?, payment_method=?, note=?, youtube_link=? WHERE id=?";
    $stmt_update_data = $conn->prepare($sql_update_data);

    if (!$stmt_update_data) {
        $_SESSION['error'] = "Error preparing SQL statement for employee_data update: " . $conn->error;
        header("Location: error_page.php");
        exit();
    }

    $stmt_update_data->bind_param(
        "ssssssssssssssssssssssssi",
        $name,
        $phone,
        $state,
        $city,
        $postal_code,
        $email,
        $address,
        $propertyType,
        $resi_com_agri_type,
        $rooms,
        $baths,
        $parking,
        $property_furnish,
        $shopNo,
        $carpet_area,
        $carpet_area_unit,
        $price,
        $price_unit,
        $per,
        $advance_payment,
        $advance_price_unit,
        $payment_method,
        $note,
        $youtube_link,
        $id
    );

    if (!$stmt_update_data->execute()) {
        $_SESSION['error'] = "Error executing employee_data update: " . $stmt_update_data->error;
        header("Location: error_page.php");
        exit();
    }

    $stmt_update_data->close();

    // Handle thumbnail upload
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] == UPLOAD_ERR_OK) {
        $thumbnail_tmp_name = $_FILES['thumbnail']['tmp_name'];
        $thumbnail_name = $_FILES['thumbnail']['name'];
        $thumbnail_type = $_FILES['thumbnail']['type'];

        // Check if the file is a HEIC image and convert it to JPEG
        if (strtolower(pathinfo($thumbnail_name, PATHINFO_EXTENSION)) === 'heic') {
            $thumbnail_tmp_name = convertHeicToJpeg($thumbnail_tmp_name);
            $thumbnail_name = pathinfo($thumbnail_name, PATHINFO_FILENAME) . '.jpeg';
            $thumbnail_type = 'image/jpeg';
        }

        // Compress image
        $compressed_thumbnail = compressImage($thumbnail_tmp_name);

        // Prepare and bind parameters for employee_thumbnail table
        $sql = 'UPDATE employee_thumbnail SET image_name = ?, image_data = ?, image_type = ? WHERE user_id = ?';
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            $_SESSION['error'] = 'Error preparing SQL query: ' . $conn->error;
            header("Location: error_page.php");
            exit();
        }

        $null = NULL;
        $stmt->bind_param('sbsi', $thumbnail_name, $null, $thumbnail_type, $id);
        $stmt->send_long_data(1, $compressed_thumbnail); // Index 1 for the second parameter (image_data)

        if (!$stmt->execute()) {
            $_SESSION['error'] = 'Error executing SQL query: ' . $stmt->error;
            header("Location: error_page.php");
            exit();
        }

        $stmt->close();
    }

    $_SESSION['success'] = "Row with ID $id updated successfully.";
    header("Location: emp_fetch.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $sql_fetch_data = "SELECT name, phone, state, city, postal_code, email, address, propertyType, resi_com_agri_type, rooms, baths, parking, property_furnish, shopNo, carpet_area, carpet_area_unit, price, price_unit, per, advance_payment, advance_price_unit, payment_method, note, youtube_link FROM employee_data WHERE id=?";
    $stmt_fetch_data = $conn->prepare($sql_fetch_data);

    if (!$stmt_fetch_data) {
        $_SESSION['error'] = "Error preparing SQL statement: " . $conn->error;
        header("Location: error_page.php");
        exit();
    }

    $stmt_fetch_data->bind_param("i", $id);

    if (!$stmt_fetch_data->execute()) {
        $_SESSION['error'] = "Error executing SQL query: " . $stmt_fetch_data->error;
        header("Location: error_page.php");
        exit();
    }

    $stmt_fetch_data->bind_result(
        $name,
        $phone,
        $state,
        $city,
        $postal_code,
        $email,
        $address,
        $propertyType,
        $resi_com_agri_type,
        $rooms,
        $baths,
        $parking,
        $property_furnish,
        $shopNo,
        $carpet_area,
        $carpet_area_unit,
        $price,
        $price_unit,
        $per,
        $advance_payment,
        $advance_price_unit,
        $payment_method,
        $note,
        $youtube_link
    );

    $stmt_fetch_data->fetch();
    $stmt_fetch_data->close();
}

$conn->close();
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
    <title>Update Data</title>
    <link rel="stylesheet" href="css/form.css">
    <link rel="stylesheet" href="css/modify.css">
    <style>
        .hidden {
            display: none;
        }

        select.parking1#parking {
            width: 33.33%;
        }

        select.parking2 {
            width: 50%;
        }
    </style>
</head>

<body>
    <header>
        <?php include '../includes/config.php'; ?>
        <?php include INCLUDES_PATH . 'header.php'; ?>
    </header>
    <main style="padding-top:5%">
        <div class="backgroun-gradiant" style="top:50%; right:0"></div>
        <div class="backgroun-gradiant" style="top:0%; left:-8rem"></div>
        <div class="selection-buttons">
            <button class="section-btn"><a href="emp_fetch.php">Show Data</a></button>
        </div>
        <div class="form-section add active">
            <div class="form-container">
                <h3>Add New Data to your Account
                    <?php echo $_SESSION['username']; ?>
                </h3>
                <div class="lower">
                    <div class="right col-l-12 col-m-12 col-s-12">
                        <form method="post" enctype="multipart/form-data">
                            <div class="add-data-block-upper col-l-4 col-m-6 col-s-12">
                                <label for="name">Name:</label>
                                <input type="text" id="name" name="name" placeholder="Enter your Name" value="<?php echo $name; ?>"><br><br>
                            </div>
                            <div class="add-data-block-upper col-l-4 col-m-6 col-s-12">
                                <label for="phone">Phone No.:</label>
                                <input type="text" id="phone" name="phone" placeholder="Enter your phone number" value="<?php echo $phone; ?>"><br><br>
                            </div>
                            <div class="add-data-block-upper col-l-4 col-m-6 col-s-12">
                                <label for="state">State:</label>
                                <input type="text" id="state" name="state" placeholder="Enter your state" value="<?php echo $state; ?>"><br><br>
                            </div>
                            <div class="add-data-block-upper col-l-4 col-m-6 col-s-12">
                                <label for="city">City/Village:</label>
                                <input type="text" id="city" name="city" placeholder="Enter your city or village" value="<?php echo $city; ?>"><br><br>
                            </div>
                            <div class="add-data-block-upper col-l-4 col-m-6 col-s-12">
                                <label for="postal_code">Postal code:</label>
                                <input type="text" id="postal_code" name="postal_code" placeholder="Enter your postal code" value="<?php echo $postal_code; ?>"><br><br>
                            </div>
                            <div class="add-data-block-upper col-l-4 col-m-6 col-s-12">
                                <label for="email">Email:</label>
                                <input type="email" id="email" name="email" placeholder="Enter your email" value="<?php echo $email; ?>"><br><br>
                            </div>
                            <div style="display: flex; flex-wrap: wrap;width:100%">
                                <div class="add-data-block-mid col-l-6 col-m-6 col-s-12" style="margin: 1% 0% 2% 0%;">
                                    <label for="address" style="display: none;">Address:</label>
                                    <input type="text" id="address" name="address" placeholder="Enter your address" class="note" value="<?php echo $address; ?>"><br><br>
                                </div>
                                <div class="add-data-block-mid col-l-6 col-m-6 col-s-12" style="margin: 1% 0% 2% 0%;">
                                    <label for="youtube_link" style="display: none;">YouTube Video Link:</label>
                                    <input type="text" name="youtube_link" id="youtube_link" placeholder="Enter embed youtube link" value="<?php echo $youtube_link; ?>"><br><br>
                                </div>
                            </div>
                            <div class="add-data-block-radio">
                                <div class="add-data-block-radio-label">
                                    <label for="property_type">Types of Property:</label><br>
                                </div>
                                <div class="add-data-block-radio-button">
                                    <div class="for-radio-option col-l-4 col-m-4 col-s-12">
                                        <label for="propertyTypeResidential">Residential</label><br>
                                        <input type="radio" id="propertyTypeResidential" name="propertyType" value="Residential" onclick="togglePropertyType()" <?php echo ($propertyType == 'Residential') ? 'checked' : ''; ?>>
                                    </div>
                                    <div class="for-radio-option col-l-4 col-m-4 col-s-12">
                                        <label for="propertyTypeCommercial">Commercial</label><br>
                                        <input type="radio" id="propertyTypeCommercial" name="propertyType" value="Commercial" onclick="togglePropertyType()" <?php echo ($propertyType == 'Commercial') ? 'checked' : ''; ?>>
                                    </div>
                                    <div class="for-radio-option col-l-4 col-m-4 col-s-12">
                                        <label for="propertyTypeAgriculture">Agriculture</label><br>
                                        <input type="radio" id="propertyTypeAgriculture" name="propertyType" value="Agriculture" onclick="togglePropertyType()" <?php echo ($propertyType == 'Agriculture') ? 'checked' : ''; ?>>
                                    </div>
                                </div>

                            </div>
                            <div id="residentialSection" class="hidden" style="width: 100%;">
                                <div class="add-data-block-radio">
                                    <div class="add-data-block-radio-label">
                                        <label for="resi_com_agri_type">Type of residentiaL property:</label><br>
                                    </div>
                                    <div class="add-data-block-radio-button">
                                        <div class="for-radio-option" class="col-l-3 col-m-6 col-s-12">
                                            <label for="house">House</label><br>
                                            <input type="radio" id="house" name="resi_com_agri_type" value="House" <?php echo ($resi_com_agri_type == 'House') ? 'checked' : ''; ?>>
                                        </div>
                                        <div class="for-radio-option" class="col-l-3 col-m-6 col-s-12">
                                            <label for="flat">Flat</label><br>
                                            <input type="radio" id="flat" name="resi_com_agri_type" value="Flat" <?php echo ($resi_com_agri_type == 'Flat') ? 'checked' : ''; ?>>
                                        </div>
                                        <div class="for-radio-option" class="col-l-3 col-m-6 col-s-12">
                                            <label for="villa">Villa</label><br>
                                            <input type="radio" id="villa" name="resi_com_agri_type" value="Villa" <?php echo ($resi_com_agri_type == 'Villa') ? 'checked' : ''; ?>>
                                        </div>
                                        <div class="for-radio-option" class="col-l-3 col-m-6 col-s-12">
                                            <label for="apartment">Apartment</label><br><br>
                                            <input type="radio" id="apartment" name="resi_com_agri_type" value="Apartment" <?php echo ($resi_com_agri_type == 'Apartment') ? 'checked' : ''; ?>>
                                        </div>
                                    </div>
                                </div>
                                <div style="display: flex; flex-wrap: wrap; align-items: baseline;">
                                    <div class="add-data-block-upper col-l-4 col-m-4 col-s-12">
                                        <label for="rooms"> Number of Rooms:</label>
                                        <input type="number" id="rooms" name="rooms" placeholder="Enter No. of rooms" value="<?php echo $rooms; ?>"><br><br>
                                    </div>
                                    <div class="add-data-block-upper col-l-4 col-m-4 col-s-12">
                                        <label for="baths"> Number of Baths:</label>
                                        <input type="number" id="baths" name="baths" placeholder="Enter No. of baths" value="<?php echo $baths; ?>"><br><br>
                                    </div>
                                    <select id="parking" name="parking" style=" padding: 11px 2%; color: dimgrey; text-transform: capitalize;" class="parking1 col-l-4 col-m-4 col-s-12">
                                        <option value="Yes" <?php echo ($parking == 'Yes') ? 'selected' : ''; ?>>Yes
                                        </option>
                                        <option value="No" <?php echo ($parking == 'No') ? 'selected' : ''; ?>>No</option>
                                    </select>
                                </div>
                                <div class="add-data-block-radio">
                                    <div class="add-data-block-radio-label">
                                        <label for="property_furnish">Property Furnish Specs:</label><br>
                                    </div>
                                    <div class="add-data-block-radio-button">
                                        <div class="for-radio-option" class="col-l-4 col-m-4 col-s-12">
                                            <label for="full_furnished">Full Furnished</label><br>
                                            <input type="radio" id="full_furnished" name="property_furnish" value="Full Furnished" <?php echo ($property_furnish == 'Full Furnished') ? 'checked' : ''; ?>>
                                        </div>
                                        <div class="for-radio-option" class="col-l-4 col-m-4 col-s-12">
                                            <label for="semi_furnished">Semi Furnished</label><br>
                                            <input type="radio" id="semi_furnished" name="property_furnish" value="Semi Furnished" <?php echo ($property_furnish == 'Semi Furnished') ? 'checked' : ''; ?>>
                                        </div>
                                        <div class="for-radio-option" class="col-l-4 col-m-4 col-s-12">
                                            <label for="unfurnished">Unfurnished</label><br>

                                            <input type="radio" id="unfurnished" name="property_furnish" value="Unfurnished" <?php echo ($property_furnish == 'Unfurnished') ? 'checked' : ''; ?>>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="commercialSection" class="hidden" style="width: 100%;">
                                <div class="add-data-block-radio">
                                    <div class="add-data-block-radio-label">
                                        <label for="commercial_type">Type of Commercial Property:</label><br>
                                    </div>
                                    <div class="add-data-block-radio-button">
                                        <div class="for-radio-option" class="col-l-3 col-m-6 col-s-12">
                                            <label for="shop">Shop</label><br>
                                            <input type="radio" id="shop" name="resi_com_agri_type" value="shop" <?php echo ($resi_com_agri_type == 'shop') ? 'checked' : ''; ?>>
                                        </div>
                                        <div class="for-radio-option" class="col-l-3 col-m-6 col-s-12">
                                            <label for="hall">Hall</label><br>
                                            <input type="radio" id="hall" name="resi_com_agri_type" value="Hall" <?php echo ($resi_com_agri_type == 'Hall') ? 'checked' : ''; ?>>
                                        </div>
                                        <div class="for-radio-option" class="col-l-3 col-m-6 col-s-12">
                                            <label for="hallFloor">Hall + Independent Floor</label><br>
                                            <input type="radio" id="hallFloor" name="resi_com_agri_type" value="Hall + Independent Floor" <?php echo ($resi_com_agri_type == 'Hall + Independent Floor') ? 'checked' : ''; ?>>
                                        </div>
                                        <div class="for-radio-option" class="col-l-3 col-m-6 col-s-12">
                                            <label for="basement">Basement</label><br><br>
                                            <input type="radio" id="basement" name="resi_com_agri_type" value="Basement" <?php echo ($resi_com_agri_type == 'Basement') ? 'checked' : ''; ?>>
                                        </div>
                                    </div>
                                </div>
                                <div style="display: flex; flex-wrap: wrap; align-items: baseline;">
                                    <div class="add-data-block-upper col-l-6 col-m-6 col-s-6">
                                        <label for="shopNo">Shop No.:</label>
                                        <input type="text" id="shopNo" name="shopNo" placeholder="Enter Shop no." value="<?php echo $shopNo; ?>"><br><br>
                                    </div>
                                    <select id="parking" name="parking" style="padding: 11px 2%; color: dimgrey; text-transform: capitalize;" class="parking2 col-l-6 col-m-6 col-s-6">
                                        <option value="Yes" <?php echo ($parking == 'Yes') ? 'selected' : ''; ?>>Yes
                                        </option>
                                        <option value="No" <?php echo ($parking == 'No') ? 'selected' : ''; ?>>No</option>
                                    </select>
                                </div>
                                <div class="add-data-block-radio">
                                    <div class="add-data-block-radio-label">
                                        <label for="property_furnish">Property Furnish Specs:</label><br>
                                    </div>
                                    <div class="add-data-block-radio-button">
                                        <div class="for-radio-option" class="col-l-4 col-m-4 col-s-12">
                                            <label for="full_furnished">Full Furnished</label><br>
                                            <input type="radio" id="full_furnished" name="property_furnish" value="Full Furnished" <?php echo ($property_furnish == 'Full Furnished') ? 'checked' : ''; ?>>
                                        </div>
                                        <div class="for-radio-option" class="col-l-4 col-m-4 col-s-12">
                                            <label for="semi_furnished">Semi Furnished</label><br>
                                            <input type="radio" id="semi_furnished" name="property_furnish" value="Semi Furnished" <?php echo ($property_furnish == 'Semi Furnished') ? 'checked' : ''; ?>>
                                        </div>
                                        <div class="for-radio-option" class="col-l-4 col-m-4 col-s-12">
                                            <label for="unfurnished">Unfurnished</label><br>

                                            <input type="radio" id="unfurnished" name="property_furnish" value="Unfurnished" <?php echo ($property_furnish == 'Unfurnished') ? 'checked' : ''; ?>>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="add-data-block-bottom">
                                <div style="display: flex; align-items: center;padding-right: 1%;" class="col-l-6 col-m-6 col-s-12">
                                    <label for="carpet_area">Carpet Area:</label>
                                    <input type="text" id="carpet_area" name="carpet_area" class="other" style="margin-right: 10px; width: 50% !important;" value="<?php echo $carpet_area; ?>">
                                    <select id="carpet_area_unit" name="carpet_area_unit" style="width: 50%; padding: 2%; color: dimgrey; text-transform: capitalize;">
                                        <option value="sq feet" <?php echo ($carpet_area_unit == 'sq feet') ? 'selected' : ''; ?>>sq ft</option>
                                        <option value="sq meter" <?php echo ($carpet_area_unit == 'sq meter') ? 'selected' : ''; ?>>sq m</option>
                                        <option value="acre" <?php echo ($carpet_area_unit == 'acre') ? 'selected' : ''; ?>>ac</option>
                                        <option value="ha" <?php echo ($carpet_area_unit == 'ha') ? 'selected' : ''; ?>>ha
                                        </option>
                                    </select>
                                </div>
                                <div style="display: flex; align-items: center;padding-left: 1%;" class="col-l-6 col-m-6 col-s-12">
                                    <label for="price">Price of property:</label>
                                    <input type="text" id="price" name="price" class="other" style="margin-right: 10px; width: 50% !important;" value="<?php echo $price; ?>">
                                    <select id="price_unit" name="price_unit" style="width: 50%; padding: 2%; color: dimgrey; text-transform: capitalize;">
                                        <option value="Thou" <?php echo ($price_unit == 'Thou') ? 'selected' : ''; ?>>thou
                                        </option>
                                        <option value="Lakh" <?php echo ($price_unit == 'Lakh') ? 'selected' : ''; ?>>lakh
                                        </option>
                                        <option value="Cr" <?php echo ($price_unit == 'Cr') ? 'selected' : ''; ?>>cr
                                        </option>
                                        <option value="M" <?php echo ($price_unit == 'M') ? 'selected' : ''; ?>>M</option>
                                        <option value="B" <?php echo ($price_unit == 'B') ? 'selected' : ''; ?>>B</option>
                                    </select> /
                                    <select id="per" name="per" style="width: 50%; padding: 2%; color: dimgrey; text-transform: capitalize;">
                                        <option value="Total" <?php echo ($per == 'Total') ? 'selected' : ''; ?>>Total
                                        </option>
                                        <option value="Month" <?php echo ($per == 'Month') ? 'selected' : ''; ?>>Month
                                        </option>
                                        <option value="Year" <?php echo ($per == 'Year') ? 'selected' : ''; ?>>Year
                                        </option>
                                        <option value="Day" <?php echo ($per == 'Day') ? 'selected' : ''; ?>>Day</option>
                                    </select>
                                </div>
                            </div>
                            <div class="add-data-block-bottom" style="margin:0%">
                                <label for="advance_payment">Amount Paid in Advance:</label>
                                <input type="text" id="advance_payment" name="advance_payment" class="other" value="<?php echo $advance_payment; ?>">
                                <select id="advance_price_unit" name="advance_price_unit" style="width: 15%; padding: 1%; color: dimgrey; text-transform: capitalize; margin-left: 1%;">
                                    <option value="Thou" <?php echo ($advance_price_unit == 'Thou') ? 'selected' : ''; ?>>
                                        thou</option>
                                    <option value="Lakh" <?php echo ($advance_price_unit == 'Lakh') ? 'selected' : ''; ?>>
                                        lakh</option>
                                    <option value="Cr" <?php echo ($advance_price_unit == 'Cr') ? 'selected' : ''; ?>>cr
                                    </option>
                                    <option value="M" <?php echo ($advance_price_unit == 'M') ? 'selected' : ''; ?>>M
                                    </option>
                                    <option value="B" <?php echo ($advance_price_unit == 'B') ? 'selected' : ''; ?>>B
                                    </option>
                                </select>
                            </div>
                            <div class="add-data-block-radio" style="margin-left:3%">
                                <div class="add-data-block-radio-label" style="font-weight: 200;">
                                    <label for="payment_method">Payment method for property:</label><br>
                                </div>
                                <div class="add-data-block-radio-button">
                                    <div class="for-radio-option" class="col-l-3 col-m-6 col-s-12">
                                        <label for="cash">Cash</label><br>
                                        <input type="checkbox" id="cash" name="payment_method[]" value="Cash" <?php echo ($payment_method == 'Cash') ? 'checked' : ''; ?>>
                                    </div>
                                    <div class="for-radio-option" class="col-l-3 col-m-6 col-s-12">
                                        <label for="online">Online</label><br>
                                        <input type="checkbox" id="online" name="payment_method[]" value="Online" <?php echo ($payment_method == 'Online') ? 'checked' : ''; ?>>
                                    </div>
                                    <div class="for-radio-option" class="col-l-3 col-m-6 col-s-12">
                                        <label for="cheque">Cheque</label><br>
                                        <input type="checkbox" id="cheque" name="payment_method[]" value="Cheque" <?php echo ($payment_method == 'Cheque') ? 'checked' : ''; ?>>
                                    </div>
                                    <div class="for-radio-option" class="col-l-3 col-m-6 col-s-12">
                                        <label for="dd">DD</label><br><br>
                                        <input type="checkbox" id="dd" name="payment_method[]" value="DD" <?php echo ($payment_method == 'DD') ? 'checked' : ''; ?>>
                                    </div>
                                </div>
                            </div>
                            <div class="add-data-block-mid col-l-12 col-m-12 col-s-12">
                                <label for="note" style="display:none">Note:</label>
                                <input type="text" id="note" name="note" placeholder="Enter note" class="note" style="border-radius: unset;" value="<?php echo $note; ?>"><br><br>
                            </div>
                            <div style="width: 100%; display: flex; flex-direction: row; align-items: flex-end;">
                                <label for="thumbnail" style="width: inherit;">Thumbnail of the Property:</label><br>
                                <input type="file" name="thumbnail" id="thumbnail" accept="image/*,.heic" value><br><br>
                            </div>
                            <div class="add-submit-button">
                                <input type="submit" name="submit_update" value="Update">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const radioButtons = document.querySelectorAll('input[name="propertyType"]');
            const residentialSection = document.getElementById('residentialSection');
            const commercialSection = document.getElementById('commercialSection');
            const agricultureSection = document.getElementById('agricultureSection');

            function toggleSections() {
                if (this.value === 'Residential') {
                    residentialSection.classList.remove('hidden');
                    commercialSection.classList.add('hidden');
                    agricultureSection.classList.add('hidden');
                } else if (this.value === 'Commercial') {
                    residentialSection.classList.add('hidden');
                    commercialSection.classList.remove('hidden');
                    agricultureSection.classList.add('hidden');
                } else if (this.value === 'Agriculture') {
                    residentialSection.classList.add('hidden');
                    commercialSection.classList.add('hidden');
                    agricultureSection.classList.remove('hidden');
                }
            }

            radioButtons.forEach(radio => {
                radio.addEventListener('change', toggleSections);
            });

            const selectedPropertyType = "<?php echo $propertyType; ?>";
            if (selectedPropertyType) {
                document.querySelector(`input[name="propertyType"][value="${selectedPropertyType}"]`).checked = true;
                toggleSections.call(document.querySelector(`input[name="propertyType"][value="${selectedPropertyType}"]`));
            }
        });
    </script>
</body>

</html>