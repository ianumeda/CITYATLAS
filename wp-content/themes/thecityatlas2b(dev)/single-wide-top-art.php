<?php
/*
Template Name Posts: wide-top-art
*/
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Starkers
 * @since Starkers 3.0
 */

get_header(); ?>

<div id="main">
	
<div id="lahg2col-container">

	<div id="lahg-center" class="column">

			<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>	
			
				<?php 
					if(function_exists('bcn_display')) { 
					echo '<div class="breadcrumbs">';
					bcn_display();
					echo '</div>';
				 	}
				 ?>

				<div id="post-wrap" >

					<div id="post-head" >

						<h1 class="title"><?php the_title(); ?></h1>


					<div class="top-info">
						<span class="post-author"><?php the_author(); ?></span>
						<span class="post-date"><?php the_date(); ?></span>
						<div class="clear">&nbsp</div>
					</div><!-- .top-info -->
					
					<?php if(function_exists('get_cityatlas_social')) get_cityatlas_social(); ?>

					</div><!-- #post-head -->

					<div id="post-top-art">
					<?PHP
					if(!post_password_required($post->ID)) {
						$nContentWidth="100%";

						$htmlVideo=get_post_videos($post->ID,$nContentWidth);
						$htmlGallery=get_post_galleries($post->ID,$nContentWidth,500);
						$htmlImageSequence=get_post_image_sequence($post->ID,$nContentWidth);
						$htmlPDF=get_post_PDFs($post->ID,$nContentWidth,500);
						if(!isset($htmlImageSequence)) $htmlImage=get_post_images($post->ID,$nContentWidth);

						// this keeps single images from showing when there's any video or gallery ... 
						if($htmlVideo||$htmlGallery||$htmlImageSequence||$htmlPDF) echo $htmlVideo.$htmlGallery.$htmlImageSequence.$htmlPDF;							
						else echo $htmlImage;
					}
					?>
					</div><!-- #post-top-art -->

					<div id="post-body">
					
						<?php the_content(); ?>
										
					</div><!-- #post-body -->					
					
					<div id="post-foot">
						
						<p><?php wp_link_pages( array( 'before' => '' . __( 'Pages:', 'twentyten' ), 'after' => '' ) ); ?></p>
						<?php if ( get_the_author_meta( 'description' ) ) : // If a user has filled out their description, show a bio on their entries  ?>
							<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"> <?php printf( __( 'View all posts by %s &rarr;', 'twentyten' ), get_the_author() ); ?> </a>
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
								<div id="comments" >
								<?php comments_template( '', true ); ?> 
								</div>
							<?php } ?>						

					<?php endwhile; // end of the loop. ?>

					</div><!-- #post-foot -->

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