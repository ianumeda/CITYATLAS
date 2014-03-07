<?php
/**
 * Template Name: Index
 *
 * A custom page template without sidebar.
 *
 * The "Template Name:" bit above allows this to be selectable
 * from a dropdown menu on the edit page screen.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>

<div id="index">

	<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

	<div class="breadcrumbs">
	<?php if(function_exists('bcn_display')) { bcn_display(); } ?>
	</div>

		<div id="page-wrap" >

			<div id="page-head" class="">
			
				<div class="container_16">
					<span class='title'><?php the_title(); ?></span>
					<?php if($subtitle=get_post_meta($post->ID,'subtitle',true)) { ?> 
					<span class="tagline"><?php echo $subtitle; ?></span>
					<?php } ?>
					
				</div>

				<?php endwhile; ?>
				
				<div class="clear">&nbsp;</div>

				</div><!-- #page-head -->

				<div id="page-body">
				<div class="container_16 fixed-width">
				
					<div class="index-column">

					<span class="section-head">Explore</span>
					<span class="section-subhead">MAPS AND GRAPHICS</span>

					<?php $g=make_gridster(array('queryValue'=>array('explore'), 'gridElementBorder'=>1, 'maxColumns'=>1, 'maxRows'=>12, 'totalWidth'=>192, 'gridElementHeight'=>240, 'margins'=>4, 'padding'=>6, 'hasInfoTab'=>FALSE, 'imagePosition'=>'top', 'smartImagePlacement'=>FALSE, 'textSpace'=>array(70), 'useEmphasis'=>FALSE, 'flexibleHeight'=>true, 'numberPosts'=>12, 'morelink'=>'http://newyork.thecityatlas.org/category/explore/page/2/' ));
					 	echo $g; ?>

					</div>

						<div class="index-column">

						<span class="section-head">Lifestyle</span>
						<span class="section-subhead">ACTIVITIES</span>

						<?php $g=make_gridster(array('queryValue'=>array('lifestyle'), 'gridElementBorder'=>1, 'maxColumns'=>1, 'maxRows'=>12, 'totalWidth'=>192, 'gridElementHeight'=>240, 'margins'=>4, 'padding'=>6, 'hasInfoTab'=>FALSE, 'imagePosition'=>'top', 'smartImagePlacement'=>FALSE, 'textSpace'=>array(70), 'useEmphasis'=>FALSE, 'flexibleHeight'=>true, 'numberPosts'=>12, 'morelink'=>'http://newyork.thecityatlas.org/category/lifestyle/page/2/' )); 
							echo $g; ?>

						</div>
					<div class="index-column">
					
					<span class="section-head">People</span>
					<span class="section-subhead">INTERVIEWS ABOUT NYC</span>
				
					<?php $g=make_gridster(array('queryValue'=>array('people'), 'gridElementBorder'=>1, 'maxColumns'=>1, 'maxRows'=>12, 'totalWidth'=>192, 'gridElementHeight'=>240, 'margins'=>4, 'padding'=>6, 'hasInfoTab'=>FALSE, 'imagePosition'=>'top', 'smartImagePlacement'=>FALSE, 'textSpace'=>array(70), 'useEmphasis'=>FALSE, 'flexibleHeight'=>true, 'numberPosts'=>12, 'morelink'=>'http://newyork.thecityatlas.org/category/people/page/2/'  )); 
					 	echo $g; ?>
					</div>


					<div class="index-column">
					
					<span class="section-head">Archive</span>
					<span class="section-subhead">EXISTING PROJECTS</span>

					<?php $g=make_gridster(array('queryValue'=>array('archive'), 'gridElementBorder'=>1, 'maxColumns'=>1, 'maxRows'=>12, 'totalWidth'=>192, 'gridElementHeight'=>240, 'margins'=>4, 'padding'=>6, 'hasInfoTab'=>FALSE, 'imagePosition'=>'top', 'smartImagePlacement'=>FALSE, 'textSpace'=>array(70), 'useEmphasis'=>FALSE, 'flexibleHeight'=>true, 'numberPosts'=>12, 'morelink'=>'http://newyork.thecityatlas.org/category/archive/page/2/' ));
					 	echo $g; ?>
					</div>
					
					<div class="index-column">

					<span class="section-head">Atlas Lab</span>
					<span class="section-subhead">NEW EXPERIMENTS</span>

					<?php $g=make_gridster(array('queryArgs'=>array('category_name'=>'atlas-lab','post_type'=>'post'), 'gridElementBorder'=>1, 'maxColumns'=>1, 'maxRows'=>12, 'totalWidth'=>192, 'gridElementHeight'=>240, 'margins'=>4, 'padding'=>6, 'hasInfoTab'=>FALSE, 'imagePosition'=>'top', 'smartImagePlacement'=>FALSE, 'textSpace'=>array(70), 'useEmphasis'=>FALSE, 'flexibleHeight'=>true, 'numberPosts'=>12, 'morelink'=>'http://newyork.thecityatlas.org/category/atlas-lab/page/2/' ));
					 	echo $g; ?>
					</div>
                    </div><!--.container_16 -->
		         </div> <!-- #-grid -->

	</div><!-- #page-wrap -->

</div><!-- #index -->
<?php get_footer(); ?>
