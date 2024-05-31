<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  include "../includes/connection.php";
  // Check if connection is successful
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Create the Employee table if not exists
  $sql_create_employee_table = "CREATE TABLE IF NOT EXISTS employee (
      id INT AUTO_INCREMENT PRIMARY KEY,
      name VARCHAR(255) NOT NULL,
      username VARCHAR(255) UNIQUE NOT NULL,
      password VARCHAR(255) NOT NULL
  )";

  if ($conn->query($sql_create_employee_table) === TRUE) {
    echo "Table Employee created successfully.<br>";
  } else {
    echo "Error creating table Employee: " . $conn->error . "<br>";
  }

  // Retrieve user input
  $name = $_POST['name'];
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Hash the password
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Check if the username already exists
  $sql_check_username_entry = "SELECT COUNT(*) as count FROM employee WHERE username = ?";
  $stmt_check_username = $conn->prepare($sql_check_username_entry);
  if (!$stmt_check_username) {
    die("Error preparing query: " . $conn->error);
  }
  $stmt_check_username->bind_param("s", $username);
  if (!$stmt_check_username->execute()) {
    die("Error executing query: " . $stmt_check_username->error);
  }
  $stmt_check_username->bind_result($count);
  $stmt_check_username->fetch();
  $stmt_check_username->close();

  if ($count > 0) {
    echo "<script>alert('$username already exists. Please choose another username.'); window.location.href = 'employee_login.php';</script>";
    exit;
  } else {
    // Create the username_data table
    $sql_create_username_data_table = "CREATE TABLE IF NOT EXISTS employee_data (
          id INT AUTO_INCREMENT PRIMARY KEY,
          emp_id INT NOT NULL,
          date VARCHAR(20),
          customer_id VARCHAR(20) UNIQUE,
          name VARCHAR(50),
          phone VARCHAR(20),
          state VARCHAR(50),
          city VARCHAR(50),
          postal_code VARCHAR(20),
          email VARCHAR(50),
          address TEXT,
          property VARCHAR(20),
          propertyType VARCHAR(20),
          resi_com_agri_type VARCHAR(20),
          rooms VARCHAR(20),
          baths VARCHAR(50),
          parking Varchar (20),
          property_furnish VARCHAR(20),
          shopNo VARCHAR(50),
          carpet_area VARCHAR(20),
          carpet_area_unit VARCHAR(20),
          price DECIMAL(10,2),
          price_unit VARCHAR(20),
          per Varchar (20),
          advance_payment DECIMAL(10,2),
          advance_price_unit VARCHAR (20),
          payment_method VARCHAR(255),
          note TEXT,
          youtube_link VARCHAR(255),
          FOREIGN KEY (emp_id) REFERENCES employee(id) ON DELETE CASCADE
      )";

    $result2 = $conn->query($sql_create_username_data_table);

    if ($result2 === TRUE) {

      $sql_user_images = "CREATE TABLE IF NOT EXISTS employee_images (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT, 
        image_name VARCHAR(255) NOT NULL,
        image_data LONGBLOB,
        image_type VARCHAR(255),
        FOREIGN KEY (user_id) REFERENCES employee_data(id) ON DELETE CASCADE
    )";


      // Execute the query to create the property_images table
      $result4 = $conn->query($sql_user_images);

      if ($result4 === TRUE) {
        $sql_user_thumbnail = "CREATE TABLE IF NOT EXISTS employee_thumbnail (
          id INT AUTO_INCREMENT PRIMARY KEY,
          user_id INT, 
          image_name VARCHAR(255) NOT NULL,
          image_data LONGBLOB,
          image_type VARCHAR(255),
          FOREIGN KEY (user_id) REFERENCES employee_data(id) ON DELETE CASCADE
      )";


        // Execute the query to create the property_images table
        $result6 = $conn->query($sql_user_thumbnail);

        if ($result6 === TRUE) {
          // Insert data into the Employee table
          $sql_insert_employee = "INSERT INTO employee (name, username, password) VALUES (?, ?, ?)";
          $stmt_insert_employee = $conn->prepare($sql_insert_employee);
          if (!$stmt_insert_employee) {
            die("Error preparing insert statement for Employee: " . $conn->error);
          }
          $stmt_insert_employee->bind_param("sss", $name, $username, $hashed_password);
          if ($stmt_insert_employee->execute()) {
            echo "<script>alert('Account Created Successfully with Username $username'); window.location.href = 'employee_login.php';</script>";
            exit; // Stop execution after successful insertion
          } else {
            echo "<script>alert('Error inserting data into Employee table. Please try again later.'); window.location.href = 'employee_login.php';</script>";
          }
          $stmt_insert_employee->close();
        } else {
          echo "Error creating table username_thumbnail: " . $conn->error . "<br>";
        }
      } else {
        echo "Error creating table username_images: " . $conn->error . "<br>";
      }
    } else {
      echo "<script>alert('Error creating username data table. Please try again later.'); window.location.href = 'employee_login.php';</script>";
    }
  }
  $conn->close();
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="icon" href="../images/proptokart-black.png" type="image/x-icon">
  <meta charset="UTF-8" />
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
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="css/style.css" />
  <title>Sign in & Sign up Form</title>
