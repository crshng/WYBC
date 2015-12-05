App.filter('slice', function() {
	return function(arr, start, end) {
		if(arr.length > 0){
			return (arr || []).slice(start, end);
		}
	};
});