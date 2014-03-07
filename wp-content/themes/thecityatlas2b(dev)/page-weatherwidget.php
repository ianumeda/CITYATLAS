<?php
/**
 * Template Name: Weather Widget
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


	<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

	<div class="breadcrumbs">
	<?php if(function_exists('bcn_display')) { bcn_display(); } ?>
	</div>

		<div id="page-wrap">

			<div id="page-head">
				<div class="container_16">
					<span class='title'><?php the_title(); ?></span><span class="tagline"> <?php if($tagline=get_post_meta($post->ID,'subtitle', true)) { echo $tagline; } ?> </span>
				</div>

				</div><!-- #page-head -->
				
				<div id="page-body">
				
				<?php // include('tempwidget.php'); ?>
				
				<?php the_content(); ?>

				</div><!--#page-body-->
				
	</div><!-- .page-wrap -->
	<?php endwhile; ?>

<?php get_footer(); ?>
