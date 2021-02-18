<!DOCTYPE html>
<html lang="en">
  <head>
    <title>About us | Play Dense</title>
    <meta charset="utf-8">

    <!-- CSS -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Roboto|Oxygen&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/master.css?t={{ts}}">
    <link rel="stylesheet" type="text/css" href="css/notifications.css?t={{ts}}">
    <link rel="stylesheet" type="text/css" href="css/subpage.css?t={{ts}}">
    <link rel="stylesheet" type="text/css" href="css/about-us.css?t={{ts}}">

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
              <img class="menu-anchor current-item" onclick="window.location.href='about-us';" src="resources/mail.png" alt="About us">
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
      <!-- About title -->
      <h1>About us</h1>
      <!-- About text -->
      <p>PlayDense is a unique service that creates fun and joy for friends and family.<br>We provide an array of entertainment rooms with different themes such as, Escape Rooms, Axe throwing rooms and Bowling rooms. Come join us and experience a fantastic adventure!</p>
      <!-- Separator -->
      <hr>
      <!-- Contact title -->
      <h1>Contact us</h1>
      <!-- Contact form -->
      <form id="contact" class="conact-form" action="about-us" method="post">
        <!-- Left text field -->
        <div class="input-area">
          <!-- Descriptive text -->
          <label for="full_name">Full Name</label>
          <!-- Text field -->
          <input type="name" name="full_name" placeholder="Max Mustermann" required autocomplete="on">
        </div>
        <!-- Right text field -->
        <div class="second-input-area">
          <!-- Descriptive text -->
          <label for="email_address">E-Mail Address</label>
          <!-- Text field -->
          <input type="email" name="email_address" placeholder="max@mustermail.com" required autocomplete="on">
        </div>
        <!-- Descriptive text -->
        <label for="message">Message</label>
        <!-- Text area (large text field) -->
        <textarea name="message" placeholder="Type your message here ..." required spellcheck="true" minlength="50"></textarea>
        <!-- reCAPTCHA field -->
        <input type="hidden" name="captcha" id="captcha">
        <!-- "Send" button -->
        <button type="submit">Send</button>
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
    <script src="https://www.google.com/recaptcha/api.js?render=6LdDUvoUAAAAAEQHi6hQbKVjVIwezGSGVOzui3RX"></script>
    <script type="text/javascript">
      <!-- reCAPTCHA -->
      grecaptcha.ready(function() {
        grecaptcha.execute('6LdDUvoUAAAAAEQHi6hQbKVjVIwezGSGVOzui3RX', {action: 'submit'}).then(function(token) {
          document.getElementById('captcha').value = token;
        });
      });
    </script>
  </body>
</html>

<?php
  //Import PHPMailer
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;

  //Load PHPMailer
  require 'vendor/autoload.php';

  //Check if form was submitted
  if(isset($_POST['full_name']) && isset($_POST['email_address']) && isset($_POST['message'])) {
    //Get reCAPTCHA results
    $request = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LdDUvoUAAAAAFUOgpDxdHTxciA9OuFuVJ8YPewu&response=" . $_POST["captcha"]);
    $request = json_decode($request);

    //Check if reCAPTCHA could check the results
    if($request->success){
      if($request->score >= 0.7){
				$mail = new PHPMailer(true);
				$emailAddress = "public@justix-dev.de";
				$emailPassword = "Me12345678!";

				try {
					$message = "You got a new message:<br><br>From: " . $_POST['full_name'] . "<br>Email: " . $_POST['email_address'] . "<br>Message: " . str_replace("\r\n", "<br>", getEntities($_POST['message']));

					//Set server and auth settings
					$mail->Timeout = 10;
			    $mail->isSMTP();
			    $mail->Host = 'cmail01.mc-host24.de';
			    $mail->SMTPSecure = 'tls';
			    $mail->Port = 25;
			    $mail->SMTPAuth = true;
			    $mail->Username = $emailAddress;
			    $mail->Password = $emailPassword;

          //Prepare mail
					$mail->setFrom($emailAddress, "System");
					$mail->addAddress("justix.dev@gmx.de", "Admin");
					$mail->isHTML(true);
					$mail->Subject = "New message!";
					$mail->Body = $message;
					$mail->AltBody = str_replace('<br>', '\n', str_replace('<br />', '<br>', $message));

          //Send mail
					$mail->send();

					echo "<script>infoHref('Your message was delivered successfully!', 'about-us');</script>";
				} catch (Exception $e) {
					echo "<script>error('" . $e->getMessage() . "');</script>";
				}
			} else {
				echo "<script>error('Your request was rejected:<br>You were recognized as a bot');</script>";
			}
		} else {
			$_POST = array();
			echo "<script>error('Captcha failed');</script>";
		}
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
