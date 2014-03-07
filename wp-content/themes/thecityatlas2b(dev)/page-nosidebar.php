<?php
/**
 * Template Name: Default No Sidebar
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

<div id="main" class="container_16">
	
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

		<div class="breadcrumbs"> <?php if(function_exists('bcn_display')) { bcn_display(); } ?> </div>

		<div id="page-wrap" class="grid_16">

			<div id="page-head">

				<h1><?php the_title(); ?></h1>

			</div><!-- #page-head -->

			<div id="page-body">

				<?php the_content(); ?>


			</div><!--#page-body-->
			<div id="page-foot">
				
				<?php wp_link_pages( array( 'before' => '' . __( 'Pages:', 'twentyten' ), 'after' => '' ) ); ?>

			</div><!--#page-foot-->

			</div><!-- #page-wrap -->

			<?php endwhile; ?>


</div><!-- #main -->

<?php get_footer(); ?>