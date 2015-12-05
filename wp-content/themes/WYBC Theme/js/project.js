var App = angular.module('App', ['ngRoute', 'ngSanitize']);
App.config(function($routeProvider, $locationProvider) {
	$routeProvider
		.when('/', {
			templateUrl: '/wp-content/themes/WYBC Theme/views/home.html'
		})
		.when('/djs', {
			templateUrl: '/wp-content/themes/WYBC Theme/views/djs.html'
		})
		.when('/schedule', {
			templateUrl: '/wp-content/themes/WYBC Theme/views/schedule.html'
		})
		.when('/shows', {
			templateUrl: '/wp-content/themes/WYBC Theme/views/shows.html'
		})
		.when('/about', {
			templateUrl: '/wp-content/themes/WYBC Theme/views/about.html'
		})
		.when('/zine', {
			templateUrl: '/wp-content/themes/WYBC Theme/views/zine.html'
		})
		.otherwise( {
			templateUrl : '/wp-content/themes/WYBC Theme/views/404.html'
		});
	$locationProvider.html5Mode(true);
}).run(function() {
	FastClick.attach(document.body);
});
// ipt src="http://chips.ng/wp-content/themes/WYBC Theme/bower_components/ryanmullins-angular-hammer/angular.hammer.js"></script>


window.requestAnimFrame = (function () {
	return window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || window.oRequestAnimationFrame || window.msRequestAnimationFrame || function (callback) {
		window.setTimeout(callback, 1000 / 60);
	};
})();

