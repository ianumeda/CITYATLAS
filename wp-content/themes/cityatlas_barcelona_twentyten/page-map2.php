<?php
/**
 * Template Name: Map2
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
	<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
	<div class="breadcrumbs">
	<?php if(function_exists('bcn_display')) { bcn_display(); } ?>
	</div>
		<div id="page-wrap" class="full-width">
		<div id="page-head" >
			<span class='title'><?php the_title(); ?></span>
			<?php if($subtitle=get_post_meta($post->ID,'subtitle',true)) { ?> 
				<span class="tagline"><?php echo $subtitle; ?></span>
			<?php } ?>
		</div><!-- #page-head -->
		<?php endwhile; ?>
		<div id="page-body" >
		<div id="lahg2col-container">
			<div id="lahg-center" class="column">
				<?php
				$args=array('numberposts'=>6,'category'=>259);
				$featuredPosts = get_posts($args);
				if($featuredPosts) 
				{ 
					$current="current";
					$hide="";
					echo '<div id="organic-tabs" > <div id="ot-maps" class=""> <ul class="nav">';
					$i=1;
					foreach($featuredPosts as $featuredPost) 
					{
						$post=get_post($featuredPost);
						setup_postdata($post);
						$shortname=get_post_meta($post->ID,'short-name',true);
						if(empty($shortname)) $shortname=$post->post_name;
						echo '<li class="nav"><a href="#'. $post->post_name .'" alt="'. $post->post_title .'" class="'. $current .' tab-color-1" >'. $shortname .'</a></li>';
						$current="";
						if($i>=4) { $i=1; } else { $i++; } 
					}
					echo '</ul>';
					wp_reset_postdata();
					echo '<div class="list-wrap">';
					foreach($featuredPosts as $featuredPost) 
					{
						$post=get_post($featuredPost);
						setup_postdata($post);
						$filteredcontent=null;
						$filteredcontent=$post->post_content;
						$filteredcontent=apply_filters('the_content', $filteredcontent);
						$filteredcontent=str_replace(']]>', ']]&gt;', $filteredcontent);
						echo '<ul id="'. $post->post_name .'" class="'. $hide .'"> <li class="organic-tabs">';
						echo '<div class="featured-map-content">'. $filteredcontent .'</div><!-- .featured-map-content --> ';
						if(function_exists('get_cityatlas_social')) get_cityatlas_social($post);
						echo '<div class="clear">&nbsp;</div> </li> </ul>';
						$hide="hide";
					}
					echo '</div> <!-- END List Wrap --> </div> <!-- #ot-maps --> </div> <!-- #organic-tabs --> <div class="clear">&nbsp</div>';
				}
				?>
		</div><!-- #lahg-center -->
		<div id="lahg-right" class="column sidebar-border-right">
			<div class="page-sidebar">
			<h3 class="feed-head">The Maps Feed:</h3>
			<?php echo make_gridster(array('queryValue'=>array('explore'), 'gridElementBorder'=>1,'maxColumns'=>1,'numberPosts'=>10, 'totalWidth'=>240,'margins'=>array(8),'padding'=>4,'gridElementHeight'=>220,'hasInfoTab'=>true, 'smartImagePlacement'=>FALSE,'useEmphasis'=>FALSE,'imagePosition'=>'top', 'flexibleHeight'=>TRUE, 'morelink'=>'http://newyork.thecityatlas.org/category/explore/')); ?>
			</div><!-- .page-sidebar -->
		</div><!-- #lahg-right -->
		</div><!-- #lahg-container -->
		</div><!-- #page-body -->
	<?php get_footer(); ?>
