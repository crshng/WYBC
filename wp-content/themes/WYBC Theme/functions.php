<?php
add_filter('stylesheet_uri','wpi_stylesheet_uri',10,2);
function wpi_stylesheet_uri($stylesheet_uri, $stylesheet_dir_uri){
	return $stylesheet_dir_uri.'/css/styles.css';
}

// ACF OPTIONS PAGES
// if(function_exists('acf_add_options_page')) {
// 	acf_add_options_page();
// 	acf_add_options_sub_page('Header');
// 	acf_add_options_sub_page('Footer');
// }

// Facebook Open Graph
add_action('wp_head', 'add_fb_open_graph_tags');
function add_fb_open_graph_tags() {
	if (is_single()) {
		global $post;
		if(get_the_post_thumbnail($post->ID, 'thumbnail')) {
			$thumbnail_id = get_post_thumbnail_id($post->ID);
			$thumbnail_object = get_post($thumbnail_id);
			$image = $thumbnail_object->guid;
		} else {
			$image = ''; // Change this to the URL of the logo you want beside your links shown on Facebook
		}
		//$description = get_bloginfo('description');
		$description = my_excerpt( $post->post_content, $post->post_excerpt );
		$description = strip_tags($description);
		$description = str_replace("\"", "'", $description);
?>
<meta property="og:title" content="<?php the_title(); ?>" />
<meta property="og:type" content="article" />
<?php if(strlen($image) > 3){ ?><meta property="og:image" content="<?php echo $image; ?>" /><?php } ?>
<meta property="og:url" content="<?php the_permalink(); ?>" />
<meta property="og:description" content="<?php echo $description ?>" />
<meta property="og:site_name" content="<?php echo get_bloginfo('name'); ?>" />

<?php 	}
}

function my_excerpt($text, $excerpt){
	if ($excerpt) return $excerpt;
	$text = strip_shortcodes( $text );
	$text = apply_filters('the_content', $text);
	$text = str_replace(']]>', ']]&gt;', $text);
	$text = strip_tags($text);
	$excerpt_length = apply_filters('excerpt_length', 55);
	$excerpt_more = apply_filters('excerpt_more', ' ' . '[...]');
	$words = preg_split("/[\n
	 ]+/", $text, $excerpt_length + 1, PREG_SPLIT_NO_EMPTY);
	if ( count($words) > $excerpt_length ) {
		array_pop($words);
		$text = implode(' ', $words);
		$text = $text . $excerpt_more;
	} else {
		$text = implode(' ', $words);
	}
	return apply_filters('wp_trim_excerpt', $text, $excerpt);
}

// Remove canonical meta tag
remove_action('wp_head', 'rel_canonical');

// CUSTOM WYSIWYG FORMATS
add_filter('mce_css', 'tuts_mcekit_editor_style');
function tuts_mcekit_editor_style($url) {
	if ( !empty($url) )
		$url .= ',';
	// Retrieves the plugin directory URL
	// Change the path here if using different directories
	$url .= trailingslashit( get_stylesheet_directory_uri() ) . '/editor-style.css';
	return $url;
}
/** Add "Styles" drop-down */
add_filter( 'mce_buttons_2', 'tuts_mce_editor_buttons' );

function tuts_mce_editor_buttons( $buttons ) {
	array_unshift( $buttons, 'styleselect' );
	return $buttons;
}

/** Add styles/classes to the "Styles" drop-down */
add_filter( 'tiny_mce_before_init', 'tuts_mce_before_init' );

function tuts_mce_before_init( $settings ) {

	$style_formats = array(
		array(
			'title' => 'Small Caps',
			'inline' => 'span',
			'classes' => 'smallcap'
		)
	);

	$settings['style_formats'] = json_encode( $style_formats );

	return $settings;

}

add_action( 'admin_bar_menu', 'modify_admin_bar' );

function modify_admin_bar( $wp_admin_bar ){
?>
<script type="text/javascript" >
	jQuery(document).ready(function($) {
		$(".ab-item").each(function(){
			$(this).attr("target","_self");
		});
	});
</script>
<?php
}

// Add Featured Images to Wordpress
// add_theme_support( 'post-thumbnails' );
add_action( 'admin_footer', 'data_regen_javascript' ); // Write our JS below here
function data_regen_javascript() { ?>
	<script type="text/javascript" >

	jQuery(document).ready(function($) {
		var allowRegen = 1;

		var buildPages = [
			'data_regen_home',
			'data_regen_archive',
			'data_regen_clips'
		];

		var currentBuildItem = null;
		var buildDataJS = function(){
			var data = {
				'action': buildPages[currentBuildItem]
			};
			$("#jsBuild").append("Building " + buildPages[currentBuildItem].replace("data_regen_","") + " function... ");

			$.post(ajaxurl, data, function(response) {
				console.log(response);
				if(response == "Success" || response === ""){
					$("#jsBuild").append("Success!<br />");
					if(currentBuildItem < buildPages.length){
						currentBuildItem++;
						buildDataJS();
					}
				} else {
					alert(response);
					$("#jsBuild").append("Failed!<br />");
				}
			});
		};

		$(".ab-item").on("click",function(e){
			if($(this).attr("href") == "/"){
				e.preventDefault();
				$("#wpwrap").append('<div style="position:fixed;bottom:0;left:50%;margin-left:-300px;width:600px;padding:30px;background-color:white;border:solid 2px red;border-bottom:none;color:#990000;font-size:24px;line-height:1.2;z-index:10;" id="jsBuild"></div>"');
				$("#jsBuild").on("click",function(){
					$(this).remove();
				});
				currentBuildItem = 0;
				buildDataJS();
			}
		});
		$(".data-regen a").on("click",function(e){
			e.preventDefault();
			var buildPage = 'data_regen_home';
			if($(this).parent().hasClass("data-regen-home")){
				buildPage = 'data_regen_home';
				console.log("kicking off home page");
			} else if($(this).parent().hasClass("data-regen-archive")){
				buildPage = 'data_regen_archive';
				console.log("kicking off Archive rebuild");
			} else if($(this).parent().hasClass("data-regen-clips")){
				buildPage = 'data_regen_clips';
				console.log("kicking off Clips rebuild");
			} else {

			}
			if(allowRegen === 1){
				allowRegen = 0;
				var data = {
					'action': buildPage
				};
				var tempThis = $(this);
				$(this).addClass("rebuilding");
				$.post(ajaxurl, data, function(response) {
					allowRegen = 1;
					console.log(response);
					// $(".data-regen a").html("Regenerate Data Files");
					tempThis.removeClass("rebuilding");
					if(response == "Success"){
						tempThis.parent().removeClass("status-bad").parent().addClass("status-good");
					} else {
						alert(response);
					}
				});
			}
		});

	});
	</script> <?php
}


