<?php /*
Example template for use with WPML (WP Multilingual, http://wpml.org)
Author: mitcho (Michael Yoshitaka Erlewine)
*/ 

if (function_exists("icl_register_string")) {
  icl_register_string("Yet Another Related Posts Plugin","related posts header","Related Posts");
  icl_register_string("Yet Another Related Posts Plugin","no related posts message","No related posts.");
}

?>
<?php 
if ($related_query->have_posts()):
	$postsListed=",";
	$htmlRelatedPosts='<div id="yarpp" class="widget-container" ><h3 class="widget-title">Related:</h3>';
	// $htmlRelatedPosts.='<div class="yarpp_category">';
	$elements=array("image","title","author");
	$imageheight="120px";
 	while ($related_query->have_posts()) : $related_query->the_post();

	$htmlRelatedPosts.='<a href="'. get_permalink($post->ID) .'"><div class="postpreview post-'. $post->ID .'">';
	foreach($elements as $element){
		if($element=="date") {
			if(tribe_is_event($post->ID)) {
				$thedate=(tribe_get_event_date_format(tribe_get_start_date($post->ID, true, 'M j, Y'), tribe_get_end_date($post->ID, true, 'M j, Y')));
				$htmlRelatedPosts.='<div class="thedateevent previewtext">'. $thedate .'</div>';
			}
			else {
				$thedate=mysql2date('M j, Y', $post->post_date);
				$htmlRelatedPosts.='<div class="thedate previewtext">'. $thedate .'</div>';
			}
		}
		elseif($element=="image"){
			if($arrimage=get_image_from_post_bamn($post, null, null)) {}
			else $arrimage=array('http://newyork.thecityatlas.org/wp-content/themes/twentytwelve-dev/images/thecityatlaslogo-grey-160x160.jpg',160,160); 
			$htmlRelatedPosts.='<div class="image" style="display:block; width:100%; height:'. $imageheight .'; background: url('. $arrimage[0] .') no-repeat center center scroll; -webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover; background-size: cover;"></div>';
		}
		elseif($element=="title") $htmlRelatedPosts.='<div class="title previewtext">'. $post->post_title .'</div>';
		elseif($element=="subtitle") { 
			if($subtitle=get_post_meta($post->ID, 'subtitle', true)) {
				$htmlRelatedPosts.='<div class="subtitle previewtext">'. $subtitle .'</div>'; 
			}
		}
		elseif($element=="excerpt") $htmlRelatedPosts.='<div class="excerpt previewtext">'. get_the_excerpt($post->ID) .'</div>';
		elseif($element=="author") $htmlRelatedPosts.='<div class="author previewtext">by '. get_the_author($post->ID) .'</div>';
	}
	$htmlRelatedPosts.='</div></a>';

	endwhile;
	
	$htmlRelatedPosts.='</div><!-- #yarpp -->';
	echo $htmlRelatedPosts;
else:
endif;
?>
