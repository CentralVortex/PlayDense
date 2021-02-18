<?php
  //Start the php session if it is not started yet
  if (session_status() == PHP_SESSION_NONE) {
    session_start();
  }

  //Redirect client to the sign-in page if he's not signed in
  if(!(isset($_SESSION['pd_session_id']))) {
    header("location: sign-in");
  }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Games | Play Dense</title>
    <meta charset="utf-8">

    <!-- CSS -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Roboto|Oxygen&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/master.css?t={{ts}}">
    <link rel="stylesheet" type="text/css" href="css/notifications.css?t={{ts}}">
    <link rel="stylesheet" type="text/css" href="css/subpage.css?t={{ts}}">
    <link rel="stylesheet" type="text/css" href="css/rooms.css?t={{ts}}">

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
      <!-- Rooms container -->
      <div class="rooms">
        <!-- "Order a room" title -->
        <h1>Order a room</h1>
        <?php
          //Connect to the mysql server and select the database
          $con = mysqli_connect("localhost", "root", "Mdg5edL,dwOe");
          mysqli_select_db($con, "private");

          //Create the mysql table for the rooms if it does not exist yet
          mysqli_query($con, "CREATE TABLE IF NOT EXISTS `pd_rooms` (id varchar(256) PRIMARY KEY NOT NULL, user_id int, user_full_name varchar(96), user_phone varchar(96))");

          //Initiate the important settings (times, types and themes)
          $times = ['10.00', '11.00', '12.00', '13.00', '14.00', '15.00', '16.00', '17.00', '18.00', '19.00', '20.00', '21.00'];
          $types = ['Escape', 'Axe throwing', 'Bowling'];
          $themes = ['Disco', 'Jungle', 'Retro', 'Theatre', 'Candy', 'Weather'];

          //[Date][Time][Room][Theme] = available
          $rooms = [];

          //Set the time zone of PHP to Europe/Berlin
          date_default_timezone_set("Europe/Berlin");

          //Loop through the code below 7 times -> 1 for each day
          for ($i=1; $i <= 7; $i++) {
            //Get the current date
            $date = new \DateTime();
            //Add the days (= index $i of the loop) to the current date
            $date->add(\DateInterval::createFromDateString($i . ' days'));

            //Convert the date to the american date format
            $dateString = date_format($date, 'm/d/Y');

            //Initiate the data for the date
            $rooms[$dateString] = [];

            //Loop through all the times
            foreach ($times as $time) {
              //Initiate the data for the time
              $rooms[$dateString][$time] = [];

              //Loop through all the types
              foreach ($types as $type) {
                //Initiate the data for the type
                $rooms[$dateString][$time][$type] = [];

                //Loop through all the themes
                foreach ($themes as $theme) {
                  //Initiate the data for the theme
                  $rooms[$dateString][$time][$type][$theme] = -1;
                }
              }
            }
          }

          //Get the mysql data from the rooms table (= ordered rooms)
          $orderedRooms = mysqli_query($con, "SELECT * FROM `pd_rooms`");

          //Check if there are any ordered rooms
          if($orderedRooms->num_rows > 0){
            //Go through the ordered rooms one by one
            while ($row = $orderedRooms->fetch_assoc()) {
              //Split the id (= DATE;TIME;TYPE;THEME)
              $data = explode(';', $row['id']);

              //Change the value of the theme to the user id -> ordered
              $rooms[$data[0]][$data[1]][$data[2]][$data[3]] = $row['user_id'];
            }
          }

          //Initiate the rooms string (= what should be added to the html)
          $roomsString = "";

          //Loop through the data of the dates
          foreach ($rooms as $dateString => $times) {
            //The date should be disabled if there is no room available
            $dateDisabled = true;

            //Loop through all data to check if there is a room available, if there is one (user id is not -1), the date will not be disabled
            foreach ($times as $time => $types) {
              foreach ($types as $type => $themes) {
                foreach ($themes as $theme => $userId) {
                  if($userId == -1)
                    $dateDisabled = false;
                }
              }
            }

            //Add the date to the html and add the "disabled" class if there is no room available
            $roomsString .= "<div class='room-date" . ($dateDisabled ? " disabled" : "") . "'><span class='room-subtitle'>" . date_format(date_create($dateString), 'l, m/d/Y') . "</span>";

            //Loop through the data of the times
            foreach ($times as $time => $types) {
              //The time should be disabled if there is no room available
              $timeDisabled = true;

              //Loop through all data to check if there is a room available, if there is one (user id is not -1), the date will not be disabled
              foreach ($types as $type => $themes) {
                foreach ($themes as $theme => $userId) {
                  if($userId == -1)
                    $timeDisabled = false;
                }
              }

              //Add the time to the html and add the "disabled" class if there is no room available
              $roomsString .= "<div class='room-time" . ($timeDisabled ? " disabled" : "") . "'><span class='room-subtitle'>" . $time . "</span>";

              //Loop through the data of the types
              foreach ($types as $type => $themes) {
                //The type should be disabled if there is no room available
                $typeDisabled = true;

                //Loop through all data to check if there is a room available, if there is one (user id is not -1), the date will not be disabled
                foreach ($themes as $theme => $userId) {
                  if($userId == -1)
                    $typeDisabled = false;
                }

                //Add the type to the html and add the "disabled" class if there is no room available
                $roomsString .= "<div class='room-type" . ($typeDisabled ? " disabled" : "") . "'><span class='room-subtitle'>" . $type . "</span>";

                //Loop through the data of the themes
                foreach ($themes as $theme => $userId) {
                  //Add the theme to the html and add the "disabled" class if it is not available, it it is available, one will be redirected to the order page by clicking on the theme
                  $roomsString .= "<span " . (($userId == -1) ? ("onclick='window.location.href=\"order?id=" . $dateString . ";" . $time . ";" . $type . ";" . $theme . "\";' class='room-theme'") : "class=\"room-theme disabled\"") . ">" . $theme . "</span>";
                }

                //Finish the html part for the type
                $roomsString .= "</div>";
              }

              //Finish the html part for the time
              $roomsString .= "</div>";
            }

            //Finish the html part for the date
            $roomsString .= "</div>";
          }

          //Add the rooms to the html
          echo $roomsString;
        ?>
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

    <!-- JavaScript -->
    <script type="text/javascript">
      //Prepare all elements with class "room-theme" (the <span> elements) for the fade in animation
      for (var element of document.getElementsByClassName('room-theme')) {
        element.style.transform = "translateY(-" + element.parentNode.clientHeight + "px)";
        element.style.display = "none";
      }

      //Prepare all elements with class "room-type" for the fade in animation and change the click listener
      for (var element of document.getElementsByClassName('room-type')) {
        element.onclick = onRoomButtonClick;
        element.style.transform = "translateY(-" + element.parentNode.clientHeight + "px)";
        element.style.display = "none";
      }

      //Prepare all elements with class "room-time" for the fade in animation and change the click listener
      for (var element of document.getElementsByClassName('room-time')) {
        element.onclick = onRoomButtonClick;
        element.style.transform = "translateY(-" + element.parentNode.clientHeight + "px)";
        element.style.display = "none";
      }

      //Change the click listener for all elements with class "room-date"
      for (var element of document.getElementsByClassName('room-date')) {
        element.onclick = onRoomButtonClick;
      }

      //Executed when a room button is clicked (date, time or type)
      function onRoomButtonClick(event) {
        //Only execute once, don't fire event for other elements
        event.stopPropagation();

        //Loop through all clicked elements, find the first <div> and start the fade in or fade out animation of it's content
        for (var pathElement of event.path) {
          if(pathElement.tagName === "DIV") {
            toggle(pathElement);
            break;
          }
        }
      }

      function toggle(element) {
        //Check if first element is "open" (= content visible) and invert it -> content is invisible: fade in content | otherwise: fade out content
        var fadeIn = !(element.children[0].classList.contains("open"));

        //Loop through all child elements
        for (var childElement of element.children) {
          //Add/remove the class "open" to make the check above work
          childElement.classList.toggle("open");

          //If the element is a div (= has child elements) or is a theme element (= last element, no children), start the fade in/out animation | otherwise: hide the text when the content is fading in, so that the title doesn't prevent the animation to look good, or start the fade out animation for the text
          if((childElement.tagName === "DIV") || childElement.classList.contains("room-theme")) {
            if(fadeIn)
              childElement.style.display = "inline-block";
            else
              childElement.style.transform = "translateY(-" + element.clientHeight + "px)";
          } else if(fadeIn)
            childElement.style.opacity = "0";
          else
            childElement.style.transform = "translateY(-" + element.clientHeight + "px)";
        }

        //If the content fades in, the transform style of the <div> elements and the theme elements will be removed to animate the elements
        //If the content fades out, the child elements will be removed from the page to minify the parent element or the transform style of the text will be removed to animate the text
        setTimeout(() => {
          for (var childElement of element.children) {
            if((childElement.tagName === "DIV") || childElement.classList.contains("room-theme")) {
              if(fadeIn)
                childElement.style.transform = "";
              else
                childElement.style.display = "none";
            } else if(!(fadeIn))
              childElement.style.transform = "";
          }
        }, fadeIn ? 0 : 400);

        //If the content fades in, the text will slowly appear when the content is almost at the end of the animation, so that it is visible when the other content is at the designated position
        if(fadeIn) {
          setTimeout(() => {
            for (var childElement of element.children) {
              if(!((childElement.tagName === "DIV") || childElement.classList.contains("room-theme")))
                childElement.style.opacity = "100";
            }
          }, 380);
        }
      }
    </script>
  </body>
</html>
