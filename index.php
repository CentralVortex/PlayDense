<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Home | Play Dense</title>
    <meta charset="utf-8">

    <!-- CSS -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Roboto|Oxygen&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/master.css?t={{ts}}">
    <link rel="stylesheet" type="text/css" href="css/notifications.css?t={{ts}}">
    <link rel="stylesheet" type="text/css" href="css/home.css?t={{ts}}">

    <!-- JavaScript -->
    <script src="js/notifications.js" charset="utf-8"></script>

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/png" href="resources/favicon.png" sizes="64x64">

    <!-- Mobile optimization -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">

    <!-- No cache -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
		<meta http-equiv="Pragma" content="no-cache">
		<meta http-equiv="Expires" content="0">
  </head>
  <body class="container">
    <!-- Preload -->
    <div class="preload">
      <!-- Preload container -->
      <div class="preload-container">
        <!-- Preload dots -->
        <div class="preload-dot"></div>
        <div class="preload-dot"></div>
        <div class="preload-dot"></div>
      </div>
    </div>

    <!-- Preload JavaScript -->
    <script src="js/preload.js" charset="utf-8"></script>

    <!-- Header -->
    <div class="header">
      <!-- Overlay -->
      <div class="header-overlay">
        <!-- Header content -->
        <div class="header-content">
          <!-- Logo -->
          <img class="header-logo" onclick="window.location.href='./';" src="resources/logo.png" alt="Logo">
          <!-- Menu -->
          <div class="menu-container">
            <!-- Menu list -->
            <ul class="menu">
              <!-- Menu items -->
              <li class="upper-menu-item">
                <!-- Hover text -->
                <abbr title="Home">
                  <!-- Home icon -->
                  <img class="menu-anchor current-item" onclick="window.location.href='./';" src="resources/house.png" alt="Home">
                </abbr>
              </li>
              <li class="menu-item">
                <!-- Hover text -->
                <abbr title="Rooms">
                  <!-- Rooms/Games icon -->
                  <img class="menu-anchor" onclick="window.location.href='rooms';" src="resources/dice.png" alt="Rooms">
                </abbr>
              </li>
              <li class="menu-item">
                <!-- Hover text -->
                <abbr title="About us">
                  <!-- About/Contact icon -->
                  <img class="menu-anchor" onclick="window.location.href='about-us';" src="resources/mail.png" alt="About us">
                </abbr>
              </li>
              <li class="upper-menu-item">
                <!-- Hover text -->
                <abbr title="Profile">
                  <!-- Profile icon -->
                  <img class="menu-anchor" onclick="window.location.href='profile';" src="resources/user-icon.png" alt="Profile">
                </abbr>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <!-- Footer -->
    <div class="footer">
      <!-- Footer links -->
      <div class="footer-anchor-texts">
        <!-- Left link -->
        <a class="footer-anchor-text" href="imprint">Imprint</a>
        <!-- Inline separator -->
        <div class="footer-anchor-separator"></div>
        <!-- Right link -->
        <a class="footer-anchor-text" href="about-us#contact">Contact</a>
      </div>

      <!-- Copyright text -->
      <p class="footer-text">© 2021 Daniel Noam</p>
    </div>
  </body>
</html>
