var App = angular.module('App', ['ngRoute', 'ngSanitize', 'angular-inview']);
App.config(function($routeProvider, $locationProvider) {
	$routeProvider
		.when('/', {
			// templateUrl: '/wp-content/themes/chips-ng/views/home.html'
			templateUrl: '/wp-content/uploads/data/home.html'
		})
		.when('/work/:name', {
			templateUrl : '/wp-content/themes/chips-ng/views/work.html'
		})
		.when('/clips/', {
			templateUrl : '/wp-content/themes/chips-ng/views/clips.html'
		})
		.when('/clips/:name', {
			templateUrl : '/wp-content/themes/chips-ng/views/clip.html'
		})
		.when('/info/', {
			templateUrl : '/wp-content/themes/chips-ng/views/info.html'
		})
		.when('/archive/', {
			templateUrl : '/wp-content/themes/chips-ng/views/archive.html',
			controller : 'archiveCtrl as archive'
		})
		.otherwise( {
			templateUrl : '/wp-content/themes/chips-ng/views/404.html'
		});
	$locationProvider.html5Mode(true);
}).run(function() {
	FastClick.attach(document.body);
});
// ipt src="http://chips.ng/wp-content/themes/chips-ng/bower_components/ryanmullins-angular-hammer/angular.hammer.js"></script>


window.requestAnimFrame = (function () {
	return window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || window.oRequestAnimationFrame || window.msRequestAnimationFrame || function (callback) {
		window.setTimeout(callback, 1000 / 60);
	};
})();