// Move the JS to the footer
remove_action('wp_head', 'wp_print_scripts');
remove_action('wp_head', 'wp_print_head_scripts', 9);
remove_action('wp_head', 'wp_enqueue_scripts', 1);
add_action('wp_footer', 'wp_print_scripts', 5);
add_action('wp_footer', 'wp_enqueue_scripts', 5);
add_action('wp_footer', 'wp_print_head_scripts', 5);
// Clean up WP header
remove_action( 'wp_head', 'feed_links_extra'); // Display the links to the extra feeds such as category feeds
remove_action( 'wp_head', 'feed_links'); // Display the links to the general feeds: Post and Comment Feed
remove_action( 'wp_head', 'rsd_link'); // Display the link to the Really Simple Discovery service endpoint, EditURI link
remove_action( 'wp_head', 'wlwmanifest_link' ); // Display the link to the Windows Live Writer manifest file.
remove_action( 'wp_head', 'index_rel_link' ); // index link
remove_action( 'wp_head', 'parent_post_rel_link', 10); // prev link
remove_action( 'wp_head', 'start_post_rel_link', 10); // start link
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10); // Display relational links for the posts adjacent to the current post.
remove_action( 'wp_head', 'wp_generator'); // Display the XHTML generator that is generated on the wp_head hook, WP version
remove_action( 'admin_print_styles', 'print_emoji_styles' );
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
add_editor_style( 'css/editor-style.css' );

add_action( 'admin_enqueue_scripts', 'load_admin_style' );
function load_admin_style() {
	wp_enqueue_style( 'admin_css', get_template_directory_uri() . '/css/editor-style.css', false, '1.0.0' );
}

function replace_howdy( $wp_admin_bar ) {
	$my_account=$wp_admin_bar->get_node('my-account');
	$newtitle = str_replace( 'Howdy,', '', $my_account->title );
	$wp_admin_bar->add_node( array(
		'id' => 'my-account',
		'title' => $newtitle,
	) );
}
add_filter( 'admin_bar_menu', 'replace_howdy',25 );

function remove_admin_menu_items() {
	$remove_menu_items = array('Links','Comments');
	global $menu;
	end ($menu);
	while (prev($menu)){
		$item = explode(' ',$menu[key($menu)][0]);
		if(in_array($item[0] != NULL?$item[0]:"" , $remove_menu_items)){
		unset($menu[key($menu)]);}
	}
}
add_action('admin_menu', 'remove_admin_menu_items');

function remove_recent_comment_style() {
	global $wp_widget_factory;
	remove_action(
		'wp_head',
		array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' )
	);
}
add_action( 'widgets_init', 'remove_recent_comment_style' );

add_action('wp_dashboard_setup', 'my_custom_dashboard_widgets');
function my_custom_dashboard_widgets() {
	global $wp_meta_boxes;
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);

	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);
}

function is_login_page() {
    return in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'));
}


add_filter( 'manage_edit-exhibitions_columns', 'my_edit_exhibitions_columns' ) ;

function my_edit_exhibitions_columns( $columns ) {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Exhibition Title' ),
		'artists' => __( 'Artists' ),
		'date' => __( 'Date' )
	);
	return $columns;
}

add_action( 'manage_exhibitions_posts_custom_column', 'my_manage_exhibitions_columns', 10, 2 );

function my_manage_exhibitions_columns( $column, $post_id ) {
	global $post;
	switch( $column ) {
		case 'artists' :
			$artists = get_field("artists",$post_id);
			if ( empty( $artists ) || !is_array($artists) )
				print "";
			else
				$loop = 1;
				if(is_array($artists)){
					foreach($artists as $photog){
						if($loop > 1){ print ", "; }
						if(isset($photog->post_title) && $photog->post_title !== ""){
							print $photog->post_title;
							$loop++;
						}
					}
				}
			break;
		default :
			break;
	}
}

add_action( 'load-edit.php', 'my_edit_exhibitions_load' );
function my_edit_exhibitions_load() {
	add_filter( 'request', 'my_sort_exhibitionss' );
}

function my_sort_exhibitionss( $vars ) {
	if ( isset( $vars['post_type'] ) && 'exhibitions' == $vars['post_type'] ) {
		if ( isset( $vars['orderby'] ) && 'epnum' == $vars['orderby'] ) {
			$vars = array_merge(
				$vars,
				array(
					'meta_key' => 'epnum',
					'orderby' => 'meta_value_num'
				)
			);
		} else if ( isset( $vars['orderby'] ) && 'photographer' == $vars['orderby'] ) {
			$vars = array_merge(
				$vars,
				array(
					'meta_key' => 'photographer',
					'orderby' => 'meta_value_num'
				)
			);
		}
	}
	return $vars;
}

function get_url($url) {
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,5);
	$content = curl_exec($ch);
	curl_close($ch);
	return $content;
}


add_action( 'wp_ajax_data_regen', 'data_regen_callback' );
add_action( 'wp_ajax_data_regen_home', 'data_regen_home_callback' );
add_action( 'wp_ajax_data_regen_archive', 'data_regen_archive_callback' );
add_action( 'wp_ajax_data_regen_clips', 'data_regen_clips_callback' );

function data_regen_home_callback() {
	global $wpdb; $upload_dir = wp_upload_dir();
	if (!is_dir($upload_dir['basedir'].'/data')) { mkdir($upload_dir['basedir'].'/data');}
	$dataFileName = $upload_dir['basedir'].'/data/home.js';
	$dataHome = buildJSON('home');
	file_put_contents($dataFileName,$dataHome);
	print "Success";
	wp_die();
}

