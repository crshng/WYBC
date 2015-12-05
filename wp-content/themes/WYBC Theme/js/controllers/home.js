App.controller('HomeController',['urlhelper','pages', '$window', function(urlhelper,pages,$window){
	$window.ga('send', 'pageview', location.pathname);
	urlhelper.currentSection = 'home';
	if(pages.favFlipped === true){
		pages.favFlipped = false;
		$("link[type=\"image/x-icon\"]").attr("href","/favicon.png");
		$("link[type=\"image/png\"]").attr("href","/favicon.png");
	}


	var localCtrl = this;
	this.localData = null;
	pages.setLoading(true);
	document.title = "CHIPS";
	$("body").removeClass().addClass("home");
	pages.getHomeData().then(function(passedData){
		pages.setLoading(false);
		localCtrl.localData = passedData.data[0].projectData;
	});

	// if($location.path().length > 3){
	// 	pages.setActivePage($location.path());
	// } else {
	// 	pages.setActivePage('');
	// }
}]);
