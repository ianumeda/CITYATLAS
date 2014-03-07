<?php
/**
 * The template for displaying attachments.
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

							<p><a href="<?php echo get_permalink( $post->post_parent ); ?>" title="<?php esc_attr( printf( __( 'Return to %s', 'twentyten' ), get_the_title( $post->post_parent ) ) ); ?>" rel="gallery"><?php
								/* translators: %s - title of parent post */
								printf( __( '<span>&larr;</span> %s', 'twentyten' ), get_the_title( $post->post_parent ) );
							?></a></p>

								<h2><?php the_title(); ?></h2>

							<div class="top-info">
								<span class="post-author"><?php the_author(); ?></span>
								<span class="post-date"><?php the_date(); ?></span>
								<div class="clear">&nbsp</div>
							</div><!-- .top-info -->

					<?php if(function_exists('get_cityatlas_social')) get_cityatlas_social(); ?>

					<div class="clear">&nbsp</div>


				</div><!-- #post-head -->

				<div id="post-body">


					<?php if ( wp_attachment_is_image() ) :
						$attachments = array_values( get_children( array( 'post_parent' => $post->post_parent, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID' ) ) );
						foreach ( $attachments as $k => $attachment ) {
							if ( $attachment->ID == $post->ID )
								break;
						}
						$k++;
						// If there is more than 1 image attachment in a gallery
						if ( count( $attachments ) > 1 ) {
							if ( isset( $attachments[ $k ] ) )
								// get the URL of the next image attachment
								$next_attachment_url = get_attachment_link( $attachments[ $k ]->ID );
							else
								// or get the URL of the first image attachment
								$next_attachment_url = get_attachment_link( $attachments[ 0 ]->ID );
						} else {
							// or, if there's only 1 image attachment, get the URL of the image
							$next_attachment_url = wp_get_attachment_url();
						}
					?>
						<p><a href="<?php echo $next_attachment_url; ?>" title="<?php echo esc_attr( get_the_title() ); ?>" rel="attachment"><?php
							$attachment_size = apply_filters( 'twentyten_attachment_size', 900 );
							echo wp_get_attachment_image( $post->ID, array( $attachment_size, 9999 ) ); // filterable image width with, essentially, no limit for image height.
						?></a></p>

							<?php previous_image_link( false ); ?>
							<?php next_image_link( false ); ?>
<?php else : ?>
						<a href="<?php echo wp_get_attachment_url(); ?>" title="<?php echo esc_attr( get_the_title() ); ?>" rel="attachment"><?php echo basename( get_permalink() ); ?></a>
<?php endif; ?>
<?php
	if ( wp_attachment_is_image() ) {
		echo ' | ';
		$metadata = wp_get_attachment_metadata();
		printf( __( 'Full size is %s pixels', 'twentyten'),
			sprintf( '<a href="%1$s" title="%2$s">%3$s &times; %4$s</a>',
				wp_get_attachment_url(),
				esc_attr( __('Link to full-size image', 'twentyten') ),
				$metadata['width'],
				$metadata['height']
			)
		);
	}
?>
						<?php if ( !empty( $post->post_excerpt ) ) the_excerpt(); ?>

<?php the_content( __( 'Continue reading &rarr;', 'twentyten' ) ); ?>
<?php wp_link_pages( array( 'before' => '' . __( 'Pages:', 'twentyten' ), 'after' => '' ) ); ?>

							<div class="clear">&nbsp</div>

						</div><!-- #post-body -->					

						<div id="post-foot">

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