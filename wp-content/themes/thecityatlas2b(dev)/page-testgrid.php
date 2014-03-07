<?php
/**
 * Template Name: Grid Dev
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

<div id="people">

	<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

	<div class="breadcrumbs">
	<?php if(function_exists('bcn_display')) { bcn_display(); } ?>
	</div>


		<div id="page-wrap" class="container_16 fixed-width" >

			<div id="page-head" class="">

					<span class='title'><?php the_title(); ?></span><span class="tagline"><?php the_content(); ?></span>

				<?php endwhile; ?>

				</div><!-- #page-head -->

				<div id="dev-grid" >
                    
					<?php echo make_gridster( array('queryValue'=>array('people'), 'numberPosts'=>16, 'maxColumns'=>5, 'totalWidth'=>960, 'margins'=>array(3), 'padding'=>array(6), 'gridElementHeight'=>300, 'textSpace'=>array(180,120), 'imagePosition'=>'top', 'gridElementBorder'=>1, 'hasInfoTab'=>FALSE, 'useEmphasis'=>TRUE, 'emphasisSchedule'=>array('3x2','0','0','2x2','0'), 'imageOptions'=>'fullbleed', 'textOptions'=>array('title') ) ); ?>

		         </div> <!-- #dev-grid -->

	</div><!-- .page-wrap -->

</div><!-- #people -->

<?php get_footer(); ?>
