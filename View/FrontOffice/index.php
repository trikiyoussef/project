<?php
session_start();

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['id']);
$id = $isLoggedIn ? $_SESSION['id'] : null;

// Handle logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
  session_destroy();
  header('Location: index.php'); // Redirect to the homepage after logout
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Heritage Tunisie</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Cardo:ital,wght@0,400;0,700;1,400;1,700&display=swap"
    rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="./assets/css/main.css" rel="stylesheet">

  <style>
    .login-button {
      padding: 10px 20px;
      font-weight: bold;
      background-color: #ffffff;
      color: #27a776;
      border: 2px solid #27a776;
      border-radius: 5px;
      text-decoration: none;
      display: inline-block;
      cursor: pointer;
    }

    .login-button:hover {
      background-color: #27a776;
      color: #ffffff;
    }

    #userInfo {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    #userInfo img {
      width: 40px;
      height: 40px;
      border-radius: 50%;
    }

    #signOutButton {
      padding: 5px 10px;
      border: none;
      background-color: #f44336;
      color: white;
      border-radius: 5px;
      cursor: pointer;
    }
  </style>
</head>

<body class="index-page">

  <header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container-fluid position-relative d-flex align-items-center justify-content-between">

      <a href="index.php" class="logo d-flex align-items-center me-auto me-xl-0">
        <img src="assets/img/logo.png" alt="">
        <h1 class="sitename">Heritage Tunisie</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="index.php" class="active">Home</a></li>
          <li class="dropdown"><a href="gallery.html"><span>Locations</span> <i
                class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="gallery.html">Museums</a></li>
              <li><a href="gallery.html">Monuments</a></li>
              <li><a href="gallery.html">Archaeological Sites</a></li>
              <li><a href="gallery.html">Historic Towns</a></li>
              <li><a href="gallery.html">Nature</a></li>
            </ul>
          </li>
          <li><a href="contact.html">Contact</a></li>
          <li><a href="about.html">About</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

      <!-- User Info or Login Button -->
      <?php if ($isLoggedIn): ?>
        <div id="userInfo">
          <img src="assets/img/humanavatar.png" alt="User Avatar">
          <span>
            <?= htmlspecialchars($id) ?>
          </span>
          <a href="user.php" class="btn btn-primary">Profile</a>
          <a href="index.php?action=logout" id="signOutButton">Sign Out</a>
        </div>
      <?php else: ?>
        <a href="login.php" id="loginButton" class="login-button">Login</a>
      <?php endif; ?>
    </div>
  </header>

  <main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-6 text-center" data-aos="fade-up" data-aos-delay="100">
            <h2>Welcome to <span class="underlight">Heritage Tunisie</span></h2>
            <p>Discover the wonders of Tunisia's history and culture.</p>
            <a href="contact.html" class="btn-get-started">Contact Us</a>
          </div>
        </div>
      </div>
    </section>

  </main>

  <footer id="footer" class="footer">
    <div class="container">
      <div class="copyright text-center ">
        <p>© <span>Copyright</span> <strong class="px-1 sitename">Heritage Tunisie</strong> <span>All Rights
            Reserved</span>
        </p>
      </div>
    </div>
  </footer>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="./assets/js/main.js"></script>
</body>

</html>