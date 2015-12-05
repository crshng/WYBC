<?php
// define('WP_USE_THEMES', false);
require('./wp-blog-header.php');
header('Content-type: application/json;');

if(isset($_GET['dType'])){
	$pageRequest = $_GET['dType'];
	if($pageRequest !== ""){
		$postTypeUse = $pageRequest;
		$finalText = buildJSON($postTypeUse);
	} else {
		$finalText = buildJSON('static');
	}
}

print $finalText;
?>
