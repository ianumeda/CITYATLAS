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
<link rel="icon" type="image/png" href="http://thecityatlas.org/thecityatlaslogo-favicon-32.gif">
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
	function getpreview(slug){
		var thepreview='<div class="header"><span class="blurb"><span class="title">'+ window.postdata[slug][0].title +'</span>: '+ window.postdata[slug][0].subtitle +'</span><span class="pagepreview"><a href="'+ window.postdata[slug][0].permalink +'" style="color:inherit; text-decoration:none;">more <span style="color:#8fc54b;">'+ window.postdata[slug][0].title +'</span> &rarr;</a></span></div><div class="grid">';
		for(i=1; i<window.postdata[slug].length; i++){
			thepreview+='<a href="'+ window.postdata[slug][i].permalink +'"><div class="postpreview"><div class="image" style="background: url('+ window.postdata[slug][i].image.url +') no-repeat center center scroll; -webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover; background-size: cover;"></div><div class="title previewtext">'+ window.postdata[slug][i].title +'</div><div class="excerpt previewtext">'+ window.postdata[slug][i].excerpt +'</div>';
			if(window.postdata[slug][i].date!="") { thepreview+='<div class="thedateevent previewtext">'+ window.postdata[slug][i].date +'</div>'; }
      thepreview+='<div class="subtitle previewtext">'+ window.postdata[slug][i].subtitle +'</div></div></a>';
      // thepreview+='<div class="author previewtext">by '+ window.postdata[slug][i].author +'</div></div></a>';
		}  
		thepreview+='</div>';
		document.getElementById('previewarea').innerHTML=thepreview;
		$("div#previewarea").animate({ bottom: '0px' });
		$("div#previewarea").fadeIn();
	}
	function killpreview(){
		$("div#previewarea").fadeOut();
		// $("div#previewarea").animate({ bottom: '-300px' });
	}
</script>
	<script src="http://cdn.leafletjs.com/leaflet-0.4/leaflet.js"></script>
	<link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.4/leaflet.css" />	

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?> onload="scrollToOneIfNoScrollHasHappenedYet();">

	<div id="previewarea"></div>
	<script>
	$('#previewarea').mouseleave(function() {
		killpreview();
	});	
	</script>
	<div id="fixedfooter" class="menu_background">
		<div id="bottom-logo">
			<a href="<?php echo home_url('/'); ?>">City Atlas</a></div><div id="bottom-logo-cityname"><a href="<?php echo home_url('/'); ?>"><?php bloginfo('name'); ?></a>
		</div><!-- #bottom-logo -->
		<div id="section-menu" class="" style="float:right;">
			<span class="section-button" onmouseover="getpreview('explore');"><a href="http://newyork.thecityatlas.org/explore">Explore</a></span>
			<span class="section-button" onmouseover="getpreview('lifestyle');"><a href="http://newyork.thecityatlas.org/lifestyle">Lifestyle</a></span>
			<span class="section-button" onmouseover="getpreview('people');"><a href="http://newyork.thecityatlas.org/people">People</a></span>
			<span class="section-button" onmouseover="getpreview('lab');"><a href="http://newyork.thecityatlas.org/lab">Lab</a></span>
		</div><!--#section-menu -->
		<div id="fixed-second-row" class="inner-shadow">
			<?php if(empty($_ENV['ismappage']) && !is_mobile() && function_exists('insert_newsticker') ) { insert_newsticker(); } elseif($_ENV['ismappage']) { ?>
				<div id="map-controls-wrapper"></div>
			<?php } ?>
		</div><!-- #fixed-second-row -->
	</div>
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