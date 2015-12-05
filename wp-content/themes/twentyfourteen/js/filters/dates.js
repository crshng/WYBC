function parseDate(input) {
	var parts = input.split('-');
	return new Date(parts[2], parts[1], parts[0]); 
}

App.filter("datefilter", function() {
	return function(items, from, to) {
			// var df = parseDate(from);
			// var dt = parseDate(to);
			var dfa = Date.parse(from);
			var dta = Date.parse(to);
			var result = [];
			if(typeof(items) !== "undefined" && items !== null){
				for (var i=0; i<items.length; i++){
					var tf = new Date(items[i].start_date),
						tt = new Date(items[i].end_date);
					if (tf > dfa && tt < dta)  {
						result.push(items[i]);
					}
				}
			}
			return result;
	};
});