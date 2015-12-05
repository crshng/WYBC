
	App.directive('hoverThumb', function() {
		return {
			restrict: 'A',
			link: function(scope, element){
				if (element[0].className === "overlayLink") {
					element.bind('mouseover', function() {
						angular.element(element).siblings('img').addClass("hovered");
					});
					element.bind('mouseout',function(){
						angular.element(element).siblings('img').removeClass("hovered");
					});
				} else {
					element.bind('mouseover', function() {
						element.addClass("hovered");
					});
					element.bind('mouseout',function(){
						element.removeClass("hovered");
					});
				}
			}
		};
	});

	App.directive('hoverClass', function() {
		return {
			restrict: 'C',
			link: function(scope, element){
				element.bind('mouseover', function() {
					element.addClass("hovered");
				});
				element.bind('mouseout',function(){
					element.removeClass("hovered");
				});
			}
		};
	});

	App.directive('homebg',function() {
		return {
			restrict: 'A',
			scope: { homebg: '@'},
			link: function(scope, element, attrs) {
				element.bind('mouseover', function() {
					if(element.hasClass("video-wrapper")){
						targ = element.children('.video-container');
					} else {
						targ = element.children('.img-pad');
					}
					targ.css({'background-color':scope.homebg});
					element.addClass("hovered");
				});
				element.bind('mouseout',function(){
					targ.css({'background-color':'transparent'});
					element.removeClass("hovered");
				});
			}
		};
	});

	App.directive('imgbg',[function() {
		return {
			restrict: 'A',
			scope: { imgbg: '@' },
			link: function(scope, element, attrs) {
				if(typeof(scope.imgbg) !== "undefined"){
					var $el = $(element);
					var src = scope.imgbg;
					$el.css({'background-image': 'url(' + src + ')' }).find('.preload').remove();
				}
			}
		};
	}]);

	App.directive('imgbglazy',[function() {
		return {
			restrict: 'A',
			scope: { imgbg: '@' },
			link: function(scope, element, attrs) {
				if(typeof(scope.imgbg) !== "undefined"){
					//element.attr('style', 'background-image:url('+scope.imgbg+')');
					var $el = $(element);
					var src = scope.imgbg;
					// add autofade if not triggered by lazyload on scroll
					if ( ! $el.hasClass('lazyload') ){
						$el.addClass('toload');
						$el.parent().addClass("loadParent");	
					} 
					// append an hidden image
					$el.append('<img class="preload" src="' + src + '" width="1" height="1"/>').imagesLoaded(function(){
						// add css background and remove the image
						$el.css({'background-image': 'url(' + src + ')' }).find('.preload').remove();
						// add class loaded and fire an imageload event
						$el.addClass('loaded').trigger('imageload');
						// pages.preloadIncrement();
					});
				} else {
					$(element).remove();
				}
			}
		};
	}]);