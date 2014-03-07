<?php
/**
 * Template Name: Index2 Template
 * Description: A Page Template that showcases Sticky Posts, Asides, and Blog Posts
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
					?>
						<div class="indexgrid">
						<?php echo makepreviewgrid( array('category_name'=>$cat[0], 'post_type'=>'post', 'posts_per_page'=>10) ); ?>
						</div>
					<?php } else { ?>
					<div class="indexgrid">
					<?php echo makepreviewgrid( array('category_name'=>$cat[0], 'posts_per_page'=>10) ); ?>
					</div>
					<?php } ?>
					</div>		
				<?php } ?>

				</div><!-- .index-grid -->

					<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'twentyeleven' ) . '</span>', 'after' => '</div>' ) ); ?>
				</div><!-- .entry-content -->


				<footer class="entry-meta">
					<?php edit_post_link( __( 'Edit', 'twentyeleven' ), '<span class="edit-link">', '</span>' ); ?>
				</footer><!-- .entry-meta -->

			</article><!-- #post-<?php the_ID(); ?> -->

		<?php endwhile; ?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?>