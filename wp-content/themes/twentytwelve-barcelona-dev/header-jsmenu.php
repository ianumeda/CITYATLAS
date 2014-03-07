<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?><!DOCTYPE html>
<!--[if IE 7 | IE 8]>
<html class="ie" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php // Loads HTML5 JavaScript file to add support for HTML5 elements in older IE versions. ?>
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script> 
<script type='text/javascript'>
<!-- // Scroll to Anchor //-->
	function goToByScroll(id){ $('html,body').animate({scrollTop: $("#"+id).offset().top},'slow'); }
	function makeLead(leadhtml) { document.getElementById("lead").innerHTML=leadhtml; document.getElementById("lead").style.height="100%"; }
</script>
	<script src="http://cdn.leafletjs.com/leaflet-0.4/leaflet.js"></script>
	<link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.4/leaflet.css" />	

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<script type='text/javascript'>
<!-- // make post previews only on rollover //-->
	function get_section_preview(title){
		alert(window.preview-[title]);
		document.getElementById("section-preview").innerHTML=window.preview-[title];
		document.getElementById("section-preview").style.height="200px";
		document.getElementById("section-preview").style.width="100%";
		document.getElementById("section-preview").style.position="relative";
		document.getElementById("section-preview").style.top="-200px";
		document.getElementById("section-preview").style.display="block";
	}
</script>
<?php
	function get_menu_item($post_slug, $title, $cat="", $customargs=null, $item_class="", $title_style="", $customlink=null, $blurb=""){
		$args=array(
		  'name' => $post_slug,
		  'post_type' => 'page',
		  'post_status' => 'publish',
		  'numberposts' => 1
		);
		if($posts=get_posts($args)){
			if(!isset($title)) $title=$posts[0]->post_title;
			if(!isset($cat)) $cat=get_the_category($posts[0]->ID);
			else if($cat=="no cat") $cat="";
			if(empty($blurb)) $blurb=get_post_meta($posts[0]->ID, 'subtitle', true);
			if(!empty($customlink)) $link=$customlink;
			else $link=get_permalink($posts[0]->ID);
			$standardargs=array('queryValue'=>array($cat), 'numberPosts'=>9, 'maxColumns'=>3, 'totalWidth'=>960, 'margins'=>array(0,10,6,0), 'padding'=>array(4), 'gridElementHeight'=>60, 'textSpace'=>array(230,60), 'imagePosition'=>'left', 'gridElementBorder'=>1, 'hasInfoTab'=>false, 'useEmphasis'=>TRUE, 'textOptions'=>array('title'=>array('style'=>'span','link'=>'yes please'), 'excerpt'=>array('style'=>'span', 'maxlength'=>50)), 'emphasisSchedule'=>array('1x2','0') );
			if(is_array($customargs)) $queryargs=$customargs;
			else $queryargs=$standardargs;
			$gridhtml='<div class="menu-content"><div class="head"><span class="blurb">'. $blurb .'</span></div><div class="grid"> '. make_gridster( $queryargs ) .'</div> </div>';
			echo '<script type="text/javascript">
			<!-- // store preview html in js variables //-->
			var preview-'.$title.'=\''.$gridhtml.'\'";
			</script>';
			// $gridhtml=htmlentities($gridhtml);
			if(!empty($title_style)) $htmlMenuItem='<div class="menu-item "><span class="'.$title_style.'"><a class="menu-item" href="'. $link .'" onmouseover=\'get_section_preview("'.$title.'");\'>'. $title .'</a></span></div>';
			else $htmlMenuItem='<div class="menu-item "><a class="menu-item" href="'. $link .'" onmouseover=\'get_section_preview("'.$title.'");\'>'. $title .'</a></div>';
		}
		return $htmlMenuItem;
	}
