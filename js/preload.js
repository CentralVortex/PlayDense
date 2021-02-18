//Define html elements and initiate the timer
const PRELOAD = document.querySelector(".preload"), PRELOAD_CONTAINER = document.querySelector(".preload-container");
var timerCount = 0;

//Start the animation (tick: every 0.2 seconds)
var timer = setInterval(function() {
  //If the timer is 3, go back to 0
  if(timerCount == 3)
    timerCount = 0;

  //Change the opacity of the dots depending on the timer
  PRELOAD_CONTAINER.children[(timerCount == 0) ? 2 : (timerCount - 1)].style.opacity = 0;
  PRELOAD_CONTAINER.children[timerCount].style.opacity = 1;

  //Increase the timer
  timerCount++;
}, 200);

//When everything is loaded, stop the animation and show the page
window.onload = function() {
  //Use a timeout of 0.6s (3 ticks) to show all the dots at least once
  setTimeout(function() {
    //Hide the overlay
    PRELOAD.style.opacity = 0;

    //Cancel the animation
    clearInterval(timer);

    //Remove the overlay
    setTimeout(function() {
      PRELOAD.parentElement.removeChild(PRELOAD);
    }, 500);
  }, 600);
};
