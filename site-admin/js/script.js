DomReady.ready(function() {
	var links = document.getElementsByClassName("user-delete");
	for (var i = links.length - 1; i >= 0; i--) {
		bindEvent(links[i], 'click', confirm_delete);
	}
});

var bindEvent = function(element, type, handler) {
    if (element.addEventListener) {
        element.addEventListener(type, handler, false);
    } else {
        element.attachEvent('on'+type, handler);
    }
};

function confirm_delete(e) {
	if ( !confirm("Do you really want to delete the user?") ) { e.preventDefault(); }
}

// after add name if path is not set then add path automatically