</head>

<body>
  <div class="container">
    <div class="forms-container">
      <div class="signin-signup">
        <form action="employee_access.php" method="post" enctype="multipart/form-data" class="sign-in-form">
          <h2 class="title">Sign in</h2>
          <div class="input-field">
            <i class="fas fa-user"></i>
            <input type="text" placeholder="Username" name="username" />
          </div>
          <div class="input-field">
            <i class="fas fa-lock"></i>
            <div class="password-container">
              <input type="password" placeholder="Password" name="password" id="passwordField1" />
              <span class="eye-icon" onclick="togglePasswordVisibility1()">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                  <path d="M12 4c-4.97 0-9 4.03-9 9s4.03 9 9 9 9-4.03 9-9-4.03-9-9-9zm0 2c3.87 0 7 3.13 7 7s-3.13 7-7 7-7-3.13-7-7 3.13-7 7-7zm-1 2h2v2h-2V8zm.04 6c-.47 0-.93-.07-1.37-.2l1.57-1.57C11.72 12.44 11.87 12.22 12 12c.15-.25.28-.53.38-.8l1.57-1.57C14.07 9.07 13.53 9 13 9 11.3 9 10 10.3 10 12s1.3 3 3 3c.53 0 1.07-.07 1.57-.2l1.57-1.57c-.1-.27-.23-.55-.38-.8l-1.57-1.57c.44-.13.9-.2 1.37-.2 1.7 0 3 1.3 3 3s-1.3 3-3 3z" />
                </svg>
              </span>
            </div>
          </div>
          <input type="submit" value="Login" class="btn solid" name="signin" />
        </form>

        <form method="post" enctype="multipart/form-data" class="sign-up-form">
          <h2 class="title">Sign up</h2>
          <div class="input-field">
            <i class="fas fa-user"></i>
            <input type="text" placeholder="Name" name="name" />
          </div>
          <div class="input-field">
            <i class="fas fa-user-check"></i>
            <input type="text" placeholder="UserName" name="username" />
          </div>
          <div class="input-field">
            <i class="fas fa-lock"></i>
            <div class="password-container">
              <input type="password" placeholder="Password" name="password" id="passwordField" />
              <span class="eye-icon" onclick="togglePasswordVisibility()">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                  <path d="M12 4c-4.97 0-9 4.03-9 9s4.03 9 9 9 9-4.03 9-9-4.03-9-9-9zm0 2c3.87 0 7 3.13 7 7s-3.13 7-7 7-7-3.13-7-7 3.13-7 7-7zm-1 2h2v2h-2V8zm.04 6c-.47 0-.93-.07-1.37-.2l1.57-1.57C11.72 12.44 11.87 12.22 12 12c.15-.25.28-.53.38-.8l1.57-1.57C14.07 9.07 13.53 9 13 9 11.3 9 10 10.3 10 12s1.3 3 3 3c.53 0 1.07-.07 1.57-.2l1.57-1.57c-.1-.27-.23-.55-.38-.8l-1.57-1.57c.44-.13.9-.2 1.37-.2 1.7 0 3 1.3 3 3s-1.3 3-3 3z" />
                </svg>
              </span>
            </div>
          </div>
          <input type="submit" class="btn" value="Sign up" name="signup" />
        </form>
      </div>
    </div>

    <div class="panels-container">
      <div class="panel left-panel">
        <div class="content">
          <h3>New to our community ?</h3>
          <p>
            Discover a world of possibilities! Join us and explore a vibrant
            community where ideas flourish and connections thrive.
          </p>
          <button class="btn transparent" id="sign-up-btn">
            Sign up
          </button>
        </div>
        <img src="https://i.ibb.co/6HXL6q1/Privacy-policy-rafiki.png" class="image" alt="" />
      </div>
      <div class="panel right-panel">
        <div class="content">
          <h3>One of Our Valued Members</h3>
          <p>
            Thank you for being part of our community. Your presence enriches our
            shared experiences. Let's continue this journey together!
          </p>
          <button class="btn transparent" id="sign-in-btn">
            Sign in
          </button>
        </div>
        <img src="https://i.ibb.co/nP8H853/Mobile-login-rafiki.png" class="image" alt="" />
      </div>
    </div>
  </div>

  <script src="js/script.js"></script>
</body>

</html>