function data_regen_archive_callback() {
	global $wpdb; $upload_dir = wp_upload_dir();
	if (!is_dir($upload_dir['basedir'].'/data')) { mkdir($upload_dir['basedir'].'/data');}
	$dataFileName = $upload_dir['basedir'].'/data/archive.js';
	$dataFinal = buildJSON('archive');
	file_put_contents($dataFileName,$dataFinal);
	print "Success";
	wp_die();
}

function data_regen_clips_callback() {
	global $wpdb; $upload_dir = wp_upload_dir();
	if (!is_dir($upload_dir['basedir'].'/data')) { mkdir($upload_dir['basedir'].'/data');}
	$dataFileName = $upload_dir['basedir'].'/data/clips.js';
	$dataFinal = buildJSON('clips');
	file_put_contents($dataFileName,$dataFinal);
	print "Success";
	wp_die();
}



function create_dwb_menu() {
	global $wpdb;
	$upload_dir = wp_upload_dir();
	global $wp_admin_bar;
	$menu_id = 'dwb';
	$wp_admin_bar->add_menu(array('id' => $menu_id, 'title' => __('Regenerate'), 'href' => '/'));

	$extraClass = " has-status";
	if (file_exists($upload_dir['basedir'].'/data/home.js') && filesize($upload_dir['basedir'].'/data/home.js')>=100) {
		$extraClass .= " status-good";
	} else {
		$extraClass .= " status-bad";
	}
	$wp_admin_bar->add_menu(
		array(
			'parent' => $menu_id,
			'title' => __('Home'),
			'id' => 'dwb-home',
			'href' => '#',
			'meta' => array(
				'class' => 'data-regen data-regen-home' .$extraClass
			)
		)
	);

	$extraClass = " has-status";
	if (file_exists($upload_dir['basedir'].'/data/archive.js') && filesize($upload_dir['basedir'].'/data/archive.js')>=100) {
		$extraClass .= " status-good";
	} else {
		$extraClass .= " status-bad";
	}
	$wp_admin_bar->add_menu(
		array(
			'parent' => $menu_id,
			'title' => __('Archive'),
			'id' => 'dwb-archive',
			'href' => '#',
			'meta' => array(
				'class' => 'data-regen data-regen-archive' .$extraClass
			)
		)
	);

	$extraClass = " has-status";
	if (file_exists($upload_dir['basedir'].'/data/clips.js') && filesize($upload_dir['basedir'].'/data/clips.js')>=100) {
		$extraClass .= " status-good";
	} else {
		$extraClass .= " status-bad";
	}
	$wp_admin_bar->add_menu(
		array(
			'parent' => $menu_id,
			'title' => __('Clips'),
			'id' => 'dwb-clips',
			'href' => '#',
			'meta' => array(
				'class' => 'data-regen data-regen-clips' .$extraClass
			)
		)
	);
}
add_action('admin_bar_menu', 'create_dwb_menu', 50);

function save_work_meta( $post_id, $post, $update ) {
	$hours = 24;
	$upload_dir = wp_upload_dir();
	// print_r($upload_dir);

	if (!is_dir($upload_dir['basedir'].'/data')) {
		mkdir($upload_dir['basedir'].'/data');
	}

	if (get_post_type() == "work") {
		$dataURL = 'http://'.$_SERVER['HTTP_HOST'].'/json/?dType=work';
		$dataFileName = $upload_dir['basedir'].'/data/archive.js';
		$content = buildJSON('work');
	} else if (get_post_type() == "clips") {
		$dataURL = 'http://'.$_SERVER['HTTP_HOST'].'/json/?dType=clips';
		$dataFileName = $upload_dir['basedir'].'/data/clips.js';
		$content = buildJSON('clips');
	} else {
		$dataURL = 'http://'.$_SERVER['HTTP_HOST'].'/json/?dType=home';
		$dataFileName = $upload_dir['basedir'].'/data/home.js';
		$content = buildJSON('home');
	}
	// $current_time = time(); $expire_time = $hours * 60 * 60; if(file_exists($dataFileName)){ $file_time = filemtime($dataFileName); }
	// $content = get_url($dataURL);
	// $content.= '<!-- cached:  '.time().'-->';

	file_put_contents($dataFileName,$content);

	return;
}

add_action( 'save_post', 'save_work_meta', 10, 3);

