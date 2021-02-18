<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Sign up | Play Dense</title>
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
      <!-- Sign up form -->
      <form class="signup-form" action="sign-up" method="post" onsubmit="return isValidForm()">
        <!-- Text fields -->
        <div class="inputs">
          <!-- Descriptive text for the text field -->
          <label for="email_address">E-Mail Address</label>
          <!-- Text field -->
          <input type="email" name="email_address" placeholder="max@mustermann.com" required autocomplete="on" maxlength="96">
          <!-- Descriptive text for the first password field -->
          <label for="password">Password</label>
          <!-- First password field -->
          <input id="password" type="password" name="password" required minlength="8">
          <!-- Descriptive text for the second password field -->
          <label for="password2">Confirm Password</label>
          <!-- Second password field -->
          <input id="password2" type="password" name="password2" required minlength="8">
        </div>
        <!-- "Sign up" button -->
        <button type="submit">Sign up</button>
        <!-- Additional text with a link to the "Sign in" page -->
        <p>Already have an account?<br><a href="sign-in">Sign in now</a></p>
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

    <!-- JavaScript -->
    <script type="text/javascript">
      //Executed before the form gets submitted
      function isValidForm() {
        //If the passwords match, submit the form
        if(document.getElementById("password").value === document.getElementById("password2").value)
          return true;

        //The passwords do not match, don't submit the form

        error("Passwords do not match!");

        return false;
      }
    </script>
  </body>
</html>

<?php
  //Check if form was submitted
  if(isset($_POST['email_address']) && isset($_POST['password']) && isset($_POST['password2'])) {
    $emailAddress = getEntities($_POST['email_address']);
    $password = getEntities($_POST['password']);
    $password2 = getEntities($_POST['password2']);

    //Check if the passwords match
    if($password == $password2) {
      //Check if the lenghts of the password and the email address are okay
      if((strlen($password) >= 8) && (strlen($password) <= 128) && (strlen($emailAddress) <= 96)) {
        //Connect to the mysql server and select the database
        $con = mysqli_connect("localhost", "root", "Mdg5edL,dwOe");
      	mysqli_select_db($con, "private");

        //Create the mysql table for the users if it does not exist yet
        mysqli_query($con, "CREATE TABLE IF NOT EXISTS `pd_users` (id int PRIMARY KEY NOT NULL AUTO_INCREMENT, email varchar(96), password text(512))");

        //Check if the email address is registered in the mysql table -> already in use
        if(mysqli_query($con, "SELECT * FROM `pd_users` WHERE email='" . $emailAddress . "'")->num_rows == 0) {
          //Insert the given email address and the encrypted given password into the mysql table -> sign up user
  				mysqli_query($con, "INSERT INTO `pd_users` (email, password) VALUES ('" . $emailAddress . "', '" . j25($password) . "')");

          echo "<script>infoHref('Signed up successfully!', 'sign-in');</script>";
        } else
          echo "<script>error('Email address already in use!');</script>";
      } else
        echo "<script>error('Password is too short!');</script>";
    } else
      echo "<script>error('Passwords do not match!');</script>";
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
