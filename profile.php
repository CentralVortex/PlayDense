<?php
  if (session_status() == PHP_SESSION_NONE){
    session_start();
  }

  if(!(isset($_SESSION['pd_session_id']))) {
    header("location: sign-in");
  }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Profile | Play Dense</title>
    <meta charset="utf-8">

    <!-- CSS -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Roboto|Oxygen&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/master.css?t={{ts}}">
    <link rel="stylesheet" type="text/css" href="css/notifications.css?t={{ts}}">
    <link rel="stylesheet" type="text/css" href="css/subpage.css?t={{ts}}">
    <link rel="stylesheet" type="text/css" href="css/profile.css?t={{ts}}">

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
      <!-- Logo -->
      <img class="header-logo" onclick="window.location.href='./';" src="resources/logo.png" alt="Logo">
      <!-- Menu -->
      <div class="menu-container">
        <ul class="menu">
          <li class="menu-item">
            <abbr title="Home">
              <img class="menu-anchor" onclick="window.location.href='./';" src="resources/house.png" alt="Home">
            </abbr>
          </li>
          <li class="menu-item">
            <abbr title="Rooms">
              <img class="menu-anchor" onclick="window.location.href='rooms';" src="resources/dice.png" alt="Rooms">
            </abbr>
          </li>
          <li class="menu-item">
            <abbr title="About us">
              <img class="menu-anchor" onclick="window.location.href='about-us';" src="resources/mail.png" alt="About us">
            </abbr>
          </li>
          <li class="menu-item">
            <abbr title="Profile">
              <img class="menu-anchor current-item" onclick="window.location.href='profile';" src="resources/user-icon.png" alt="Profile">
            </abbr>
          </li>
        </ul>
      </div>
    </div>

    <!-- Content -->
    <div class="content">
      <div class="ordered">
        <h1>Ordered rooms</h1>
        <div class="table-container">
          <table>
            <th>Date</th>
            <th>Time</th>
            <th>Type</th>
            <th>Theme</th>
            <th>Full name</th>
            <th>Phone</th>
            <th>E-Mail address</th>

            <?php
              $userId = $_SESSION['pd_session_id'];

              $con = mysqli_connect("localhost", "root", "Mdg5edL,dwOe");
              mysqli_select_db($con, "private");

              $rooms = mysqli_query($con, "SELECT * FROM `pd_rooms`" . (($userId == 1) ? "" : (" WHERE user_id='" . $userId . "'")));

              if($rooms->num_rows > 0) {
                while ($row = $rooms->fetch_assoc()) {
                  $data = explode(";", $row['id']);
                  $emailAddress = $_SESSION['pd_session_email_address'];

                  if($userId == 1) {
                    $users = mysqli_query($con, "SELECT * FROM `pd_users` WHERE id='" . $row['user_id'] . "'");

                    if($users->num_rows > 0) {
                      while ($row2 = $users->fetch_assoc()) {
                        $emailAddress = $row2['email'];
                      }
                    }
                  }

                  echo "<tr>
                          <td>" . $data[0] . "</td>
                          <td>" . $data[1] . "</td>
                          <td>" . $data[2] . "</td>
                          <td>" . $data[3] . "</td>
                          <td>" . $row['user_full_name'] . "</td>
                          <td>" . $row['user_phone'] . "</td>
                          <td>" . $emailAddress . "</td>
                        </tr>";
                }
              }
            ?>

          </table>
        </div>
        <hr>
        <button onclick="window.location.href='profile?logout'">Logout</button>
      </div>
    </div>

    <!-- Footer -->
    <div class="footer">
      <div class="footer-anchor-texts">
        <a class="footer-anchor-text" href="imprint">Imprint</a>
        <div class="footer-anchor-separator"></div>
        <a class="footer-anchor-text" href="about-us#contact">Contact</a>
      </div>

      <p class="footer-text">© 2021 Daniel Noam</p>
    </div>

    <?php
      if(isset($_GET['logout'])) {
        session_destroy();

        echo "<script>infoHref('Logged out successfully!', 'sign-in');</script>";
      }
    ?>
  </body>
</html>