function buildJSON($pageType){
	$mData = array();

	if(isset($pageType)){
		if($pageType == "work" || $pageType == "archive"){
			if(isset($_GET['catFilters'])){
				$cats = $_GET['catFilters'];
				if(is_array($cats)){
					$catsUse = implode(',', $cats);
				} else {
					$catsUse = $cats;
				}
			} else {
				$catsUse = "";
			}

			if(isset($_GET['eID']) && $_GET['eID'] !== ""){
				query_posts(array(
					'posts_per_page' => -1,
					'post_type' => array('work'),
					'p' => $_GET['eID']
				));
			} else {
				if($catsUse == "" || $catsUse == "on" || $catsUse == "all"){
					query_posts(array(
						'posts_per_page' => -1,
						'post_type' => array('work'),
						'orderby' => 'menu_order',
						'order' => 'ASC',
						'post_status' => 'publish'
					));
				} else {
					query_posts(array(
						'posts_per_page' => -1,
						'post_type' => array('work'),
						'themes' => $catsUse,
						'orderby' => 'menu_order',
						'order' => 'ASC',
						'post_status' => 'publish'
					));
				}
			}

			$trackDate = "";
			if ( have_posts() ) while ( have_posts() ) : the_post();
				$singleEpData = array();
				$insertDate = false;

				$post_for_slug = get_post( get_the_ID() );
				$slug = $post_for_slug->post_name;

				// $slug = sanitize_title( get_the_title(), $fallback_title );
				$singleEpData["id"] = get_the_ID();
				$singleEpData["date"] = get_the_date('Y');
				if($singleEpData["date"] !== $trackDate){
					$trackDate = $singleEpData['date'];
					$insertDate = true;
				}
				$singleEpData["cs"] = get_field('case_study_flag');
				$singleEpData["title"] = get_the_title();
				$singleEpData["url"] = str_replace(home_url(), '', apply_filters('the_permalink', get_permalink()));
				$singleEpData["excerpt"] = get_field("excerpt");
				$singleEpData["arcimg"] = get_field("archive_image");
				$singleEpData["color"] = get_field("color");
				if(strlen($singleEpData["excerpt"]) < 10){
					$singleEpData["excerpt"] = get_the_content();
				}
				$singleEpData["links"] = get_field("links");
				// $singleEpData["contents"] = get_field("contents");
				$singleEpData["fImg"] = get_the_post_thumbnail($singleEpData["id"],"large");
				$singleEpData["aImg"] = wp_get_attachment_image_src( get_post_thumbnail_id( $singleEpData["id"] ), "medium" );

				$singleEpData["cats"] = wp_get_post_terms( $singleEpData["id"], "category" );

				$workMarkup = '<div class="gi g5 o3 work-section">
							<div class="project-text project-excerpt"><h1>'.get_the_title().'</h1>'.get_field("excerpt").'</div>';

				$pageLinks = get_field("links");
				if(is_array($pageLinks) && !empty($pageLinks)){
					$workMarkup .= '<ul class="project-links">';
					foreach($pageLinks as $pageLink){
						$workMarkup .= '<li><a href="' . $pageLink["url"] . '" target="_blank">' . $pageLink["text"] . '</a></li>';
					}
					$workMarkup .= '</ul>';
				}

				$workMarkup .= '</div>';

				$pageContents = get_field("contents");
				if(is_array($pageContents) && !empty($pageContents)){
					foreach($pageContents as $pageContent){
						if($pageContent['acf_fc_layout'] == "text"){
							$workMarkup .= '<div class="loop-txt gi g5 o3 work-section work-text"><div class="project-text">'.$pageContent["text"].'</div></div>';
						} else if($pageContent['acf_fc_layout'] == "spacer"){
							$spacerInner = "";
							if($pageContent['size'] === "custom" && $pageContent['sp_size'] !== ""){
								$spacerInner = '<div style="height:' . $pageContent['sp_size'] . 'px"></div>';
							}
							$workMarkup .= '<div class="loop-spacer ' . $pageContent['size'] . ' work-spacer">'.$spacerInner.'</div>';
						} else if($pageContent['acf_fc_layout'] == "single_item"){
							$wrapClass = "wrap";
							if($pageContent["grid_offset"] == 0 && $pageContent["grid_cols"] == 8){
								$wrapClass .= " full";
							} else if ($pageContent["grid_offset"] == 0 && $pageContent["grid_cols"] < 8){
								$wrapClass .= " rHalf";
							} else if ($pageContent["grid_offset"] > 0 && $pageContent["grid_cols"] + $pageContent["grid_offset"] == 8){
								$wrapClass .= " oHalf";
							} else {
								$wrapClass .= " bHalf";
							}
							$workMarkup .= '
							<ul class="loop-single-item work-section">
								<li class="single-item-contents border-'.$pageContent["border_option"] . ' g'.$pageContent["grid_cols"] . ' o'.$pageContent["grid_offset"] . '">
							';
							$mPadClass = "";
							if($pageContent['mobile_padding']){
								$mPadClass = " mobile-padding";
							}
							$workMarkup .= '<div class="' . $wrapClass . '">';
							$workMarkup .= '<div class="framing framing-' . $pageContent["framing"] . $mPadClass .'">';
							if($pageContent["media_type"] === "image" && isset($pageContent['image']) && isset($pageContent['image']['sizes'])){
								$imgRatio = ($pageContent['image']['sizes']['large-height'] / $pageContent['image']['sizes']['large-width'])*100;
								$workMarkup .= '<div class="col-img-wrap inline-ratio" style="padding-bottom:'.$imgRatio.'%"><img src="'.$pageContent['image']['sizes']['large'] . '" srcset="'.$pageContent['image']['sizes']['medium'].' 900w, '.$pageContent['image']['sizes']['large'].' 2048w" width="'.$pageContent['image']['sizes']['large-width'] . '" height="'.$pageContent['image']['sizes']['large-height'] . '"></div>';
							}
							if($pageContent["media_type"] === "video"){
								$workMarkup .= '<vimeo vid="'.$pageContent['vimeo_id'] . '" aspect-ratio="' . $pageContent['aspect_ratio'] . '" ui="'.$pageContent['video_ui'] . '" mp4="'.$pageContent['mp4_url'].'" view-play="{{workCtrl.viewPlay}}" in-view-options="{\'offsetTop\': 100, \'offsetBottom\': -100}" in-view="workCtrl.isInView($inview, $event, $inviewpart);"></vimeo>';
							}
							$workMarkup .= '</div>'; // End of framing div
							if($pageContent["caption"] !== ""){
								$captClass = "caption-" . $pageContent['caption_positioning'];
								if($pageContent['caption_positioning'] === "left"){
									$captClass .= " g".$pageContent['grid_offset'];
								} else if($pageContent['caption_positioning'] === "right"){
									$captClass .= " g".(8 - $pageContent['grid_cols'] - $pageContent['grid_offset']) . " o" . ($pageContent['grid_cols'] + $pageContent['grid_offset']);
								}
								// $workMarkup .= '<div class="caption caption-'.$pageContent['caption_positioning'] . ' {{workCtrl.captionWidthClass(c)}}">'.$pageContent['caption'] . '</div>';
								$workMarkup .= '<div class="caption '. $captClass . '">'.$pageContent['caption'] . '</div>';
							}
							$workMarkup .= '</div>'; // End of wrap div
							$workMarkup .= '
								</li>
							</ul>
							';
						} else if($pageContent['acf_fc_layout'] == "two_column"){

							$workMarkup .= '<ul class="loop-two-column work-section">
								<li class="col col-1 border-' . $pageContent['column_one'][0]['border_option'] . ' g' . $pageContent['column_one'][0]['grid_cols'] . ' o' . $pageContent['column_one'][0]['grid_offset'] . '">
									<div class="rHalf">
							';

							$mPadClass = "";
							if($pageContent['column_one'][0]['mobile_padding']){
								$mPadClass = " mobile-padding";
							}
							$workMarkup .= '<div class="framing framing-' . $pageContent['column_one'][0]["framing"] . $mPadClass . '">';
							if($pageContent['column_one'][0]['media_type'] === "image" && isset($pageContent['column_one'][0]['image']) && isset($pageContent['column_one'][0]['image']['sizes'])){
								$imgRatio = ($pageContent['column_one'][0]['image']['sizes']['large-height'] / $pageContent['column_one'][0]['image']['sizes']['large-width'])*100;
								$workMarkup .= '<div class="col-img-wrap inline-ratio" style="padding-bottom:'.$imgRatio.'%"><img src="' . $pageContent['column_one'][0]['image']['sizes']['large'] . '" srcset="'.$pageContent['column_one'][0]['image']['sizes']['medium'].' 900w, '.$pageContent['column_one'][0]['image']['sizes']['large'].' 2048w" width="' . $pageContent['column_one']['column_one'][0]['image']['sizes']['large-width'] . '" height="' . $pageContent['column_one'][0]['image']['sizes']['large-height'] . '"></div>';
							}
							if($pageContent['column_one'][0]['media_type'] === "video"){
								$workMarkup .= '<vimeo vid="' . $pageContent['column_one'][0]['vimeo_id'] . '" aspect-ratio="'.$pageContent['column_one'][0]['aspect_ratio'].'" ui="' . $pageContent['column_one'][0]['video_ui'] . '" mp4="'.$pageContent['column_one'][0]['mp4_url'].'" view-play="{{workCtrl.viewPlay}}" in-view-options="{\'offsetTop\': 100, \'offsetBottom\': -100}" in-view="workCtrl.isInView($inview, $event, $inviewpart);"></vimeo>';
							}
							$workMarkup .= '</div>'; // End of framing div
							if($pageContent['column_one'][0]['caption'] !== ""){
								$workMarkup .= '<div class="caption">'.$pageContent['column_one'][0]['caption'].'</div>';
							}

							$workMarkup .= '</div>
								</li>';

							$col2Width = "g".(8 - $pageContent['column_one'][0]['grid_cols'] - $pageContent['column_one'][0]['grid_offset']);
							$workMarkup .= '
								<li class="col col-2 border-' . $pageContent['column_two'][0]['border_option'] . ' ' . $col2Width . '">
									<div class="oHalf">
							';
							$mPadClass = "";
							if($pageContent['column_two'][0]['mobile_padding']){
								$mPadClass = " mobile-padding";
							}
							$workMarkup .= '<div class="framing framing-' . $pageContent['column_two'][0]["framing"] . $mPadClass . '">';
							if($pageContent['column_two'][0]['media_type'] === "image" && isset($pageContent['column_two'][0]['image']) && isset($pageContent['column_two'][0]['image']['sizes'])){
								$imgRatio = ($pageContent['column_two'][0]['image']['sizes']['large-height'] / $pageContent['column_two'][0]['image']['sizes']['large-width'])*100;
								$workMarkup .= '<div class="col-img-wrap inline-ratio" style="padding-bottom:'.$imgRatio.'%"><img src="' . $pageContent['column_two'][0]['image']['sizes']['large'] . '" srcset="'.$pageContent['column_two'][0]['image']['sizes']['medium'].' 900w, '.$pageContent['column_two'][0]['image']['sizes']['large'].' 2048w" width="' . $pageContent['column_two'][0]['image']['sizes']['large-width'] . '" height="' . $pageContent['column_two'][0]['image']['sizes']['large-height'] . '"></div>';
							}
							if($pageContent['column_two'][0]['media_type'] === "video"){
								$workMarkup .= '<vimeo vid="' . $pageContent['column_two'][0]['vimeo_id'] . '" aspect-ratio="'.$pageContent['column_one'][0]['aspect_ratio'].'" ui="' . $pageContent['column_two'][0]['video_ui'] . '" mp4="'.$pageContent['column_two'][0]['mp4_url'].'" view-play="{{workCtrl.viewPlay}}" in-view-options="{\'offsetTop\': 100, \'offsetBottom\': -100}" in-view="workCtrl.isInView($inview, $event, $inviewpart);"></vimeo>';
							}
							$workMarkup .= '</div>'; // End of framing div
							if($pageContent['column_two'][0]['caption'] !== ""){
								$workMarkup .= '<div class="caption">'.$pageContent['column_two'][0]['caption'].'</div>';
							}

							$workMarkup .= '</div>
								</li></ul>';
						} else if($pageContent['acf_fc_layout'] == "three_column"){

							$workMarkup .= '
							<ul class="loop-three-column work-section">
								<li class="col col-1 border-' . $pageContent['column_one'][0]['border_option'] . ' gThreeCol">
									<div class="rHalf">
							';

							$mPadClass = "";
							if($pageContent['column_one'][0]['mobile_padding']){
								$mPadClass = " mobile-padding";
							}
							$workMarkup .= '<div class="framing framing-' . $pageContent['column_one'][0]["framing"] . $mPadClass . '">';
							if($pageContent['column_one'][0]['media_type'] === "image" && isset($pageContent['column_one'][0]['image']) && isset($pageContent['column_one'][0]['image']['sizes'])){
								$imgRatio = ($pageContent['column_one'][0]['image']['sizes']['large-height'] / $pageContent['column_one'][0]['image']['sizes']['large-width'])*100;
								$workMarkup .= '<div class="col-img-wrap inline-ratio" style="padding-bottom:'.$imgRatio.'%"><img src="' . $pageContent['column_one'][0]['image']['sizes']['large'] . '" srcset="'.$pageContent['column_one'][0]['image']['sizes']['medium'].' 900w, '.$pageContent['column_one'][0]['image']['sizes']['large'].' 2048w" width="' . $pageContent['column_one'][0]['image']['sizes']['large-width'] . '" height="' . $pageContent['column_one'][0]['image']['sizes']['large-height'] . '"></div>';
							}
							if($pageContent['column_one'][0]['media_type'] === "video"){
								$workMarkup .= '<vimeo vid="' . $pageContent['column_one'][0]['vimeo_id'] . '" aspect-ratio="' .$pageContent['column_one'][0]['aspect_ratio'] . '"  ui="' . $pageContent['column_one'][0]['video_ui'] . '" mp4="'.$pageContent['column_one'][0]['mp4_url'].'" view-play="{{workCtrl.viewPlay}}" in-view-options="{\'offsetTop\': 100, \'offsetBottom\': -100}" in-view="workCtrl.isInView($inview, $event, $inviewpart);"></vimeo>';
							}
							$workMarkup .= '</div>';
							if($pageContent['column_one'][0]['caption'] !== ""){
								$workMarkup .= '<div class="caption">'.$pageContent['column_one'][0]['caption'].'</div>';
							}

							$workMarkup .= '</div>
								</li>';

							$workMarkup .= '
								<li class="col col-2 border-' . $pageContent['column_two'][0]['border_option'] . ' gThreeCol">
									<div class="tHalf">
							';
							$mPadClass = "";
							if($pageContent['column_two'][0]['mobile_padding']){
								$mPadClass = " mobile-padding";
							}
							$workMarkup .= '<div class="framing framing-' . $pageContent['column_two'][0]["framing"] . $mPadClass . '">';
							if($pageContent['column_two'][0]['media_type'] === "image" && isset($pageContent['column_two'][0]['image']) && isset($pageContent['column_two'][0]['image']['sizes'])){
								$imgRatio = ($pageContent['column_two'][0]['image']['sizes']['large-height'] / $pageContent['column_two'][0]['image']['sizes']['large-width'])*100;
								$workMarkup .= '<div class="col-img-wrap inline-ratio" style="padding-bottom:'.$imgRatio.'%"><img src="' . $pageContent['column_two'][0]['image']['sizes']['large'] . '" srcset="'.$pageContent['column_two'][0]['image']['sizes']['medium'].' 900w, '.$pageContent['column_two'][0]['image']['sizes']['large'].' 2048w" width="' . $pageContent['column_two'][0]['image']['sizes']['large-width'] . '" height="' . $pageContent['column_two'][0]['image']['sizes']['large-height'] . '"></div>';
							}
							if($pageContent['column_two'][0]['media_type'] === "video"){
								$workMarkup .= '<vimeo vid="' . $pageContent['column_two'][0]['vimeo_id'] . '" aspect-ratio="' .$pageContent['column_two'][0]['aspect_ratio'] . '" ui="' . $pageContent['column_two'][0]['video_ui'] . '" mp4="'.$pageContent['column_two'][0]['mp4_url'].'" view-play="{{workCtrl.viewPlay}}" in-view-options="{\'offsetTop\': 100, \'offsetBottom\': -100}" in-view="workCtrl.isInView($inview, $event, $inviewpart);"></vimeo>';
							}
							$workMarkup .= '</div>';
							if($pageContent['column_two'][0]['caption'] !== ""){
								$workMarkup .= '<div class="caption">'.$pageContent['column_two'][0]['caption'].'</div>';
							}

							$workMarkup .= '</div>
								</li>';

							$workMarkup .= '
								<li class="col col-3 border-' . $pageContent['column_three'][0]['border_option'] . ' gThreeCol">
									<div class="oHalf">
							';
							$mPadClass = "";
							if($pageContent['column_three'][0]['mobile_padding']){
								$mPadClass = " mobile-padding";
							}
							$workMarkup .= '<div class="framing framing-' . $pageContent['column_three'][0]["framing"] . $mPadClass .'">';
							if($pageContent['column_three'][0]['media_type'] === "image" && isset($pageContent['column_three'][0]['image']) && isset($pageContent['column_three'][0]['image']['sizes'])){
								$imgRatio = ($pageContent['column_three'][0]['image']['sizes']['large-height'] / $pageContent['column_three'][0]['image']['sizes']['large-width'])*100;
								$workMarkup .= '<div class="col-img-wrap inline-ratio" style="padding-bottom:'.$imgRatio.'%"><img src="' . $pageContent['column_three'][0]['image']['sizes']['large'] . '" srcset="'.$pageContent['column_three'][0]['image']['sizes']['medium'].' 900w, '.$pageContent['column_three'][0]['image']['sizes']['large'].' 2048w" width="' . $pageContent['column_three'][0]['image']['sizes']['large-width'] . '" height="' . $pageContent['column_three'][0]['image']['sizes']['large-height'] . '"></div>';
							}
							if($pageContent['column_three'][0]['media_type'] === "video"){
								$workMarkup .= '<vimeo vid="' . $pageContent['column_three'][0]['vimeo_id'] . '" aspect-ratio="' .$pageContent['column_three'][0]['aspect_ratio'] . '" ui="' . $pageContent['column_three'][0]['video_ui'] . '" mp4="'.$pageContent['column_three'][0]['mp4_url'].'" view-play="{{workCtrl.viewPlay}}" in-view-options="{\'offsetTop\': 100, \'offsetBottom\': -100}" in-view="workCtrl.isInView($inview, $event, $inviewpart);"></vimeo>';
							}
							$workMarkup .= '</div>'; // End of framing div
							if($pageContent['column_three'][0]['caption'] !== ""){
								$workMarkup .= '<div class="caption">'.$pageContent['column_three'][0]['caption'].'</div>';
							}

							$workMarkup .= '</div>
								</li></ul>';
						}
					}
				}

				$upload_dir = wp_upload_dir();
				if (!is_dir($upload_dir['basedir'].'/data')) { mkdir($upload_dir['basedir'].'/data');}
				if (!is_dir($upload_dir['basedir'].'/data/templates')) { mkdir($upload_dir['basedir'].'/data/templates');}
				$dataFileName = $upload_dir['basedir'].'/data/templates/' . $slug . ".html";
				file_put_contents($dataFileName,$workMarkup);




				// 		</div>
				// 	</div>

				// ';
				// $singleEpData["markup"] = $workMarkup;
				if($insertDate === true){
					$mData[] = array("type" => "Date", "title" => $trackDate, 'url' => '');
				}
				$mData[] = $singleEpData;
			endwhile;
			buildJSON('colors');
		} else if($pageType == "clips"){
			if(isset($_GET['catFilters'])){
				$cats = $_GET['catFilters'];
				if(is_array($cats)){
					$catsUse = implode(',', $cats);
				} else {
					$catsUse = $cats;
				}
			} else {
				$catsUse = "";
			}

			query_posts(array(
				'posts_per_page' => -1,
				'post_type' => array('clips'),
				'cat' => -4
			));

			if ( have_posts() ) while ( have_posts() ) : the_post();
				$singleEpData = array();
				$post_for_slug = get_post( get_the_ID() );
				$slug = $post_for_slug->post_name;

				$singleEpData["id"] = get_the_ID();
				$singleEpData["date"] = get_the_date('Y-m-d h:ia');
				$singleEpData["title"] = get_the_title();

				$singleEpData["date"] = get_the_date('M j');
				$singleEpData["year"] = get_the_date('Y');
				$singleEpData["url"] = str_replace(home_url(), '', apply_filters('the_permalink', get_permalink()));
				$singleEpData["excerpt"] = get_field("excerpt");
				if(strlen($singleEpData["excerpt"]) < 10){
					$singleEpData["excerpt"] = get_the_content();
				}
				$singleEpData["links"] = get_field("links");
				$singleEpData["contents"] = get_field("contents");
				$singleEpData["fImg"] = get_the_post_thumbnail($singleEpData["id"],"large");
				$singleEpData["aImg"] = wp_get_attachment_image_src( get_post_thumbnail_id( $singleEpData["id"] ), "medium" );

				$singleEpData["hero"] = get_field('hero_content');
				$singleEpData["c"] = get_field('order');
				$singleEpData["b"] = get_field('border_option');

				$singleEpData["cats"] = wp_get_post_terms( $singleEpData["id"], "category" );
				$mData[] = $singleEpData;


				$clipMarkup = '<div class="hero-content border-'.$singleEpData["b"].'"><div class="hero-inner">';

				foreach($singleEpData["hero"] as $heroS){
					if($heroS['acf_fc_layout'] === "html"){
						$clipMarkup .= '<div class="html-wrap">' . $heroS['text'] .'</div>';
					} else if($heroS['acf_fc_layout'] === "image"){
						$clipMarkup .= '<div class="image-wrap"><img src="' . $heroS['image']['sizes']['large'] . '" srcset="'.$heroS['image']['sizes']['medium'].' 900w, '.$heroS['image']['sizes']['large'].' 2048w" width="' . $heroS['image']['sizes']['large-width'] . '" height="' . $heroS['image']['sizes']['large-height'] . '"></div>';
					} else if($heroS['acf_fc_layout'] === "video"){
						$clipMarkup .= '<div class="video-wrap" style="padding-bottom: ' . ($heroS['arh'] / $heroS['arw'])*100 . '%">' . $heroS['video'] . '</div>';
					} else {

					}
				}

				$clipMarkup .= '</div></div>

		<div class="gi g5 o3"><div class="project-text"><h1>'.$singleEpData["title"].'</h1>
				<h6 class="clip-date"> ' . $singleEpData["date"] . ', ' . $singleEpData['year'] . '</h6>
				<div class="clip-excerpt">' . $singleEpData['excerpt'] . get_the_content() . '</div>
			</div>
		</div>
				';


				$upload_dir = wp_upload_dir();
				if (!is_dir($upload_dir['basedir'].'/data')) { mkdir($upload_dir['basedir'].'/data');}
				if (!is_dir($upload_dir['basedir'].'/data/clips')) { mkdir($upload_dir['basedir'].'/data/clips');}
				$dataFileName = $upload_dir['basedir'].'/data/clips/' . $slug . ".html";
				file_put_contents($dataFileName,$clipMarkup);

			endwhile;
			buildJSON('colors');
		} else if($pageType == "colors"){
			query_posts(array(
				'posts_per_page' => -1,
				'post_type' => array('clips','work','page'),
				'post_status' => 'publish'
			));
			$finalStyleMarkup = "";
			if ( have_posts() ) while ( have_posts() ) : the_post();
				$postID = get_the_ID();
				$pColor = get_field("color",$postID);
				$pRGB = hex2rgb($pColor);
				if($pColor !== '' && strlen($pColor) >2){
					$finalStyleMarkup .= '.no-touchevents .p-' . $postID . ' .work-section a:HOVER, .touchevents .p-' . $postID . ' .work-section a:ACTIVE, .no-touchevents .p-' . $postID . ' .archive-link:HOVER, .touchevents .p-' . $postID . ' .archive-link:ACTIVE { color:'.$pColor.'} .no-touchevents .p-' . $postID . ' .href-uline:HOVER, .touchevents .p-' . $postID . ' .href-uline:ACTIVE { border-bottom-color:'.$pColor.'} .p-' . $postID . ' .work-section a, .p-' . $postID . ' .archive-link { border-bottom-color:rgba(' . $pRGB . ',0.2); } .touchevents .p-' . $postID . ' .work-section a:ACTIVE, .no-touchevents .p-' . $postID . ' .work-section a:HOVER, .touchevents .p-' . $postID . ' .archive-link:ACTIVE, .no-touchevents .p-' . $postID . ' .archive-link:HOVER { border-bottom-color:rgba(' . $pRGB . ',0.8); } .p-' . $postID . ' ::selection { background: ' . $pColor . '; text-shadow:none; }
					';
				} else {
				}
			endwhile;
			$upload_dir = wp_upload_dir();
			if (!is_dir($upload_dir['basedir'].'/data')) { mkdir($upload_dir['basedir'].'/data');}
			$dataFileName = $upload_dir['basedir'].'/data/colors.css';
			file_put_contents($dataFileName,$finalStyleMarkup);
		} else if($pageType == "home"){
			$homeData = array();
			$homeData["projectData"] = get_field("projects",93);
			$countCheck = 0;
			$homeMarkup = '<div class="project-list" ng-controller="HomeController as HomeCtrl">';
			foreach($homeData["projectData"] as $singleProjectData){
				if($singleProjectData['acf_fc_layout'] == "single_feature"){
					$url = get_permalink($singleProjectData['project_link'][0]);
					$homeMarkup .= '<div class="project-row single_feature">';
					$homeMarkup .= outputHomeContent($singleProjectData,'single_feature',array());
					$homeMarkup .= '</div>'; // End project row div
				} else if($singleProjectData['acf_fc_layout'] == "two_item_row"){
					$url1 = get_permalink($singleProjectData['item_one'][0]['project_link'][0]);
					$url2 = get_permalink($singleProjectData['item_two'][0]['project_link'][0]);
					$homeMarkup .= '<div class="project-row two_item_row">';
					$homeMarkup .= outputHomeContent($singleProjectData['item_one'][0],'item_one',$singleProjectData['item_two'][0]);
					$homeMarkup .= outputHomeContent($singleProjectData['item_two'][0],'item_two',$singleProjectData['item_one'][0]);
					$homeMarkup .= '</div>'; // End project row div
				}
				$countCheck++;
			}

			// $countCheck = 0;
			// foreach($homeData["projectData"] as $singleProjectData){
			// 	if($singleProjectData['acf_fc_layout'] == "single_feature"){
			// 		$url = get_permalink($singleProjectData['project_link'][0]);
			// 		$homeData["projectData"][$countCheck]['pURL'] = $url;
			// 	} else if ($singleProjectData['acf_fc_layout'] == "instagram_and_item"){
			// 		$itemUrl = get_permalink($singleProjectData['item'][0]['project_link'][0]);

			// 		$homeData["projectData"][$countCheck]['item']['pURL'] = $itemUrl;

			// 		query_posts(array(
			// 			'posts_per_page' => 1,
			// 			'post_type' => 'post',
			// 			'category_name' => 'instagram'
			// 		));

			// 		if ( have_posts() ) while ( have_posts() ) : the_post();
			// 			$homeData["projectData"][$countCheck]['insta_item']['pURL'] = 'http://instagram.com/chipsny';
			// 			$homeData["projectData"][$countCheck]['insta_item']['caption'] = get_the_content();
			// 			$homeData["projectData"][$countCheck]['insta_item']['instaSrc'] = get_the_title();
			// 			$homeData["projectData"][$countCheck]['insta_item']['date'] = get_the_date('n-j-y');
			// 		endwhile;
			// 	} else {
			// 		$url1 = get_permalink($singleProjectData['item_one'][0]['project_link'][0]);
			// 		$url2 = get_permalink($singleProjectData['item_two'][0]['project_link'][0]);
			// 		$homeData["projectData"][$countCheck]['item_one']['pURL'] = $url1;
			// 		$homeData["projectData"][$countCheck]['item_two']['pURL'] = $url2;
			// 	}
			// 	$countCheck++;
			// }
			$homeMarkup .= '</div>';
			// print $homeMarkup;
			$mData[] = $homeData;
			$upload_dir = wp_upload_dir();
			if (!is_dir($upload_dir['basedir'].'/data')) { mkdir($upload_dir['basedir'].'/data');}
			$dataFileName = $upload_dir['basedir'].'/data/home.html';
			file_put_contents($dataFileName,$homeMarkup);
		}
	} else {
		// print $pageType;
	}

	$finalText = json_encode($mData);
	$finalText = str_replace("Protected: ","",str_replace("-width","width",str_replace("-height","height",$finalText)));
	return $finalText;

}

