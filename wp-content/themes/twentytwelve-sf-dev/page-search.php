<?php
/**
 * Template Name: Search Page Template
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

	<?php 
		if(function_exists('bcn_display')) { 
			echo '<div class="breadcrumbs">';
			bcn_display();
			echo '</div>';
		} 
	?>
	<?php endwhile; ?>
	
		<div id="primary" class="site-content">
			<div id="content" role="main">

				<article id="post-0" class="post error404 no-results not-found">
					<header class="entry-header">
						<h1 class="entry-title"><?php _e( 'Search The City Atlas', 'twentytwelve' ); ?></h1>
					</header>

					<div class="entry-content">
						<p><?php _e( 'Enter your search terms in the form below. ', 'twentytwelve' ); ?></p>
						<?php get_search_form(); ?>
					</div><!-- .entry-content -->
				</article><!-- #post-0 -->

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?>