<?php if(!is_login_page()){?>
<footer ng-if="urlhelper.currentSection !== 'information'">
	<nav>
		<a class="home-link" ng-if="urlhelper.currentSection !== 'home'" ng-click="pages.gaClick('Footer links', 'Footer nav', 'Home click')" href="/">Chips</a>
		<a class="info-link" ng-if="urlhelper.currentSection !== 'home'" ng-class="{'active' : urlhelper.currentSection == 'information'}" href="/info" ng-click="mainCtrl.navClass = 'information'; pages.gaClick('Footer links', 'Footer nav', 'Information click');">Information</a>
		<a class="archive-link" ng-class="{'active' : urlhelper.currentSection == 'archive', 'arrow' : urlhelper.currentSection == 'home'}" ng-click="pages.gaClick('Footer links', 'Footer nav', 'Archive click')" href="/archive">Archive</a>
		<a class="clips-link" ng-if="urlhelper.currentSection !== 'home'" ng-class="{'active' : urlhelper.currentSection == 'clips'}" ng-click="pages.gaClick('Footer links', 'Footer nav', 'Clips click')" href="/clips">Clips</a>
	</nav>
</footer>
<?php } ?>

<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.28/angular.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.28/angular-route.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.28/angular-sanitize.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/fastclick/1.0.6/fastclick.min.js"></script>
<script src="//f.vimeocdn.com/js/froogaloop2.min.js"></script>

<script src="<?php bloginfo('template_directory'); ?>/js/modernizr-custom.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/bower_components/angular-inview/angular-inview.js"></script>

<script src="<?php bloginfo('template_directory'); ?>/js/project.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/controllers/main.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/controllers/home.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/controllers/work.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/controllers/clips.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/controllers/information.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/controllers/archive.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/directives/directives.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/directives/video.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/services/urlhelper.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/services/pages.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/filters/slice.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/filters/dates.js"></script>
<?php wp_footer(); ?>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-15634087-1', 'auto');
  // ga('send', 'pageview');

</script>
</body>
</html>