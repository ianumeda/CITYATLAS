<?php
/**
 * Template Name: Events
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
<div id="lifestyle">
	<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
	<div class="breadcrumbs">
	<?php if(function_exists('bcn_display')) { bcn_display(); } ?>
	</div>
	<div id="page-wrap">
		<div id="page-head" class="">
			<div class="container_16">
				<span class='title'><?php the_title(); ?></span>
				<?php if($subtitle=get_post_meta($post->ID,'subtitle',true)) { ?> 
				<span class="tagline"><?php echo $subtitle; ?></span>
				<?php } ?>
			</div>
	<?php endwhile; ?>
			<div class="clear">&nbsp</div>
		</div><!-- #page-head -->
		<div id="page-body">
			<div class="container_16 " >
        		<div id="events1" class="grid_5 grid_margins">
					<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar('Events1') ) : ?>
					<?php endif; ?>
					<a href="<?php echo home_url("/events/month/"); ?>"><img style="position:absolute; bottom:0; right:0; margin:0 6px 12px 4px;" src="<?php bloginfo('stylesheet_directory'); ?>/images/calendaricon.jpg" alt="calendar"></a>
				</div>
				<div id="events2" class="grid_5 grid_margins" >
					<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar('Events2') ) : ?>
					<?php endif; ?>
					<a href="<?php echo home_url("/events/month/"); ?>"><img style="position:absolute; bottom:0; right:0; margin:0 6px 12px 4px;" src="<?php bloginfo('stylesheet_directory'); ?>/images/calendaricon.jpg" alt="calendar"></a>
        		</div> 
				<div id="events3" class="grid_5 grid_margins" >
					<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar('Events3') ) : ?>
					<?php endif; ?>
					<a href="<?php echo home_url("/events/month/"); ?>"><img style="position:absolute; bottom:0; right:0; margin:0 6px 12px 4px;" src="<?php bloginfo('stylesheet_directory'); ?>/images/calendaricon.jpg" alt="calendar"></a>
        		</div> 
				<div class="clear">&nbsp</div>		
			</div>
		</div><!-- #page-body -->
	</div><!-- #page-wrap -->
</div><!-- #lifestyle -->
<?php get_footer(); ?>
