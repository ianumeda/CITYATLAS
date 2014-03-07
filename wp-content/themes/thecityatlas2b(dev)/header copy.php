<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Starkers
 * @since Starkers 3.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="description" content="A user's guide to sustainable NYC" >
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<link rel="icon" type="image/png" href="<?php bloginfo('stylesheet_directory'); ?>/images/thecityatlaslogo-favicon-16.png">
<!--[if !IE 7]>
	<style type="text/css">
		#wrap {display:table;height:100%}
	</style>
<![endif]-->
<!-- jQuery (required) -->
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script> 
	<!-- // Organic Tabs //-->
    <script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/OrganicTabs/js/organictabs.jquery.js"></script>
    <script type='text/javascript'>
        $(function() {
            $("#example-one").organicTabs();
            $("#ot-lifestyle").organicTabs({ "speed": 200 });
	        $("#ot-maps").organicTabs({ "speed": 200 });
	        $("#ot-events").organicTabs({ "speed": 200 });
        });
	<!-- // End Organic Tabs //-->
		
	<!-- // Scroll to Anchor //-->
		function goToByScroll(id){
     			$('html,body').animate({scrollTop: $("#"+id).offset().top},'slow');
		}
    </script>
	
<?php
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );
	wp_head();
?>
</head>
<?php flush(); ?>
<body <?php body_class(); ?>>
<?php
	function get_menu_item($post_id, $title, $cat="", $customargs=null, $item_class="", $title_style="", $customlink=null, $blurb=""){
		$post=get_post($post_id);
		if(!isset($title)) $title=$post->post_title;
		if(!isset($cat)) $cat=get_the_category($post_id);
		else if($cat=="no cat") $cat="";
		if(empty($blurb)) $blurb=get_post_meta($post->ID, 'subtitle', true);
		if(!empty($customlink)) $link=$customlink;
		else $link=get_permalink($post->ID);
		$standardargs=array('queryValue'=>array($cat), 'numberPosts'=>5, 'maxColumns'=>3, 'totalWidth'=>960, 'margins'=>array(0,10,6,0), 'padding'=>array(4), 'gridElementHeight'=>60, 'textSpace'=>array(230,60), 'imagePosition'=>'left', 'gridElementBorder'=>1, 'hasInfoTab'=>false, 'useEmphasis'=>TRUE, 'textOptions'=>array('title'=>array('style'=>'h2','link'=>'yes please'), 'excerpt'=>array('style'=>'p', 'maxlength'=>50)), 'emphasisSchedule'=>array('1x2','0') );
		if(is_array($customargs)) $queryargs=$customargs;
		else $queryargs=$standardargs;
		if($title_style!="") $htmlMenuItem='<div class="menu-item "><span class="'.$title_style.'"><a class="menu-item" href="'. $link .'">'. $title .'</a></span><div class="sub-menu fader '.$item_class.'"><div id="post-'.$post_id.'" class="menu-content-popover">';
		else $htmlMenuItem='<div class="menu-item "><a class="menu-item" href="'. $link .'">'. $title .'</a><div class="sub-menu fader '.$item_class.'"><div id="post-'.$post_id.'" class="menu-content-popover">';
		
		$htmlMenuItem.='<div class="menu-content container_16">
							<div class="head"><span class="blurb">'. $blurb .'</span></div>
            				<div class="grid"> '. make_gridster( $queryargs ) .'</div> <!-- .grid_7 --> </div><!--.container_16-->';
		$htmlMenuItem.='</div></div></div>';
		return $htmlMenuItem;
	}
?>
<div id='fixedfooter'>
	<div class="menu_background ">
		<div id="fixedfooter-menu" class="sf-menu">
	<div id="bottom-logo"><a href="<?php echo home_url('/'); ?>">City Atlas</a></div><div id="bottom-logo-beta"><a href="<?php echo home_url('/beta-testing-feedback/'); ?>">[BETA]</a></div><!-- #bottom-logo -->
				<ul id="" class="" style="float:left; margin-left:20px;">
					<?php echo get_menu_item(34, null, "explore"); ?>
					<?php echo get_menu_item(36, null, "lifestyle"); ?>
					<?php echo get_menu_item(32, null, "people"); ?>
					<?php echo get_menu_item(42, "LAB", "atlas-lab", array('queryArgs'=>array('post_parent'=>42, 'post_type'=>'page'), 'numberPosts'=>3, 'maxColumns'=>3, 'maxRows'=>1, 'totalWidth'=>960, 'margins'=>array(0,10,6,0), 'padding'=>array(4), 'gridElementHeight'=>120, 'textSpace'=>array(230,60), 'imagePosition'=>'left', 'gridElementBorder'=>1, 'hasInfoTab'=>false, 'useEmphasis'=>TRUE, 'textOptions'=>array('title'=>array('style'=>'h2','link'=>'yes please'), 'excerpt'=>array('style'=>'p', 'maxlength'=>50)) ) ); ?>
					<?php echo get_menu_item(40, null, "archive"); ?>
				</ul><!--#menu-sites-->
		</div><!-- #site-menu -->
	</div><!-- #menu_background -->
	<div class='clear'>&nbsp</div>
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
<!--			<div id="top-logo"><a href="<?php echo home_url('/'); ?>">City Atlas</a></div><div id="top-logo-beta"><a href="<?php echo home_url('/beta-testing-feedback/'); ?>">[BETA]</a></div> #top-logo -->
			<div id="tempwidget"><?php include('tempwidget.php'); ?></div> 
			<div id="top-bar-right">
				<div id="top-menu" >
					<?php wp_nav_menu( array('container_class' => 'menu-header', 'menu' => 'root' )); ?>
					<div class="clear">&nbsp</div>
				</div> <!-- #top-menu -->
				<div id="top-social">
					<ul>
						<li class="fadehover"><a class="signup" href="http://eepurl.com/fX5Qz" alt="Signup">Signup</a>
							<ul>
								<li id="signup">
									<blockquote class="social-bubble">
									<p>Signup!</p>
									<div id="container-launchrock"></div>
									</blockquote>
								</li>
							</ul>
						</li>
						<li class="fadehover"><a class="facebook" href="http://facebook.com/cityatlas" alt="facebook">Facebook</a>
							<ul>
								<li id="facebook-feed">
									<blockquote class="social-bubble">
									<p>Like The City Atlas!</p>
									<p><?php //fb_likebutton('http://www.facebook.com/CityAtlas'); ?></p>
									</blockquote>
								</li>
							</ul>
						</li>
						<li class="fadehover"><a class="twitter" href="http://twitter.com/cityatlas" alt="Twitter">Twitter</a>
							<ul>
								<li id="twitter-feed">
									<blockquote class="social-bubble">
									<div class="widget-twitter-bubble">
										<p>Follow The City Atlas on Twitter!</p>
										<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Twitter Bubble') ) : ?>
										<?php endif; ?>
										<div class="clear">&nbsp</div>
									</div>
									</blockquote>
								</li>
							</ul>
						</li>
						<li class="fadehover"><a class="rss" href="<?php echo home_url('feed'); ?>" alt="RSS Feed">RSS</a>
						</li>
						</ul>
					<div class="clear">&nbsp</div>
				</div> <!-- #top-social -->
				<div id="top-search"><?php include (TEMPLATEPATH . '/searchform.php'); ?><div class="clear">&nbsp</div></div>
			</div><!-- #top-bar-right -->
		</div> <!-- #home-top-bar -->

