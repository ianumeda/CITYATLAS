<?php
/**
 * The template used for displaying page content in page-grid2.php
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */
?>
<?php 
extract($_REQUEST);
if(empty($offset)) $offset=0;
$numberPosts=get_post_meta($post->ID,'numberPosts',TRUE);
if(!is_numeric($numberPosts)) $numberPosts=18;
$nextPage=$offset+$numberPosts;
$previousPage=$offset-$numberPosts;
if($previousPage<0) $previousPage=0;
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
		<div id="pagegrid"></div>
   	<?php
 		$category=get_post_meta($post->ID,'page_feed_category',true);
		if(empty($category)) $category=($post->post_name);
		echo '<script>';
		echo '$(document).ready(function() { alert("makegrid"); makegrid("pagegrid", "'. $category .'", 0); });';
		echo '</script>';
		// $jsonpostdata=
		// global $post;
		// $q=new WP_Query();
		// $q->query('category_name='.$category);
		// if ( $q->have_posts() ) {
		// 	while ($q->have_posts()) : $q->the_post();
		// 		$jsonpostdata.=getjsonpostdata($post).',';
		// 	endwhile;
		// 	$jsonpostdata=substr($jsonpostdata,0,-1);
		// 	$jsonpostdata.='];';
		// }
		// wp_reset_query();
		// 
		// $theposts=array();
		// $thesections[]=array('sectiontitle'=>$post->post_title, 'slug'=>$post->post_name, 'query'=>null, 'blurb'=>null);
		// 
		// echo '<script>';
		// $sectiondata=getjsonsectiondata( $thesections ); 
		// echo '</script>';
		//echo make_gridster( array('queryArgs'=>array('tax_query' => array('relation' => 'OR', array( 'taxonomy' => 'category', 'field' => 'slug', 'terms' => array( $category ) ) ) ), 'numberPosts'=>$numberPosts, 'postOffset'=>$offset, 'gridElementBorder'=>1, 'maxColumns'=>3, 'totalWidth'=>960, 'margins'=>10, 'padding'=>15, 'gridElementHeight'=>420, 'textSpace'=>array(240,200), 'imagePosition'=>"top", 'hasBottomInfo'=>TRUE, 'getStickies'=>TRUE, 'smartImagePlacement'=>FALSE, 'useEmphasis'=>TRUE, 'flexibleHeight'=>FALSE, 'flipFloatOnNewRow'=>FALSE,  'shareButton'=>FALSE) ); ?>
		<div class="page-nav">
			<?php if($offset>0) { ?>
			<div class="previous"><a href="./?offset=<?php echo $previousPage; ?>">Previous Posts</a></div>
			<?php } if($numberPosts>0) { ?>
			<div class="next"><a href="./?offset=<?php echo $nextPage; ?>">Next Posts</a></div>
			<?php } ?>
		</div>
	</div><!-- .entry-content -->
	<footer class="entry-meta">
		<?php edit_post_link( __( 'Edit', 'twentyeleven' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-meta -->

</article><!-- #post-<?php the_ID(); ?> -->
