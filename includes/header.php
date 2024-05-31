<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" />
  <link rel="stylesheet" href="../proptokart_css/header.css" />
  <link rel="icon" href="../images/proptokart-black.png" type="image/x-icon">
</head>

<body>
  <!-- Navigation menu (default bg-primary) -->
  <header class="demo demo1">
    <nav class="navbar bg-primary">
      <div class="navbar-brand">
        <a href="<?php echo BASE_URL; ?>index.php">
          <img src="<?php echo BASE_URL; ?>images/proptokart.png" alt="Proptokart" style="width: 70px" />
        </a>
      </div>
      <button class="navbar-toggler">
        <i class="fa fa-bars" aria-hidden="true"></i>
      </button>
      <ul class="navbar-nav">
        <li class="nav-close">
          <button class="btn-nav-close">
            <span class="close-btn">+</span>
          </button>
        </li>
        <!-- <li class="nav-item">
          <a href="#" class="nav-link">Explore</a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">Works </a>
          <ul class="dropdown">
            <li class="nav-item">
              <a href="#" class="nav-link">Dropdown level-1</a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">Dropdown level-1</a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">Dropdown level-1 </a>
              <ul class="dropdown">
                <li class="nav-item">
                  <a href="#" class="nav-link">Dropdown level-2</a>
                </li>
                <li class="nav-item">
                  <a href="#" class="nav-link">Dropdown level-2</a>
                </li>
                <li class="nav-item">
                  <a href="#" class="nav-link">Dropdown level-2 </a>
                  <ul class="dropdown">
                    <li class="nav-item">
                      <a href="#" class="nav-link">Dropdown level-3</a>
                    </li>
                    <li class="nav-item">
                      <a href="#" class="nav-link">Dropdown level-3</a>
                    </li>
                    <li class="nav-item">
                      <a href="#" class="nav-link">Dropdown level-3</a>
                    </li>
                  </ul>
                </li>
              </ul>
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">Shop</a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">Team</a>
          <ul class="dropdown">
            <li class="nav-item">
              <a href="#" class="nav-link">Dropdown level-1</a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">Dropdown level-1</a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">Dropdown level-1 </a>
              <ul class="dropdown">
                <li class="nav-item">
                  <a href="#" class="nav-link">Dropdown level-2</a>
                </li>
                <li class="nav-item">
                  <a href="#" class="nav-link">Dropdown level-2</a>
                </li>
                <li class="nav-item">
                  <a href="#" class="nav-link">Dropdown level-2 </a>
                </li>
              </ul>
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">Contact</a>
        </li> -->
        <li class="nav-item">
          <a href="<?php echo BASE_URL; ?>users/add_property.php" class="nav-link">For Owners <i class="fa-solid fa-plus"></i></a>
        </li>
        <li class="nav-item">
          <a href="<?php echo BASE_URL; ?>users/add_property.php" class="nav-link">For Tenants <i class="fa-solid fa-plus"></i></a>
        </li>
        <li class="nav-item">
          <a href="<?php echo BASE_URL; ?>users/add_property.php" class="nav-link">For Dealers / Builders <i class="fa-solid fa-plus"></i></a>
        </li>
        <li>
          <button class="btn-rectangular" onclick="window.location.href='<?php echo BASE_URL; ?>users/add_property.php'">
            Post Property for FREE <i class="fa-solid fa-plus"></i>
          </button>
        </li>
      </ul>
      <div class="navbar-utils">
        <div class="utils-search">

          <button class="btn-rectangular btn btn-primary" type="button" data-toggle="modal" data-target="#exampleModalCenter">
            <i class="fa-regular fa-user" aria-hidden="true"></i>
          </button>
        </div>
      </div>
    </nav>
  </header>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <script>
    /* Navbar toggler */
    const toggleBtn = document.querySelector(".navbar-toggler");
    const navbarNav = document.querySelector(".navbar-nav");
    const navCloseBtn = document.querySelector(".btn-nav-close");

    toggleBtn.addEventListener("click", () => {
      navbarNav.classList.toggle("active");
    });
    navCloseBtn.addEventListener("click", () => {
      navbarNav.classList.remove("active");
    });
  </script>
</body>

</html>