<?php $upload_dir = wp_upload_dir(); ?>
<!DOCTYPE html>
<html ng-app="App">
<head>
	<meta charset="utf-8">
	<title>WYBC Yale Radio</title>
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<meta name="fragment" content="!">
	<base href="/">
	<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
	<?php wp_head(); ?>
</head>
<?php 

?>

<body ng-controller="MainController as mainCtrl" in-view-container>
<?php if(!is_login_page()){?>
<header>
	<h1><a href="/">WYBC</a></h1>
	<nav>
		Angular routing example: 
		<a href="/schedule">Schedule</a>
		<a href="/djs">DJs</a>
		<a href="/shows">Shows</a>
		<a href="/zine">Zine</a>
		<a href="/about">About us</a>
	</nav>
</header>
<?php } ?>
