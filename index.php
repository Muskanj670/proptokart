<?php

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "includes/connection.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" href="images/proptokart-black.png" type="image/x-icon">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="proptokart_css/new.css">
    <link rel="stylesheet" href="proptokart_css/header.css">
    <link rel="stylesheet" href="proptokart_css/footer.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
    <title>Proptokart - Your Ultimate Destination for Real Estate Solutions</title>
    <style>
        .video-container {
            text-align: center;
            margin: 0 auto;
            padding: 15px;
        }

        .video-container iframe {
            width: 100%;
            height: 261px;
            /* This should be adjusted if you want a different aspect ratio */
            max-width: 600px;
            /* Maximum width of the iframe */
            border: none;
            /* Removes default border */
        }

        @media (min-width: 992px) {
            .video-container {
                width: 33.3333%;
                /* 4 columns out of */
            }
        }
    </style>
</head>

<body>
    <header>
        <?php include 'includes/config.php'; ?>
        <?php include INCLUDES_PATH . 'header.php'; ?>
    </header>
    <main>
        <div class="front">
            <div class="video-background">
                <video autoplay muted loop>
                    <source src="images/3D Visualization _ Al-Hammad City _ 3D Architectural _ housing scheme _ 3D Animation housing society.mp4" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
            <div class="overlay"></div>
            <div class="front-outer-container">
                <div class="front-content">
                    <h1><span class="front-heading">Discover Hidden Gems</span></h1>
                </div>
                <div class="front-icons-container">
                    <div class="front-icons col-l-2 col-m-4 col-s-6 col-xs-12">
                        <form action="users/search_properties.php" method="GET" style="display: inline;">
                            <input type="hidden" name="property[]" value="rent">
                            <button type="submit" name="filter" value="Apply Filter" style="border: none; background: none; display: flex; flex-direction: column; align-items: center;">
                                <img src="favicon/icons8-rent-50.png" alt="" srcset="">
                                <span>Rent</span>
                            </button>
                        </form>
                    </div>
                    <div class="front-icons col-l-2 col-m-4 col-s-6 col-xs-12">
                        <form action="users/search_properties.php" method="GET" style="display: inline;">
                            <input type="hidden" name="property[]" value="buy">
                            <button type="submit" name="filter" value="Apply Filter" style="    border: none; background: none; display: flex; flex-direction: column; align-items: center;">
                                <img src="favicon/icons8-property-50.png" alt="" srcset="">
                                <span>Buy</span>
                            </button>
                        </form>
                    </div>
                    <div class="front-icons col-l-2 col-m-4 col-s-6 col-xs-12">
                        <form action="users/search_properties.php" method="GET" style="display: inline;">
                            <input type="hidden" name="propertyType[]" value="commercial">
                            <button type="submit" name="filter" value="Apply Filter" style=" border: none; background: none; display: flex; flex-direction: column; align-items: center;">
                                <img src="favicon/icons8-commercial-50.png" alt="" srcset="">
                                <span>Commercial</span>
                            </button>
                        </form>
                    </div>
                    <div class="front-icons col-l-2 col-m-4 col-s-6 col-xs-12">
                        <form action="users/search_properties.php" method="GET" style="display: inline;">
                            <input type="hidden" name="propertyType[]" value="agriculture">
                            <button type="submit" name="filter" value="Apply Filter" style=" border: none; background: none; display: flex; flex-direction: column; align-items: center;">
                                <img src="favicon/icons8-geography-book-50.png" alt="" srcset="">
                                <span>Plots / Land</span>
                            </button>
                        </form>
                    </div>

                    <div class="front-icons col-l-2 col-m-4 col-s-6 col-xs-12">
                        <a href="./users/projects.php">
                            <img src="favicon/icons8-projects-50.png" alt="" srcset="">
                            <span>Projects</span>
                        </a>
                    </div>

                    <div class="front-icons col-l-2 col-m-4 col-s-6 col-xs-12">
                        <a href="#">
                            <img src="favicon/icons8-feedback-50.png" alt="" srcset="">
                            <span>Testimonial</span>
                        </a>
                    </div>
                </div>
                <div class="search">
                    <form method="GET" action="./users/search_properties.php">

                        <div style="width: -webkit-fill-available;color: white;">
                            <div class="select-menu">
                                <div class="radio-group">
                                    <?php
                                    // Query to fetch property types from the database
                                    $sql = "SELECT DISTINCT property FROM employee_data";
                                    $result = $conn->query($sql);

                                    // Check if there are any property types returned
                                    if ($result->num_rows > 0) {
                                        // Loop through each row and display property type as a radio button
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<label class='radio-label'><input type='radio' name='property' value='" . $row['property'] . "' class='radio-input'>" . ucfirst($row['property']) . "</label>";
                                        }
                                    } else {
                                        echo "<span>No property types found</span>";
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div style="display: flex; flex-wrap: wrap;background: white;">
                            <div class="select-menu col-l-3 col-m-6 col-s-12 col-xs-12">
                                <select name="propertyType" class="select">
                                    <option value="" selected disabled class="options-list">Select Property Type
                                    </option>
                                    <?php
                                    // Query to fetch cities from the employee_data table
                                    $sql = "SELECT DISTINCT propertyType FROM employee_data";
                                    $result = $conn->query($sql);

                                    // Check if there are any cities returned
                                    if ($result->num_rows > 0) {
                                        // Loop through each row and display city as an option
                                        while ($row = $result->fetch_assoc()) {
                                            $propertyType = $row['propertyType']; // Keep spaces in value
                                            $propertyType_display = str_replace(' ', '', $propertyType); // Remove spaces from display text
                                            echo "<option value='" . $propertyType_display . "' class='options-list' style='padding-top: 5px; padding-bottom: 5px;'>" . ucfirst($propertyType) . "</option>";
                                        }
                                    } else {
                                        echo "<option>No Property Type found</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="select-menu col-l-3 col-m-6 col-s-12 col-xs-12">
                                <select name="resi_com_agri_type" class="select">
                                    <option value="" selected disabled class="options-list">Select Type
                                    </option>
                                    <?php
                                    // Query to fetch cities from the employee_data table
                                    $sql = "SELECT DISTINCT resi_com_agri_type FROM employee_data WHERE resi_com_agri_type IS NOT NULL";
                                    $result = $conn->query($sql);

                                    // Check if there are any cities returned
                                    if ($result->num_rows > 0) {
                                        // Loop through each row and display city as an option
                                        while ($row = $result->fetch_assoc()) {
                                            $resi_com_agri_type = $row['resi_com_agri_type']; // Keep spaces in value
                                            $resi_com_agri_type_display = str_replace(' ', '', $resi_com_agri_type); // Remove spaces from display text
                                            echo "<option value='" . $resi_com_agri_type . "' class='options-list' style='padding-top: 5px; padding-bottom: 5px;'>" . ucfirst($resi_com_agri_type) . "</option>";
                                        }
                                    } else {
                                        echo "<option>No Type found</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="select-menu col-l-3 col-m-6 col-s-12 col-xs-12">
                                <select name="city" class="select" required>
                                    <option value="" selected disabled class="options-list">Select Indian City</option>
                                    <?php
                                    // Query to fetch cities from the employee_data table
                                    $sql = "SELECT DISTINCT city FROM employee_data";
                                    $result = $conn->query($sql);

                                    // Check if there are any cities returned
                                    if ($result->num_rows > 0) {
                                        // Loop through each row and display city as an option
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<option value='" . $row['city'] . "' class='options-list'>" . ucfirst($row['city']) . "</option>";
                                        }
                                    } else {
                                        echo "<option>No cities found</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-l-1 col-m-2 col-s-4 col-xs-4">
                                <div class="select button-select"><i class="fa-solid fa-sliders"></i></div>
                            </div>
                            <div class="select-menu button col-l-2 col-m-4 col-s-8 col-xs-8">
                                <button type="submit" name="search" class="btn btn-outline-warning select button-select">Submit</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        <div class="neighborhoods recent_uploaded">
            <div class="neighborhoods-heading">
                <h2 style="    font-size: 35px;
    font-weight: 600;
    line-height: 1.2em;
    letter-spacing: -.03em;">Explore Newly Listed Properties: Residential, Commercial, and Agricultural</h2>
                <p style="font-family: Hind, sans-serif;
    font-weight: 400;
    font-size: 18px;
    line-height: 23px; color: #6f6e6e">Stay ahead with our latest listings of homes, offices, and farmland. Discover
                    fresh opportunities tailored to your residential, commercial, and agricultural needs.</p>
            </div>

            <div class="pictures">
                <?php
                // Include your database connection
                // include "./includes/connection.php";

                // Fetch images for properties for rent
                $sql = "SELECT et.image_data, ed.resi_com_agri_type,ed.propertyType, ed.address , ed.city, ed.state, ed.postal_code, ed.property
                        FROM employee_thumbnail et 
                        JOIN employee_data ed ON et.user_id = ed.id 
                        ORDER BY et.user_id DESC limit 3";

                // Prepare statement
                $stmt = $conn->prepare($sql);

                // Check if statement preparation succeeded
                if (!$stmt) {
                    echo "<p>Error: " . $conn->error . "</p>";
                } else {
                    // Execute statement
                    $stmt->execute();

                    // Bind result variables
                    $stmt->bind_result($image_data, $resi_com_agri_type, $propertyType, $address, $city, $state, $postal_code, $property);

                    // Fetch and display results
                    while ($stmt->fetch()) {

                        // < class="front-icons col-l-2 col-m-4 col-s-6 col-xs-12" style="width: 100%; display: flex; justify-content: center;">
                        echo "<form action='users/search_properties.php' method='GET' style='display: inline;'>
                        <input type='hidden' name='property[]' value=" . htmlspecialchars($property) . ">
                        <input type='hidden' name='propertyType[]' value=" . htmlspecialchars($propertyType) . ">
                                <button type='submit' name='filter' value='Apply Filter' style=' border: none; background: none; display: flex; flex-direction: column; align-items: center;'>";

                        echo "<a href='#'>";
                        echo "<figure>";
                        echo "<img src='data:image/jpeg;base64," . base64_encode($image_data) . "' alt=''>";
                        echo "<figcaption>";
                        echo "<h4 style = 'text-transform: capitalize;'>" . htmlspecialchars($propertyType) . " " . htmlspecialchars($resi_com_agri_type) . " FOR " . htmlspecialchars($property) . "</h4>";
                        echo htmlspecialchars($address) . " , " . htmlspecialchars($city) . " , " . htmlspecialchars($state) . " , " . htmlspecialchars($postal_code);
                        echo "</figcaption>";
                        echo "</figure>";
                        echo "</a>";

                        echo "</button>
                            </form>";
                    }

                    // Close statement
                    $stmt->close();
                }

                // Close connection
                // $conn->close();
                ?>
            </div>

            <div class="front-icons col-l-2 col-m-4 col-s-6 col-xs-12 see_more_container">
                <form action="users/search_properties.php" method="GET" style="display: inline;">
                    <button type="submit" name="filter" value="Apply Filter" class="see_more_button">
                        <span class="see_more_span">See More -></span>
                    </button>
                </form>
            </div>
        </div>

        <div class="offers">
            <div class="offer-heading">
                <h2 style=" font-size: 35px; font-weight: 600; line-height: 1.2em; letter-spacing: -.03em;">
                    Exclusive Weeklong Offers</h2>
                <p style="font-family: Hind, sans-serif; font-weight: 400; font-size: 18px; line-height: 23px; color: #6f6e6e">
                    Uncover this week's exclusive offers! Dive into a world of savings on top-notch products. Hurry,
                    grab them before they're gone!</p>
            </div>

            <div class="videos">
                <?php
                // Fetch YouTube links for properties
                $sql = "SELECT youtube_link FROM employee_data ORDER BY id DESC LIMIT 3";

                // Prepare statement
                $stmt = $conn->prepare($sql);

                // Check if statement preparation succeeded
                if (!$stmt) {
                    echo "<p>Error: " . $conn->error . "</p>";
                } else {
                    // Execute statement
                    $stmt->execute();

                    // Bind result variables
                    $stmt->bind_result($youtube_link);

                    // Fetch and display results
                    while ($stmt->fetch()) {
                        echo "<div class='col-l-4 col-m-6 col-s-12 video-container' style = 'text-align: center;'>
                                <iframe src='" . $youtube_link . "' title='YouTube video player' frameborder='0'
                                allow='accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share'
                                referrerpolicy='strict-origin-when-cross-origin' allowfullscreen></iframe>
                              </div>";
                    }
                    // Close statement
                    $stmt->close();
                }
                ?>
            </div>



            <div style="margin-top: 1.5%; text-align: center;">
                <a href="https://youtube.com/@proptokart?si=zsnm6VJYzPePFIJG" class="see_more_button" style=" width: fit-content; text-align: center; padding: 12px 14px; "><span class="see_more_span">
                        See More -></span></a>
            </div>


        </div>

        <div class="how-it-works">
            <div class="how-it-works-heading">
                <h2 style="    font-size: 35px;
    font-weight: 600;
    line-height: 1.2em;
    letter-spacing: -.03em;">Discover the Process</h2>
                <p style="font-family: Hind, sans-serif;
    font-weight: 400;
    font-size: 18px;
    line-height: 23px; color: #6f6e6e">Explore the intricate steps involved in our real estate
                    procedures, meticulously designed to guide you seamlessly through your property journey.</p>
            </div>
            <div class="how-it-works-container">
                <div class="works-images col-s-12 col-m-4 col-l-4">
                    <img src="images/home-process-img-1.png" alt="" srcset="">
                    <h3>Search and Select</h3>
                    <p>Begin your journey by browsing through our extensive property listings. Filter by location,
                        price, and property type to find options that best match your preferences.</p>
                </div>
                <div class="works-images col-s-12 col-m-4 col-l-4">
                    <img src="images/home-process-img-2.png" alt="" srcset="">
                    <h3>Schedule a Viewing</h3>
                    <p>Once you've shortlisted properties, schedule a visit to see them in person. Our team will arrange
                        tours at your convenience to help you evaluate each option firsthand.</p>
                </div>
                <div class="works-images col-s-12 col-m-4 col-l-4">
                    <img src="images/home-process-img-3.png" alt="" srcset="">
                    <h3>Secure Your Deal</h3>
                    <p>After selecting your ideal property, we'll guide you through the negotiation, paperwork, and
                        financing process to ensure a smooth and secure transaction.</p>
                </div>
            </div>
        </div>
        <div class="suggest">
            <p>Don’t see the city you were looking for? Help us out by suggesting!</p>
            <button>Suggest City</button>
        </div>
        <div class="neighborhoods recent_for_rent">
            <div class="neighborhoods-heading">
                <h2 style="    font-size: 35px;
    font-weight: 600;
    line-height: 1.2em;
    letter-spacing: -.03em;">Discover Premier Rental Properties: Homes, Offices, and Farmland</h2>
                <p style="font-family: Hind, sans-serif;
    font-weight: 400;
    font-size: 18px;
    line-height: 23px; color: #6f6e6e">Find the perfect rental property with our selection of premier homes, offices,
                    and farmland. Discover diverse options that cater to all your residential, commercial, and
                    agricultural needs.</p>
            </div>

            <div class="pictures">
                <?php
                // Include your database connection
                // include "./includes/connection.php";

                // Fetch images for properties for buy
                $sql = "SELECT ed.propertyType, et.image_data, ed.resi_com_agri_type, ed.address, ed.city, ed.state, ed.postal_code
        FROM employee_thumbnail et 
        JOIN employee_data ed ON et.user_id = ed.id 
        WHERE ed.property = 'rent' 
        GROUP BY ed.propertyType
        ORDER BY ed.propertyType DESC;";

                // Prepare statement
                $stmt = $conn->prepare($sql);

                // Check if statement preparation succeeded
                if (!$stmt) {
                    echo "<p>Error: " . $conn->error . "</p>";
                } else {
                    // Execute statement
                    $stmt->execute();

                    // Bind result variables
                    $stmt->bind_result($propertyType, $image_data, $resi_com_agri_type, $address, $city, $state, $postal_code);

                    // Fetch and display results
                    while ($stmt->fetch()) {
                        echo "<form action='users/search_properties.php' method='GET' style='display: inline;'>
                        <input type='hidden' name='property[]' value='rent'>
                        <input type='hidden' name='propertyType[]' value=" . htmlspecialchars($propertyType) . ">
                                <button type='submit' name='filter' value='Apply Filter' style=' border: none; background: none; display: flex; flex-direction: column; align-items: center;'>";
                        echo "<a href='#'>";
                        echo "<figure>";
                        echo "<img src='data:image/jpeg;base64," . base64_encode($image_data) . "' alt=''>";
                        echo "<figcaption>";
                        echo "<h4>" . htmlspecialchars($propertyType) . " " . htmlspecialchars($resi_com_agri_type) . "</h4>";
                        echo htmlspecialchars($address) . " , " . htmlspecialchars($city) . " , " . htmlspecialchars($state) . " , " . htmlspecialchars($postal_code);
                        echo "</figcaption>";
                        echo "</figure>";
                        echo "</a>";
                        echo "</button>
                        </form>";
                    }

                    // Close statement
                    $stmt->close();
                }

                // Close connection
                // $conn->close();
                ?>
            </div>

            <div class="front-icons col-l-2 col-m-4 col-s-6 col-xs-12 see_more_container">
                <form action="users/search_properties.php" method="GET" style="display: inline;">
                    <input type="hidden" name="property[]" value="rent">
                    <button type="submit" name="filter" value="Apply Filter" class="see_more_button">
                        <!-- <img src="favicon/icons8-property-50.png" alt="" srcset=""> -->
                        <span class="see_more_span">See More -></span>
                    </button>
                </form>
            </div>
        </div>
        <div class="neighborhoods recent_for_buy">
            <div class="neighborhoods-heading">
                <h2 style="font-size: 35px; font-weight: 600; line-height: 1.2em; letter-spacing: -.03em;">
                    Find Your Dream Property: Homes, Offices, and Farmlands
                </h2>
                <p style="font-family: Hind, sans-serif; font-weight: 400; font-size: 18px; line-height: 23px; color: #6f6e6e">
                    Discover a diverse selection of dream homes, office spaces, and farmlands tailored to your unique
                    needs.
                </p>
            </div>

            <div class="pictures">
                <?php
                // Include your database connection
                // include "./includes/connection.php";

                // Fetch images for properties for buy
                $sql = "SELECT ed.propertyType, et.image_data, ed.resi_com_agri_type, ed.address, ed.city, ed.state, ed.postal_code
        FROM employee_thumbnail et 
        JOIN employee_data ed ON et.user_id = ed.id 
        WHERE ed.property = 'buy' 
        GROUP BY ed.propertyType
        ORDER BY ed.propertyType DESC;";

                // Prepare statement
                $stmt = $conn->prepare($sql);

                // Check if statement preparation succeeded
                if (!$stmt) {
                    echo "<p>Error: " . $conn->error . "</p>";
                } else {
                    // Execute statement
                    $stmt->execute();

                    // Bind result variables
                    $stmt->bind_result($propertyType, $image_data, $resi_com_agri_type, $address, $city, $state, $postal_code);

                    // Fetch and display results
                    while ($stmt->fetch()) {
                        echo "<form action='users/search_properties.php' method='GET' style='display: inline;'>
                        <input type='hidden' name='property[]' value=" . htmlspecialchars($property) . ">
                        <input type='hidden' name='propertyType[]' value=" . htmlspecialchars($propertyType) . ">
                                <button type='submit' name='filter' value='Apply Filter' style=' border: none; background: none; display: flex; flex-direction: column; align-items: center;'>";
                        echo "<a href='#'>";
                        echo "<figure>";
                        echo "<img src='data:image/jpeg;base64," . base64_encode($image_data) . "' alt=''>";
                        echo "<figcaption>";
                        echo "<h4>" . htmlspecialchars($propertyType) . " " . htmlspecialchars($resi_com_agri_type) . "</h4>";
                        echo htmlspecialchars($address) . " , " . htmlspecialchars($city) . " , " . htmlspecialchars($state) . " , " . htmlspecialchars($postal_code);
                        echo "</figcaption>";
                        echo "</figure>";
                        echo "</a>
                        </button>
                        </form>";
                    }

                    // Close statement
                    $stmt->close();
                }

                // Close connection
                // $conn->close();
                ?>
            </div>
            <div class="front-icons col-l-2 col-m-4 col-s-6 col-xs-12 see_more_container">
                <form action="users/search_properties.php" method="GET" style="display: inline;">
                    <input type="hidden" name="property[]" value="buy">
                    <button type="submit" name="filter" value="Apply Filter" class="see_more_button">
                        <!-- <img src="favicon/icons8-property-50.png" alt="" srcset=""> -->
                        <span class="see_more_span">See More -></span>
                    </button>
                </form>
            </div>
        </div>
        <div class="special-offer">
            <div class="special-offer-heading">
                <h2 style="    font-size: 35px;
    font-weight: 600;
    line-height: 1.2em;
    letter-spacing: -.03em;">Daily Real Estate Deals: Exclusive Offers Await!</h2>
                <p style="font-family: Hind, sans-serif;
    font-weight: 400;
    font-size: 18px;
    line-height: 23px; color: #6f6e6e">Unlock Special Offers Daily: Your Premier Destination for
                    Real Estate Deals</p>
            </div>
            <div class="special-offer-container">
                <p> invites you to delve into a realm of exclusive opportunities in the real estate market. With a
                    commitment to delivering unparalleled value, our platform presents a diverse array of properties and
                    investment options tailored to your preferences. Explore daily deals meticulously curated to elevate
                    your property journey, ensuring you find the perfect match for your needs. Join us and seize the
                    chance to discover your dream property amidst a plethora of enticing offerings.</p>
                <button>Purchase</button>
            </div>
        </div>
        <div class="every-day">
            <div class="every-day-heading">
                <h2 style="    font-size: 35px;
    font-weight: 600;
    line-height: 1.2em;
    letter-spacing: -.03em;">Daily Dream Deals: Explore Your Perfect Property Now!</h2>
                <p style="font-family: Hind, sans-serif;
    font-weight: 400;
    font-size: 18px;
    line-height: 23px; color: #6f6e6e">Find your dream property with our daily deals. Explore now!</p>
            </div>
            <div class="slideshow-container">
                <div class="mySlides fade">
                    <div class="every-day-slide">
                        <div class="every-day-col col-l-6 col-m-6 col-s-12 col-xs-12">
                            <img src="images/h1-testimonials-1.png" alt="" srcset="">
                            <p>"Duis autem vel eum iriure dolor in hendrerit in vulpu velit esse molestie conse quat,
                                vel illum dolore blandit praesent lupta tum del enit augue dolore lorem litte rarum"</p>
                        </div>
                        <div class="every-day-col col-l-6 col-m-6 col-s-12 col-xs-12">
                            <img src="images/h1-testimonials-2.png" alt="" srcset="">
                            <p>"Duis autem vel eum iriure dolor in hendrerit in vulpu velit esse molestie conse quat,
                                vel illum dolore blandit praesent lupta tum del enit augue dolore lorem litte rarum"</p>
                        </div>
                    </div>

                </div>
                <div class="mySlides fade">
                    <div class="every-day-slide">

                        <div class="every-day-col col-l-6 col-m-6 col-s-12 col-xs-12">
                            <img src="images/h1-testimonials-3.png" alt="" srcset="">
                            <p>"Duis autem vel eum iriure dolor in hendrerit in vulpu velit esse molestie conse quat,
                                vel
                                illum dolore blandit praesent lupta tum del enit augue dolore lorem litte rarum"</p>
                        </div>
                        <div class="every-day-col col-l-6 col-m-6 col-s-12 col-xs-12">
                            <img src="images/h1-testimonials-4.png" alt="" srcset="">
                            <p>"Duis autem vel eum iriure dolor in hendrerit in vulpu velit esse molestie conse quat,
                                vel
                                illum dolore blandit praesent lupta tum del enit augue dolore lorem litte rarum"</p>
                        </div>

                    </div>
                </div>
                <div style="text-align:center;">

                    <span class="dot"></span>
                    <span class="dot"></span>

                </div>
                <a class="prev" onclick="prevSlide()">&#10094;</a>
                <a class="next" onclick="showSlides()">&#10095;</a>
            </div>
        </div>
        <div class="suggest-2">
            <p>Don’t see the city you were looking for? Help us out by suggesting!</p>
            <button>Suggest City</button>
        </div>
    </main>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/script.js"></script>
</body>

<footer>
    <?php include 'includes/config.php'; ?>
    <?php include INCLUDES_PATH . '_footer.php'; ?>
</footer>
<?php include "./includes/icons.html"; ?>

</html>