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
			$standardargs=array('queryValue'=>array($cat), 'numberPosts'=>4, 'maxColumns'=>3, 'floatOrder'=>array('right','left'), 'totalWidth'=>960, 'margins'=>array(0,8,0,0), 'padding'=>array(4), 'gridElementHeight'=>120, 'textSpace'=>array(230,60), 'imagePosition'=>'left', 'gridElementBorder'=>1, 'hasInfoTab'=>false, 'useEmphasis'=>FALSE, 'textOptions'=>array('title'=>array('style'=>'h2','link'=>'yes please'), 'excerpt'=>array('style'=>'p', 'maxlength'=>50)), 'emphasisSchedule'=>array('1x2','0') );
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
	<div class="menu-background ">
		<div id="fixedfooter-menu" class="sf-menu">
	<div id="bottom-logo"><a href="<?php echo home_url('/'); ?>">City Atlas</a></div><div id="bottom-logo-beta"><a href="<?php echo home_url('/'); ?>"><?php bloginfo('name'); ?></a></div><!-- #bottom-logo -->
				<ul id="" class="" style="float:right; margin-left:20px;">
					<?php echo get_menu_item('explore', null, "explore"); ?>
					<?php echo get_menu_item('blog', null, "blog"); ?>
					<?php echo get_menu_item('people', null, "people"); ?>
					<?php echo get_menu_item('lab', "LAB", "atlas-lab", array('queryArgs'=>array('post_parent'=>42, 'post_type'=>'page'), 'numberPosts'=>3, 'maxColumns'=>3, 'maxRows'=>1, 'totalWidth'=>960, 'margins'=>array(0,10,6,0), 'padding'=>array(4), 'gridElementHeight'=>120, 'textSpace'=>array(230,60), 'imagePosition'=>'left', 'gridElementBorder'=>1, 'hasInfoTab'=>false, 'useEmphasis'=>TRUE, 'textOptions'=>array('title'=>array('style'=>'h2','link'=>'yes please'), 'excerpt'=>array('style'=>'p', 'maxlength'=>50)) ) ); ?>
					<?php //echo get_menu_item('events', null, null, array('queryArgs'=>array('tax_query' => array('relation' => 'OR', array( 'taxonomy' => 'event', 'field' => 'slug', 'terms' => array( 'events' ) ) ) ), 'numberPosts'=>10, 'maxColumns'=>3, 'maxRows'=>1, 'totalWidth'=>960, 'margins'=>array(0,10,6,0), 'padding'=>array(4), 'gridElementHeight'=>120, 'textSpace'=>array(230,60), 'imagePosition'=>'left', 'gridElementBorder'=>1, 'hasInfoTab'=>false, 'useEmphasis'=>TRUE,'floatOrder'=>array('right','left'), 'textOptions'=>array('title'=>array('style'=>'h2','link'=>'yes please'), 'excerpt'=>array('style'=>'p', 'maxlength'=>50) ) ) ); ?>
					<?php 
						// this inserts the events widget into the main menu...
						echo '<div class="menu-item "><a class="menu-item" href="'. home_url('/events') .'">Agenda</a><div class="sub-menu fader"><div class="menu-content-popover">';
						echo '<div class="menu-content grid_margins"><div class="head"><span class="blurb">Una agenda de eventos sexy y sostenibles</span></div><div class="grid"> ';
						if ( function_exists('dynamic_sidebar') && dynamic_sidebar('Main Menu Events') ) : endif;
				  		echo '</div> <!-- .grid_7 --> </div><!--.container_16-->';
						echo '</div></div></div>';
					?>
				</ul><!--#menu-sites-->
		</div><!-- #site-menu -->
	</div><!-- #menu-background -->
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
			<div id="tempwidget"><?php include('tempwidget.php'); ?></div> 
			<div id="top-bar-right">
				<div id="top-menu" >
					<?php wp_nav_menu( array('container_class' => 'menu-header', 'menu' => 'root' )); ?>
					<div class="clear">&nbsp</div>
				</div> <!-- #top-menu -->
				<div id="top-social">
					<?php include (TEMPLATEPATH . '/gigya_sharing_template_top.php'); ?>
					<div class="clear">&nbsp</div>
				</div> <!-- #top-social -->
				<div id="top-search"><?php include (TEMPLATEPATH . '/searchform.php'); ?><div class="clear">&nbsp</div></div>
			</div><!-- #top-bar-right -->
		</div> <!-- #home-top-bar -->

