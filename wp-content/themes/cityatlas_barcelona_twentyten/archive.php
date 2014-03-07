<?php
/**
 * The template for displaying Archive pages.
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Starkers
 * @since Starkers 3.0
 */

get_header(); ?>

<?php
	/* Queue the first post, that way we know
	 * what date we're dealing with (if that is the case).
	 *
	 * We reset this later so we can run the loop
	 * properly with a call to rewind_posts().
	 */
	if ( have_posts() )
		the_post();
?>

	<div id="main">

		<div id="lahg2col-container">

			<div id="lahg-center" class="column">

					<?php 
						if(function_exists('bcn_display')) { 
						echo '<div class="breadcrumbs">';
						bcn_display();
						echo '</div>';
					 	}
					 ?>


			<div id="page-wrap" class="float-content" >

				<div id="page-head">

			<h1>
<?php if ( is_day() ) : ?>
				<?php printf( __( 'Daily Archives: %s', 'twentyten' ), get_the_date() ); ?>
<?php elseif ( is_month() ) : ?>
				<?php printf( __( 'Monthly Archives: %s', 'twentyten' ), get_the_date('F Y') ); ?>
<?php elseif ( is_year() ) : ?>
				<?php printf( __( 'Yearly Archives: %s', 'twentyten' ), get_the_date('Y') ); ?>
<?php else : ?>
				<?php _e( 'Archives:', 'twentyten' ); ?>
<?php endif; ?>
			</h1>

		</div><!--#page-head-->
		<div id="page-body">

<?php
	/* Since we called the_post() above, we need to
	 * rewind the loop back to the beginning that way
	 * we can run the loop properly, in full.
	 */
	rewind_posts();

	/* Run the loop for the archives page to output the posts.
	 * If you want to overload this in a child theme then include a file
	 * called loop-archives.php and that will be used instead.
	 */
	 get_template_part( 'loop', 'archive' );
?>

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