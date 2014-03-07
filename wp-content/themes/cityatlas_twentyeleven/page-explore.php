<?php
/**
 * Template Name: Explore Template
 * Description: A Page Template that showcases Sticky Posts, Asides, and Blog Posts
 *
 * The showcase template in Twenty Eleven consists of a featured posts section using sticky posts,
 * another recent posts area (with the latest post shown in full and the rest as a list)
 * and a left sidebar holding aside posts.
 *
 * We are creating two queries to fetch the proper posts and a custom widget for the sidebar.
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */

// Enqueue showcase script for the slider
get_header(); ?>

	<?php while ( have_posts() ) : the_post(); ?>
		<div class="breadcrumbs">
		<?php if(function_exists('bcn_display')) { bcn_display(); } ?>
		</div>

		<div id="primary" class="showcase">
			<div id="content" role="main">
				
			<?php
				/**
				 * We are using a heading by rendering the_content
				 * If we have content for this page, let's display it.
				 */
				get_template_part( 'content', 'explore' );
			?>

		<?php endwhile; ?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?>