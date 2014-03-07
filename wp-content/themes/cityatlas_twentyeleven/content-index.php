<?php
/**
 * The template used for displaying page content in page-index.php
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<h1 class="entry-title"><?php the_title(); ?></h1>
		<?php if($subtitle=get_post_meta($post->ID,'subtitle',true)) { ?> 
		<span class="tagline"><?php echo $subtitle; ?></span>
		<?php } ?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>

	<div class="index-grid">
		<div class="index-column">

		<span class="section-head">Explore</span>
		<span class="section-subhead">MAPS AND GRAPHICS</span>

		<?php echo make_gridster(array('queryValue'=>array('explore'), 'gridElementBorder'=>1, 'maxColumns'=>1, 'maxRows'=>12, 'totalWidth'=>192, 'gridElementHeight'=>240, 'margins'=>4, 'padding'=>6, 'hasInfoTab'=>FALSE, 'imagePosition'=>'top', 'smartImagePlacement'=>FALSE, 'textSpace'=>array(70), 'useEmphasis'=>FALSE, 'flexibleHeight'=>true, 'numberPosts'=>12, 'morelink'=>'http://newyork.thecityatlas.org/category/explore/page/2/', 'totalHeight'=>3000 )); ?>

		</div>

			<div class="index-column">

			<span class="section-head">Lifestyle</span>
			<span class="section-subhead">ACTIVITIES</span>

			<?php echo make_gridster(array('queryValue'=>array('lifestyle'), 'gridElementBorder'=>1, 'maxColumns'=>1, 'maxRows'=>12, 'totalWidth'=>192, 'gridElementHeight'=>240, 'margins'=>4, 'padding'=>6, 'hasInfoTab'=>FALSE, 'imagePosition'=>'top', 'smartImagePlacement'=>FALSE, 'textSpace'=>array(70), 'useEmphasis'=>FALSE, 'flexibleHeight'=>true, 'numberPosts'=>12, 'morelink'=>'http://newyork.thecityatlas.org/category/lifestyle/page/2/', 'totalHeight'=>3000 )); ?>

			</div>
		<div class="index-column">

		<span class="section-head">People</span>
		<span class="section-subhead">INTERVIEWS ABOUT NYC</span>

		<?php echo make_gridster(array('queryValue'=>array('people'), 'gridElementBorder'=>1, 'maxColumns'=>1, 'maxRows'=>12, 'totalWidth'=>192, 'gridElementHeight'=>240, 'margins'=>4, 'padding'=>6, 'hasInfoTab'=>FALSE, 'imagePosition'=>'top', 'smartImagePlacement'=>FALSE, 'textSpace'=>array(70), 'useEmphasis'=>FALSE, 'flexibleHeight'=>true, 'numberPosts'=>12, 'morelink'=>'http://newyork.thecityatlas.org/category/people/page/2/', 'totalHeight'=>3000 )); ?>
		</div>


		<div class="index-column">

		<span class="section-head">Archive</span>
		<span class="section-subhead">EXISTING PROJECTS</span>

		<?php echo make_gridster(array('queryValue'=>array('archive'), 'gridElementBorder'=>1, 'maxColumns'=>1, 'maxRows'=>12, 'totalWidth'=>192, 'gridElementHeight'=>240, 'margins'=>4, 'padding'=>6, 'hasInfoTab'=>FALSE, 'imagePosition'=>'top', 'smartImagePlacement'=>FALSE, 'textSpace'=>array(70), 'useEmphasis'=>FALSE, 'flexibleHeight'=>true, 'numberPosts'=>12, 'morelink'=>'http://newyork.thecityatlas.org/category/archive/page/2/', 'totalHeight'=>3000 )); ?>
		</div>

		<div class="index-column">

		<span class="section-head">Atlas Lab</span>
		<span class="section-subhead">NEW EXPERIMENTS</span>

		<?php echo make_gridster(array('queryArgs'=>array('category_name'=>'lab','post_type'=>'post'), 'gridElementBorder'=>1, 'maxColumns'=>1, 'maxRows'=>12, 'totalWidth'=>192, 'gridElementHeight'=>240, 'margins'=>4, 'padding'=>6, 'hasInfoTab'=>FALSE, 'imagePosition'=>'top', 'smartImagePlacement'=>FALSE, 'textSpace'=>array(70), 'useEmphasis'=>FALSE, 'flexibleHeight'=>true, 'numberPosts'=>12, 'morelink'=>'http://newyork.thecityatlas.org/category/atlas-lab/page/2/', 'totalHeight'=>3000 )); ?>
		</div>
	</div><!-- .index-grid -->

		<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'twentyeleven' ) . '</span>', 'after' => '</div>' ) ); ?>
	</div><!-- .entry-content -->
	

	<footer class="entry-meta">
		<?php edit_post_link( __( 'Edit', 'twentyeleven' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-meta -->

</article><!-- #post-<?php the_ID(); ?> -->
