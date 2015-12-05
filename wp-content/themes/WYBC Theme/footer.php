<?php if(!is_login_page()){?>
<footer>
	<nav>
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

<script src="<?php bloginfo('template_directory'); ?>/js/project.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/controllers/main.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/controllers/home.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/controllers/about.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/controllers/dj.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/controllers/schedule.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/controllers/shows.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/controllers/zine.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/directives/directives.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/services/urlhelper.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/services/pages.js"></script>
<?php wp_footer(); ?>
</body>
</html>