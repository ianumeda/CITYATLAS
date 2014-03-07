<?php
/*
Template Name Posts: Trang
*/
/**
 * for development
 *
 * @package WordPress
 * @subpackage Starkers
 * @since Starkers 3.0
 */

get_header(); ?>
<?php 
if ( have_posts() ) while ( have_posts() ) : the_post(); 
$custom_fields = get_post_custom($post->ID);
$background_image = $custom_fields['lead-image'];
if($background_image)
{
	$theBG=$background_image[0]; // only take the first background image;
	if(is_numeric($theBG)) $theBGurl=get_ngg_image_url($theBG); 	// is ngg image ID. get URL from NGG functions
	else $theBGurl=$theBG;
?>
<div id="lead-wrap">		
	<div id="fullscreen-lead" style="background: url(<?php echo $theBGurl; ?>) no-repeat center center scroll; ">
		<div id="lead-text">
			<a href="#article"><h1 class="post-title"><?php the_title(); ?></h1>
			<?php if( $sCustomSubtitle=get_post_meta($post->ID,"subtitle",true) ) echo '<h2 class="post-subtitle">'.$sCustomSubtitle.'</h2>'; ?><h1 class="post-title">⇣</h1></a>
		</div><!-- #lead-text -->
	</div><!-- #fullscreen-lead -->
</div><!-- #lead-wrap -->
<?php } ?>
<div id="main" class="hasleadwrap">
<div id="lahg2col-container">
	<div id="lahg-center" class="column">
			<a name="article">&nbsp;</a>
			<div class="breadcrumbs">
			<?php if(function_exists('bcn_display')) { bcn_display(); } ?>
			</div>
			<div id="post-wrap" class="longtext" >
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
				<div id="post-body" class="long-text ">
	
					<?PHP
					function youtube_id($url) {
						$pattern = '#^(?:https?://)?(?:www\.)?(?:youtu\.be/|youtube\.com(?:/embed/|/v/|/watch\?v=|/watch\?.+&v=))([\w-]{11})(?:.+)?$#x';
						preg_match($pattern, $url, $matches);
						return (isset($matches[1])) ? $matches[1] : false;
					}
					
					function get_image_page_element($imageID, $float, $margin){
						$aImageText=get_ngg_image_caption($imageID);
						$title=$aImageText[0];
						$caption=$aImageText[1];
						return ('<div class="imagebox"><span class="imagebox-image" style="float:'.$float.'; margin-'.$margin.':20px;">'.make_image($imageID,320,300,null,array(false)).'</span><span class="imagebox-text"><span class="title">'.$title.'</span><span class="caption">'.$caption.'</span></span><div class="clear">&nbsp</div></div>'); 
					}
						
					function get_pullquote_page_element($txt, $float, $margin){
						return ('<div class="pullquote" style="display:inline; float:'.$float.'; margin-'.$margin.':20px; margin-'.$float.':-00px;">'.$txt.'</div>');
					}
		
					if(!post_password_required($post->ID)) 
					{
						$the_content=nl2br(get_the_content($post->ID));
						$custom_fields=get_post_custom($post->ID);
						$image_sequence=array();
						
						$video_ids = $custom_fields['multivid'];
						foreach ($video_ids as $key => $value) {
							
							$separateInfo = explode(' | ', $value, 3);
							$separateInfo[0] = youtube_id($separateInfo[0]);
							
							if ($key == 0) {
								echo '<iframe name="mainVid" id="mainVid" width="800" height="480" src="http://www.youtube.com/embed/' .$separateInfo[0]. '?rel=0" frameborder="0" allowfullscreen></iframe>';
							}
							
							if (count($video_ids) > 1) {
								if ($key == 0) {
									echo '<div id="vidChoice"><ul>';
								}

								echo '<li><a href="http://www.youtube.com/embed/' .$separateInfo[0]. '?rel=0" target="mainVid" onClick="goToByScroll(\'lahg-center\')">
								<img src="http://img.youtube.com/vi/' .$separateInfo[0]. '/default.jpg">
								<strong>' .$separateInfo[1]. '</strong>'
								.$separateInfo[2].
								'</a></li>';
								
								if (($key+1)%4 == 0) {
									echo '<div style="clear: both;"></div>';
								}
								
								if ($key == (count($video_ids)-1)) {
									echo '<div style="clear: both;"></div></ul></div>';
								}
							}
							

						}
						
						if(count($custom_fields['gallery'])>0) 
						{
							$image_sequence=nggdb::get_ids_from_gallery($custom_fields['gallery'][0], $ngg_options['galSort'], $ngg_options['galSortDir']);
						}
						if($custom_fields['image'])
						{
							foreach($custom_fields['image'] as $key => $value) $image_sequence[]=$value;
						}
						$pull_quotes=$custom_fields['pull_quote'];
						$floatThisWay=array('right','left');
						if(count($image_sequence)+count($pull_quotes)>0)
						{
							$aVisuals=array();
							if(count($image_sequence)>0 && count($pull_quotes)>0) 
							{
								// if there are both images and pullquotes merge the two arrays and intersperse evenly...
								$imgTOtxt=(count($image_sequence)/count($pull_quotes));
								if($imgTOtxt>=1)
								{
									// $imgTOtxt=($imgTOtxt);
									$a=0; $b=0; $c=0; 
									while($a<count($image_sequence))
									{
										if(($a+1)/($b+1)>=$imgTOtxt)
										{
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
									if($pull_quotes) {
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
								}
							} elseif(count($image_sequence)>0){
								foreach($image_sequence as $key=>$imageID){
									$aVisuals[]=get_image_page_element($imageID,$floatThisWay[$key%2],$floatThisWay[($key+1)%2]);
								}
							} else if(count($pull_quotes)>0){
								foreach($pull_quotes as $key=>$txt){
									$aVisuals[]=get_pullquote_page_element($txt,$floatThisWay[$key%2],$floatThisWay[($key+1)%2]);
								}
							}
							$page_length=(int)(strlen($the_content)/(count($aVisuals))); // this should divide the images and pull-quotes evenly about the text
						} else $page_length=2000;
						$i=1;
						$page_start=0;
						$page_min=1500;
						$aPageEnders=array("\n",". "," "); // ". " keeps the page breaks between sentences. " " keeps breaks between words. "\n" breaks between paragraphs
						while($the_content_page=substr($the_content,$page_start,$page_length)) 
						{
							echo '<div class="long-text-page">';
							$newEndOfPage=$page_length;
							$k=0;
							while($k<count($aPageEnders) && $newEndOfPage==$page_length)
							{
								$newEndOfPage=strripos($the_content_page,$aPageEnders[$k]); // find last instance of the pageEnder
								if($newEndOfPage < $page_min) $newEndOfPage=$page_length; // if search finds a page length less than desired then try another search
								else $newEndOfPage+=strlen($aPageEnders[$k]);
								$k++;
							}
							$the_content_page=substr($the_content_page,0,$newEndOfPage);
							// these filter the content so shortcodes work...
							$the_content_page=apply_filters('the_content', $the_content_page);
							$the_content_page = str_replace(']]>', ']]&gt;', $the_content_page);
							$page_start+=$newEndOfPage;
							if($aVisuals[$i-1]!=null) echo $aVisuals[$i-1]; 
							echo '<span class="long-text-body" style="display:inline;">'.$the_content_page.'</span></div>';
							$i++;
						}
					}
					?>
					</div><!--#post-body-->
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
						<div id="bottom-post-social" >
							<?php if(function_exists('selfserv_shareaholic')) { selfserv_shareaholic(); } ?>
							<?php if (comments_open()) { ?>
								<div id="comments" > <?php comments_template( '', true ); ?> </div>
						<?php } ?>
						</div><!-- #bottom-post-social -->
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