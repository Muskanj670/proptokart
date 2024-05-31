<?php
// Check if the ID parameter exists in the URL
if (isset($_GET['id'])) {
    // Retrieve the ID from the URL parameter
    $id = $_GET['id'];
    include "../includes/connection.php";
    $sql_details = "SELECT property, propertyType, resi_com_agri_type, address, state, postal_code, property_furnish, rooms, baths, parking, carpet_area, carpet_area_unit, price, price_unit, per, shopNo, city,date, youtube_link,note FROM employee_data WHERE id = ?";
    $stmt_details = $conn->prepare($sql_details);

    $stmt_details->bind_param("i", $id);
    $stmt_details->execute();
    $stmt_details->store_result();
    $stmt_details->bind_result($property, $propertyType, $resi_com_agri_type, $address, $state, $postal_code, $property_furnish, $rooms, $baths, $parking, $carpet_area, $carpet_area_unit, $price, $price_unit, $per, $shopNo, $city, $date, $youtube_link, $note);

    // Fetch data from employee_data
    $stmt_details->fetch();

?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
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
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Proptokart - More Details about the Property</title>
        <link rel="stylesheet" href="../proptokart_css/more_details.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="..CDNs/leaflet.css" />
        <style>
            #map {
                height: 35vh;
                width: 94%;
                position: absolute;
                margin: 2% 2% 0 0;
            }
        </style>
    </head>

    <body>
        <header>
            <?php include '../includes/config.php'; ?>
            <?php include INCLUDES_PATH . 'header.php'; ?>
        </header>
        <main>
            <div class="backgroun-gradiant" style="top:50%; right:0"></div>
            <div class="backgroun-gradiant" style="top:0%; left:-8rem"></div>

            <div class="product-heading" id="listing-home">
                <div class="row align-items-end">
                    <div class="col-lg-8">
                        <div class="product-condition">
                            <span class="listing-type-badge">
                                <?php echo $property; ?> </span>
                            <div class="rtcl-listing-badge-wrap"><span class="badge rtcl-badge-popular popular-badge badge-success">Popular</span><span class="badge rtcl-badge-_top">Top</span></div>
                        </div>

                        <!--TODO: Listing Title Condition may be change -->
                        <h2 class="product-title"><?php echo $propertyType . " " . $resi_com_agri_type; ?></h2>

                        <div class="header-info">
                            <ul class="entry-meta">
                                <li>
                                    <i class="fas fa-map-marker-alt"></i><?php echo $address; ?><span class="rtcl-delimiter">,</span>
                                    <?php echo $city; ?><span class="rtcl-delimiter">,</span>
                                    <?php echo $state; ?><span class="rtcl-delimiter">,</span>
                                    <?php echo $postal_code; ?>
                                </li>
                                <?php
                                // Your specific date
                                $specific_date = strtotime($date); // Change this to your specific date

                                // Today's date
                                $current_date = time();

                                // Calculate the difference in seconds
                                $seconds_diff = $current_date - $specific_date;

                                // Define time intervals in seconds
                                $minute = 60;
                                $hour = $minute * 60;
                                $day = $hour * 24;
                                $week = $day * 7;
                                $month = $day * 30;
                                $year = $day * 365;

                                // Calculate time difference
                                if ($seconds_diff < $minute) {
                                    $time_diff = "just now";
                                } elseif ($seconds_diff < $hour) {
                                    $time_diff = floor($seconds_diff / $minute) . " minutes ago";
                                } elseif ($seconds_diff < $day) {
                                    $hours_diff = floor($seconds_diff / $hour);
                                    $time_diff = ($hours_diff == 1) ? "an hour ago" : "$hours_diff hours ago";
                                } elseif ($seconds_diff < $week) {
                                    $days_diff = floor($seconds_diff / $day);
                                    $time_diff = ($days_diff == 1) ? "yesterday" : "$days_diff days ago";
                                } elseif ($seconds_diff < $month) {
                                    $weeks_diff = floor($seconds_diff / $week);
                                    $time_diff = ($weeks_diff == 1) ? "a week ago" : "$weeks_diff weeks ago";
                                } elseif ($seconds_diff < $year) {
                                    $months_diff = floor($seconds_diff / $month);
                                    $time_diff = ($months_diff == 1) ? "a month ago" : "$months_diff months ago";
                                } else {
                                    $years_diff = floor($seconds_diff / $year);
                                    $time_diff = ($years_diff == 1) ? "a year ago" : "$years_diff years ago";
                                }

                                echo "<li><i class='far fa-clock'></i> $time_diff</li>";
                                ?>

                                <li>
                                    <i class="far fa-eye"></i>Views: <span>1,404</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-4 product-price-wrap">
                        <div class="product-price">
                            <div class="rtcl-price price-type-fixed"><span class="rtcl-price-amount amount"><bdi><span class="rtcl-price-currencySymbol">₹</span><?php echo $price . $price_unit . "/" . $per; ?></bdi></span><span class="rtcl-price-meta"><span class="rtcl-price-type-label rtcl-price-type-fixed">(Fixed)</span></span></div>
                        </div>

                        <div class="btn-area">
                            <ul>
                                <li data-toggle="tooltip" data-placement="bottom" data-original-title="Report Abuse">
                                    <a href="javascript:void(0)" class="rtcl-require-login">
                                        <i class="fas fa-bug"></i>
                                    </a>
                                </li>

                                <li data-toggle="tooltip" data-placement="top" data-original-title="Share">
                                    <a href="#" id="share-btn"><abbr title="Share"><i class="fa-solid fa-share"></i></abbr>
                                </li>

                                <li data-toggle="tooltip" data-placement="bottom" data-original-title="Add to Favourite">
                                    <a href="javascript:void(0)" class="rtcl-require-login "><i class="fa-regular fa-heart"></i><span class="favourite-label"></span></a>
                                </li>

                                <li data-toggle="tooltip" data-placement="bottom" data-original-title="Compare">
                                    <a class="rtcl-compare " href="#" data-listing_id="17433">
                                        <i class="fa-solid fa-arrow-right-arrow-left"></i>
                                    </a>
                                </li>

                                <li data-toggle="tooltip" data-placement="bottom" data-original-title="Print">
                                    <a href="#" onclick="window.print();"><i class="fa-solid fa-print"></i></a>
                                </li>
                            </ul>
                            <div class="share-icon">
                                <a class="facebook" href="javascript:void(0)" onclick="share('facebook', '<?php echo $id; ?>')" rel="nofollow">
                                    <abbr title='Share on Facebook'><i class="fab fa-facebook-f social-icon"></i></abbr>
                                </a>

                                <a class="twitter" href="javascript:void(0)" onclick="share('twitter', '<?php echo $id; ?>')" rel="nofollow">
                                    <abbr title='Share on Twitter'><i class="fab fa-twitter social-icon"></i></abbr>
                                </a>

                                <a class="linkedin" href="javascript:void(0)" onclick="share('linkedin', '<?php echo $id; ?>')" rel="nofollow">
                                    <abbr title='Share on LinkedIn'><i class="fab fa-linkedin-in social-icon"></i></abbr>
                                </a>

                                <a class="pinterest" href="javascript:void(0)" onclick="share('pinterest', '<?php echo $id; ?>')" rel="nofollow">
                                    <abbr title='Share on Pinterest'><i class="fab fa-pinterest social-icon"></i></abbr>
                                </a>

                                <a class="whatsapp" href="javascript:void(0)" onclick="share('whatsapp', '<?php echo $id; ?>')" rel="nofollow">
                                    <abbr title='Share on Whatsapp'><i class="fab fa-whatsapp social-icon"></i></abbr>
                                </a>

                                <a class="copy_link" href="javascript:void(0)" onclick="copyToClipboard(getCurrentURL())" rel="nofollow">
                                    <abbr title='Link copied to clipboard'><i class="fa-solid fa-copy"></i></abbr>
                                </a>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
            </div>
            <div class="media">
                <?php
                $sql_image = "SELECT image_data FROM employee_images WHERE user_id = ?";
                $stmt_image = $conn->prepare($sql_image);
                if (!$stmt_image) {
                    echo "<p>Error: Unable to fetch data.</p>";
                } else {
                    $stmt_image->bind_param("i", $id);
                    $stmt_image->execute();
                    $stmt_image->store_result();
                    $stmt_image->bind_result($image_data);

                    if ($stmt_image->num_rows() > 0) {
                        echo "<div class='gallery col-xl-7 col-l-7 col-m-12 col-s-12 col-xs-12'>";
                        echo "<div class='list'>";
                        $first = true; // Flag to mark the first image in the main list
                        while ($stmt_image->fetch()) {
                            if ($first) {
                                echo "<div class='item active'>";
                                $first = false; // Set the flag to false after the first iteration
                            } else {
                                echo "<div class='item'>";
                            }
                            echo "<img src='data:image/jpeg;base64," . base64_encode($image_data) . "' alt='product-img'>";
                            echo "</div>"; // Close the item div
                        }
                        echo "</div>"; // Close the list div
                        echo "<div class='arrows'>
                            <button id='prev'>&lt;&lt;</button> 
                            <button id='next'>&gt;&gt;</button>
                        </div>";
                        echo "<div class='thumbnail'>";
                        $stmt_image->data_seek(0); // Reset the result set pointer to the beginning
                        $first_thumbnail = true; // Flag to mark the first image in the thumbnail list
                        while ($stmt_image->fetch()) {
                            if ($first_thumbnail) {
                                echo "<div class='item active'>";
                                $first_thumbnail = false; // Set the flag to false after the first iteration
                            } else {
                                echo "<div class='item'>";
                            }
                            // echo "<img src='data:image/jpeg;base64," . base64_encode($image_data) . "' alt='product-img' height='250'>";
                            echo "<i class='fa-solid fa-circle' style = 'font-size: x-large;'></i>";
                            echo "</div>"; // Close the item div
                        }
                        echo "</div>"; // Close the thumbnail div
                        echo "</div>"; // Close the gallery div
                    }
                }
                $stmt_image->close();

                ?>
                <div class="other_details col-xl-5 col-l-5 col-m-12 col-s-12 col-xs-12">
                    <div class="details_container">
                        <div class="heading">
                            <h3>Overview</h3>
                        </div>
                        <div class="details-row">
                            <div class="detail-item col-xl-4 col-l-4 col-m-6 col-s-6 col-xs-6" style="<?php echo empty($id) ? 'display:none;' : ''; ?>">
                                <i class="fa-solid fa-tags"></i>
                                <div>
                                    <span class="key">ID No</span>
                                    <Span class="value"><?php echo $id; ?></Span>
                                </div>
                            </div>
                            <div class="detail-item col-xl-4 col-l-4 col-m-6 col-s-6 col-xs-6" style="<?php echo empty($propertyType) ? 'display:none;' : ''; ?>">

                                <i class="fa-solid fa-house"></i>
                                <div>
                                    <span class="key">Type</span>
                                    <span class="value"><?php echo $propertyType; ?></Span>
                                </div>
                            </div>
                            <div class="detail-item col-xl-4 col-l-4 col-m-6 col-s-6 col-xs-6" style="<?php echo empty($parking) ? 'display:none;' : ''; ?>">

                                <i class="fa-solid fa-square-parking"></i>
                                <div>
                                    <span class="key">Parking</span>
                                    <span class="value"><?php echo $parking; ?></Span>
                                </div>
                            </div>
                            <div class="detail-item col-xl-4 col-l-4 col-m-6 col-s-6 col-xs-6" style="<?php echo empty($rooms) ? 'display:none;' : ''; ?>">

                                <i class="fa-solid fa-bed"></i>
                                <div>
                                    <span class="key">Bedroom</span>
                                    <span class="value"><?php echo $rooms; ?></Span>
                                </div>
                            </div>
                            <div class="detail-item col-xl-4 col-l-4 col-m-6 col-s-6 col-xs-6" style="<?php echo empty($baths) ? 'display:none;' : ''; ?>">

                                <i class="fa-solid fa-sink"></i>
                                <div>
                                    <span class="key">Bath</span>
                                    <span class="value"><?php echo $baths; ?></Span>
                                </div>
                            </div>
                            <div class="detail-item col-xl-4 col-l-4 col-m-6 col-s-6 col-xs-6" style="<?php echo empty($property) ? 'display:none;' : ''; ?>">

                                <i class="fa-solid fa-list-check"></i>
                                <div>
                                    <span class="key">Purpose</span>
                                    <span class="value"><?php echo $property; ?></Span>
                                </div>
                            </div>
                            <div class="detail-item col-xl-4 col-l-4 col-m-6 col-s-6 col-xs-6" style="<?php echo empty($shopNo) ? 'display:none;' : ''; ?>">

                                <i class="fa-solid fa-shop"></i>
                                <div>
                                    <span class="key">Shop No</span>
                                    <span class="value"><?php echo $shopNo; ?></Span>
                                </div>
                            </div>
                            <div class="detail-item col-xl-8 col-l-8 col-m-6 col-s-6 col-xs-6" style="<?php echo empty($carpet_area) ? 'display:none;' : ''; ?>">

                                <i class="fa-solid fa-maximize"></i>
                                <div>
                                    <span class="key">Area (in <?php echo $carpet_area_unit; ?>) </span>
                                    <span class="value"><?php echo $carpet_area; ?></Span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="youtube">
                        <iframe src="<?php echo $youtube_link; ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                    </div>
                </div>
                <div class="note">
                    <div class="note-para">
                        <?php echo $note; ?>
                    </div>
                </div>
                <div class="map-container">
                    <?php include "./map.php"; ?>
                </div>

            </div>


        </main>
        <footer>
            <?php include INCLUDES_PATH . '_footer.php'; ?>
        </footer>
        <script src="../js/more_details.js"></script>
    </body>
<?php
} else {
    // If the ID parameter is not present in the URL, handle the case accordingly
    echo "<p>Error: ID parameter not found in the URL.</p>";
}
?>

    </html>