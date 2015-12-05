App.directive('vimeo', function($timeout){
		var vidL = this;

		return {
			restrict: 'E',
			replace: true,
			scope: {
				title: '@'
			},
			templateUrl: '/wp-content/themes/chips-ng/views/video-iframe.html',
			link: function (scope, element, attrs) {
				var localPlayer = null;
				var player = null;

				scope.$on('$destroy', function () {
					element.unbind( "click" );
					element.remove();
				});
				if (attrs.ui == "hover") {
					scope.cursorState = 'cursor-video-pause';
				}
				scope.nonCustom = (Modernizr.touchevents) || (attrs.mp4 === "") || (attrs.mp4 === null) || (attrs.mp4 === undefined) || attrs.ui !== "scroll";
				scope.nonTouch = !(Modernizr.touchevents);
				scope.vid = attrs.vid;
				scope.allowPlay = true;
				element.addClass(attrs.aspectRatio);
				var iframe = null;
				var targetElVid = null;
				scope.uiState = attrs.ui;
				var randID = "Vid"+Math.floor(Math.random() * 1000000000);
				if(!scope.nonCustom){
					iframe = element.find('video');
					targetElVid = iframe[0];
				} else {
					iframe = element.find('iframe');
					targetElVid = iframe[0];
				}
				if(typeof(targetElVid) !== "undefined"){
					targetElVid.setAttribute("id",randID);
				}
				
				var url = "https://player.vimeo.com/video/" + attrs.vid + "?title=0&byline=0&portrait=0&loop=1&api=1&player_id="+randID;
				if(!scope.nonCustom){
					url = attrs.mp4;
				}
				var vidURL = attrs.mp4;
				if (attrs.ui == "scroll") {
					// url = url + "&autoplay=1";
				}
				iframe.attr('src', url);
				// var iframe = element[0];
				player = $f(iframe[0]);

				localPlayer = player;

				if (attrs.ui == "hover") {
					element.bind('click', function () {
						if(scope.allowPlay === true){
							scope.allowPlay = false;
							localPlayer.api("pause");
							scope.$apply(function() {
								// console.log('apply play');
								scope.cursorState = 'cursor-video-play';
							});
						} else {
							scope.allowPlay = true;
							localPlayer.api("play");
							scope.$apply(function() {
								// console.log('apply pause');
								scope.cursorState = 'cursor-video-pause';
							});
						}
					});


					element.bind('mouseenter', function () {
						if(scope.allowPlay === true){ localPlayer.api("play"); }
					});
					element.bind('mouseleave', function () {
						if(scope.allowPlay === true){ localPlayer.api("pause"); }
					});
				}
				if(scope.nonCustom){
					localPlayer.addEvent('ready', function() {
						$timeout(function() {
							localPlayer.api('setVolume', 1);

							if (attrs.ui == "scroll") {
								if (attrs.viewPlay == attrs.vid) {
									localPlayer.api("play");
								}
								attrs.$observe('viewPlay', function(value) {
									if (attrs.inView) {
										if (value == attrs.vid) {
											localPlayer.api("play");
										} else {
											localPlayer.api("pause");
										}
									}
								});
							}
						});
					});
				}
			},
		};
	});
