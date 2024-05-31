<?php
session_start();

$valid_username = 'PROPTOKART';
$valid_password_hash = password_hash('Proptokart123@', PASSWORD_DEFAULT);

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Set the duration of the block in seconds ( 24 hours )
$block_duration = 5;

// Check if the block duration has elapsed since the last failed attempt
if (isset($_SESSION['last_failed_attempt_time']) && time() - $_SESSION['last_failed_attempt_time'] < $block_duration) {
    $time_remaining = $block_duration - (time() - $_SESSION['last_failed_attempt_time']);
    $error_message = 'Too many login attempts. Please try again later. You can try again after ' . gmdate('H:i:s', $time_remaining) . '.';
    $_SESSION['error'] = $error_message;
    echo "<script>alert('$error_message'); window.location.href = 'admin_login.php';</script>";
    exit;
}

// Check if there have been 3 or more failed attempts
if (isset($_SESSION['login_attempts']) && $_SESSION['login_attempts'] >= 3) {
    // If there have been 3 or more failed attempts, start timing
    $_SESSION['last_failed_attempt_time'] = time();
    $_SESSION['login_attempts'] = 0;
    // Reset login attempts counter
}

if (empty($username) || empty($password)) {
    // Handle empty username or password
    $_SESSION['error'] = 'Username and password are required.';
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
    $_SESSION['error'] = 'Incorrect username or password.';
    echo "<script>alert('Incorrect username or password.'); window.location.href = 'admin_login.php';</script>";
    $_SESSION['last_failed_attempt_time'] = time();
    // Update the timestamp of the last failed attempt
    // Increment login attempts counter
    $_SESSION['login_attempts'] = isset($_SESSION['login_attempts']) ? $_SESSION['login_attempts'] + 1 : 1;
    exit;
}
?>

<?php include './../includes/connection.php';
?>

<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link rel='icon' href='../images/proptokart-black.png' type='image/x-icon'>
    <link rel='stylesheet' href='./css/admin.css'>
    <link rel='stylesheet' href='./CDNs/bootstrap.min.css'>
    <link rel='stylesheet' href='./CDNs/custom.css'>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
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
</head>

