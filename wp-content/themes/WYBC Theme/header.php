<?php $upload_dir = wp_upload_dir(); ?>
<!DOCTYPE html>
<html ng-app="App">
<head>
	<meta charset="utf-8">
	<title><?php print trim(wp_title('', false)); ?><?php if(wp_title('', false)) { echo ' | '; } ?><?php bloginfo('name'); ?></title>
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<meta name="fragment" content="!">
	<base href="/">
	<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
	<link rel="stylesheet" type="text/css" media="all" href="<?php print $upload_dir['baseurl'] . '/data/colors.css'; ?>" />
	<link rel="icon" type="image/x-icon" href="/favicon.ico">
	<link rel="icon" type="image/png" href="/favicon.png">
	<link rel="shortcut icon" type="image/x-icon" href="/favicon.png">
	<?php wp_head(); ?>
	<meta property="og:locale" content="en_US" />
	<meta property="og:type" content="website" />
	<?php
		$soc_title = "CHIPS";
		$soc_description = "CHIPS is a studio for design and development founded by partners Dan Shields, Teddy Blanks, and Adam Squires. We are currently working with Sean Manchee and Chris Hong.";
		$soc_url = "http://". $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		$soc_img = "http://chips.nyc/CHIPS_og.jpg";
		if(get_post_type()==="work"){
			$soc_title = get_the_title();
			$tempImg = get_field("archive_image");
			if(is_array($tempImg) && !empty($tempImg)){
				$soc_img = $tempImg['sizes']['medium'];
			}
			$soc_description = trim(preg_replace('/\s\s+/', ' ', strip_tags(get_field("excerpt"))));
		} else if (strpos($_SERVER['REQUEST_URI'], 'clips') !== false) {
			$soc_img = "http://chips.nyc/CLIPS_og.jpg";
		}
	?>
	<meta property="og:title" content="<?php print $soc_title; ?>" />
	<meta property="og:description" content="<?php print $soc_description; ?>" />
	<meta property="og:url" content="<?php print $soc_url;?>" />
	<meta property="og:site_name" content="CHIPS" />
	<meta property="og:image" content="<?php print $soc_img; ?>" />
	<meta name="twitter:card" content="summary"/>
	<meta name="twitter:description" content="<?php print $soc_description; ?>"/>
	<meta name="twitter:title" content="<?php print $soc_title; ?>"/>
	<meta name="twitter:site" content="@chipsny"/>
	<meta name="twitter:image:src" content="<?php print $soc_img; ?>"/>
</head>
<?php 

?>

<body ng-controller="MainController as mainCtrl" in-view-container>
<?php if(!is_login_page()){?>
<header>
	<h1 ng-class="{'chipclip' : urlhelper.currentSection == 'clips'}"><a class="home-link" href="/" ng-click="pages.gaClick('Home page links', 'Top nav', 'Logo click')">CHIPS</a></h1>
	<nav>
		<a class="info-link" ng-class="{'active' : urlhelper.currentSection == 'information'}" href="/info" ng-click="mainCtrl.navClass = 'information'; pages.gaClick('Home page links', 'Top nav', 'Information click');">Information</a>
		<a class="archive-link" ng-class="{'active' : urlhelper.currentSection == 'archive'}" ng-click="pages.gaClick('Home page links', 'Top nav', 'Archive click')" href="/archive">Archive</a>
		<a class="clips-link" ng-class="{'active' : urlhelper.currentSection == 'clips'}" ng-click="pages.gaClick('Home page links', 'Top nav', 'Clips click')" href="/clips">Clips</a>
	</nav>
</header>
<?php } ?>
