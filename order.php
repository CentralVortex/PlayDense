<?php
  //Start the php session if it is not started yet
  if (session_status() == PHP_SESSION_NONE) {
    session_start();
  }

  //Redirect client to the sign-in page if he's not signed in
  if(!(isset($_SESSION['pd_session_id']))) {
    header("location: sign-in");
  }

  //Redirect client to the rooms page if no room id is given
  if(!(isset($_GET['id'])) && !(isset($_POST['id']))) {
    header("location: rooms");
  }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Order | Play Dense</title>
    <meta charset="utf-8">

    <!-- CSS -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Roboto|Oxygen&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/master.css?t={{ts}}">
    <link rel="stylesheet" type="text/css" href="css/notifications.css?t={{ts}}">
    <link rel="stylesheet" type="text/css" href="css/subpage.css?t={{ts}}">
    <link rel="stylesheet" type="text/css" href="css/order.css?t={{ts}}">

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
        <!-- Menu list -->
        <ul class="menu">
          <!-- Menu items -->
          <li class="menu-item">
            <!-- Hover text -->
            <abbr title="Home">
              <!-- Home icon -->
              <img class="menu-anchor" onclick="window.location.href='./';" src="resources/house.png" alt="Home">
            </abbr>
          </li>
          <li class="menu-item">
            <!-- Hover text -->
            <abbr title="Rooms">
              <!-- Rooms/Games icon -->
              <img class="menu-anchor current-item" onclick="window.location.href='rooms';" src="resources/dice.png" alt="Rooms">
            </abbr>
          </li>
          <li class="menu-item">
            <!-- Hover text -->
            <abbr title="About us">
              <!-- About/Contact icon -->
              <img class="menu-anchor" onclick="window.location.href='about-us';" src="resources/mail.png" alt="About us">
            </abbr>
          </li>
          <li class="menu-item">
            <!-- Hover text -->
            <abbr title="Profile">
              <!-- Profile icon -->
              <img class="menu-anchor" onclick="window.location.href='profile';" src="resources/user-icon.png" alt="Profile">
            </abbr>
          </li>
        </ul>
      </div>
    </div>

    <!-- Content -->
    <div class="content">
      <!-- Order form -->
      <form class="order-form" action="order" method="post">
        <!-- Text fields -->
        <div class="inputs">
          <!-- Descriptive text for first text field -->
          <label for="full_name">Full Name</label>
          <!-- First text field -->
          <input type="name" name="full_name" placeholder="Max Mustermann" required autocomplete="on" maxlength="96">
          <!-- Descriptive text for second text field -->
          <label for="phone">Phone</label>
          <!-- Second text field -->
          <input type="tel" name="phone" placeholder="054 123 4567" required autocomplete="on">
        </div>
        <!-- Hidden input field to deliver the room id to the php code -->
        <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
        <!-- "Order" button -->
        <button type="submit">Order</button>
        <!-- New line -->
        <br>
        <!-- "Cancel order" button -->
        <button onclick="window.location.href='rooms';">Cancel order</button>
      </form>
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

<?php
  //Check if form was submitted
  if(isset($_POST['full_name']) && isset($_POST['phone']) && isset($_POST['id'])) {
    $id = $_POST['id'];

    //Connect to the mysql server and select the database
    $con = mysqli_connect("localhost", "root", "Mdg5edL,dwOe");
  	mysqli_select_db($con, "private");

    //Check if the room id is registered in the mysql table -> already ordered
    if(mysqli_query($con, "SELECT * FROM `pd_rooms` WHERE id='" . $id . "'")->num_rows == 0) {
      //Insert the room id and user information into the mysql table -> order room
			mysqli_query($con, "INSERT INTO `pd_rooms` (id, user_id, user_full_name, user_phone) VALUES ('" . $id . "', '" . $_SESSION['pd_session_id'] . "', '" . getEntities($_POST['full_name']) . "', '" . getEntities($_POST['phone']) . "')");

      echo "<script>infoHref('Ordered room successfully!', 'profile');</script>";
    } else
      echo "<script>errorHref('Someone ordered this room already!', 'rooms');</script>";
	}

  //Convert html text into plain utf8 text (to avoid sql injections)
	function getEntities($string) {
    $stringBuilder = "";
    $offset = 0;

    if ( empty( $string ) ) {
      return "";
    }

    while ( $offset >= 0 ) {
      $decValue = ordutf8( $string, $offset );
      $char = unichr($decValue);

      $htmlEntited = htmlentities( $char );

      if( $char != $htmlEntited ){
        $stringBuilder .= $htmlEntited;
      } elseif( $decValue >= 128 ){
        $stringBuilder .= "&#" . $decValue . ";";
      } else {
        $stringBuilder .= $char;
      }
    }

    return $stringBuilder;
	}

	function ordutf8($string, &$offset) {
    $code = ord(substr($string, $offset,1));

    if ($code >= 128) {
      if ($code < 224) $bytesnumber = 2;
      else if ($code < 240) $bytesnumber = 3;
      else if ($code < 248) $bytesnumber = 4;

      $codetemp = $code - 192 - ($bytesnumber > 2 ? 32 : 0) - ($bytesnumber > 3 ? 16 : 0);

      for ($i = 2; $i <= $bytesnumber; $i++) {
        $offset ++;
        $code2 = ord(substr($string, $offset, 1)) - 128;
        $codetemp = $codetemp*64 + $code2;
      }

      $code = $codetemp;
    }

    $offset += 1;

    if ($offset >= strlen($string)) $offset = -1;

    return $code;
	}

	function unichr($u) {
    return mb_convert_encoding('&#' . intval($u) . ';', 'UTF-8', 'HTML-ENTITIES');
	}
?>
