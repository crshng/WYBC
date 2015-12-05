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
		// getSinglePage: function(pageName){
		// 	// getData(function(data) {
		// 	// 	var work = data.filter(function(entry){
		// 	// 		return entry.url === pageName + "/";
		// 	// 	})[0];
		// 	// 	callback(work);
		// 	// });
		// 	var work = $http.get('/site/json/?dType=work',{'cache':true}).filter(function(entry){
		// 			return entry.url === pageName + "/";
		// 		})[0];
		// 	console.log(work);
		// },
		getLoading: function(){
			return isLoading;
		},
		gaClick: function(category, action, label) {
			$window.ga('send', {
				hitType: 'event',
				eventCategory: category,
				eventAction: action,
				eventLabel: label
			});
		},
	};
});