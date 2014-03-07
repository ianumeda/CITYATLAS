<?php
/*
 * Template Name: Atlas Lab1
*/
/** *
 * @package WordPress
 * @subpackage Starkers
 * @since Starkers 3.0
 */

get_header(); ?>
	<?php 
	if ( have_posts() ) while ( have_posts() ) : the_post(); 
	$custom_fields = get_post_custom($post->ID);
	$labcategory = $custom_fields['blog-feed-category'];
	$background_image = $custom_fields['lead-image'];
	$lead_html = $custom_fields['lead-html'];
	if(isset($background_image) || isset($lead_html)) :
		if(isset($lead_html)) { 
		?>
			<div id="lead-wrap">		
				<div id="fullscreen-lead">
				<?php echo $lead_html[0]; ?>
					<div id="lead-text">
						<h1 class="post-title"><?php the_title(); ?></h1>
						<?php if( $sCustomSubtitle=get_post_meta($post->ID,"subtitle",true) ) echo '<h2 class="post-subtitle">'.$sCustomSubtitle.'</h2>'; ?>
					</div><!-- #lead-text -->
				</div><!-- #fullscreen-lead -->
			</div><!-- #lead-wrap -->
		<?php
		} else {
			$theBG=$background_image[0]; // only take the first background image;
			if((int)$theBG==$theBG) $theBGurl=get_ngg_image_url($theBG); 	// is ngg image ID. get URL from NGG functions
			else $theBGurl=$theBG; ?>
				<div id="lead-wrap">		
					<div id="fullscreen-lead" style="background: url(<?php echo $theBGurl; ?>) no-repeat center center scroll; ">
						<div id="lead-text">
							<h1 class="post-title"><?php the_title(); ?></h1>
							<?php if( $sCustomSubtitle=get_post_meta($post->ID,"subtitle",true) ) echo '<h2 class="post-subtitle">'.$sCustomSubtitle.'</h2>'; ?>
						</div><!-- #lead-text -->
					</div><!-- #fullscreen-lead -->
				</div><!-- #lead-wrap -->
		<?php } ?>
	<?php endif; ?>
	<div id="main" class="hasleadwrap">
		<div id="lahg2col-container">
			<div id="lahg-center" class="column">
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
							<div class="clear">&nbsp</div>
						</div><!-- #post-body -->					
						<div id="post-foot">
							<p><?php wp_link_pages( array( 'before' => '' . __( 'Pages:', 'twentyten' ), 'after' => '' ) ); ?></p>
							<?php if ( get_the_author_meta( 'description' ) ) : // If a user has filled out their description, show a bio on their entries  ?>
								<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>">
									<?php printf( __( 'View all posts by %s &rarr;', 'twentyten' ), get_the_author() ); ?>
								</a>
								<?php endif; ?>
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
					<?php if(isset($labcategory[0])) : ?>
					<h3 class="feed-head"><?php the_title(); ?> Blog</h3>
					<?php echo make_gridster(array('queryValue'=>array($labcategory[0]), 'gridElementBorder'=>1, 'maxColumns'=>1, 'gridElementHeight'=>100, 'flexibleHeight'=>TRUE,'totalWidth'=>240, 'margins'=>4, 'padding'=>6, 'hasInfoTab'=>FALSE, 'imagePosition'=>'left', 'smartImagePlacement'=>FALSE, 'textSpace'=>array(160,120), 'textOptions'=>array('date','title','excerpt'), 'useEmphasis'=>FALSE, 'morelink'=>'http://newyork.thecityatlas.org/category/micro-textile-lab-blog/page/2/', 'numberPosts'=>10 )); ?>
					<?php endif; ?>
					</div><!-- #post-sidebar -->
				</div><!-- #lahg-right -->
			</div><!-- #lahg-container -->
		</div><!-- #main -->
<?php get_footer(); ?>