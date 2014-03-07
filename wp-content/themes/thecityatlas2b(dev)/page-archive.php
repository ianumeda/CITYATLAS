<?php
/**
 * Template Name: Archive
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

<div id="archive">

	<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

	<div class="breadcrumbs">
	<?php if(function_exists('bcn_display')) { bcn_display(); } ?>
	</div>

		<div id="page-wrap">

			<div id="page-head">
				<div class="container_16">
					<span class='title'><?php the_title(); ?></span><span class="tagline"> <?php if($tagline=get_post_meta($post->ID,'subtitle', true)) { echo $tagline; } ?> </span>
				</div>

				<?php endwhile; ?>

				</div><!-- #page-head -->

				<div id="page-body">

				<div id="listen-grid" class="container_16 fixed-width" >
                    
					<?php echo make_gridster(array('queryValue'=>array('archive'), 'gridElementBorder'=>1,'maxColumns'=>3,'totalWidth'=>960,'margins'=>8,'padding'=>8,'gridElementHeight'=>320,'hasInfoTab'=>true, 'smartImagePlacement'=>FALSE, 'useEmphasis'=>TRUE, 'textSpace'=>array(180))); ?>

		         </div> <!-- #listen-grid -->

			</div><!-- #page-body -->
			
	</div><!-- #page-wrap -->

</div><!-- #archive -->

<?php get_footer(); ?>
