App.controller('workController', ['$scope','$sce','$route', '$routeParams', '$location', 'pages', 'urlhelper', '$window', function($scope, $sce, $route, $routeParams, $location, pages, urlhelper, $window) {
	$window.ga('send', 'pageview', location.pathname);
	urlhelper.currentSection = 'archive';
	if(pages.favFlipped === true){
		pages.favFlipped = false;
		$("link[type=\"image/x-icon\"]").attr("href","/favicon.png");
		$("link[type=\"image/png\"]").attr("href","/favicon.png");
	}

	var workCtrl = this;
	this.workName = $location.path();
	this.routeParams = $routeParams;
	this.singleWork = "";
	this.title = "";
	this.excerpt = "";
	this.links = "";
	this.contents = [];
	this.viewPlay = false;
	workCtrl.localData = null;

	this.isInView = function(inview, el , inviewpart, event){
		// console.log(el.inViewTarget.attributes.vid.nodeValue + ": " + inviewpart);
		// console.log($window.innerWidth/$window.innerHeight);
		if (el.inViewTarget.children[1].play && el.inViewTarget.children[1].pause) {
			if (inview) {
				el.inViewTarget.children[1].play();
				workCtrl.viewPlay = el.inViewTarget.attributes.vid.nodeValue;
			} else {
				el.inViewTarget.children[1].pause();
			} 
		}
	};
	pages.getWorkData().then(function(data) {
		workCtrl.localData = data.data.filter(function(entry){
			return entry.url === workCtrl.workName + "/";
		})[0];
		if(typeof(workCtrl.localData) !== "undefined"){
			if(typeof(workCtrl.localData.id) !== "undefined"){
				$("body").removeClass().addClass("p-"+workCtrl.localData.id);
			} else {
				$("body").removeClass();
			}
			document.title = workCtrl.htmldecode(workCtrl.localData.title) + " | CHIPS";
		} else {
			console.log("404");
		}
	});
	this.trustHTML = function(passedMarkup){
		return $sce.trustAsHtml(passedMarkup);
	};
	this.captionWidthClass = function(captionContainer){
		if(captionContainer.caption_positioning !== "bottom"){
			var gridW = captionContainer.grid_cols;
			var gridO = captionContainer.grid_offset;
			var finalClass = "";
			if(captionContainer.caption_positioning === "left"){
				finalClass = " g"+gridO;
				return finalClass;
			} else if(captionContainer.caption_positioning === "right"){
				finalClass = " g"+(8 - gridW - gridO) + " o" + (gridW + gridO);
				return finalClass;
			} else {
				return "";
			}
		}
	};
	this.col2Width = function(col1W,col1O){
		return "g"+(8 - col1W - col1O);
	};
	this.htmldecode = function(html) {
	    var div = document.createElement('div');
	    div.innerHTML = html;
	    return div.innerText;
	};

}]);
