<?php
/**
 * The template for displaying Author Archive pages.
 *
 * @package WordPress
 * @subpackage Starkers
 * @since Starkers 3.0
 */

get_header(); ?>


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


			<div id="page-wrap" class="float-content">

				<div id="page-head">

<?php
	/* Queue the first post, that way we know who
	 * the author is when we try to get their name,
	 * URL, description, avatar, etc.
	 *
	 * We reset this later so we can run the loop
	 * properly with a call to rewind_posts().
	 */
	if ( have_posts() )
		the_post();
?>

				<h1><?php printf( __( 'Author Archives: %s', 'twentyten' ), "<a class='url fn n' href='" . get_author_posts_url( get_the_author_meta( 'ID' ) ) . "' title='" . esc_attr( get_the_author() ) . "' rel='me'>" . get_the_author() . "</a>" ); ?></h1>

<?php
// If a user has filled out their description, show a bio on their entries.
if ( get_the_author_meta( 'description' ) ) : ?>

							<?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'twentyten_author_bio_avatar_size', 60 ) ); ?>
							<h2><?php printf( __( 'About %s', 'twentyten' ), get_the_author() ); ?></h2>
							<?php the_author_meta( 'description' ); ?>

<?php endif; ?>

</div><!--#page-head-->
<div id="page-body">

<?php
	/* Since we called the_post() above, we need to
	 * rewind the loop back to the beginning that way
	 * we can run the loop properly, in full.
	 */
	rewind_posts();

	/* Run the loop for the author archive page to output the authors posts
	 * If you want to overload this in a child theme then include a file
	 * called loop-author.php and that will be used instead.
	 */
	 get_template_part( 'loop', 'author' );
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