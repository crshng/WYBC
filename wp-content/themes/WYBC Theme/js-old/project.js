var App = angular.module('App', ['ngRoute', 'ngSanitize', 'ngCookies', 'hmTouchEvents', 'angular-inview']);
App.config(function($routeProvider, $locationProvider) {
	$routeProvider
		.when('/', {
			templateUrl: '/views/main.html',
			controller : 'mainController as main'
		})
		.when('/work/:name', {
			templateUrl : '/views/work.html',
			controller : 'workCtrl as work'
		})
		.when('/clips/:name', {
			templateUrl : '/views/work.html',
			controller : 'workCtrl as work'
		})
		.when('/info/', {
			templateUrl : '/views/info.html',
			// controller : 'infoCtrl as work'
		})
		.when('/archive/', {
			templateUrl : '/views/archive.html',
			controller : 'archiveCtrl as archive'
		})
		.when('/clips/', {
			templateUrl : '/views/clips.html',
			controller : 'clipsCtrl as clip'
		})
		.otherwise( {
			templateUrl : '/views/404.html',
			controller  : 'notFoundController'
		});
	$locationProvider.html5Mode(true);
});

App.factory('pages', function($http){
	function getData(callback){
		var JSONUrl = "/data-grab.php";
		var methodType = "GET";
		$http({
			method: methodType,
			url: JSONUrl,
			cache: true
		}).success(callback);
	}
	function getDataHome(callback){
		var JSONUrl = "/data-grab-home.php";
		var methodType = "GET";
		$http({
			method: methodType,
			url: JSONUrl,
			cache: true
		}).success(callback);
	}
	return {
		list: getData,
		getSinglePage: function(pageName, callback){
			getData(function(data) {
				var work = data.filter(function(entry){
					return entry.url === pageName + "/";
				})[0];
				callback(work);
			});
		},
		getHomePage: function(callback){
			getDataHome(function(data) {
				var work = data;
				callback(work);
			});
		},
	};
});

App.controller('archiveCtrl', ['$route', '$routeParams', '$location', 'pages', function($route, $routeParams, $location, pages) {
	var archiveCtrl = this;
	this.msg = 'asdf';
	pages.list(function(pages) {
		archiveCtrl.loading = false;
		archiveCtrl.pages=pages;
		archiveCtrl.totalPages = pages.length;
		document.title = "Project Archive | CHIPS";
	});

}]);

App.controller('clipsCtrl', ['$route', '$routeParams', '$location', 'pages', function($route, $routeParams, $location, pages) {
	var clipsCtrl = this;
	this.msg = 'asdf';
	pages.list(function(pages) {
		clipsCtrl.loading = false;
		clipsCtrl.pages=pages;
		clipsCtrl.totalPages = pages.length;
		document.title = "CLIPS";
	});

}]);

App.controller('workCtrl', ['$route', '$routeParams', '$location', 'pages', function($route, $routeParams, $location, pages) {
	var workCtrl = this;
	this.workName = $location.path();
	this.routeParams = $routeParams;
	this.singleWork = "";
	this.title = "";
	this.excerpt = "";
	this.links = "";
	this.contents = [];
	this.viewPlay = false;
	this.isInView = function(inview, event){
		if(inview === true){
			workCtrl.viewPlay = true;
		} else {
			workCtrl.viewPlay = false;
		}
	};
	pages.getSinglePage(this.workName, function(data) {
		workCtrl.singleWork = data;
		workCtrl.title = data.title;
		workCtrl.excerpt = data.excerpt;
		workCtrl.links = data.links;
		document.title = htmldecode(workCtrl.title) + " | CHIPS";
	});
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
		//console.log(captionContainer);
	};
	this.col2Width = function(col1W,col1O){
		return "g"+(8 - col1W - col1O);		
	};
}]);

function htmlencode(html) {
    var div = document.createElement('div');
    div.innerText = html;
    return div.innerHTML;
}

function htmldecode(html) {
    var div = document.createElement('div');
    div.innerHTML = html;
    return div.innerText;
}

