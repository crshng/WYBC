App.directive("vimeo",function(i){var e=this;return{restrict:"E",replace:!0,scope:{title:"@"},templateUrl:"/wp-content/themes/chips-ng/views/video-iframe.html",link:function(e,o,a){var n=null,t=null;e.$on("$destroy",function(){o.unbind("click"),o.remove()}),"hover"==a.ui&&(e.cursorState="cursor-video-pause"),e.nonCustom=Modernizr.touchevents||""===a.mp4||null===a.mp4||void 0===a.mp4||"scroll"!==a.ui,e.nonTouch=!Modernizr.touchevents,e.vid=a.vid,e.allowPlay=!0,o.addClass(a.aspectRatio);var l=null,r=null;e.uiState=a.ui;var u="Vid"+Math.floor(1e9*Math.random());e.nonCustom?(l=o.find("iframe"),r=l[0]):(l=o.find("video"),r=l[0]),"undefined"!=typeof r&&r.setAttribute("id",u);var p="https://player.vimeo.com/video/"+a.vid+"?title=0&byline=0&portrait=0&loop=1&api=1&player_id="+u;e.nonCustom||(p=a.mp4);var s=a.mp4;"scroll"==a.ui,l.attr("src",p),t=$f(l[0]),n=t,"hover"==a.ui&&(o.bind("click",function(){e.allowPlay===!0?(e.allowPlay=!1,n.api("pause"),e.$apply(function(){e.cursorState="cursor-video-play"})):(e.allowPlay=!0,n.api("play"),e.$apply(function(){e.cursorState="cursor-video-pause"}))}),o.bind("mouseenter",function(){e.allowPlay===!0&&n.api("play")}),o.bind("mouseleave",function(){e.allowPlay===!0&&n.api("pause")})),e.nonCustom&&n.addEvent("ready",function(){i(function(){n.api("setVolume",1),"scroll"==a.ui&&(a.viewPlay==a.vid&&n.api("play"),a.$observe("viewPlay",function(i){a.inView&&(i==a.vid?n.api("play"):n.api("pause"))}))})})}}});