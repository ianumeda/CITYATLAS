<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package WordPress
 * @subpackage Starkers
 * @since Starkers 3.0
 */

get_header(); ?>


	<div id="main">

		<div id="lahg2col-container">

			<div id="lahg-center" class="column">

			<div class="breadcrumbs">
			<?php if(function_exists('bcn_display')) { bcn_display(); } ?>
			</div>

				<div id="page-wrap" class="float-content" >

					<div id="page-head">


			<?php if ( have_posts() ) : ?>
				<h1><?php printf( __( 'Search Results for: %s', 'twentyten' ), '' . get_search_query() . '' ); ?></h1>
				
				</div><!-- #page-head -->
				
				<div id="page-body">
				
				<?php
				/* Run the loop for the search to output the results.
				 * If you want to overload this in a child theme then include a file
				 * called loop-search.php and that will be used instead.
				 */
				 get_template_part( 'loop', 'search' );
				?>
<?php else : ?>
					<h2><?php _e( 'Nothing Found', 'twentyten' ); ?></h2>
					<p><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'twentyten' ); ?></p>
					<?php get_search_form(); ?>
<?php endif; ?>


					</div><!--#page-body-->
					<div id="page-foot">


					</div><!--#page-foot-->

				</div> <!-- #page-wrap -->

			</div><!-- #lahg-center -->

			<div id="lahg-right" class="column sidebar-border-right">

				<div id="post-sidebar" >
				<div class="widget-meta">
					<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Meta Widget') ) : ?>
					<?php endif; ?>
					<div class="clear">&nbsp</div>
				</div>
				<?PHP if(function_exists('related_entries')) related_entries(); ?>
				<?php get_sidebar(); ?>
				</div><!-- #post-sidebar -->

			</div><!-- #lahg-right -->

		</div><!-- #lahg-container -->

	</div><!-- #main -->

<?php get_footer(); ?>
