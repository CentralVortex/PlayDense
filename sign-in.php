<?php
  //Start the php session if it is not started yet
  if (session_status() == PHP_SESSION_NONE) {
    session_start();
  }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Sign in | Play Dense</title>
    <meta charset="utf-8">

    <!-- CSS -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Roboto|Oxygen&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/master.css?t={{ts}}">
    <link rel="stylesheet" type="text/css" href="css/notifications.css?t={{ts}}">
    <link rel="stylesheet" type="text/css" href="css/subpage.css?t={{ts}}">
    <link rel="stylesheet" type="text/css" href="css/signin-signup.css?t={{ts}}">

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
          <li class="menu-item">
            <!-- Hover text -->
            <abbr title="Profile">
              <!-- Profile icon -->
              <img class="menu-anchor current-item" onclick="window.location.href='profile';" src="resources/user-icon.png" alt="Profile">
            </abbr>
          </li>
        </ul>
      </div>
    </div>

    <!-- Content -->
    <div class="content">
      <!-- Sign in form -->
      <form class="signin-form" action="sign-in" method="post">
        <!-- Text fields -->
        <div class="inputs">
          <!-- Descriptive text for the text field -->
          <label for="email_address">E-Mail Address</label>
          <!-- Text field -->
          <input type="email" name="email_address" placeholder="Max Mustermann" required autocomplete="on">
          <!-- Descriptive text for the password field -->
          <label for="password">Password</label>
          <!-- Password field -->
          <input type="password" name="password" required>
        </div>
        <!-- "Sign in" button -->
        <button type="submit">Sign in</button>
        <!-- Additional text with a link to the "Sign up" page -->
        <p>Don't have an account yet?<br><a href="sign-up">Create one now</a></p>
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
  if(isset($_POST['email_address']) && isset($_POST['password'])) {
    $emailAddress = getEntities($_POST['email_address']);
    $password = getEntities($_POST['password']);

    //Connect to the mysql server and select the database
    $con = mysqli_connect("localhost", "root", "Mdg5edL,dwOe");
    mysqli_select_db($con, "private");

    //Get all the results from the mysql table where the email address is the given email address and the password is the encrypted given password
    $users = mysqli_query($con, "SELECT * FROM `pd_users` WHERE email='" . $emailAddress . "' AND password='" . j25($password) . "'");

    //Check if there are any results -> email address is registered
    if($users->num_rows == 1) {
      //Go through the results and stop after the first result
      while ($row = $users->fetch_assoc()) {
        //Save the user id and the email address in the PHP session
        $_SESSION['pd_session_id'] = $row['id'];
        $_SESSION['pd_session_email_address'] = $row['email'];

        echo "<script>infoHref('Signed in successfully!', './');</script>";
        break;
      }
    } else
      echo "<script>error('Invalid email address or password!');</script>";
  }

  //Encrypt the given text
  function j25($stringToEncrypt) {
    $result = "";
    $len = strlen($stringToEncrypt);

    for ($k = 0; $k < ((int) sqrt($len * 9 / ((int) sqrt($len)))); $k++) {
      for($i = 0; $i < $len; $i++) {
        $charCode = (int) (ord(strrev($stringToEncrypt)[$i]) * 13 / $len);

        $charCode -= $len * $k;

        if($charCode < 0) {
          $charCode *= -1;
        }

        if($charCode > 127) {
          for ($j = 0; $j < ((int) ($charCode / 127)); $j++) {
            $charCode -= 127 - ($j * 23);
          }
        }

        if($charCode < 48) {
          $charCode += 48;
        }

        if(($charCode > 57) && ($charCode < 65)) {
          $charCode += 8;
        }

        if(($charCode > 90) && ($charCode < 97)) {
          $charCode += 7;
        }

        if($charCode > 122) {
          $charCode -= 12;
        }

        if(($charCode > 31) && ($charCode < 63)) {
          $result .= (int) sqrt($charCode);
        } else {
          $result .= chr($charCode);
        }

        if(strlen($result) > 3) {
          $result = substr($result, 3) . substr($result, 0, 3);
        }
      }
    }

    return $result;
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
