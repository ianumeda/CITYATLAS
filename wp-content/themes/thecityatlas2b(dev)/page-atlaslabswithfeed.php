<?php
/**
 * Template Name: Atlas Lab with blog feed
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

	<div id="lab">
	
	<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
	

	<div class="breadcrumbs">
	<?php if(function_exists('bcn_display')) { bcn_display(); } ?>
	</div>
	
		<div id="page-wrap" class="full-width">

		<div id="page-head" >

			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<span class='title'><?php the_title(); ?></span><span class="tagline"><?php the_content(); ?></span>

			</div><!-- #post-## -->

			</div><!-- #page-head -->
			
		<div id="lahg2col-container">

			<div id="lahg-center" class="column">

		<div id="labs-grid" >
            
			<?php echo make_gridster(array('queryArgs'=>array('post_parent'=>42, 'post_type'=>'page'), 'gridElementBorder'=>1,'maxColumns'=>1,'totalWidth'=>640,'margins'=>8,'padding'=>6,'gridElementHeight'=>250, 'flexibleHeight'=>TRUE, 'textSpace'=>array(200,80), 'hasInfoTab'=>FALSE, 'imagePosition'=>'left', 'smartImagePlacement'=>FALSE,'useEmphasis'=>FALSE,'textOptions'=>array('title','excerpt'))); ?>

         </div> <!-- #labs-grid -->

			<?php endwhile; ?>

		</div><!-- #lahg-center -->

		<div id="lahg-right" class="column sidebar-border-right">
		
			<div class="page-sidebar">
		
			<h3 class="feed-head">The Atlas Lab Blog Feed:</h3>

			<?php echo make_gridster(array('queryArgs'=>array('category_name'=>'atlas-lab','post_type'=>'post'), 'gridElementBorder'=>1,'maxColumns'=>1,'totalWidth'=>240,'margins'=>array(8,0),'padding'=>4,'gridElementHeight'=>90,'hasInfoTab'=>false, 'smartImagePlacement'=>FALSE,'useEmphasis'=>FALSE,'imagePosition'=>'left', 'textSpace'=>160, 'hasTopInfo'=>TRUE, 'topInfoHeight'=>16, 'topInfoBorder'=>array(0,0,0,0), 'topTextOptions'=>array('category'=>array('style'=>'h6')),'textOptions'=>array('title','excerpt'), 'flexibleHeight'=>TRUE, 'morelink'=>'http://newyork.thecityatlas.org/category/atlas-lab/page/2/', 'numberPosts'=>10)); ?>
			
			</div><!-- .page-sidebar -->

		</div><!-- #lahg-right -->

		</div><!-- #lahg-container -->

		</div><!-- .page-wrap -->

	</div><!-- #lab -->
	
<?php get_footer(); ?>
