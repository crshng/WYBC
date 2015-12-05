<?php get_header(); ?>
<div ng-view autoscroll ng-class="pageAnimationClass" class="view-animate"><h1><?php the_title(); ?></h1><?php the_content(); ?></div>
<?php get_footer(); ?>