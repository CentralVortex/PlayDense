//Show an error notification and redirect the client
function errorHref (msg, errorUrl) {
	//Add error notification to html body
	document.getElementsByClassName('container')[0].insertAdjacentHTML('beforeend', "<div class='background'><div class='error-notification'><h2 class='error-notification-title'>Error</h2><p>" + msg + "</p></div></div>");

	//Redirect client to given url after 2 seconds
	setTimeout((function() { removeNotification(document.getElementsByClassName('background')[0]); window.location.href=errorUrl; }), 1000 * 2);

	//Blur the whole page
	applyFilter("blur(8px)");
}

//Show an error notification
function error (msg) {
	//Add error notification to html body
	document.getElementsByClassName('container')[0].insertAdjacentHTML('beforeend', "<div class='background' onclick='removeNotification(this);'><div class='error-notification'><h2 class='error-notification-title'>Error</h2><p>" + msg + "</p></div></div>");

	//Blur the whole page
	applyFilter("blur(8px)");
}

//Show an info notification and redirect the client
function infoHref (msg, successUrl) {
	//Add info notification to html body
	document.getElementsByClassName('container')[0].insertAdjacentHTML('beforeend', "<div class='background' onclick='removeNotification(this);'><div class='info-notification'><h2 class='info-notification-title'>Info</h2><p>" + msg + "</p></div></div>");

	//Redirect client to given url after 2 seconds
	setTimeout((function() { removeNotification(document.getElementsByClassName('background')[0]); window.location.href=successUrl; }), 1000 * 2);

	//Blur the whole page
	applyFilter("blur(8px)");
}

//Show an info notification
function info (msg) {
	//Add info notification to html body
	document.getElementsByClassName('container')[0].insertAdjacentHTML('beforeend', "<div class='background' onclick='removeNotification(this);'><div class='info-notification'><h2 class='info-notification-title'>Info</h2><p>" + msg + "</p></div></div>");

	//Blur the whole page
	applyFilter("blur(8px)");
}

function removeNotification (element) {
	element.style.display = "none";

	//Blur the whole page
	applyFilter("none");
}

//Apply a filter to the header, content and footer (whole page)
function applyFilter (filter) {
	var header = document.getElementsByClassName('header')[0];
	var content = document.getElementsByClassName('content')[0];
	var footer = document.getElementsByClassName('footer')[0];

	if(header != null)
		header.style.filter = filter;

	if(content != null)
		content.style.filter = filter;

	if(footer != null)
		footer.style.filter = filter;
}
