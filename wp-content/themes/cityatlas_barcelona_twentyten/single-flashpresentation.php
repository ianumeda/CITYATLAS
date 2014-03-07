<?php
/*
Template Name Posts: flash-presentation
*/
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Starkers
 * @since Starkers 3.0
 */

get_header(); ?>

<div id="main" >
<?php 
if ( have_posts() ) while ( have_posts() ) : the_post(); 

$custom_fields = get_post_custom($post->ID);
$background_image = $custom_fields['lead-image'];

if($custom_fields['lead-flash'])
{
?>
	<div id="lead-wrap">
		<div id="flash-lead" >
			<?php echo do_shortcode($custom_fields['lead-flash'][0]); ?>
		</div><!-- #flash-lead -->
	</div><!-- #lead-wrap -->
<?php
}
elseif($custom_fields['lead-image'])
{
	$theBG=$custom_fields['lead-image'][0]; // only take the first background image;
	if((int)$theBG==$theBG) $theBGurl=get_ngg_image_url($theBG); 	// is ngg image ID. get URL from NGG functions
	else $theBGurl=$theBG;
?>
<div id="lead-wrap">		

	<div id="fullscreen-lead" onclick="window.location.hash='post-wrap';" style="background: url(<?php echo $theBGurl; ?>) no-repeat center center scroll; ">

		<div id="lead-text">

			<h1 class="post-title"><?php the_title(); ?></h1>
			<?php if( $sCustomSubtitle=get_post_meta($post->ID,"subtitle",true) ) echo '<h2 class="post-subtitle">'.$sCustomSubtitle.'</h2>'; ?>

		</div><!-- #lead-text -->

	</div><!-- #fullscreen-lead -->

</div><!-- #lead-wrap -->

<?php } ?>

	<div id="lahg2col-container">

		<div id="lahg-center" class="column">
		
			<div class="breadcrumbs">
			<?php if(function_exists('bcn_display')) { bcn_display(); } ?>
			</div>

			<div id="post-wrap" class="long-text" >

			<div id="post-head" >

				<h1 class="title"><?php the_title(); ?></h1>

				<div class="top-info">
					<span class="post-author"><?php the_author(); ?></span>
					<span class="post-date"><?php the_date(); ?></span>
					<div class="clear">&nbsp</div>
				</div><!-- .top-info -->
				
				<?php if(function_exists('get_cityatlas_social')) get_cityatlas_social(); ?>

				<div class="clear">&nbsp</div>
				
			</div><!-- #post-head -->

				<div id="post-body">
					
					<?PHP
					if(!post_password_required($post->ID)) 
					{
						$nContentWidth=450;

						$htmlVideo=get_post_videos($post->ID,$nContentWidth);
						$htmlGallery=get_post_galleries($post->ID,$nContentWidth,640);
						$htmlImageSequence=get_post_image_sequence($post->ID,$nContentWidth);
						$htmlPDF=get_post_PDFs($post->ID,$nContentWidth,640);

						if(!isset($htmlImageSequence)) $htmlImage=get_post_images($post->ID,$nContentWidth);

						// this keeps single images from showing when there's any video or gallery ... 
						if($htmlVideo!=null||$htmlGallery!=null||$htmlImageSequence!=null||$htmlPDF!=null||$htmlImage!=null)
						{
							echo '<div id="post-art">';
							echo $htmlVideo.$htmlGallery.$htmlImage.$htmlImageSequence.$htmlPDF;
							echo '<div class="clear">&nbsp</div></div><!-- #post-art -->';
						}
					}
					?>
				
					<?php the_content(); ?>
									
					<div class="clear">&nbsp</div>

				</div><!--#post-body-->

						<div id="post-foot">

							<p><?php wp_link_pages( array( 'before' => '' . __( 'Pages:', 'twentyten' ), 'after' => '' ) ); ?></p>
							<?php if ( get_the_author_meta( 'description' ) ) : // If a user has filled out their description, show a bio on their entries  ?>
								<?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'twentyten_author_bio_avatar_size', 60 ) ); ?>
								<h2><?php printf( esc_attr__( 'About %s', 'twentyten' ), get_the_author() ); ?></h2>
								<?php the_author_meta( 'description' ); ?>
								<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>">
									<?php printf( __( 'View all posts by %s &rarr;', 'twentyten' ), get_the_author() ); ?>
								</a>
							<?php endif; ?>

							<div id="post-meta-3" >
								<div class="previous-post">
								<ul>
								<?php previous_post_link( '%link', '' . _x( '<strong>&larr; Previous</strong>', 'Previous post link', 'twentyten' ) . '<li class="adjacent-post-popup">%title</li>&nbsp;', TRUE ); ?>
								</ul>
								</div>
							
								<div class="posted-in">Posted in: <?php echo list_terms($post->ID,'category','Category: '); ?>
								<?php echo list_terms($post->ID,'top_level_topics','Topics: '); ?>
								</div>
								
								<div class="next-post">
								<ul>
								<?php next_post_link( '%link', '' . _x( '<strong>Next &rarr;</strong> ', 'Next post link', 'twentyten' ) . '<li class="adjacent-post-popup">%title</li>&nbsp;', TRUE ); ?>
								</ul>
								</div>
								<div class="clear">&nbsp;</div>
							</div>

								<?php if (comments_open()) { ?>
								<div id="comments" > <?php comments_template( '', true ); ?> </div>
								<?php } ?>

							</div><!--#post-foot-->

					<?php endwhile; // end of the loop. ?>
	
					</div><!-- #post-wrap -->


			</div><!-- #lahg-center -->

			<div id="lahg-right" class="column sidebar-border-right">

				<div id="post-sidebar" >
				<?PHP if(function_exists('related_entries')) related_entries(); ?>
				<?php get_sidebar(); ?>
				</div><!-- #post-sidebar -->

			</div><!-- #lahg-right -->

		</div><!-- #lahg-container -->

</div><!-- #main -->

<?php get_footer(); ?>