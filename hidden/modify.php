<?php
// Start session
session_start();
$username = $_SESSION['username'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include '../includes/connection.php';

    function convertHeicToJpeg($heicFile)
    {
        $imagick = new \Imagick();
        $imagick->readImage($heicFile);
        $imagick->setImageFormat('jpeg');
        $jpegFile = tempnam(sys_get_temp_dir(), 'jpeg');
        $imagick->writeImage($jpegFile);
        return $jpegFile;
    }

    // Function to correct image orientation if needed

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
            imagejpeg($image, $imagePath);
            // overwrite the original image
            imagedestroy($image);
        }
    }

    if (isset($_POST['submit_add'])) {
        function compressImage($source_path)
        {
            // Load image
            $image = imagecreatefromjpeg($source_path);
            // Assuming the images are JPEG

            // Compress image
            ob_start();
            imagejpeg($image, NULL, 20);
            // 50 is the compression quality ( 0-100 )
            $compressed_image = ob_get_clean();

            // Free up memory
            imagedestroy($image);

            return $compressed_image;
        }

        // Retrieve id from employee table based on username
        $sql_fetch_id = 'SELECT id FROM employee WHERE username=?';
        $stmt_fetch_id = $conn->prepare($sql_fetch_id);
        if (!$stmt_fetch_id) {
            // Handle error
            $_SESSION['error'] = 'Error preparing SQL statement: ' . $conn->error;
        }
        $stmt_fetch_id->bind_param('s', $_SESSION['username']);

        if (!$stmt_fetch_id->execute()) {
            // Handle error
            $_SESSION['error'] = 'Error executing SQL query: ' . $stmt_fetch_id->error;
        }

        $stmt_fetch_id->bind_result($emp_id);
        $stmt_fetch_id->fetch();
        $stmt_fetch_id->close();

        // Retrieve form data
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $state = $_POST['state'];
        $city = $_POST['city'];
        $postal_code = $_POST['postal_code'];
        $email = $_POST['email'];
        $address = $_POST['address'];
        $property = $_SESSION['property'];
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
        $payment_method = implode(',', $_POST['payment_method']);
        $note = $_POST['note'];
        $youtube_link = $_POST['youtube_link'];

        // Prepare and bind parameters for employee_data table
        // Insert the record into the employee_data table without customer_id

        $sql = "INSERT INTO employee_data ( emp_id, date, customer_id, name, phone, state, city, postal_code, email, address, 
            property, propertyType, resi_com_agri_type, rooms, baths, parking, property_furnish, shopNo, 
            carpet_area, carpet_area_unit, price, price_unit, per, advance_payment, advance_price_unit, payment_method, note, youtube_link
        ) VALUES (?, NOW(), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        // Check if the statement preparation succeeded
        if (!$stmt) {
            $_SESSION['error'] = 'Error preparing SQL statement: ' . $conn->error;
            exit;
        }

        // Bind parameters
        $stmt->bind_param(
            'issssssssssssssssssssssssss',
            $emp_id,
            $customer_id,
            $name,
            $phone,
            $state,
            $city,
            $postal_code,
            $email,
            $address,
            $property,
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

        // Execute the prepared statement
        if (!$stmt->execute()) {
            // Handle error
            $_SESSION['error'] = 'Error executing SQL query: ' . $stmt->error;
            exit;
        }

        // Close the statement
        $stmt->close();

        // Fetch the ID of the inserted row
        $last_inserted_id = $conn->insert_id;

        // Generate the customer_id
        // Get the current month and year
        $current_month = date('m');
        $current_year = date('Y');

        // Format the customer ID
        $customer_id = 'PS.' . $current_month . '.' . $current_year . '.' . str_pad($last_inserted_id, 3, '0', STR_PAD_LEFT);

        // Update the row with the generated customer_id
        $sql_update = 'UPDATE employee_data SET customer_id = ? WHERE id = ?';
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param('si', $customer_id, $last_inserted_id);

        // Execute the prepared statement
        if (!$stmt_update->execute()) {
            // Handle error
            $_SESSION['error'] = 'Error executing SQL query: ' . $stmt_update->error;
        }

        $stmt_update->close();

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
            $sql = 'INSERT INTO employee_thumbnail (user_id, image_name, image_data, image_type) VALUES (?, ?, ?, ?)';
            $stmt = $conn->prepare($sql);
            $null = NULL;
            $stmt->bind_param('isbs', $last_inserted_id, $thumbnail_name, $null, $thumbnail_type);
            $stmt->send_long_data(2, $compressed_thumbnail);

            // Execute the prepared statement
            if (!$stmt->execute()) {
                // Handle error
                $_SESSION['error'] = 'Error executing SQL query: ' . $stmt->error;
                exit("<script>alert('Error executing SQL query: " . $stmt->error . "');</script>");
            }

            $stmt->close();
        }

        // Handle images file uploads
        foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
            $image_name = $_FILES['images']['name'][$key];
            $image_type = $_FILES['images']['type'][$key];

            // Check if the file is a HEIC image and convert it to JPEG
            if (strtolower(pathinfo($image_name, PATHINFO_EXTENSION)) === 'heic') {
                $tmp_name = convertHeicToJpeg($tmp_name);
                $image_name = pathinfo($image_name, PATHINFO_FILENAME) . '.jpeg';
                $image_type = 'image/jpeg';
            }

            // Compress image
            $compressed_image = compressImage($tmp_name);

            // Prepare and bind parameters for employee_images table
            $sql = 'INSERT INTO employee_images (user_id, image_name, image_data, image_type) VALUES (?, ?, ?, ?)';
            $stmt = $conn->prepare($sql);
            $null = NULL;
            $stmt->bind_param('isbs', $last_inserted_id, $image_name, $null, $image_type);
            $stmt->send_long_data(2, $compressed_image);

            // Execute the prepared statement
            if (!$stmt->execute()) {
                // Handle error
                $_SESSION['error'] = 'Error executing SQL query: ' . $stmt->error;
                exit("<script>alert('Error executing SQL query: " . $stmt->error . "');</script>");
            }

            $stmt->close();
        }

        // Close connection
        $conn->close();

        // Success message
        $_SESSION['success'] = 'Property Added Successfully';
        echo "<script>alert('Property Added Successfully'); window.location.href = 'modify.php';</script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='UTF-8'>
    <link rel='icon' href='../images/proptokart-black.png' type='image/x-icon'>
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
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Modify Data</title>
    <link rel='stylesheet' href='css/form.css'>
    <link rel='stylesheet' href='css/modify.css'>
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
        <?php include '../includes/config.php';
        ?>
        <?php include INCLUDES_PATH . 'header.php';
        ?>
    </header>
    <main style='padding-top:5%'>
        <div class='backgroun-gradiant' style='top:50%; right:0'></div>
        <div class='backgroun-gradiant' style='top:0%; left:-8rem'></div>
        <div class='selection-buttons'>
            <button class='section-btn'><a href='emp_fetch.php'>Show Data</a></button>
        </div>
        <div class='form-section add active'>
            <div class='form-container'>
                <h3>Add New Data to your Account
                    <?php echo $username;
                    ?>
                </h3>
                <div class='lower'>
                    <div class='right col-l-12 col-m-12 col-s-12'>
                        <form method='post' enctype='multipart/form-data'>
                            <div class='add-data-block-upper col-l-4 col-m-6 col-s-12'>
                                <label for='name'>Name:</label>
                                <input type='text' id='name' name='name' placeholder='Enter your Name' required><br><br>
                            </div>
                            <div class='add-data-block-upper col-l-4 col-m-6 col-s-12'>
                                <label for='phone'>Phone No.:</label>
                                <input type='text' id='phone' name='phone' placeholder='Enter your phone number' required><br><br>
                            </div>
                            <div class='add-data-block-upper col-l-4 col-m-6 col-s-12'>
                                <label for='state'>State:</label>
                                <input type='text' id='state' name='state' placeholder='Enter your state' required><br><br>
                            </div>
                            <div class='add-data-block-upper col-l-4 col-m-6 col-s-12'>
                                <label for='city'>City/Village:</label>
                                <input type='text' id='city' name='city' placeholder='Enter your city or village' required><br><br>
                            </div>
                            <div class='add-data-block-upper col-l-4 col-m-6 col-s-12'>
                                <label for='postal_code'>Postal code:</label>
                                <input type='text' id='postal_code' name='postal_code' placeholder='Enter your postal code'><br><br>
                            </div>
                            <div class='add-data-block-upper col-l-4 col-m-6 col-s-12'>
                                <label for='email'>Email:</label>
                                <input type='email' id='email' name='email' placeholder='Enter your email'><br><br>
                            </div>
                            <div style='display: flex; flex-wrap: wrap;width:100%'>
                                <div class='add-data-block-mid col-l-6 col-m-6 col-s-12' style='margin: 1% 0% 2% 0%;'>
                                    <label for='address' style='display: none;'>Address:</label>
                                    <input type='text' id='address' name='address' placeholder='Enter your address' class='note'><br><br>
                                </div>
                                <div class='add-data-block-mid col-l-6 col-m-6 col-s-12' style='margin: 1% 0% 2% 0%;'>
                                    <label for='youtube_link' style='display: none;'>YouTube Video Link:</label>
                                    <input type='text' name='youtube_link' id='youtube_link' placeholder='Enter embed youtube link'><br><br>
                                </div>
                            </div>
                            <div class='add-data-block-radio'>
                                <div class='add-data-block-radio-label'>
                                    <label for='property_type'>Types of Property:</label><br>
                                </div>
                                <div class='add-data-block-radio-button'>
                                    <div class='for-radio-option' class='col-l-4 col-m-4 col-s-12'>
                                        <label for='resi_com_agri_type'>Residential</label><br>
                                        <input type='radio' id='propertyTypeResidential' name='propertyType' value='Residential' onclick='togglePropertyType()'>
                                    </div>
                                    <div class='for-radio-option' class='col-l-4 col-m-4 col-s-12'>
                                        <label for='commercial_type'>Commercial</label><br>
                                        <input type='radio' id='propertyTypeCommercial' name='propertyType' value='Commercial' onclick='togglePropertyType()'>
                                    </div>
                                    <div class='for-radio-option' class='col-l-4 col-m-4 col-s-12'>
                                        <label for='agriculture_type'>Agriculture</label><br><br>
                                        <input type='radio' id='propertyTypeAgriculture' name='propertyType' value='Agriculture' onclick='togglePropertyType()'>
                                    </div>
                                </div>
                            </div>
                            <div id='residentialSection' class='hidden' style='width: 100%;'>
                                <div class='add-data-block-radio'>
                                    <div class='add-data-block-radio-label'>
                                        <label for='resi_com_agri_type'>Type of residentiaL property:</label><br>
                                    </div>
                                    <div class='add-data-block-radio-button'>
                                        <div class='for-radio-option' class='col-l-3 col-m-6 col-s-12'>
                                            <label for='house'>House</label><br>
                                            <input type='radio' id='house' name='resi_com_agri_type' value='House'>
                                        </div>
                                        <div class='for-radio-option' class='col-l-3 col-m-6 col-s-12'>
                                            <label for='flat'>Flat</label><br>
                                            <input type='radio' id='flat' name='resi_com_agri_type' value='Flat'>
                                        </div>
                                        <div class='for-radio-option' class='col-l-3 col-m-6 col-s-12'>
                                            <label for='villa'>Villa</label><br>
                                            <input type='radio' id='villa' name='resi_com_agri_type' value='Villa'>
                                        </div>
                                        <div class='for-radio-option' class='col-l-3 col-m-6 col-s-12'>
                                            <label for='apartment'>Apartment</label><br><br>
                                            <input type='radio' id='apartment' name='resi_com_agri_type' value='Apartment'>
                                        </div>
                                    </div>
                                </div>
                                <div style='display: flex; flex-wrap: wrap; align-items: baseline;'>
                                    <div class='add-data-block-upper col-l-4 col-m-4 col-s-12'>
                                        <label for='rooms'> Number of Rooms:</label>
                                        <input type='number' id='rooms' name='rooms' placeholder='Enter No. of rooms'><br><br>
                                    </div>
                                    <div class='add-data-block-upper col-l-4 col-m-4 col-s-12'>
                                        <label for='baths'> Number of Baths:</label>
                                        <input type='number' id='baths' name='baths' placeholder='Enter No. of baths'><br><br>
                                    </div>
                                    <select id='parking' name='parking' style=' padding: 11px 2%; color: dimgrey; text-transform: capitalize;' class='parking1 col-l-4 col-m-4 col-s-12'>
                                        <option value='Yes'>Yes</option>
                                        <option value='No'>No</option>
                                    </select>
                                </div>
                                <div class='add-data-block-radio'>
                                    <div class='add-data-block-radio-label'>
                                        <label for='property_furnish'>Property Furnish Specs:</label><br>
                                    </div>
                                    <div class='add-data-block-radio-button'>
                                        <div class='for-radio-option' class='col-l-4 col-m-4 col-s-12'>
                                            <label for='full_furnished'>Full Furnished</label><br>
                                            <input type='radio' id='full_furnished' name='property_furnish' value='Full Furnished'>
                                        </div>
                                        <div class='for-radio-option' class='col-l-4 col-m-4 col-s-12'>
                                            <label for='semi_furnished'>Semi Furnished</label><br>
                                            <input type='radio' id='semi_furnished' name='property_furnish' value='Semi Furnished'>
                                        </div>
                                        <div class='for-radio-option' class='col-l-4 col-m-4 col-s-12'>
                                            <label for='unfurnished'>Unfurnished</label><br>

                                            <input type='radio' id='unfurnished' name='property_furnish' value='Unfurnished'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id='commercialSection' class='hidden' style='width: 100%;'>
                                <div class='add-data-block-radio'>
                                    <div class='add-data-block-radio-label'>
                                        <label for='commercial_type'>Type of Commercial Property:</label><br>
                                    </div>
                                    <div class='add-data-block-radio-button'>
                                        <div class='for-radio-option' class='col-l-3 col-m-6 col-s-12'>
                                            <label for='shop'>Shop</label><br>
                                            <input type='radio' id='shop' name='resi_com_agri_type' value='shop'>
                                        </div>
                                        <div class='for-radio-option' class='col-l-3 col-m-6 col-s-12'>
                                            <label for='hall'>Hall</label><br>
                                            <input type='radio' id='hall' name='resi_com_agri_type' value='Hall'>
                                        </div>
                                        <div class='for-radio-option' class='col-l-3 col-m-6 col-s-12'>
                                            <label for='hallFloor'>Hall + Independent Floor</label><br>
                                            <input type='radio' id='hallFloor' name='resi_com_agri_type' value='Hall + Independent Floor'>
                                        </div>
                                        <div class='for-radio-option' class='col-l-3 col-m-6 col-s-12'>
                                            <label for='basement'>Basement</label><br><br>
                                            <input type='radio' id='basement' name='resi_com_agri_type' value='Basement'>
                                        </div>
                                    </div>
                                </div>
                                <div style='display: flex; flex-wrap: wrap; align-items: baseline;'>
                                    <div class='add-data-block-upper col-l-6 col-m-6 col-s-6'>
                                        <label for='shopNo'>Shop No.:</label>
                                        <input type='text' id='shopNo' name='shopNo' placeholder='Enter Shop no.'><br><br>
                                    </div>
                                    <select id='parking' name='parking' style='padding: 11px 2%; color: dimgrey; text-transform: capitalize;' class='parking2 col-l-6 col-m-6 col-s-6'>
                                        <option value='Yes'>Yes</option>
                                        <option value='No'>No</option>
                                    </select>
                                </div>
                                <div class='add-data-block-radio'>
                                    <div class='add-data-block-radio-label'>
                                        <label for='property_furnish'>Property Furnish Specs:</label><br>
                                    </div>
                                    <div class='add-data-block-radio-button'>
                                        <div class='for-radio-option' class='col-l-4 col-m-4 col-s-12'>
                                            <label for='full_furnished'>Full Furnished</label><br>
                                            <input type='radio' id='full_furnished' name='property_furnish' value='Full Furnished'>
                                        </div>
                                        <div class='for-radio-option' class='col-l-4 col-m-4 col-s-12'>
                                            <label for='semi_furnished'>Semi Furnished</label><br>
                                            <input type='radio' id='semi_furnished' name='property_furnish' value='Semi Furnished'>
                                        </div>
                                        <div class='for-radio-option' class='col-l-4 col-m-4 col-s-12'>
                                            <label for='unfurnished'>Unfurnished</label><br>
                                            <input type='radio' id='unfurnished' name='property_furnish' value='Unfurnished'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class='add-data-block-bottom'>
                                <div style='display: flex; align-items: center;padding-right: 1%;' class='col-l-6 col-m-6 col-s-12'>
                                    <label for='carpet_area'>Carpet Area:</label>
                                    <input type='text' id='carpet_area' name='carpet_area' class='other' style='margin-right: 10px; width: 50% !important;'>
                                    <select id='carpet_area_unit' name='carpet_area_unit' style='width: 50%; padding: 2%; color: dimgrey; text-transform: capitalize;'>
                                        <option value='sq feet' selected>sq ft</option>
                                        <option value='sq meter'>sq m</option>
                                        <option value='acre'>ac</option>
                                        <option value='ha'>ha</option>
                                    </select>
                                </div>
                                <div style='display: flex; align-items: center;padding-left: 1%;' class='col-l-6 col-m-6 col-s-12'>
                                    <label for='price'>Price of property:</label>
                                    <input type='text' id='price' name='price' class='other' style='margin-right: 10px; width: 50% !important;'>
                                    <select id='price_unit' name='price_unit' style='width: 50%; padding: 2%; color: dimgrey; text-transform: capitalize;'>
                                        <option value='Thou' selected>thou</option>
                                        <option value='Lakh'>lakh</option>
                                        <option value='Cr'>cr</option>
                                        <option value='M'>M</option>
                                        <option value='B'>B</option>
                                    </select> /
                                    <select id='per' name='per' style='width: 50%; padding: 2%; color: dimgrey; text-transform: capitalize;'>
                                        <option value='Total' selected>Total</option>
                                        <option value='Month'>Month</option>
                                        <option value='Year'>Year</option>
                                        <option value='Day'>Day</option>
                                    </select>
                                </div>
                            </div>
                            <div class='add-data-block-bottom' style='margin:0%'>
                                <label for='advance_payment'>Amount Paid in Advance:</label>
                                <input type='text' id='advance_payment' name='advance_payment' class='other' required>
                                <select id='advance_price_unit' name='advance_price_unit' style='width: 15%; padding: 1%; color: dimgrey; text-transform: capitalize; margin-left: 1%;'>
                                    <option value='Thou' selected>thou</option>
                                    <option value='Lakh'>lakh</option>
                                    <option value='Cr'>cr</option>
                                    <option value='M'>M</option>
                                    <option value='B'>B</option>
                                </select>
                            </div>
                            <div class='add-data-block-radio' style='margin-left:3%'>
                                <div class='add-data-block-radio-label' style='font-weight: 200;'>
                                    <label for='payment_method'>Payment method for property:</label><br>
                                </div>
                                <div class='add-data-block-radio-button'>
                                    <div class='for-radio-option' class='col-l-3 col-m-6 col-s-12'>
                                        <label for='cash'>Cash</label><br>
                                        <input type='checkbox' id='cash' name='payment_method[]' value='Cash'>
                                    </div>
                                    <div class='for-radio-option' class='col-l-3 col-m-6 col-s-12'>
                                        <label for='online'>Online</label><br>
                                        <input type='checkbox' id='online' name='payment_method[]' value='Online'>
                                    </div>
                                    <div class='for-radio-option' class='col-l-3 col-m-6 col-s-12'>
                                        <label for='cheque'>Cheque</label><br>
                                        <input type='checkbox' id='cheque' name='payment_method[]' value='Cheque'>
                                    </div>
                                    <div class='for-radio-option' class='col-l-3 col-m-6 col-s-12'>
                                        <label for='dd'>DD</label><br><br>
                                        <input type='checkbox' id='dd' name='payment_method[]' value='DD'>
                                    </div>
                                </div>
                            </div>
                            <div class='add-data-block-mid col-l-12 col-m-12 col-s-12'>
                                <label for='note' style='display:none'>Note:</label>
                                <input type='text' id='note' name='note' placeholder='Enter note' class='note' style='border-radius: unset;'><br><br>
                            </div>
                            <div style='width: 100%; display: flex; flex-direction: row; align-items: flex-end;'>
                                <label for='thumbnail' style='width: inherit;'>Thumbnail of the Property:</label><br>
                                <input type='file' name='thumbnail' id='thumbnail' accept='image/*,.heic' required><br><br>

                                <label for='images' style='width: inherit;'>Images of the Property:</label><br>
                                <input type='file' name='images[]' id='images' accept='image/*,.heic' multiple required><br><br>

                            </div>
                            <div class='add-submit-button'>
                                <input type='submit' name='submit_add' value='Add'>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src='./js/modify.js'></script>
</body>

</html>