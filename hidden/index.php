<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="icon" href="../images/proptokart-black.png" type="image/x-icon">
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login As - Admin / Employee</title>
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
    body {
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
      background: linear-gradient(to left, #fc0, #F86F03);
    }

    main {
      display: flex;
      justify-content: center;
      align-items: center;
      height: calc(100vh - 80px);
      /* Adjust if you have a different header height */
    }

    .container {
      text-align: center;
    }

    .heading {
      font-size: 24px;
      margin-bottom: 20px;
    }

    .buttons {
      display: flex;
      justify-content: center;
    }

    .buttons a {
      display: inline-block;
      text-decoration: none;
      color: #fff;
      background-color: #333;
      padding: 10px 20px;
      border-radius: 5px;
      margin: 0 10px;
      transition: background-color 0.3s ease;
    }

    .buttons a:hover {
      background-color: #555;
    }
  </style>
</head>

<body>
  <header>
    <?php include '../includes/config.php'; ?>
    <?php include INCLUDES_PATH . 'header.php'; ?>
  </header>
  <main>
    <div class="container">
      <div class="heading">Login As</div>
      <div class="buttons">
        <a href="./admin_login.php">Admin</a>
        <a href="./employee_login.php">Employee</a>
      </div>
    </div>
  </main>
</body>
</html>