App.controller('MainController',['$route', '$routeParams', '$location','pages','$scope','urlhelper', function($route, $routeParams, $location,pages,$scope,urlhelper){
	var mainCtrl = this;
	$scope.urlhelper = urlhelper;
	$scope.pages = pages;
	// $scope.pages.getNowPlaying().then(function(data) {
	// 	console.log(data);
	// });
}]);