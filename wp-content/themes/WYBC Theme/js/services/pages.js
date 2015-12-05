App.factory('pages', function($http,$q,$window){
	var currentPath = '';
	var latestPost = null;
	var isLoading = false;
	var favFlipped = false;
	return {
		favFlipped: favFlipped,
		getNowPlaying: function() {
			return $http.get('http://wybc.com/last_played.php?num=1');
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