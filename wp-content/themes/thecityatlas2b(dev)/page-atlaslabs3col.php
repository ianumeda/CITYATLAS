<?php
/**
 * Template Name: Atlas Lab 3 col
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
	<div id="lab">
	<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
	<div class="breadcrumbs">
	<?php if(function_exists('bcn_display')) { bcn_display(); } ?>
	</div>
		<div id="page-wrap" class="full-width">
		<div id="page-head" >
			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<span class='title'><?php the_title(); ?></span><span class="tagline"> <?php if($tagline=get_post_meta($post->ID,'subtitle', true)) { echo $tagline; } ?> </span>
			</div><!-- #post-## -->
			</div><!-- #page-head -->
		<div id="page-body">
		<div id="lahg3col-container">
			<div id="lahg-center" class="column">
		<div id="labs-grid" >
			<?php echo make_gridster(array('queryArgs'=>array('post_parent'=>42, 'post_type'=>'page'), 'gridElementBorder'=>1,'maxColumns'=>1,'totalWidth'=>240,'margins'=>6,'padding'=>8,'gridElementHeight'=>240, 'flexibleHeight'=>TRUE, 'textSpace'=>array(200,80), 'hasInfoTab'=>FALSE, 'imagePosition'=>'top', 'smartImagePlacement'=>FALSE,'useEmphasis'=>FALSE,'textOptions'=>array('title','excerpt'))); ?>
         </div> <!-- #labs-grid -->
		</div><!-- #lahg-center -->
		<div id="lahg-left" class="column sidebar-border-left">
			<div class="page-sidebar sidebar-blurb the-content">
				<h3 class="feed-head">About Atlas Lab:</h3>
				<?php wp_reset_postdata(); the_content(); ?>
			</div><!-- .page-sidebar -->		
		</div> <!-- #lahg-left -->
		<div id="lahg-right" class="column sidebar-border-right">
			<div class="page-sidebar">
			<div class="sidebar-element events">
			<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar('Lab Sidebar') ) : ?>
			<?php endif; ?>
			<a href="<?php echo home_url("/events/month/"); ?>"><img style="position:absolute; bottom:0; right:0; margin:0 6px 12px 4px;" src="<?php bloginfo('stylesheet_directory'); ?>/images/calendaricon.jpg" alt="calendar"></a>
			<div class='clear'>&nbsp</div>
			</div><!--.sidebar-element-->
			<div class="blog-feed">
			<h3 class="feed-head">The Atlas Lab Blog Feed:</h3>
			<?php echo make_gridster(array('queryArgs'=>array('category_name'=>'atlas-lab','post_type'=>'post'), 'gridElementBorder'=>1,'maxColumns'=>1,'totalWidth'=>240,'margins'=>10,'padding'=>4,'gridElementHeight'=>90,'hasInfoTab'=>false, 'smartImagePlacement'=>FALSE,'useEmphasis'=>FALSE,'imagePosition'=>'left', 'textSpace'=>150, 'hasTopInfo'=>TRUE, 'topInfoHeight'=>16, 'topInfoBorder'=>array(0,0,0,0), 'topTextOptions'=>array('category'=>array('style'=>'h6')),'textOptions'=>array('title','excerpt'), 'flexibleHeight'=>TRUE, 'morelink'=>'http://newyork.thecityatlas.org/category/atlas-lab/page/2/', 'numberPosts'=>10)); ?>
			</div><!-- .blog-feed -->
			</div><!-- .page-sidebar -->
		</div><!-- #lahg-right -->
		</div><!-- #lahg-container -->
		</div><!-- #page-body -->
	<?php endwhile; ?>
		</div><!-- .page-wrap -->
	</div><!-- #lab -->
<?php get_footer(); ?>
