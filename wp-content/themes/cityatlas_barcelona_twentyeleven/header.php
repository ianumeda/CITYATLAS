<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */
?><!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 7]>
<html id="ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<link rel="icon" type="image/png" href="<?php bloginfo('stylesheet_directory'); ?>/images/thecityatlaslogo-favicon-16.png">
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'twentyeleven' ), max( $paged, $page ) );

	?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->

	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script> 
<script type='text/javascript'>
<!-- // Scroll to Anchor //-->
	function goToByScroll(id){
 			$('html,body').animate({scrollTop: $("#"+id).offset().top},'slow');
	}
</script>

<?php
	/* We add some JavaScript to pages with the comment form
	 * to support sites with threaded comments (when in use).
	 */
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	/* Always have wp_head() just before the closing </head>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to add elements to <head> such
	 * as styles, scripts, and meta tags.
	 */
	wp_head();
?>
</head>
<?php flush(); ?>
<body <?php body_class(); ?>>
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
			$standardargs=array('queryValue'=>array($cat), 'numberPosts'=>5, 'maxColumns'=>3, 'floatOrder'=>array('right','left'), 'totalWidth'=>960, 'margins'=>array(0,10,6,0), 'padding'=>array(4), 'gridElementHeight'=>60, 'textSpace'=>array(230,60), 'imagePosition'=>'left', 'gridElementBorder'=>1, 'hasInfoTab'=>false, 'useEmphasis'=>TRUE, 'textOptions'=>array('title'=>array('style'=>'h2','link'=>'yes please'), 'excerpt'=>array('style'=>'p', 'maxlength'=>50)), 'emphasisSchedule'=>array('1x2','0') );
			if(is_array($customargs)) $queryargs=$customargs;
			else $queryargs=$standardargs;
			if(!empty($title_style)) $htmlMenuItem='<div class="menu-item "><span class="'.$title_style.'"><a class="menu-item" href="'. $link .'">'. $title .'</a></span><div class="sub-menu fader '.$item_class.'"><div id="post-'.$post_id.'" class="menu-content-popover">';
			else $htmlMenuItem='<div class="menu-item "><a class="menu-item" href="'. $link .'">'. $title .'</a><div class="sub-menu fader '.$item_class.'"><div id="post-'.$posts[0]->ID.'" class="menu-content-popover">';

			$htmlMenuItem.='<div class="menu-content grid_margins">
								<div class="head"><span class="blurb">'. $blurb .'</span></div>
	            				<div class="grid"> '. make_gridster( $queryargs ) .'</div> <!-- .grid_7 --> </div><!--.container_16-->';
			$htmlMenuItem.='</div></div></div>';
		}
		return $htmlMenuItem;
	}
?>
<div id='fixedfooter'>
	<div class="menu_background ">
		<div id="fixedfooter-menu" class="sf-menu">
	<div id="bottom-logo"><a href="<?php echo home_url('/'); ?>">City Atlas</a></div><div id="bottom-logo-beta"><a href="<?php echo home_url('/'); ?>"><?php bloginfo('name'); ?></a></div><!-- #bottom-logo -->
				<ul id="" class="" style="float:right; margin-left:20px;">
					<?php echo get_menu_item('explore', null, "explore"); ?>
					<?php echo get_menu_item('lifestyle', null, "lifestyle"); ?>
					<?php echo get_menu_item('people', null, "people"); ?>
					<?php echo get_menu_item('lab', "LAB", "atlas-lab", array('queryArgs'=>array('post_parent'=>42, 'post_type'=>'page'), 'numberPosts'=>3, 'maxColumns'=>3, 'maxRows'=>1, 'totalWidth'=>960, 'margins'=>array(0,10,6,0), 'padding'=>array(4), 'gridElementHeight'=>120, 'textSpace'=>array(230,60), 'imagePosition'=>'left', 'gridElementBorder'=>1, 'hasInfoTab'=>false, 'useEmphasis'=>TRUE, 'textOptions'=>array('title'=>array('style'=>'h2','link'=>'yes please'), 'excerpt'=>array('style'=>'p', 'maxlength'=>50)) ) ); ?>
					<?php echo get_menu_item('events', null, "events"); ?>
				</ul><!--#menu-sites-->
		</div><!-- #site-menu -->
	</div><!-- #menu_background -->
	<div id="fixed-second-row" class="inner-shadow">
		<?php if ( function_exists('insert_newsticker') && is_front_page() ) { insert_newsticker(); } ?>
	</div><!-- #fixed-second-row -->
</div><!-- #fixedfooter -->
<?php 
	if(is_front_page()) $pageclass='-front';
	else $pageclass='-interior';
	// this is the header for the front page... 
?>
<div id="top-bar<?php echo $pageclass; ?>" class="structural-page-element">
	<div id="tempwidget"><?php include('tempwidget.php'); ?></div> 
	<div id="top-bar-right">
		<div id="top-menu" >
			<?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
			<?php// wp_nav_menu( array('container_class' => 'menu-header', 'menu' => 'root' )); ?>
		</div> <!-- #top-menu -->
		<div id="top-social">
		<?php include (TEMPLATEPATH . '/gigya_sharing_template_top.php'); ?>
<!--		<span><a class="rss" href="<?php echo home_url('feed'); ?>" alt="RSS Feed">RSS</a></span> -->
		</div> <!-- #top-social -->
		<div id="top-search"><?php include (TEMPLATEPATH . '/searchform.php'); ?></div>
	</div><!-- #top-bar-right -->
</div> <!-- #home-top-bar -->

<div id="page" class="hfeed">

	<div id="main">
