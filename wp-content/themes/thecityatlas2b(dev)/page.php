<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the wordpress construct of pages
 * and that other 'pages' on your wordpress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage Starkers
 * @since Starkers 3.0
 */

get_header(); ?>
<div id="main" >
<div id="lahg2col-container">
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
	<div id="lahg-center" class="column">
		<div class="breadcrumbs"> <?php if(function_exists('bcn_display')) { bcn_display(); } ?> </div>
		<div id="page-wrap" class="float-content">
			<div id="page-head">
				<h1><?php the_title(); ?></h1>
			<?php include('gigya_sharing_template.php'); ?>
			</div><!-- #page-head -->
			<div id="page-body" class="the-content">
				<?php the_content(); ?>
			<?php edit_post_link( __( 'Edit', 'twentyten' ), '<span class="edit-link">', '</span>' ); ?>
			</div><!--#page-body-->
			<div id="page-foot">
				<div id="bottom-post-social" >
					<?php if(function_exists('selfserv_shareaholic')) { selfserv_shareaholic(); } ?>
					<?php if (comments_open()) { ?>
						<div id="comments"> <?php comments_template( '', true ); ?> </div>
					<?php } ?>
				</div><!-- #bottom-post-social -->						
				</div><!--#page-foot-->
			</div><!-- #page-wrap -->
			<?php endwhile; ?>
		</div><!-- #lahg-center -->
		<div id="lahg-right" class="column sidebar-border-right">
			<div id="post-sidebar" >
			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Page Widgets') ) : ?>
			<?php endif; ?>
			<div class="clear">&nbsp</div>
			<?php get_sidebar(); ?>
			</div><!-- #post-sidebar -->
		</div><!-- #lahg-right -->
	</div><!-- #lahg-container -->
</div><!-- #main -->
<?php get_footer(); ?>