<?php
/**
 * Template Name: Lifestyle2
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
<div id="lifestyle">
	<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
	<div class="breadcrumbs">
	<?php if(function_exists('bcn_display')) { bcn_display(); } ?>
	</div>
	<div id="page-wrap">
		<div id="page-head" class="">
			<div class="grid_margins">
				<span class='title'><?php the_title(); ?></span>
				<?php if($subtitle=get_post_meta($post->ID,'subtitle',true)) { ?> 
				<span class="tagline"><?php echo $subtitle; ?></span>
				<?php } ?>
			</div>
	<?php endwhile; ?>
			<div class="clear">&nbsp</div>
		</div><!-- #page-head -->
		<div id="page-body">
			<div class="fluidwidth" style="min-width:960px;">
				<div class="grid_margins">
		        	<?php echo make_gridster( array('queryArgs'=>array('tax_query' => array('relation' => 'OR', array( 'taxonomy' => 'category', 'field' => 'slug', 'terms' => array( 'blog' ) ) ) ), 'numberPosts'=>18, 'gridElementBorder'=>1, 'maxColumns'=>3, 'totalWidth'=>940, 'margins'=>4, 'padding'=>6, 'gridElementHeight'=>420, 'textSpace'=>array(240,200), 'imagePosition'=>"top", 'hasBottomInfo'=>TRUE, 'getStickies'=>TRUE, 'smartImagePlacement'=>FALSE, 'useEmphasis'=>TRUE, 'emphasisSchedule'=>array(2,1,0), 'flexibleHeight'=>FALSE, 'flipFloatOnNewRow'=>FALSE, 'morelink'=>'http://barcelona.thecityatlas.org/category/blog/') ); ?>
					<div class='clear'>&nbsp</div>
				</div><!-- .grid_16 -->
			</div>
		</div><!-- #page-body -->
	</div><!-- #page-wrap -->
</div><!-- #lifestyle -->
<?php get_footer(); ?>
