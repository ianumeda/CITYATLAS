<?php
/**
 * Template Name: Map GreenMap
 *
 * A custom page template without sidebar.
 *
 * The "Template Name:" bit above allows this to be selectable
 * from a dropdown menu on the edit page screen.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
get_header(); ?>

	
	<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

	<div class="breadcrumbs">
	<?php if(function_exists('bcn_display')) { bcn_display(); } ?>
	</div>
		<div id="lead-wrap">		
			<div id="fullscreen-lead">
			 <div id="content">
			<iframe WIDTH="100%" HEIGHT="680" SRC="http://www.opengreenmap.org/greenmap/barcelona-open-green-map" SCROLLING="auto" FRAMEBORDER="0" border="0"></iframe>
			</div>
		  	<div id="map_canvas" style="width:100%; height:100%; margin-top:16px"></div>
				<div id="lead-text">
					<a href="#article"><h1 class="post-title"><?php the_title(); ?></h1>
					<?php if( $sCustomSubtitle=get_post_meta($post->ID,"subtitle",true) ) echo '<h2 class="post-subtitle">'.$sCustomSubtitle.'</h2>'; ?><h1 class="post-title">⇣</h1></a>
				</div><!-- #lead-text -->
			</div><!-- #fullscreen-lead -->
		</div><!-- #lead-wrap -->

		<div id="page-wrap" class="full-width">
		<div id="page-head" >
			<span class='title'><?php the_title(); ?></span>
			<?php if($subtitle=get_post_meta($post->ID,'subtitle',true)) { ?> 
				<span class="tagline"><?php echo $subtitle; ?></span>
			<?php } ?>
		</div><!-- #page-head -->
		<?php endwhile; ?>
		<div id="page-body" >
			<?php echo make_gridster(array('queryValue'=>array('explore'), 'gridElementBorder'=>1,'maxColumns'=>3,'numberPosts'=>10, 'totalWidth'=>960, 'margins'=>array(8), 'padding'=>6, 'gridElementHeight'=>360, 'textSpace'=>180, 'hasInfoTab'=>TRUE, 'smartImagePlacement'=>FALSE,'useEmphasis'=>TRUE,'imagePosition'=>'top', 'flexibleHeight'=>FALSE, 'morelink'=>'http://newyork.thecityatlas.org/category/explore/', 'emphasisSchedule'=>array('3x2','0') ) ); ?>
		</div><!-- #page-body -->
		<script language="javascript">
		document.onload=OnLoad();
		</script>
		</html>
	<?php get_footer(); ?>
