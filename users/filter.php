<?php
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET['filter'])) {
        // Initialize variables to hold form data
        $propertyType = $_GET['propertyType'] ?? null;
        $resi_com_agri_type = $_GET['resi_com_agri_type'] ?? null;
        $state = $_GET['state'] ?? null;
        $city = $_GET['city'] ?? null;
        $address = $_GET['address'] ?? null;
        $property = $_GET['property'] ?? null;
        $property_furnish = $_GET['property_furnish'] ?? null;
        $min_price = $_GET['min_price'] ?? null;
        $max_price = $_GET['max_price'] ?? null;
        $num_rooms = $_GET['rooms'] ?? null;
        $num_baths = $_GET['baths'] ?? null;
        $parking = $_GET['parking'] ?? null;

        // Start constructing the WHERE clause based on available parameters
        $where_clause = "1 = 1"; // Default condition to ensure the WHERE clause is always valid
        $params = array();

        // Check if property type filter is set
        if (!empty($propertyType)) {
            $where_clause .= " AND propertyType IN (?" . str_repeat(", ?", count($propertyType) - 1) . ")";
            $params = array_merge($params, $propertyType);
        }

        // Check if residential type filter is set
        if (!empty($resi_com_agri_type)) {
            $where_clause .= " AND resi_com_agri_type IN (?" . str_repeat(", ?", count($resi_com_agri_type) - 1) . ")";
            $params = array_merge($params, $resi_com_agri_type);
        }

        // Check if state filter is set
        if (!empty($state)) {
            $where_clause .= " AND state IN (?" . str_repeat(", ?", count($state) - 1) . ")";
            $params = array_merge($params, $state);
        }

        // Check if city filter is set
        if (!empty($city)) {
            $where_clause .= " AND city IN (?" . str_repeat(", ?", count($city) - 1) . ")";
            $params = array_merge($params, $city);
        }

        // Check if state filter is set
        if (!empty($address)) {
            // Ensure $address is an array
            if (!is_array($address)) {
                $address = array($address);
            }

            $where_clause .= " AND address IN (?" . str_repeat(", ?", count($address) - 1) . ")";
            $params = array_merge($params, $address);
        }


        // Check if property filter is set
        if (!empty($property)) {
            $where_clause .= " AND property IN (?" . str_repeat(", ?", count($property) - 1) . ")";
            $params = array_merge($params, $property);
        }

        // Check if property_furnish filter is set
        if (!empty($property_furnish)) {
            $where_clause .= " AND property_furnish IN (?" . str_repeat(", ?", count($property_furnish) - 1) . ")";
            $params = array_merge($params, $property_furnish);
        }

        // Check if parking filter is set
        if (!empty($parking)) {
            $where_clause .= " AND parking IN (?" . str_repeat(", ?", count($parking) - 1) . ")";
            $params = array_merge($params, $parking);
        }

        // Check if min_price filter is set
        if (!empty($min_price)) {
            $where_clause .= " AND price >= ?";
            $params[] = $min_price;
        }

        // Check if max_price filter is set
        if (!empty($max_price)) {
            $where_clause .= " AND price <= ?";
            $params[] = $max_price;
        }

        // Check if residential type filter is set
        if (!empty($num_rooms)) {
            $where_clause .= " AND rooms IN (?" . str_repeat(", ?", count($num_rooms) - 1) . ")";
            $params = array_merge($params, $num_rooms);
        }

        // Check if residential type filter is set
        if (!empty($num_baths)) {
            $where_clause .= " AND baths IN (?" . str_repeat(", ?", count($num_baths) - 1) . ")";
            $params = array_merge($params, $num_baths);
        }

        // Fetch all IDs based on the constructed WHERE clause
        $sql_ids = "SELECT id FROM employee_data WHERE $where_clause ORDER BY id DESC";
        $stmt_ids = $conn->prepare($sql_ids);
        if (!$stmt_ids) {
            echo "<p>Error: Unable to fetch data.</p>";
        } else {
            // Bind parameters dynamically
            if (!empty($params)) {
                $param_types = str_repeat('s', count($params));
                $stmt_ids->bind_param($param_types, ...$params);
            }
            $stmt_ids->execute();
            $stmt_ids->store_result();
            $stmt_ids->bind_result($id);

            if ($stmt_ids->num_rows() > 0) {
                while ($stmt_ids->fetch()) {

                    // Call function to display card details
                    display_card_details($id, $conn, $city);
                }
            } else {
                // Display a message when no properties are found
                echo "<p>No properties found matching the criteria.</p>";
            }
            $stmt_ids->close();
        }
    } elseif (isset($_GET['search'])) {
        $city = $_GET['city'] ?? null;
        $property = $_GET['property'] ?? null;
        $propertyType = $_GET['propertyType'] ?? null;
        $resi_com_agri_type = $_GET['resi_com_agri_type'] ?? null;

        if ($city === null) {
            echo "<p>Error: City parameter is missing.</p>";
        } else {
            // Construct the WHERE clause based on available parameters
            $where_clause = "city = ?";
            $params = array($city);

            if ($property !== null) {
                $where_clause .= " AND property = ?";
                $params[] = $property;
            }
            if ($propertyType !== null || $resi_com_agri_type !== null) {
                $where_clause .= " AND (propertyType = ? OR resi_com_agri_type = ?)";
                $params[] = $propertyType ?? '';
                $params[] = $resi_com_agri_type ?? '';
            }

            // Fetch all IDs based on the constructed WHERE clause
            $sql_ids = "SELECT id FROM employee_data WHERE $where_clause ORDER BY id DESC";
            $stmt_ids = $conn->prepare($sql_ids);
            if (!$stmt_ids) {
                echo "<p>Error: Unable to fetch data.</p>";
            } else {
                // Bind parameters dynamically
                if (!empty($params)) {
                    $param_types = str_repeat('s', count($params));
                    $stmt_ids->bind_param($param_types, ...$params);
                }
                $stmt_ids->execute();
                $stmt_ids->store_result();
                $stmt_ids->bind_result($id);

                if ($stmt_ids->num_rows() > 0) {
                    while ($stmt_ids->fetch()) {

                        // Call function to display card details
                        display_card_details($id, $conn, $city);
                    }
                } else {
                    echo "<p>No properties found matching the criteria.</p>";
                }
                $stmt_ids->close();
            }
        }
    }
} else {
    echo "<p>City parameter is missing.</p>";
}
// Function to display card details
function display_card_details($id, $conn, $city)
{
    // Fetch images based on the employee ID
    $sql_image = "SELECT image_data FROM employee_thumbnail WHERE user_id = ? LIMIT 1";
    $stmt_image = $conn->prepare($sql_image);
    if (!$stmt_image) {
        echo "<p>Error: Unable to fetch data.</p>";
    } else {
        $stmt_image->bind_param("i", $id);
        $stmt_image->execute();
        $stmt_image->store_result();
        $stmt_image->bind_result($image_data);

        if ($stmt_image->num_rows() > 0) {
            $stmt_image->fetch();
            // Display the card container and product card
            echo "<a href='./more_details.php?id=$id'>";
            echo "<div class='offset-m2 offset-l4 card-container col-l-3 col-m-4 col-s-6 col-xs-12'>";
            echo "<div class='product-card '>";
            echo "<div class='card z-depth-4'>";
            echo "<div class='card-image'><abbr title='Click to know more'>";
            echo "<img src='data:image/jpeg;base64," . base64_encode($image_data) . "' alt='product-img' height='250'></abbr>";

            // Fetch data from $stmt_details to get $propertyType and $state
            $sql_details = "SELECT property, propertyType, resi_com_agri_type, address, state, postal_code, property_furnish, parking, rooms, baths,shopNo, carpet_area, carpet_area_unit, price, price_unit, per FROM employee_data WHERE id = ? LIMIT 1";
            $stmt_details = $conn->prepare($sql_details);
            if (!$stmt_details) {
                echo "<p>Error: Unable to fetch data.</p>";
            } else {
                $stmt_details->bind_param("i", $id);
                $stmt_details->execute();
                $stmt_details->store_result();
                $stmt_details->bind_result($property, $propertyType, $resi_com_agri_type, $address, $state, $postal_code, $property_furnish, $parking, $rooms, $baths, $shopNo, $carpet_area, $carpet_area_unit, $price, $price_unit, $per);

                // Check if $stmt_details has rows
                if ($stmt_details->num_rows() > 0) {
                    $stmt_details->fetch();
                    // Display the card title
                    echo "<a href='#' class='btn-floating btn-large price waves-effect waves-light yellow darken-1'>" . $property . "</a>";
                    echo "<span class='card-title'><span style='font-weight: bold;'>" . $propertyType . "  " . $resi_com_agri_type . "</span></span>";

                    // Fetch current URL and replace current page name with more_details.php
                    $currentUrl = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                    $baseUrl = substr($currentUrl, 0, strrpos($currentUrl, '/') + 1);
                    $newUrl = $baseUrl . "more_details.php?id=" . $id;

                    // Create the WhatsApp share link with card details
                    $whatsapp_message = "Check out this property: " . (is_array($property) ? implode(', ', $property) : $property) . " " . (is_array($propertyType) ? implode(', ', $propertyType) : $propertyType) . " " . (is_array($resi_com_agri_type) ? implode(', ', $resi_com_agri_type) : $resi_com_agri_type) . " located at " . (is_array($address) ? implode(', ', $address) : $address) . ", " . (is_array($city) ? implode(', ', $city) : $city) . ", " . (is_array($state) ? implode(', ', $state) : $state) . ". Price: " . (is_array($price) ? implode(', ', $price) : $price) . " " . (is_array($price_unit) ? implode(', ', $price_unit) : $price_unit) . ". More details: " . (is_array($newUrl) ? implode(', ', $newUrl) : $newUrl);
                    $whatsapp_url = "https://api.whatsapp.com/send?text=" . urlencode($whatsapp_message);

                    // URL for sharing on other platforms
                    $share_url = urlencode($newUrl);

                    // Display the card image div
                    echo "</div>";
                    // Display the share button with a unique ID
                    echo "<ul class='card-action-buttons'>";
                    echo "<li><abbr title='Share on Social Media'>";
                    echo "<a href='javascript:void(0);' class='btn-floating waves-effect waves-light white share-btn' id='share-btn-$id'><i class='material-icons grey-text text-darken-3'>share</i></a>";
                    echo "</abbr></li>";
                    echo "<li><a class='btn-floating waves-effect waves-light red accent-2'><i class='material-icons like'>favorite_border</i></a></li>";
                    echo "<li><a id='buy' class='btn-floating waves-effect waves-light blue'><i class='material-icons buy'>add_shopping_cart</i></a></li>";
                    echo "</ul>";
                    // Hidden share options initially with a unique ID
                    echo "<div class='social-share-options' id='social-share-options-$id' style='display: none; padding-top: 7%; position: absolute;'>";
                    echo "<ul class='card-action-buttons'>";
                    echo "<li><abbr title='Share on Whatsapp'><a href='$whatsapp_url' target='_blank' class='btn-floating waves-effect waves-light green'><i class='fa-brands fa-whatsapp social-icon'></i></a></abbr></li>";
                    echo "<li><abbr title='Share on Facebook'><a href='https://www.facebook.com/sharer/sharer.php?u=$share_url' target='_blank' class='btn-floating waves-effect waves-light blue'><i class='fa-brands fa-facebook-f social-icon'></i></a></abbr></li>";
                    echo "<li><abbr title='Share on Twitter'><a href='https://twitter.com/intent/tweet?url=$share_url&text=$whatsapp_message' target='_blank' class='btn-floating waves-effect waves-light light-blue darken-4'><i class='fa-brands fa-twitter social-icon'></i></a></abbr></li>";
                    echo "<li><abbr title='Share on LinkedIn'><a href='https://www.linkedin.com/shareArticle?url=$share_url&title=$whatsapp_message' target='_blank' class='btn-floating waves-effect waves-light blue darken-4'><i class='fa-brands fa-linkedin-in social-icon'></i></a></abbr></li>";
                    echo "<li><abbr title='Copy link'><a href='javascript:void(0);' onclick='copyToClipboard(\"$newUrl\")' class='btn-floating waves-effect waves-light grey'><i class='fa fa-copy'></i></a></abbr></li>";
                    echo "</ul>";
                    echo "</div>";
                    // Display the card content
                    echo "<div class='card-content'>";
                    echo "<div class='row'>";
                    echo "<div class='col s12'>";
                    // Display the description
                    $description = (is_array($address) ? implode(', ', $address) : $address) . ", " . (is_array($city) ? implode(', ', $city) : $city) . ", " . (is_array($state) ? implode(', ', $state) : $state) . ", " . (is_array($postal_code) ? implode(', ', $postal_code) : $postal_code);

                    echo "<p><strong>Description:</strong><br />" . (strlen($description) > 32 ? substr($description, 0, 32) . "..." : $description) . "</p>";
                    echo "</div>";
                    echo "</div>";

                    echo "<div class='row'>";
                    echo "<div style='width: 95%; margin: auto;' id='card-$id'>";

                    // Initialize an empty array to store only non-empty chips
                    $chips = [];

                    if (!empty($property_furnish)) {
                        $chips['property_furnish'] = $property_furnish;
                    }
                    if (!empty($rooms)) {
                        $chips['rooms'] = $rooms . " Rooms";
                    }
                    if (!empty($baths)) {
                        $chips['baths'] = $baths . " Baths";
                    }
                    if (!empty($shopNo)) {
                        $chips['shopNo'] = $shopNo . " Shops";
                    }
                    if (!empty($parking)) {
                        $chips['parking'] = "Parking " . $parking;
                    }
                    if (!empty($carpet_area)) {
                        $chips['carpet_area'] = $carpet_area . " " . $carpet_area_unit;
                    }
                    if (!empty($price)) {
                        $chips['price'] = $price . " " . $price_unit . "/" . $per;
                    }

                    $chip_count = 0;
                    $displayed_chips = []; // Array to store chips that have values

                    foreach ($chips as $key => $value) {
                        if (!empty($value)) {
                            $displayed_chips[$key] = $value; // Store chips with values
                            if ($chip_count < 2) {
                                echo "<div class='chip'>" . $value . "</div>";
                            } else {
                                echo "<div class='chip more-chips-$id' style='display: none;'>" . $value . "</div>";
                            }
                            $chip_count++;
                        }
                    }

                    // Display the + chip if there are more than 2 chips
                    if ($chip_count > 2) {
                        echo "<div class='chip show-more' id='show-more-chips-$id'>+ More</div>";
                    }
                    echo "</div>";
                    echo "</div>";

                    // JavaScript to show and hide more chips when clicked
                    echo "<script>
                            document.getElementById('show-more-chips-$id').addEventListener('click', function() {
                                var moreChips = document.querySelectorAll('.more-chips-$id');
                                var isExpanded = this.getAttribute('data-expanded') === 'true';
                            
                                for (var i = 0; i < moreChips.length; i++) {
                                    moreChips[i].style.display = isExpanded ? 'none' : 'inline-block';
                                }
                            
                                this.innerHTML = isExpanded ? '+ More' : '- Less'; // Toggle button text
                                this.setAttribute('data-expanded', !isExpanded); // Toggle the expanded state
                            });
                            </script>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                    echo "</a>";
                } else {
                    echo "<p>No details found for this property.</p>";
                }
                $stmt_details->close();
            }
        } else {
            echo "<p>No images found for this property.</p>";
        }
        $stmt_image->close();
    }
}


