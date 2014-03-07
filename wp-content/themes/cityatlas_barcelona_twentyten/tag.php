<?php
/**
 * The template for displaying Tag Archive pages.
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

				<h1><?php
					printf( __( 'Tag Archives: %s', 'twentyten' ), '' . single_tag_title( '', false ) . '' );
				?></h1>

					</div><!-- #page-head -->

					<div id="page-body">

						<?php
						/* Run the loop for the tag archive to output the posts
						 * If you want to overload this in a child theme then include a file
						 * called loop-tag.php and that will be used instead.
						 */
						 get_template_part( 'loop', 'tag' );
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
