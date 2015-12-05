App.controller('ClipsController', ['$route', '$sce', '$routeParams', '$location', 'pages', 'urlhelper', '$window', function($route, $sce, $routeParams, $location, pages, urlhelper, $window) {
	$window.ga('send', 'pageview', location.pathname);
	urlhelper.currentSection = 'clips';
	this.localData = null;
	var localCtrl = this;
	this.routeParams = $routeParams;
	if(pages.favFlipped !== true){
		pages.favFlipped = true;
		$("link[type=\"image/x-icon\"]").attr("href","/favicon_CLIPS.png");
		$("link[type=\"image/png\"]").attr("href","/favicon_CLIPS.png");
	}

	$("body").removeClass();
	pages.getClipsData().then(function(passedData){
		// if(typeof(workCtrl.localData.id) !== "undefined"){
			// $("body").removeClass().addClass("p-"+workCtrl.localData.id);
		// } else {
		// }
		localCtrl.localData=passedData.data;
		localCtrl.totalPages = passedData.data.length;
		document.title = "CLIPS";
		$("body").addClass("clips");
	});

	var d = new Date();
	this.currYear = d.getFullYear();

	this.dateRender = function(passedDate, passedYear){
		if(passedYear == this.currYear){
			return passedDate;
		} else {
			return passedDate + ' ' + passedYear;
		}
	};

	this.trustHTML = function(passedMarkup){
		return $sce.trustAsHtml(passedMarkup);
	};
}]);

App.controller('ClipSingleController', ['$route', '$sce', '$routeParams', '$location', 'pages', 'urlhelper', '$window', function($route, $sce, $routeParams, $location, pages, urlhelper, $window) {
	$("body").addClass("clips-single");
	console.log("single");
}]);
