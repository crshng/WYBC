App.controller('archiveCtrl', ['$route', '$routeParams', 'pages', 'urlhelper', '$window', function($route, $routeParams, pages, urlhelper, $window) {
	$window.ga('send', 'pageview', location.pathname);
	urlhelper.currentSection = 'archive';
	if(pages.favFlipped === true){
		pages.favFlipped = false;
		$("link[type=\"image/x-icon\"]").attr("href","/favicon.png");
		$("link[type=\"image/png\"]").attr("href","/favicon.png");
	}
	var archiveCtrl = this;
	this.msg = 'asdf';
	this.hoverImg = null;
	this.hoverColor = null;
	// pages.list(function(pages) {
	// 	archiveCtrl.loading = false;
	// 	archiveCtrl.pages=pages;
	// 	archiveCtrl.totalPages = pages.length;
	// 	document.title = "Project Archive | CHIPS";
	// });

	pages.getWorkData().then(function(passedData){
		archiveCtrl.pages=passedData.data;
		archiveCtrl.totalPages = passedData.data.length;
		document.title = "Archive | CHIPS";
		$("body").removeClass().addClass("archive");
	});
}]);
