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

	$htmlRelatedPosts.=make_gridster_element($post,array('columns'=>1,'rows'=>1),get_gridster_parameters(array('gridElementBorder'=>1,'maxColumns'=>1,'maxRows'=>1,'totalWidth'=>160,'margins'=>array(5),'padding'=>5,'gridElementHeight'=>240, 'flexibleHeight'=>TRUE, 'gridElementMinWidth'=>100, 'linkWholeElement'=>TRUE, 'flexibleWidth'=>FALSE,  'textSpace'=>100,'imagePosition'=>'top','hasInfoTab'=>false,'textOptions'=>array('title'=>array('style'=>'span','link'=>'yes please')), 'defaultImage'=>array('http://newyork.thecityatlas.org/wp-content/themes/twentytwelve-dev/images/thecityatlaslogo-grey-160x160.jpg',160,160) )));

	endwhile;
	
	$htmlRelatedPosts.='</div><!-- #yarpp --><div class="clear">&nbsp</div>';
	echo $htmlRelatedPosts;
else:
endif;
?>
