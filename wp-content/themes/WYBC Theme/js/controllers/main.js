App.controller('MainController',['$route', '$routeParams', '$location','pages','$scope','urlhelper', '$sce', function($route, $routeParams, $location,pages,$scope,urlhelper, $sce){
	var mainCtrl = this;
	$scope.urlhelper = urlhelper;
	$scope.pages = pages;
	$scope.source = "http://wybc.com:8000/x.mp3";
	// $scope.pages.getNowPlaying().then(function(data) {
	// 	console.log(data);
	// });
	$scope.clearURL = function() {
        return $sce.trustAsResourceUrl($scope.source);
    };
}]);