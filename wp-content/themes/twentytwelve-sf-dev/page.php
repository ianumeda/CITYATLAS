<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header(); ?>

	<?php while ( have_posts() ) : the_post(); ?>

	<?php 
		if(function_exists('bcn_display')) { 
			echo '<div class="breadcrumbs">';
			bcn_display();
			echo '</div>';
		} 
	?>
	<header id="masthead" class="site-header" role="banner">
		<?php 
			$header_image = get_post_meta($post->ID,'lead-image',true);
			if ( ! empty( $header_image ) ) {
				if(is_numeric($header_image)) $header_image=get_ngg_image_url($header_image); 	// is ngg image ID. get URL from NGG functions
		?>
			<a href="#"><img src="<?php echo esc_url( $header_image ); ?>" class="header-image" width="100%" height="" alt="" /></a>
		<?php } ?>
	</header><!-- #masthead -->

	<div id="primary" class="site-content">
		<div id="content" role="main">

				<?php get_template_part( 'content', 'page' ); ?>
				<?php comments_template( '', true ); ?>
			<?php endwhile; // end of the loop. ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>