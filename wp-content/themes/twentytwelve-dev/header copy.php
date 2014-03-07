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
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;">
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
	function makeMapControls(mapcontrolshtml) { document.getElementById("map-controls-wrapper").innerHTML=mapcontrolshtml; }
	function scrollToOneIfNoScrollHasHappenedYet(){
		var vscroll = window.pageYOffset || document.documentElement.scrollTop;
		var hscroll = window.pageXOffset || document.documentElement.scrollLeft;
		if(vscroll==0){
			setTimeout(function(){ window.scrollTo(hscroll, 1) }, 0)
		}
	}
</script>
	<script src="http://cdn.leafletjs.com/leaflet-0.4/leaflet.js"></script>
	<link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.4/leaflet.css" />	

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?> onload="scrollToOneIfNoScrollHasHappenedYet();">

<?php
	function get_menu_item($post_slug, $title, $cat="", $customargs=null, $item_class="", $title_style="", $customlink=null, $blurb=""){
		$args=array(
		  'name' => $post_slug,
		  'post_type' => 'page',
		  'post_status' => 'publish',
		  'numberposts' => 1
		);
		if(!is_mobile() && $posts=get_posts($args)){
			if(!isset($title)) $title=$posts[0]->post_title;
			if(!isset($cat)) $cat=get_the_category($posts[0]->ID);
			else if($cat=="no cat") $cat="";
			if(empty($blurb)) $blurb=get_post_meta($posts[0]->ID, 'subtitle', true);
			if(!empty($customlink)) $link=$customlink;
			else $link=get_permalink($posts[0]->ID);

			$standardargs=array('queryValue'=>array($cat), 'numberPosts'=>10, 'maxColumns'=>1, 'totalWidth'=>160, 'margins'=>array(0,10,6,0), 'padding'=>array(4), 'gridElementHeight'=>180, 'textSpace'=>array(65), 'imagePosition'=>'top', 'gridElementBorder'=>1, 'hasInfoTab'=>false, 'useEmphasis'=>FALSE, 'textOptions'=>array('title'=>array('style'=>'span','link'=>'yes please')), 'defaultImage'=>array('http://newyork.thecityatlas.org/wp-content/themes/twentytwelve-dev/images/thecityatlaslogo-grey-160x160.jpg',160,160));
			if(is_array($customargs)) $queryargs=$customargs;
			else $queryargs=$standardargs;
			if(!empty($title_style)) $htmlMenuItem='<div class="menu-item "><span class="'.$title_style.'"><a class="menu-item" href="'. $link .'">'. $title .'</a></span><div class="sub-menu '.$item_class.'"><div id="post-'.$post_id.'" class="menu-content-popover">';
			else $htmlMenuItem='<div class="menu-item "><a class="menu-item" href="'. $link .'">'. $title .'</a><div class="sub-menu '.$item_class.'">';

			if($cat!="no popup"){
				$htmlMenuItem.='<div id="post-'.$posts[0]->ID.'" class="menu-content-popover">';
				$htmlMenuItem.='<div class="menu-content">
									<div class="head"><span class="blurb">'. $blurb .'</span></div>';
				if($cat!="no grid") $htmlMenuItem.='<div class="grid"> '. make_gridster( $queryargs ) .'</div>';
				$htmlMenuItem.='<div class="pagepreview" style="position: absolute; right: 0; top: 0; width: 240px; height: 200px; background: white; padding: 10px; box-shadow: -1px 0 8px #000;"><a href="'.$link.'" style="color:inherit; text-decoration:none;"><span class="title">more <span style="color:#8fc54b;">'.$title.'</span> &rarr;</span></a><span class="image">'.get_fit_image(get_image_from_post_bamn($posts[0],230,180), 230, 180, $link).'</span></div>';
				$htmlMenuItem.='</div></div>';
			}
			$htmlMenuItem.='</div></div>';
		}
		return $htmlMenuItem;
	}
