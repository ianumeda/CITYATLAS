<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header(); ?>
	<?php while ( have_posts() ) : the_post(); ?>
		<?php 
			$header_image = get_post_meta($post->ID,'lead-image',true);
			if ( ! empty( $header_image ) ) {
				if(is_numeric($header_image)) $header_image=get_ngg_image_url($header_image); 	// is ngg image ID. get URL from NGG functions
				$header_image=esc_url($header_image);
				if( $sCustomSubtitle=get_post_meta($post->ID,"subtitle",true) ) $subtitle='<h2 class="post-subtitle">'.str_replace(array("\r", "\r\n", "\n"), '', $sCustomSubtitle).'</h2>';
				$leadhtml='<div id="fullscreen-lead" onClick="goToByScroll(\'page\')" style="background: url('.$header_image.') no-repeat center center scroll; box-shadow: 0px 0px 10px #666; -webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover; background-size: cover;"><div id="lead-text"><a href="#primary"><h1 class="post-title">'.get_the_title($post->ID).'</h1>'.$subtitle.'<h2 class="post-subsubtitle">(click to go to article)</h2></a></div></div>';
		?>
			<script type='text/javascript'>
				makeLead("<?php echo escapehtmlchars($leadhtml); ?>");
			</script>
		<?php } ?>
	<?php 
		if(function_exists('bcn_display')) { 
			echo '<div class="breadcrumbs">';
			bcn_display();
			echo '</div>';
		} 
	?>
	<div id="primary" class="site-content">
		<div id="content" role="main">

				<?php get_template_part( 'content', get_post_format() ); ?>
				
				<nav class="nav-single">
					<h3 class="assistive-text"><?php _e( 'Post navigation', 'twentytwelve' ); ?></h3>
					<span class="nav-previous"><?php previous_post_link( '%link', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'twentytwelve' ) . '</span> %title' ); ?></span>
					<span class="nav-next"><?php next_post_link( '%link', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'twentytwelve' ) . '</span>' ); ?></span>
				</nav><!-- .nav-single -->

				<?php
					// If comments are open or we have at least one comment, load up the comment template
					if ( comments_open() || '0' != get_comments_number() )
						comments_template( '', true );
				?>

			<?php endwhile; // end of the loop. ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>