// JavaScript function for copying to clipboard and toggling share options
echo "<script>
document.addEventListener('DOMContentLoaded', function() {
    // Function to copy URL to clipboard
    function copyToClipboard(text) {
        var textArea = document.createElement('textarea');
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        alert('Link copied to clipboard');
    }

    // Get all share buttons and social share options
    var shareBtns = document.querySelectorAll('.share-btn');
    var socialShareOptions = document.querySelectorAll('.social-share-options');

    // Add event listeners to each share button
    shareBtns.forEach(function(shareBtn) {
        shareBtn.addEventListener('click', function() {
            // Find the corresponding social share options by ID
            var id = shareBtn.id.split('-').pop(); // Extract the ID from the share button's ID
            var shareOptions = document.querySelector('#social-share-options-' + id);

            // Toggle display of social share options
            socialShareOptions.forEach(function(option) {
                if (option.id === 'social-share-options-' + id) {
                    option.style.display = option.style.display === 'block' ? 'none' : 'block';
                } else {
                    option.style.display = 'none'; // Hide all other options
                }
            });
        });
    });

    // Add event listeners to each like button
    var likeBtns = document.querySelectorAll('.like');
    likeBtns.forEach(function(likeBtn) {
        likeBtn.addEventListener('click', function() {
            // Toggle like button state
            likeBtn.classList.toggle('liked');

            // Implement your like functionality here, e.g., send a request to server to like the item
            var liked = likeBtn.classList.contains('liked');
            var action = liked ? 'favorite' : 'favorite_border';
            likeBtn.innerHTML = '<i class=\"material-icons\">' + action + '</i>';
        });
    });

    // Add event listeners to each add-to-cart button
    var cartBtns = document.querySelectorAll('.buy');
    cartBtns.forEach(function(cartBtn) {
        cartBtn.addEventListener('click', function() {
            // Implement your add-to-cart functionality here, e.g., send a request to server to add the item to cart
            alert('Item added to cart');
        });
    });
});
</script>";