?>
<div id='fixedfooter'>
	<div class="menu_background ">
		<div id="fixedfooter-menu" class="sf-menu">
	<div id="bottom-logo"><a href="<?php echo home_url('/'); ?>">City Atlas</a></div><div id="bottom-logo-cityname"><a href="<?php echo home_url('/'); ?>"><?php bloginfo('name'); ?></a></div><!-- #bottom-logo -->
				<ul id="section-menu" class="" style="float:right;">
					<?php echo get_menu_item('explore', "EXPLORE", "events", array('queryArgs'=>array('post_type'=>'tribe_events', 'meta_key'=>'_EventEndDate', 'orderby'=>'meta_value_num', 'order'=>'ASC','meta_value' => date('Y-m-d H:i', time()), 'meta_compare' => '>'  ), 'numberPosts'=>10, 'maxColumns'=>1, 'totalWidth'=>160, 'margins'=>array(0,10,6,0), 'padding'=>array(4), 'gridElementHeight'=>180, 'textSpace'=>array(65), 'imagePosition'=>'top', 'gridElementBorder'=>1, 'hasInfoTab'=>false, 'useEmphasis'=>FALSE, 'textOptions'=>array('title'=>array('style'=>'span','link'=>'yes please') ) ) ); ?>
					<?php //echo get_menu_item('explore', null, "explore"); ?>
					<?php echo get_menu_item('lifestyle', null, "lifestyle"); ?>
					<?php echo get_menu_item('people', null, "people"); ?>
					<?php echo get_menu_item('lab', "LAB", "atlas-lab", array('queryArgs'=>array('post_parent'=>42, 'post_type'=>'page'), 'numberPosts'=>5, 'maxColumns'=>3, 'maxRows'=>1, 'totalWidth'=>960, 'margins'=>array(0,10,6,0), 'padding'=>array(4), 'gridElementHeight'=>180, 'textSpace'=>array(100), 'imagePosition'=>'top', 'gridElementBorder'=>1, 'hasInfoTab'=>false, 'useEmphasis'=>TRUE, 'textOptions'=>array('title'=>array('style'=>'span','link'=>'yes please'), 'excerpt'=>array('style'=>'span', 'maxlength'=>50)) ) ); ?>
					<?php 
						// echo get_menu_item('atlas-events', "EVENTS", "events", array('queryArgs'=>array('post_type'=>'tribe_events', 'meta_key'=>'_EventEndDate', 'orderby'=>'meta_value_num', 'order'=>'ASC','meta_value' => date('Y-m-d H:i', time()), 'meta_compare' => '>'  ), 'numberPosts'=>10, 'maxColumns'=>1, 'totalWidth'=>160, 'margins'=>array(0,10,6,0), 'padding'=>array(4), 'gridElementHeight'=>160, 'textSpace'=>array(40), 'imagePosition'=>'top', 'gridElementBorder'=>1, 'hasInfoTab'=>false, 'useEmphasis'=>FALSE, 'textOptions'=>array('title'=>array('style'=>'span','link'=>'yes please') ) ) );
					?>
				</ul><!--#menu-sites-->
				<div id="menu-mobile-sections">
					<form action="getsection">
					<select name="sections">
					<option value="explore">Explore</option>
					<option value="lifestyle" selected>Lifestyle</option>
					<option value="people">People</option>
					<option value="lab">Lab</option>
					<option value="archive">Archive</option>
					</select>
					</form>
				</div>
			<?php //wp_nav_menu( array('menu' => 'mobile-sections' )); ?>
		</div><!-- #site-menu -->
	</div><!-- #menu_background -->
	<div id="fixed-second-row" class="inner-shadow">
		<?php if(empty($_ENV['ismappage']) && !is_mobile() && function_exists('insert_newsticker') ) { insert_newsticker(); } elseif($_ENV['ismappage']) { ?>
			<div id="map-controls-wrapper"></div>
		<?php } ?>
	</div><!-- #fixed-second-row -->
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
			<?php wp_nav_menu( array( 'menu' => 'root' ) ); ?>
			<?php wp_nav_menu( array('menu' => 'mobile-root' )); ?>
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