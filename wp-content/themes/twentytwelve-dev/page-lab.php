<?php
/**
 * Template Name: Lab Template
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
			$header_image = get_post_meta($post->ID,'lead-image',true);
			$header_html=get_post_meta($post->ID,'lead-html',true);
			if ( ! empty( $header_image ) ) {
				if(is_numeric($header_image)) $header_image=get_ngg_image_url($header_image); 	// is ngg image ID. get URL from NGG functions
				$header_image=esc_url($header_image);
				if( $sCustomSubtitle=get_post_meta($post->ID,"subtitle",true) ) $subtitle='<h2 class="post-subtitle">'.str_replace(array("\r", "\r\n", "\n"), '', $sCustomSubtitle).'</h2>';
				$leadhtml='<div id="fullscreen-lead" onClick="goToByScroll(\'page\')" style="background: url('.$header_image.') no-repeat center center scroll; box-shadow: 0px 0px 10px #666; -webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover; background-size: cover;"><div id="lead-text"><a href="#primary"><h1 class="post-title">'.get_the_title($post->ID).'</h1>'.$subtitle.'<h2 class="post-subsubtitle">(click to go to article)</h2></a></div></div>';
			}
			elseif(!empty($header_html)) {
				// $header_html=esc_url($header_html);
				if( $sCustomSubtitle=get_post_meta($post->ID,"subtitle",true) ) $subtitle='<h2 class="post-subtitle">'.str_replace(array("\r", "\r\n", "\n"), '', $sCustomSubtitle).'</h2>';
				$leadhtml='<div id="fullscreen-lead" onClick="goToByScroll(\'page\')">'.$header_html.'<div id="lead-text"><a href="#primary"><h1 class="post-title">'.get_the_title($post->ID).'</h1>'.$subtitle.'<h2 class="post-subsubtitle">(click to go to article)</h2></a></div></div>';
			}
			if(!empty($leadhtml)) {
		?>
			<script type='text/javascript'>
				makeLead("<?php echo escapehtmlchars($leadhtml); ?>");
			</script>
		<?php } ?>

	<?php 
		if(function_exists('bcn_display')) { 
			echo '<div class="breadcrumbs">';
			bcn_display();
			echo '</div>';
		} 
	?>

		<div id="primary" class="site-content">
			<div id="content" role="main">
				
			<?php
				/**
				 * We are using a heading by rendering the_content
				 * If we have content for this page, let's display it.
				 */
				get_template_part( 'content', 'page' );
			?>
				<?php $category=get_post_meta($post->ID,'page_feed_category',true);
				if(empty($category)) $category=null; ?>

		<?php endwhile; ?>

			</div><!-- #content -->
		</div><!-- #primary -->

		<div id="secondary" class="widget-area" role="complementary">
			<div class="widget-container">
			<?php 
			if($category) { 
				$post_preview_format=array('imageheight'=>200, 'elements'=>array("image","title","subtitle","excerpt","author"));
				echo '<h3 class="widget-title">Blog:</h3>';
				echo makepreviewgrid(array('category_name'=>$category , 'posts_per_page'=>18, 'offset' => 0), $post_preview_format);
			}
			elseif(function_exists('related_entries')) related_entries();
				 ?>
			</div>
		</div>
<?php get_footer(); ?>