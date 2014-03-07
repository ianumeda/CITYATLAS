<?php
/**
 * Template Name: Home Page Template
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
	<div id="logo-container">
	<a href="<?php echo home_url( '/about/' ); ?>">
		<div id="home-logo"></div><!-- #home-logo -->
		<?php if($logositenameimage=get_post_meta($post->ID, 'image',true)){ 
			// for the city name to appear below the city atlas logo place an 'image' custom field in the home page and give it the full url to the image as the value ?>
			<div id="logo-city-name" style="background:url('<?php echo $logositenameimage; ?>') no-repeat center center scroll;">
			</div>
		<?php } ?>
	</a>
	</div><!-- #logo-container -->

		<div id="primary" class="showcase">
			<div id="content" role="main">

				<?php
					/**
					 * We are using a heading by rendering the_content
					 * If we have content for this page, let's display it.
					 */
					if ( '' != get_the_content() )
						get_template_part( 'content', 'intro' );
				?>

				<?php endwhile; ?>


			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?>