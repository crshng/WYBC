App.factory('pages', function($http,$q,$window){
	var currentPath = '';
	var latestPost = null;
	var isLoading = false;
	var favFlipped = false;
	return {
		favFlipped: favFlipped,
		getWorkData: function() {
			return $http.get('/wp-content/uploads/data/archive.js',{'cache':true});
		},
		getClipsData: function() {
			return $http.get('/wp-content/uploads/data/clips.js',{'cache':true});
		},
		getHomeData: function() {
			return $http.get('/wp-content/uploads/data/home.js',{'cache':true});
		},
		setActivePage:function(newPath){
			var finalNewPath = newPath.replace("/","").replace("/","");
			currentPath = finalNewPath;
		},
		setLoading: function(loadValue){
			isLoading = loadValue;
		},
	};
});