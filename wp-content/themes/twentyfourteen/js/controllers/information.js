App.controller('InfoController', ['urlhelper','$window','pages', function(urlhelper,$window,pages) {
	$window.ga('send', 'pageview', location.pathname);
	document.title = "Information | CHIPS";
	if(pages.favFlipped === true){
		pages.favFlipped = false;
		$("link[type=\"image/x-icon\"]").attr("href","/favicon.png");
		$("link[type=\"image/png\"]").attr("href","/favicon.png");
	}
	$("body").removeClass().addClass("information");
	urlhelper.currentSection = 'information';
	this.isInView = function(inview, el , inviewpart, event){
		if (inview) {
			el.inViewTarget.children[1].play();
			this.viewPlay = el.inViewTarget.attributes.vid.nodeValue;
		} 
	};
}]);