function outputHomeContent($passedObject,$passedType,$addParams){

	$returnHTML = '';
	$rT = $passedObject['media_type'];
	$extraClass = '';
	if($passedType === "item_one"){
		$extraClass = $addParams['aspect_ratio'] . '-'. $passedObject['aspect_ratio'] . ' margin-image two_item_row';
	} else if($passedType === "item_two"){
		$extraClass = $addParams['aspect_ratio'] .'-'. $passedObject['aspect_ratio'] . ' two_item_row';
	}
	if($rT === "image"){
		$returnHTML .= '
<div class="img-wrapper border-'.$passedObject['border_option'].' ' . $extraClass .'" homebg="'.get_field('color',$passedObject['project_link'][0]).'">
	<a href="'.get_permalink($passedObject['project_link'][0]).'" class="overlayLink"  ng-click="pages.gaClick(\'Home page links\', \'Projects nav\', \'' . $passedObject['project_title'] . '\' + \' click\')"></a>
	<div class="img-pad '.$passedObject['aspect_ratio'].'"><img src="'.$passedObject['image']['sizes']['medium'].'" srcset="'.$passedObject['image']['sizes']['medium'].' 900w, '.$passedObject['image']['sizes']['large'].' 2048w" width="'.$passedObject['image']['sizes']['medium-width'].'" height="'.$passedObject['image']['sizes']['medium-height'].'" class="'.$passedType.'"></div>
	<div class="p-text">
		<h1>'.$passedObject['project_title'].'</h1>
		<p>'.$passedObject['description'].'</p>
	</div>
</div>
';
	} else if($rT === "video"){
		$returnHTML .= '
<div class="video-wrapper border-'.$passedObject['border_option'].' ' . $passedType .'" homebg="'.get_field('color',$passedObject['project_link'][0]).'">
	<a href="'.get_permalink($passedObject['project_link'][0]).'" class="overlayLink" ng-click="pages.gaClick(\'Home page links\', \'Projects nav\', \'' . $passedObject['project_title'] . '\' + \' click\')"></a>
	<vimeo vid="'.$passedObject['vimeo_id'].'" ui="'.$passedObject['video_ui'].'" mp4="'.$passedObject['mp4_url'].'" aspect-ratio="'.$passedObject['aspect_ratio'].'" in-view-options="{\'offsetTop\': 100, \'offsetBottom\': -100}" in-view="w.isInView($inview, $event, $inviewpart);"></vimeo>
	<div class="p-text">
		<h1>'.$passedObject['project_title'].'</h1>
		<p>'.$passedObject['description'].'</p>
	</div>
</div>
';
	}
	// if($passedObject)
	return $returnHTML;
}


function hex2rgb( $colour ) {
        if ( $colour[0] == '#' ) {
                $colour = substr( $colour, 1 );
        }
        if ( strlen( $colour ) == 6 ) {
                list( $r, $g, $b ) = array( $colour[0] . $colour[1], $colour[2] . $colour[3], $colour[4] . $colour[5] );
        } elseif ( strlen( $colour ) == 3 ) {
                list( $r, $g, $b ) = array( $colour[0] . $colour[0], $colour[1] . $colour[1], $colour[2] . $colour[2] );
        } else {
                return false;
        }
        $r = hexdec( $r );
        $g = hexdec( $g );
        $b = hexdec( $b );
        return $r . ',' . $g . ',' . $b;
}