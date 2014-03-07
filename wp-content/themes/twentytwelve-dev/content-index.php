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
	<?php if($aCategories=get_post_meta($post->ID,'index_category_|_subtitle',false)) {
	} else $aCategories=array('explore | Maps and Graphics','lifestyle | Activities','people | Interviews About NYC','archive | Existing Projects','Lab | New Experiments | children');
	foreach($aCategories as $item){ 
		$cat=explode(' | ',$item); 
		?>
		<div id="category-<?php echo $cat[0]; ?>" class="index-column">
		<span class="section-head"><a href="<?php echo home_url( '/'.$cat[0].'/' ) ?>"><?php echo $cat[0]; ?></a></span>
		<span class="section-subhead"><?php echo $cat[1]; ?></span>
		<?php 
			if ($cat[2]=='children'){
				$morelink=home_url('/category/'.$cat[0].'/page/2/');
				echo make_gridster(array('queryArgs'=>array('category_name'=>$cat[0],'post_type'=>'post'), 'gridElementBorder'=>1, 'maxColumns'=>1, 'maxRows'=>12, 'totalWidth'=>192, 'gridElementHeight'=>240, 'margins'=>4, 'padding'=>6, 'hasInfoTab'=>FALSE, 'imagePosition'=>'top', 'smartImagePlacement'=>FALSE, 'textSpace'=>array(70), 'useEmphasis'=>FALSE, 'flexibleHeight'=>true, 'numberPosts'=>12, 'morelink'=>$morelink, 'totalHeight'=>3000 )); 
			} else {
				echo make_gridster(array('queryValue'=>array($cat[0]), 'gridElementBorder'=>1, 'maxColumns'=>1, 'maxRows'=>12, 'totalWidth'=>192, 'gridElementHeight'=>240, 'margins'=>4, 'padding'=>6, 'hasInfoTab'=>FALSE, 'imagePosition'=>'top', 'smartImagePlacement'=>FALSE, 'textSpace'=>array(70), 'useEmphasis'=>FALSE, 'flexibleHeight'=>true, 'numberPosts'=>12, 'morelink'=>$morelink, 'totalHeight'=>3000 )); 
			}
		?>
		</div>		
	<?php } ?>

	</div><!-- .index-grid -->

		<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'twentyeleven' ) . '</span>', 'after' => '</div>' ) ); ?>
	</div><!-- .entry-content -->
	

	<footer class="entry-meta">
		<?php edit_post_link( __( 'Edit', 'twentyeleven' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-meta -->

</article><!-- #post-<?php the_ID(); ?> -->
