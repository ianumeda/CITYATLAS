<?php
/**
 * Template Name: Home Background Gallery(2)
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the wordpress construct of pages
 * and that other 'pages' on your wordpress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage Starkers
 * @since Starkers 3.0
 */

get_header(); ?>

<div id="main" class="">


					<div id="header-logo-front" class="" ><a href="<?php echo home_url( '/about/' ); ?>"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/thecityatlaslogowhitetext260x340.png" width="260" height="340" alt="<?php bloginfo( 'name' ); ?>"></a>
					</div><!-- #header-logo-front -->	
					<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
				
<!--					<div id="home-tagline">
					<h1 ><?php  // the_title(); ?></h1>
				
					<p><?php the_content(); ?></p>
-->
					<?php endwhile; ?>
					</div><!-- #tagline -->



</div><!-- #main -->
<?php get_footer(); ?>