App.controller('mainController',['$http','$routeParams','$scope', '$location', 'pages', '$timeout', function($http, $routeParams, $scope, $location, pages, $timeout){
	var mainCtrl = this;
	// this.showHome = true;
	// $scope.$on('$routeChangeSuccess', function() {
	// 	var path = $location.path().split("/")[1];
	// 	if (path === "work") {
	// 		mainCtrl.showHome = false;
	// 	} else {
	// 		mainCtrl.showHome = true;
	// 	}
	// });
	pages.getHomePage(function(data) {
		mainCtrl.work = data[0].projectData;
		// mainCtrl.title = data.title;
		// mainCtrl.excerpt = data.excerpt;
		// mainCtrl.links = data.links;
		document.title = "CHIPS";
	});
	this.checkScroll = setInterval(function(){
		if(mainCtrl.hasScrolled === true){
			mainCtrl.triggerScroll();
			mainCtrl.hasScrolled = false;
		}
	},1000);

	$("#pages").bind("scroll",function(){
		mainCtrl.hasScrolled = true;
	});

	this.triggerScroll = function(){
		// console.log("scrolled");
	};

	this.linkClick = function(event){
		event.preventDefault();
	};

	pages.list(function(pages) {
		mainCtrl.loading = false;
		mainCtrl.pages=pages;
		mainCtrl.totalPages = pages.length;
	});

	this.archiveOnly = function(page){
		if(typeof(page.cats[0]) !== "undefined"){
			return !(page.cats[0].name == 'clips' || page.cats[0].name == 'Instagram');
		} else {
			return true;
		}
	};

	this.clipsOnly = function(page){
		if(typeof(page.cats[0]) !== "undefined"){
			return (page.cats[0].name == 'clips');
		} else {
			return false;
		}
	};

}]);

App.directive('videoCreator', function(){
	this.allowPlay = true;
	var vidL = this;
	var vidPlayRef = vidPlay;
	this.checkpoint = function(){
		console.log("Charlie!");
	};
	return {
		restrict: 'E',
		scope: {
			title: '@'
		},
		templateUrl: '/views/video.html',
		link: function ($scope, element, attrs) {
			element.bind('click', function () {
				if(vidL.allowPlay === true){
					vidL.allowPlay = false;
					player.pause();
				} else {
					vidL.allowPlay = true;
					player.play();
				}
			});
			element.bind('mouseenter', function () {
				if(vidL.allowPlay === true){ player.play(); }
			});
			element.bind('mouseleave', function () {
				if(vidL.allowPlay === true){ player.pause(); }
			});

			var setup = {
				'techOrder' : ['html5', 'flash'],
				'controls' : false,
				'preload' : 'auto',
				'autoplay' : false,
				'height' : 480,
				'width' : 854
			};

			var randID = Math.floor(Math.random() * 1000);
			var videoid = randID;
			attrs.id = "videojs" + videoid;
			var vidEl = $(element).find("video");
			vidEl.attr("ID",attrs.id);
			vidEl.attr("src",attrs.src);
			vidEl.attr("poster",attrs.poster);
			vidEl.attr("type","video/mp4");
			var player = _V_(attrs.id, setup, function(){

			});
		}
	};
});

App.directive('vimeo', function(){
	this.allowPlay = true;
	var vidL = this;


	return {
		restrict: 'E',
		replace: true,
		scope: {
			title: '@'
		},
		templateUrl: '/views/video-iframe.html',
		link: function ($scope, element, attrs) {
			// $scope.$watch(attrs.viewPlay, function(){console.log('changed');});
			var randID = "Vid"+Math.floor(Math.random() * 1000000000);
			element[0].setAttribute("id",randID);
			// console.log(attrs.ui);

			var url = "http://player.vimeo.com/video/" + attrs.vid + "?title=0&byline=0&portrait=0&loop=1&api=1&player_id="+randID;			
			if (attrs.ui == "scroll") {
				url = url + "&autoplay=true";

			}
			element.attr('src', url);
			var iframe = element[0];
			player = $f(iframe);
			var localPlayer = player;
		
			localPlayer.addEvent('ready', function() {
				// if (attrs.mute == "true") {
					localPlayer.api('setVolume', 0);
				// }
				if (attrs.ui == "hover") {
					element.bind('click', function () {
						if(vidL.allowPlay === true){
							vidL.allowPlay = false;
							localPlayer.api("pause");
						} else {
							vidL.allowPlay = true;
							localPlayer.api("play");
						}
					});
					element.bind('mouseenter', function () {
						if(vidL.allowPlay === true){ localPlayer.api("play"); }
					});
					element.bind('mouseleave', function () {
						if(vidL.allowPlay === true){ localPlayer.api("pause"); }
					});
				}
				if (attrs.ui == "scroll") {
					attrs.$observe('viewPlay', function(value) {
						if (attrs.inView) {
							if (value == "true") {
							localPlayer.api("play");
							} else {
								localPlayer.api("pause");
							}
						}
					});
				}
			});
		},
	};
});
