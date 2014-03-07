<?php
/**
 * Template Name: Grid2
 * Description: 
 *
 * The showcase template in Twenty Eleven consists of a featured posts section using sticky posts,
 * another recent posts area (with the latest post shown in full and the rest as a list)
 * and a left sidebar holding aside posts.
 *
 * We are creating two queries to fetch the proper posts and a custom widget for the sidebar.
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */

// Enqueue showcase script for the slider
get_header(); ?>
	<?php while ( have_posts() ) : the_post(); ?>

	<?php 
		if(function_exists('bcn_display')) { 
			echo '<div class="breadcrumbs">';
			bcn_display();
			echo '</div>';
		} 
	?>
	<header id="masthead" class="site-header" role="banner">
		<?php 
			$header_image = get_post_meta($post->ID,'lead-image',true);
			if ( ! empty( $header_image ) ) {
				if(is_numeric($header_image)) $header_image=get_ngg_image_url($header_image); 	// is ngg image ID. get URL from NGG functions
		?>
			<a href="#"><img src="<?php echo esc_url( $header_image ); ?>" class="header-image" width="100%" height="" alt="" /></a>
		<?php } ?>
	</header><!-- #masthead -->
		<div id="primary" class="showcase">
			<div id="content" role="main">
				
			<?php 
			extract($_REQUEST);
			if(empty($offset)) $offset=0;
			$numberPosts=get_post_meta($post->ID,'numberPosts',TRUE);
			if($post_preview_format=get_post_meta($post->ID,'post_preview_format',TRUE)) { $post_preview_format=array('imageheight'=>200, 'elements'=>explode(',', $post_preview_format)); }
			else $post_preview_format=array('imageheight'=>200, 'elements'=>array("image","title","subtitle","excerpt","author"));
			if(!is_numeric($numberPosts)) $numberPosts=18;
			$nextPage=$offset+$numberPosts;
			$previousPage=$offset-$numberPosts;
			if($previousPage<0) $previousPage=0;
	 		$category=get_post_meta($post->ID,'page_feed_category',true);
			if(empty($category)) $category=($post->post_name);
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
					<div id="pagegrid"><?php echo makepreviewgrid(array('category_name'=>$category , 'posts_per_page'=>$numberPosts, 'offset' => $offset), $post_preview_format); ?></div>
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
			
		<?php endwhile; ?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?>