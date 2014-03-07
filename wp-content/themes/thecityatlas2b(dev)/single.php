<?php
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
	<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
		<div id="lahg-center" class="column">
		<?php 
			if(function_exists('bcn_display')) { 
			echo '<div class="breadcrumbs">';
			bcn_display();
			echo '</div>';
		 	}
		 ?>
				<div id="post-wrap" class="two-column" >
					<div id="post-head" >
						<h1 class="title"><?php the_title(); ?></h1>
						<div class="top-info">
							<span class="post-author"><?php the_author(); ?></span>
							<span class="post-date"><?php the_date(); ?></span>
							<div class="clear">&nbsp</div>
						</div><!-- .top-info -->
						<?php include('gigya_sharing_template.php'); ?>
						<div class="clear">&nbsp</div>
					</div><!-- #post-head -->
					<div id="post-body" class="the-content">
						<?PHP
						if(!post_password_required($post->ID)) 
						{
							$nContentWidth=450;
							$htmlMap=get_google_map_embed($post->ID, $nContentWidth); // relies on "address" or "latlon" custom fields
							$htmlVideo=get_post_videos($post->ID,$nContentWidth);
							$htmlGallery=get_post_galleries($post->ID,$nContentWidth,640);
							$htmlImageSequence=get_post_image_sequence($post->ID,$nContentWidth);
							$htmlPDF=get_post_PDFs($post->ID,$nContentWidth,640);
							if(!isset($htmlImageSequence)) $htmlImage=get_post_images($post->ID,$nContentWidth);
							// this keeps single images from showing when there's any video or gallery ... 
							if($htmlMap!=null || $htmlVideo!=null || $htmlGallery!=null || $htmlImageSequence!=null || $htmlPDF!=null || $htmlImage!=null)
							{
								echo '<div id="post-art">';
								echo $htmlVideo.$htmlGallery.$htmlImage.$htmlMap.$htmlImageSequence.$htmlPDF;
								echo '<div class="clear">&nbsp</div></div><!-- #post-art -->';
							}
						}
						?>
						<?php the_content(); ?>
						<?php do_action('make_urtak_widget'); ?>
						<div class="clear">&nbsp</div>
						<?php edit_post_link( __( 'Edit', 'twentyten' ), '<span class="edit-link">', '</span>' ); ?>
						
					</div><!-- #post-body -->					
					<div id="post-foot">
						<p><?php wp_link_pages( array( 'before' => '' . __( 'Pages:', 'twentyten' ), 'after' => '' ) ); ?></p>
						<?php if (1===2 && get_the_author_meta( 'description' ) ) : // If a user has filled out their description, show a bio on their entries  ?>
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
							<div id="bottom-post-social" >
								<?php if(function_exists('selfserv_shareaholic')) { selfserv_shareaholic(); } ?>
								<?php if (comments_open()) { ?>
									<div id="comments"> <?php comments_template( '', true ); ?> </div>
								<?php } ?>
							</div><!-- #bottom-post-social -->						
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