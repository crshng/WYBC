App.controller('MainController',['$route', '$routeParams', '$location','pages','$scope','urlhelper', function($route, $routeParams, $location,pages,$scope,urlhelper){
	var mainCtrl = this;
	this.pageFound = false;
	this.allData = null;
	pages.setLoading(true);
	$scope.urlhelper = urlhelper;
	$scope.pages = pages;
	this.currentYear = null;
	pages.getHomeData().then(function(passedData){
		pages.setLoading(false);
		mainCtrl.allData = passedData.data[0].projectData;
	});

	if($location.path().length > 3){
		pages.setActivePage($location.path());
	} else {
		pages.setActivePage('');
	}
}]);