<body class='nav-md'>

    <div class='container body'>
        <div class='main_container'>
            <div class='col-md-3 left_col'>
                <div class='left_col scroll-view'>
                    <div class='navbar nav_title' style='border: 0;'>
                        <a href='.././index.php' class='site_title'><i class='fa fa-dashboard'></i>
                            <span>Admin</span></a>
                    </div>

                    <div class='clearfix'></div>

                    <!-- menu profile quick info -->
                    <div class='profile clearfix'>
                        <div class='profile_pic'>
                            <img src='../images//proptokart.png' alt='...' class='img-circle profile_img'>
                        </div>
                        <div class='profile_info'>
                            <span>Welcome, </span>
                            <h2>Proptokart</h2>
                        </div>
                    </div>
                    <!-- /menu profile quick info -->

                    <br />

                    <!-- sidebar menu -->
                    <div id='sidebar-menu' class='main_menu_side hidden-print main_menu'>
                        <div class='menu_section'>
                            <h3>General</h3>
                            <ul class='nav side-menu'>
                                <li><a><i class='fa fa-home'></i> Home <span class='fa fa-chevron-down'></span></a>
                                    <ul class='nav child_menu'>
                                        <li><a href='index.html'>Dashboard</a></li>
                                        <li><a href='index2.html'>Dashboard2</a></li>
                                        <li><a href='index3.html'>Dashboard3</a></li>
                                    </ul>
                                </li>
                                <li><a><i class='fa fa-edit'></i> Forms <span class='fa fa-chevron-down'></span></a>
                                    <ul class='nav child_menu'>
                                        <li><a href='form.html'>General Form</a></li>
                                        <li><a href='form_advanced.html'>Advanced Components</a></li>
                                        <li><a href='form_validation.html'>Form Validation</a></li>
                                        <li><a href='form_wizards.html'>Form Wizard</a></li>
                                        <li><a href='form_upload.html'>Form Upload</a></li>
                                        <li><a href='form_buttons.html'>Form Buttons</a></li>
                                    </ul>
                                </li>
                                <li><a><i class='fa fa-desktop'></i> UI Elements <span class='fa fa-chevron-down'></span></a>
                                    <ul class='nav child_menu'>
                                        <li><a href='general_elements.html'>General Elements</a></li>
                                        <li><a href='media_gallery.html'>Media Gallery</a></li>
                                        <li><a href='typography.html'>Typography</a></li>
                                        <li><a href='icons.html'>Icons</a></li>
                                        <li><a href='glyphicons.html'>Glyphicons</a></li>
                                        <li><a href='widgets.html'>Widgets</a></li>
                                        <li><a href='invoice.html'>Invoice</a></li>
                                        <li><a href='inbox.html'>Inbox</a></li>
                                        <li><a href='calendar.html'>Calendar</a></li>
                                    </ul>
                                </li>
                                <li><a><i class='fa fa-table'></i> Tables <span class='fa fa-chevron-down'></span></a>
                                    <ul class='nav child_menu'>
                                        <li><a href='tables.html'>Tables</a></li>
                                        <li><a href='tables_dynamic.html'>Table Dynamic</a></li>
                                    </ul>
                                </li>
                                <li><a><i class='fa fa-bar-chart-o'></i> Data Presentation <span class='fa fa-chevron-down'></span></a>
                                    <ul class='nav child_menu'>
                                        <li><a href='chartjs.html'>Chart JS</a></li>
                                        <li><a href='chartjs2.html'>Chart JS2</a></li>
                                        <li><a href='morisjs.html'>Moris JS</a></li>
                                        <li><a href='echarts.html'>ECharts</a></li>
                                        <li><a href='other_charts.html'>Other Charts</a></li>
                                    </ul>
                                </li>
                                <li><a><i class='fa fa-clone'></i>Layouts <span class='fa fa-chevron-down'></span></a>
                                    <ul class='nav child_menu'>
                                        <li><a href='fixed_sidebar.html'>Fixed Sidebar</a></li>
                                        <li><a href='fixed_footer.html'>Fixed Footer</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class='menu_section'>
                            <h3>Live On</h3>
                            <ul class='nav side-menu'>
                                <li><a><i class='fa fa-bug'></i> Additional Pages <span class='fa fa-chevron-down'></span></a>
                                    <ul class='nav child_menu'>
                                        <li><a href='e_commerce.html'>E-commerce</a></li>
                                        <li><a href='projects.html'>Projects</a></li>
                                        <li><a href='project_detail.html'>Project Detail</a></li>
                                        <li><a href='contacts.html'>Contacts</a></li>
                                        <li><a href='profile.html'>Profile</a></li>
                                    </ul>
                                </li>
                                <li><a><i class='fa fa-windows'></i> Extras <span class='fa fa-chevron-down'></span></a>
                                    <ul class='nav child_menu'>
                                        <li><a href='page_403.html'>403 Error</a></li>
                                        <li><a href='page_404.html'>404 Error</a></li>
                                        <li><a href='page_500.html'>500 Error</a></li>
                                        <li><a href='plain_page.html'>Plain Page</a></li>
                                        <li><a href='login.html'>Login Page</a></li>
                                        <li><a href='pricing_tables.html'>Pricing Tables</a></li>
                                    </ul>
                                </li>
                                <li><a><i class='fa fa-sitemap'></i> Multilevel Menu <span class='fa fa-chevron-down'></span></a>
                                    <ul class='nav child_menu'>
                                        <li><a href='#level1_1'>Level One</a>
                                        <li><a>Level One<span class='fa fa-chevron-down'></span></a>
                                            <ul class='nav child_menu'>
                                                <li class='sub_menu'><a href='level2.html'>Level Two</a>
                                                </li>
                                                <li><a href='#level2_1'>Level Two</a>
                                                </li>
                                                <li><a href='#level2_2'>Level Two</a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li><a href='#level1_2'>Level One</a>
                                        </li>
                                    </ul>
                                </li>
                                <li><a href='javascript:void(0)'><i class='fa fa-laptop'></i> Landing Page <span class='label label-success pull-right'>Coming Soon</span></a></li>
                            </ul>
                        </div>

                    </div>
                    <!-- /sidebar menu -->

                    <!-- /menu footer buttons -->
                    <div class='sidebar-footer hidden-small'>
                        <a data-toggle='tooltip' data-placement='top' title='Settings'>
                            <span class='glyphicon glyphicon-cog' aria-hidden='true'></span>
                        </a>
                        <a data-toggle='tooltip' data-placement='top' title='FullScreen'>
                            <span class='glyphicon glyphicon-fullscreen' aria-hidden='true'></span>
                        </a>
                        <a data-toggle='tooltip' data-placement='top' title='Lock'>
                            <span class='glyphicon glyphicon-eye-close' aria-hidden='true'></span>
                        </a>
                        <a data-toggle='tooltip' data-placement='top' title='Logout' href='login.html'>
                            <span class='glyphicon glyphicon-off' aria-hidden='true'></span>
                        </a>
                    </div>
                    <!-- /menu footer buttons -->
                </div>
            </div>

            <!-- top navigation -->
            <div class='top_nav'>
                <div class='nav_menu'>
                    <nav>
                        <div class='nav toggle'>
                            <a id='menu_toggle'><i class='fa fa-bars'></i></a>
                        </div>
                    </nav>
                </div>
            </div>
            <!-- /top navigation -->

            <!-- page content -->
            <div class='right_col' role='main'>
                <canvas id="employeeChart" style="max-width: 50%; max-height: 50vh;"></canvas>
                <?php
                $query = "SELECT property, COUNT(*) as count FROM employee_data GROUP BY property";
                $result = $conn->query($query);

                if (!$result) {
                    die("Query execution failed: " . $conn->error);
                }

                $data = array();
                while ($row = $result->fetch_assoc()) {
                    $data[$row['property']] = $row['count'];
                }

                $chart_data_employee = json_encode($data);
                ?>
                <canvas id="propertyChart" style="max-width: 50%; max-height: 50vh;"></canvas>
                <?php
                $query = "SELECT DATE_FORMAT(date, '%Y-%m-%d') as week_start, COUNT(*) as new_properties 
              FROM employee_data 
              WHERE date >= DATE_SUB(NOW(), INTERVAL 6 WEEK) 
              GROUP BY week_start";

                $result = $conn->query($query);

                if (!$result) {
                    die("Query execution failed: " . $conn->error);
                }

                $data = array();
                while ($row = $result->fetch_assoc()) {
                    $data[$row['week_start']] = $row['new_properties'];
                }

                $chart_data_property = json_encode($data);
                ?>
            </div>
            <!-- /page content -->

        </div>
    </div>
    <script src='https://code.jquery.com/jquery-3.6.0.min.js'></script>
    <!-- <script src = 'path/to/admin.js'></script> -->
    <script src='./js/admin.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js'></script>

    <script>
        var data_employee = <?php echo $chart_data_employee; ?>;
        var ctx_employee = document.getElementById('employeeChart').getContext('2d');
        var myChart_employee = new Chart(ctx_employee, {
            type: 'bar',
            data: {
                labels: Object.keys(data_employee),
                datasets: [{
                    label: 'Employee Count by Property Type',
                    data: Object.values(data_employee),
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        var data_property = <?php echo $chart_data_property; ?>;
        var ctx_property = document.getElementById('propertyChart').getContext('2d');
        var myChart_property = new Chart(ctx_property, {
            type: 'line',
            data: {
                labels: Object.keys(data_property),
                datasets: [{
                    label: 'New Properties Added per Week',
                    data: Object.values(data_property),
                    fill: false,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2,
                    pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                    pointRadius: 4,
                    pointHoverRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Properties'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Week'
                        }
                    }
                }
            }
        });
    </script>
</body>

</html>