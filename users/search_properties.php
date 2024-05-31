<?php
// Enable error reporting and display errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "../includes/connection.php";

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    // This is an AJAX request, do nothing
} else {
    // This is an HTTP request, check for doctype
    $doc_start = file_get_contents($_SERVER['DOCUMENT_ROOT'] . $_SERVER['PHP_SELF'], NULL, NULL, 0, 100); // Read more content
    if (stripos($doc_start, '<!DOCTYPE html>') !== false) {
        die("Error: <!DOCTYPE html> tag found. This script should not be executed within an HTML document.");
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" href="../images/proptokart-black.png" type="image/x-icon">
    <meta charset="UTF-8">
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
    <title>Employee Details</title>
    <link rel="stylesheet" href="../proptokart_css/header.css">
    <link rel="stylesheet" href="../CDNs/materialize.min.css">
    <link rel="stylesheet" type="text/css" href="../CDNs/slick.min.css" />
    <link rel="stylesheet" type="text/css" href="../CDNs/slick-theme.min.css" />
    <link rel="stylesheet" href="../proptokart_css/search_prop.css">
    <link rel="stylesheet" href="../CDNs/googleapis.css">
    <link rel="stylesheet" href="../CDNs/font-awesome.all.min.css" />

    <style>
        .filter-container {
            position: absolute;
            top: 0px;
            left: 0;
            width: 300px;
            /* height: calc(100vh - 100px); */
            /* Adjust as necessary */
            background-color: #ffffff;
            /* overflow-y: auto; */
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            z-index: 100;
            display: none;
            max-height: 100vh;
            height: 100vh;
        }

        #icon {
            z-index: 10000;
            position: relative;
        }

        header i.fa-solid.fa-plus {
            display: none !important;
        }
    </style>


</head>

<body>
    <header>
        <?php include '../includes/config.php'; ?>
        <?php include INCLUDES_PATH . 'header.php'; ?>
    </header>

    <main>
        <div style="background-color: #ffffff; cursor: pointer;"><abbr title="Filter tab" style="border: none;"><i class="fa-solid fa-bars" id="icon" style="margin: 7rem 1em 0 1em; font-size: 15px;"></i></abbr>
            <div class="filter-container" id="filter-containers">
                <h5 style="text-align: center; border-bottom: 1px solid #868686;">Filter</h5>
                <form action="" method="get" class="filter">
                    <div class="filter-item">
                        <label for="propertyType" class="filter-heading">Property Type:</label><br>
                        <?php
                        // Query to fetch distinct property types from the employee_data table
                        $sql_propertyTypes = "SELECT DISTINCT propertyType FROM employee_data order by propertyType";
                        $result_propertyTypes = $conn->query($sql_propertyTypes);

                        // Check if there are any property types returned
                        if ($result_propertyTypes->num_rows > 0) {
                            // Loop through each row and generate a checkbox for each property type
                            while ($row = $result_propertyTypes->fetch_assoc()) {
                                $propertyType = $row['propertyType']; // Keep spaces in value                      
                                $propertyType_display = str_replace(' ', '', $propertyType);
                                echo "<input type='checkbox' id='property-type-" . $propertyType_display . "' name='propertyType[]' value='" . $propertyType . "'>";
                                echo "<label for='property-type-" . $propertyType_display . "'>" . ucfirst($propertyType) . "</label><br>";
                            }
                        } else {
                            echo "<p>No property types found</p>";
                        }
                        ?>
                    </div>

                    <div class="filter-item">
                        <label for="resi_com_agri_type" class="filter-heading">Type:</label><br>
                        <?php
                        // Query to fetch distinct residential types from the employee_data table
                        $sql_resi_com_agri_types = "SELECT DISTINCT resi_com_agri_type FROM employee_data WHERE resi_com_agri_type IS NOT NULL order by resi_com_agri_type";

                        $result_resi_com_agri_types = $conn->query($sql_resi_com_agri_types);

                        // Check if there are any residential types returned
                        if ($result_resi_com_agri_types->num_rows > 0) {
                            // Loop through each row and generate a checkbox for each residential type
                            while ($row = $result_resi_com_agri_types->fetch_assoc()) {
                                $resi_com_agri_type = $row['resi_com_agri_type']; // Keep spaces in value                      
                                $resi_com_agri_type_display = str_replace(' ', '', $resi_com_agri_type);
                                echo "<input type='checkbox' id='residential-type-" . $resi_com_agri_type . "' name='resi_com_agri_type[]' value='" . $resi_com_agri_type . "'>";
                                echo "<label for='residential-type-" . $resi_com_agri_type . "'>" . ucfirst($resi_com_agri_type) . "</label><br>";
                            }
                        } else {
                            echo "<p>No residential types found</p>";
                        }
                        ?>
                    </div>

                    <div class="filter-item">
                        <label for="state" class="filter-heading">State:</label><br>
                        <?php
                        // Query to fetch distinct states from the employee_data table
                        $sql_states = "SELECT DISTINCT state FROM employee_data WHERE state IS NOT NULL order by state";
                        $result_states = $conn->query($sql_states);

                        // Check if there are any states returned
                        if ($result_states->num_rows > 0) {
                            // Loop through each row and generate a checkbox for each state
                            while ($row = $result_states->fetch_assoc()) {
                                $state = $row['state']; // Keep spaces in value                      
                                $state_display = str_replace(' ', '', $state);
                                echo "<input type='checkbox' id='state-" . $state_display . "' name='state[]' value='" . $state . "'>";
                                echo "<label for='state-" . $state_display . "'>" . ucfirst($state) . "</label><br>";
                            }
                        } else {
                            echo "<p>No states found</p>";
                        }
                        ?>
                    </div>

                    <div class="filter-item">
                        <label for="city" class="filter-heading">City:</label><br>
                        <?php
                        // Query to fetch distinct cities from the employee_data table
                        $sql_cities = "SELECT DISTINCT city FROM employee_data WHERE city IS NOT NULL order by city";
                        $result_cities = $conn->query($sql_cities);

                        // Check if there are any cities returned
                        if ($result_cities->num_rows > 0) {
                            // Loop through each row and generate a checkbox for each city
                            while ($row = $result_cities->fetch_assoc()) {
                                $city = $row['city']; // Keep spaces in value                      
                                $city_display = str_replace(' ', '', $city);
                                echo "<input type='checkbox' id='city-" . $city_display . "' name='city[]' value='" . $city . "'>";
                                echo "<label for='city-" . $city_display . "'>" . ucfirst($city_display) . "</label><br>";
                            }
                        } else {
                            echo "<p>No cities found</p>";
                        }
                        ?>
                    </div>

                    <div class="filter-item">
                        <label for="address" class="filter-heading">Nearest Zone:</label><br>
                        <?php
                        // Query to fetch distinct states from the employee_data table
                        $sql_addresss = "SELECT DISTINCT address FROM employee_data WHERE address IS NOT NULL order by address";
                        $result_addresss = $conn->query($sql_addresss);

                        // Check if there are any addresss returned
                        if ($result_addresss->num_rows > 0) {
                            // Loop through each row and generate a checkbox for each address
                            while ($row = $result_addresss->fetch_assoc()) {
                                $address = $row['address']; // Keep spaces in value                      
                                $address_display = str_replace(' ', '', $address);
                                echo "<input type='checkbox' id='address-" . $address_display . "' name='address[]' value='" . $address . "'>";
                                echo "<label for='address-" . $address_display . "'>" . ucfirst($address) . "</label><br>";
                            }
                        } else {
                            echo "<p>No Nearest Zone found</p>";
                        }
                        ?>
                    </div>

                    <div class="filter-item">
                        <label for="property" class="filter-heading">Property:</label><br>
                        <?php
                        // Query to fetch distinct properties from the employee_data table
                        $sql_properties = "SELECT DISTINCT property FROM employee_data order by property";
                        $result_properties = $conn->query($sql_properties);

                        // Check if there are any properties returned
                        if ($result_properties->num_rows > 0) {
                            // Loop through each row and generate a checkbox for each property
                            while ($row = $result_properties->fetch_assoc()) {
                                $property = $row['property']; // Keep spaces in value                      
                                $property_display = str_replace(' ', '', $property);
                                echo "<input type='checkbox' id='property-" . $property_display . "' name='property[]' value='" . $property . "'>";
                                echo "<label for='property-" . $property_display . "'>" . ucfirst($property_display) . "</label><br>";
                            }
                        } else {
                            echo "<p>No properties found</p>";
                        }
                        ?>
                    </div>

                    <div class="filter-item">
                        <label for="property_furnish" class="filter-heading">Property Furnish:</label><br>
                        <?php
                        // Query to fetch distinct property furnish types from the employee_data table
                        $sql_property_furnish = "SELECT DISTINCT property_furnish FROM employee_data WHERE property_furnish IS NOT NULL order by property_furnish";
                        $result_property_furnish = $conn->query($sql_property_furnish);

                        // Check if there are any property furnish types returned
                        if ($result_property_furnish->num_rows > 0) {
                            // Loop through each row and generate a checkbox for each property furnish type
                            while ($row = $result_property_furnish->fetch_assoc()) {
                                $property_furnish = $row['property_furnish']; // Keep spaces in value                      
                                $property_furnish_display = str_replace(' ', '', $property_furnish);
                                echo "<input type='checkbox' id='property-furnish-" . $property_furnish_display . "' name='property_furnish[]' value='" . $property_furnish . "'>";
                                echo "<label for='property-furnish-" . $property_furnish_display . "'>" . ucfirst($property_furnish) . "</label><br>";
                            }
                        } else {
                            echo "<p>No property furnish types found</p>";
                        }
                        ?>
                    </div>

                    <div class="filter-item">
                        <label for="parking" class="filter-heading">Parking:</label><br>
                        <?php
                        // Query to fetch distinct property furnish types from the employee_data table
                        $sql_parking = "SELECT DISTINCT parking FROM employee_data WHERE parking IS NOT NULL order by parking";
                        $result_parking = $conn->query($sql_parking);

                        // Check if there are any property furnish types returned
                        if ($result_parking->num_rows > 0) {
                            // Loop through each row and generate a checkbox for each property furnish type
                            while ($row = $result_parking->fetch_assoc()) {
                                $parking = $row['parking']; // Keep spaces in value                      
                                $parking_display = str_replace(' ', '', $parking);
                                echo "<input type='checkbox' id='property-furnish-" . $parking_display . "' name='parking[]' value='" . $parking . "'>";
                                echo "<label for='property-furnish-" . $parking_display . "'>" . ucfirst($parking) . "</label><br>";
                            }
                        } else {
                            echo "<p>No property furnish types found</p>";
                        }
                        ?>
                    </div>

                    <div class="filter-item">
                        <label for="min_price" class="filter-heading">Minimum Price:</label>
                        <input type="number" id="min_price" name="min_price" placeholder="Enter min. price">
                    </div>

                    <div class="filter-item">
                        <label for="max_price" class="filter-heading">Maximum Price:</label>
                        <input type="number" id="max_price" name="max_price" placeholder="Enter max. price">
                    </div>

                    <div class="filter-item">
                        <label for="rooms" class="filter-heading">Number of Rooms:</label><br>
                        <?php
                        // Query to fetch distinct states from the employee_data table
                        $sql_rooms = "SELECT DISTINCT rooms FROM employee_data where rooms > 0 order by rooms";
                        $result_rooms = $conn->query($sql_rooms);

                        // Check if there are any states returned
                        if ($result_rooms->num_rows > 0) {
                            // Loop through each row and generate a checkbox for each state
                            while ($row = $result_rooms->fetch_assoc()) {
                                $rooms = $row['rooms']; // Keep spaces in value                      
                                $rooms_display = str_replace(' ', '', $rooms);
                                echo "<input type='checkbox' id='rooms-" . $rooms_display . "' name='rooms[]' value='" . $rooms . "'>";
                                echo "<label for='rooms-" . $rooms_display . "'>" . ucfirst($rooms) . " Rooms</label><br>";
                            }
                        }

                        ?>
                    </div>

                    <div class="filter-item">
                        <label for="baths" class="filter-heading">Number of baths:</label><br>
                        <?php
                        // Query to fetch distinct states from the employee_data table
                        $sql_baths = "SELECT DISTINCT baths FROM employee_data where baths > 0  order by baths";
                        $result_baths = $conn->query($sql_baths);

                        // Check if there are any states returned
                        if ($result_baths->num_rows > 0) {
                            // Loop through each row and generate a checkbox for each state
                            while ($row = $result_baths->fetch_assoc()) {
                                $baths = $row['baths']; // Keep spaces in value                      
                                $baths_display = str_replace(' ', '', $baths);
                                echo "<input type='checkbox' id='baths-" . $baths_display . "' name='baths[]' value='" . $baths . "'>";
                                echo "<label for='baths-" . $baths_display . "'>" . ucfirst($baths) . " Baths</label><br>";
                            }
                        }

                        ?>
                    </div>

                    <input type="submit" name="filter" value="Apply Filter" class="filter_submit">
                </form>
            </div>

        </div>

        <div class="right main-content" style="padding: 5% 2% 2% 0;">
            <div class="row">
                <div class="backgroun-gradiant" style="top:18%; right:0"></div>
                <div class="backgroun-gradiant" style="top:16%; left:-8rem"></div>
                <?php include "./filter.php"; ?>
            </div>
        </div>
    </main>

    <footer>
        <?php include INCLUDES_PATH . '_footer.php'; ?>
    </footer>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>

    <script>
        jQuery(document).ready(function($) {
            //Buy button effects
            $(".buy").on("click", function() {
                //It is possible to put the 1st argument of setTimeout as callback of the Materialize.toast function but that approach seems significantly slower. I don't know why yet
                setTimeout(function() {
                    $("#buy").removeClass("green");
                    $(".buy").fadeOut(100, function() {
                        $(this).text("add_shopping_cart").fadeIn(150);
                    });
                }, 5000);

                $("#buy").addClass("green");
                $(".buy").fadeOut(100, function() {
                    $(this).text("check").fadeIn(150);
                });

                var $toastContent = $(
                    '<div class="flow-text">ORDERED! &nbsp <a href="#" class=" amber-text">MY CART</a></div>'
                );
                Materialize.toast($toastContent, 5000, "rounded");
            });

            //Like button effects

            $(".like").on("click", function() {
                setTimeout(function() {
                    $(".like").fadeOut(100, function() {
                        $(this).text("favorite_border").fadeIn(150);
                    });
                }, 5000);

                $(".like").fadeOut(100, function() {
                    $(this).text("favorite").fadeIn(150);
                });

                var $toastContent2 = $('<div class="flow-text">LIKED!</div>');
                Materialize.toast($toastContent2, 5000, "pink rounded");
            });
        });


        var icon = document.getElementById('icon');
        var filterContainer = document.getElementById('filter-containers');

        icon.addEventListener('click', function() {
            if (filterContainer.style.display === 'block') {
                filterContainer.style.display = 'none';
            } else {
                filterContainer.style.display = 'block';
            }
        });
    </script>

</body>

</html>