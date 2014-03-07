<?php
/*
Template Name Posts: full-screen-top-image-with-trailing-article
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
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>	

<?php 
$custom_fields = get_post_custom($post->ID);
$background_image = $custom_fields['lead-image'];
if($background_image)
{
	$theBG=$background_image[0]; // only take the first background image;
	if((int)$theBG==$theBG) $theBGurl=get_ngg_image_url($theBG); 	// is ngg image ID. get URL from NGG functions
	else $theBGurl=$theBG;
	?>
		
	<div id="fullscreen-lead" onclick="window.location.hash='post-head';" style="background: url(<?php echo $theBGurl; ?>) no-repeat center center scroll; ">
		<div id="lead-text">
			<h1 class="post-title"><?php the_title(); ?></h1>
			<?php if( $sCustomSubtitle=get_post_meta($post->ID,"subtitle",true) ) echo '<h2 class="post-subtitle">'.$sCustomSubtitle.'</h2>'; ?>
		</div><!-- #lead-text -->
	</div><!-- #fullscreen-lead -->
		
	<?php } ?>
	
	<div class="colmask rightmenu" >

	<div class="IIcol-colleft" >

		<div class="IIcol-col1wrap" >
	
			<div class="IIcol-col1" >
		
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
		
					function get_image_page_element($imageID, $float, $margin){
						$aImageText=get_ngg_image_caption($imageID);
						$title=$aImageText[0];
						$caption=$aImageText[1];
						return ('<div class="imagebox"><span class="imagebox-image" style="float:'.$float.'; margin-'.$margin.':20px;">'.make_image($imageID,320,300,null,array(false)).'</span><span class="imagebox-text"><span class="title">'.$title.'</span><span class="caption">'.$caption.'</span></span><div class="clear">&nbsp</div></div>'); 
					}
					function get_pullquote_page_element($txt, $float, $margin){
						return ('<div class="pullquote" style="display:inline; float:'.$float.'; margin-'.$margin.':20px; margin-'.$float.':-60px;">'.$txt.'</div>');
					}
		
					if(!post_password_required($post->ID)) 
					{
						$the_content=nl2br(get_the_content($post->ID)); // this adds <br> tags to the content
						$custom_fields=get_post_custom($post->ID);
						$image_sequence=array();
						if(count($custom_fields['gallery'])>0) $image_sequence=nggdb::get_ids_from_gallery($custom_fields['gallery'][0], $ngg_options['galSort'], $ngg_options['galSortDir']);
						foreach($custom_fields['image'] as $key => $value) $image_sequence[]=$value;
						$pull_quotes=$custom_fields['pull_quote'];
						if(count($image_sequence)+count($pull_quotes)>0)
						{
							$floatThisWay=array('right','left');
							$aVisuals=array();
							if(count($image_sequence)>0 && count($pull_quotes)>0) {
								// if there are both images and pullquotes merge the two arrays and intersperse evenly...
								$imgTOtxt=(count($image_sequence)/count($pull_quotes));
								if($imgTOtxt>=1){
									$imgTOtxt=($imgTOtxt);
									$a=0; $b=0; $c=0; 
									while($a<count($image_sequence)){
										if(($a+1)/($b+1)>=$imgTOtxt){
											$aVisuals[]=get_pullquote_page_element($pull_quotes[$b],$floatThisWay[$c%2],$floatThisWay[($c+1)%2]);
											$b++;
											$c++;
										}
										$aVisuals[]=get_image_page_element($image_sequence[$a],$floatThisWay[$c%2],$floatThisWay[($c+1)%2]);
										$a++; 
										$c++;
									}
								} else {
									$txtTOimg=(1/$imgTOtxt);
									$a=0; $b=0; $c=0; 
									while($a<count($pull_quotes)){
										if(($a+1)/($b+1)>=$txtTOimg){
											$aVisuals[]=get_image_page_element($image_sequence[$b],$floatThisWay[$c%2],$floatThisWay[($c+1)%2]);
											$b++;
											$c++;
										}
										$aVisuals[]=get_pullquote_page_element($pull_quotes[$a],$floatThisWay[$c%2],$floatThisWay[($c+1)%2]);
										$a++; 
										$c++;
									}
								}
							} elseif(count($image_sequence)>0){
								foreach($image_sequence as $key=>$imageID){
									$aVisuals[]=get_image_page_element($imageID,$floatThisWay[$key],$floatThisWay[($key+1)%2]);
								}
							} else if(count($pull_quotes)>0){
								foreach($pull_quotes as $key=>$txt){
									$aVisuals[]=get_pullquote_page_element($txt,$floatThisWay[$key],$floatThisWay[($key+1)%2]);
								}
							}
							$page_length=(int)(strlen($the_content)/(count($aVisuals)-2)); // this should divide the images and pull-quotes evenly about the text
						} else $page_length=2000;
			
						$i=1;
						$page_start=0;
						$page_min=1500;
						$aPageEnders=array("\n",". "," "); // ". " keeps the page breaks between sentences. " " keeps breaks between words. "\n" breaks between paragraphs
						while($the_content_page=substr($the_content,$page_start,$page_length)) {
							echo '<div class="long-text-page">';
							$newEndOfPage=$page_length;
							$k=0;
							while($k<count($aPageEnders) && $newEndOfPage==$page_length){
								$newEndOfPage=strripos($the_content_page,$aPageEnders[$k]); // find last instance of the pageEnder
								if($newEndOfPage < $page_min) $newEndOfPage=$page_length; // if search finds a page length less than desired then try another search
								else $newEndOfPage+=strlen($aPageEnders[$k]);
								$k++;
							}
							$the_content_page=substr($the_content_page,0,$newEndOfPage);
							$page_start+=$newEndOfPage;
							if($aVisuals[$i-1]!=null) echo $aVisuals[$i-1]; 
							echo '<span style="display:inline;">'.$the_content_page.'</span></div>';
							$i++;
						}
					}
			
					?>
					</div><!-- #post-body -->
					<div class="clear">&nbsp</div>

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
								<div id="comments" >
								<?php comments_template( '', true ); ?> 
								</div>

							</div><!--#post-foot-->

						<?php endwhile; // end of the loop. ?>

					</div><!-- #post-wrap -->

			</div> <!-- .IIcol-col1 -->
		
		</div> <!-- .IIcol-col1wrap -->
	
		<div class="IIcol-col2 ">
	
			<div id="post-sidebar" class="">
			<?PHP if(function_exists('related_entries')) related_entries(); ?>
			<?php get_sidebar(); ?>
			</div><!-- #post-sidebar -->
	
		</div><!-- .IIcol-col2 -->
	
	</div><!-- .IIcol-colleft -->

	</div><!-- .IIcol-colmask -->

</div><!-- #main -->

<?php get_footer(); ?>