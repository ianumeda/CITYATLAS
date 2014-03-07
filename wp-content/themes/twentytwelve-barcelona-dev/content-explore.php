<?php
/**
 * The template used for displaying page content in page-explore.php
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

   	<?php echo make_gridster( array('queryArgs'=>array('tax_query' => array('relation' => 'OR', array( 'taxonomy' => 'category', 'field' => 'slug', 'terms' => array( 'explore' ) ) ) ), 'numberPosts'=>18, 'gridElementBorder'=>1, 'maxColumns'=>3, 'totalWidth'=>960, 'margins'=>4, 'padding'=>6, 'gridElementHeight'=>420, 'textSpace'=>array(240,200), 'imagePosition'=>"top", 'hasBottomInfo'=>TRUE, 'getStickies'=>TRUE, 'smartImagePlacement'=>FALSE, 'useEmphasis'=>TRUE, 'flexibleHeight'=>FALSE, 'flipFloatOnNewRow'=>FALSE, 'morelink'=>$siteurl.'/category/lifestyle/', 'shareButton'=>FALSE) ); ?>

		<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'twentyeleven' ) . '</span>', 'after' => '</div>' ) ); ?>
	</div><!-- .entry-content -->
	<footer class="entry-meta">
		<?php edit_post_link( __( 'Edit', 'twentyeleven' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-meta -->

</article><!-- #post-<?php the_ID(); ?> -->
