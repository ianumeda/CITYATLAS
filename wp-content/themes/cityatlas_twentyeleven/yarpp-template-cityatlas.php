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
 	while ($related_query->have_posts()) : $related_query->the_post();

	$htmlRelatedPosts.=make_gridster_element($post,array('columns'=>1,'rows'=>1),get_gridster_parameters(array('gridElementBorder'=>1,'maxColumns'=>1,'maxRows'=>1,'totalWidth'=>240,'margins'=>array(5),'padding'=>5,'gridElementHeight'=>60, 'flexibleHeight'=>TRUE, 'textSpace'=>150,'imagePosition'=>'left','hasInfoTab'=>false,'textOptions'=>array('title'=>array('style'=>'h2','link'=>'yes please')) )));

	endwhile;
	
	$htmlRelatedPosts.='</div><!-- #yarpp --><div class="clear">&nbsp</div>';
	echo $htmlRelatedPosts;
else:
endif;
?>