?>
<div id='fixedfooter'>
	<div class="menu_background ">
		<div id="fixedfooter-menu" class="sf-menu">
	<div id="bottom-logo"><a href="<?php echo home_url('/'); ?>">City Atlas</a></div><div id="bottom-logo-cityname"><a href="<?php echo home_url('/'); ?>"><?php bloginfo('name'); ?></a></div><!-- #bottom-logo -->
				<ul id="" class="" style="float:right; margin-left:20px;">
					<?php echo get_menu_item('explore', null, "explore"); ?>
					<?php echo get_menu_item('lifestyle', null, "lifestyle"); ?>
					<?php echo get_menu_item('people', null, "people"); ?>
					<?php echo get_menu_item('lab', "LAB", "atlas-lab", array('queryArgs'=>array('post_parent'=>42, 'post_type'=>'page'), 'numberPosts'=>5, 'maxColumns'=>3, 'maxRows'=>1, 'totalWidth'=>960, 'margins'=>array(0,10,6,0), 'padding'=>array(4), 'gridElementHeight'=>120, 'textSpace'=>array(230,60), 'imagePosition'=>'left', 'gridElementBorder'=>1, 'hasInfoTab'=>false, 'useEmphasis'=>TRUE, 'textOptions'=>array('title'=>array('style'=>'span','link'=>'yes please'), 'excerpt'=>array('style'=>'span', 'maxlength'=>50)) ) ); ?>
					<?php 
						echo get_menu_item('atlas-events', "EVENTS", "events", array('queryArgs'=>array('post_type'=>'tribe_events', 'meta_key'=>'_EventEndDate', 'orderby'=>'meta_value_num', 'order'=>'ASC','meta_value' => date('Y-m-d H:i', time()), 'meta_compare' => '>'  ), 'numberPosts'=>10, 'numberPosts'=>9, 'maxColumns'=>3, 'totalWidth'=>960, 'margins'=>array(0,10,6,0), 'padding'=>array(4), 'gridElementHeight'=>60, 'textSpace'=>array(230,60), 'imagePosition'=>'left', 'gridElementBorder'=>1, 'hasInfoTab'=>false, 'useEmphasis'=>TRUE, 'textOptions'=>array( 'tec_date'=>array('style'=>'span','link'=>'yes please'), 'title'=>array('style'=>'span','link'=>'yes please'), 'excerpt'=>array('style'=>'span', 'maxlength'=>50)), 'emphasisSchedule'=>array('1x2','0'), 'getStickies'=>TRUE, 'smartImagePlacement'=>FALSE, 'flexibleHeight'=>FALSE, 'shareButton'=>FALSE) );
					?>
				</ul><!--#menu-sites-->
		</div><!-- #site-menu -->
	</div><!-- #menu_background -->
	<div id="fixed-second-row" class="inner-shadow">
		<?php if ( function_exists('insert_newsticker') ) { insert_newsticker(); } ?>
	</div><!-- #fixed-second-row -->
	<div id="section-preview"></div>
</div><!-- #fixedfooter -->
<?php 
	if(is_front_page()) $pageclass='-front';
	else $pageclass='-interior';
	// this is the header for the front page... 
?>
<div id="top-bar<?php echo $pageclass; ?>" class="">
	<div id="tempwidget"><?php include('tempwidget.php'); ?></div> 
	<div id="top-bar-right">
		<div id="top-menu" >
			<?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
			<?php wp_nav_menu( array('container_class' => 'menu-header', 'menu' => 'root-mobile' )); ?>
		</div> <!-- #top-menu -->
		<div id="top-social">
			<script type="text/javascript">

			var showFollowBarUI_params=
			{ 
				containerID: 'componentDivTopFollow',
				iconSize: 17,
				buttons: [
				{ 
					provider: 'facebook',
					actionURL: 'http://www.facebook.com/cityatlas',
					action: 'dialog'
				},
				{ 
					provider: 'twitter',
					action: 'dialog',
					followUsers: 'cityatlas'
				},
				{ 
					provider: 'googleplus',
					actionURL: 'https://plus.google.com/u/2/b/118238833777329286621/118238833777329286621/posts'
				},
				{ 
					provider: 'rss',
					actionURL: '<?php echo home_url('/feed/'); ?>'
				}
				]
			}
			</script>
			<div id="componentDivTopFollow"></div>
			<script type="text/javascript">
			   gigya.socialize.showFollowBarUI(showFollowBarUI_params);
			</script>
		<?php include (TEMPLATEPATH . '/gigya_sharing_template_top.php'); ?>
<!--		<span><a class="rss" href="<?php echo home_url('feed'); ?>" alt="RSS Feed">RSS</a></span> -->
		</div> <!-- #top-social -->
		<div id="top-search"><?php include (TEMPLATEPATH . '/searchform.php'); ?></div>
	</div><!-- #top-bar-right -->
</div> <!-- #home-top-bar -->
<div id="lead"></div>
<div id="page" class="hfeed site page<?php echo $pageclass; ?>">
	<div id="main" class="wrapper">