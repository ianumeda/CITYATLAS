<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package WordPress
 * @subpackage Starkers
 * @since Starkers 3.0
 */
header('location: http://newyork.thecityatlas.org');
get_header(); ?>


	<div id="main">

		<div id="lahg2col-container">

			<div id="lahg-center" class="column">

					<?php 
						if(function_exists('bcn_display')) { 
						echo '<div class="breadcrumbs">';
						bcn_display();
						echo '</div>';
					 	}
					 ?>


			<div id="page-wrap" class="float-content" >

					<div id="page-head">

					<h1><?php _e( 'Not Found', 'twentyten' ); ?></h1>
				
			
					</div><!--#page-head-->
					<div id="page-body">

						<p><?php _e( 'Apologies, but the page you requested could not be found. Perhaps searching will help.', 'twentyten' ); ?></p>
						<?php get_search_form(); ?>

						<script type="text/javascript">
							// focus on search field after it has loaded
							document.getElementById('s') && document.getElementById('s').focus();
						</script>

					</div><!--#page-body-->
					<div id="page-foot">


					</div><!--#page-foot-->

				</div> <!-- #page-wrap -->

			</div><!-- #lahg-center -->

			<div id="lahg-right" class="column sidebar-border-right">

				<div id="post-sidebar" >
				<div class="widget-meta">
					<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Meta Widget') ) : ?>
					<?php endif; ?>
					<div class="clear">&nbsp</div>
				</div>
				<?PHP if(function_exists('related_entries')) related_entries(); ?>
				<?php get_sidebar(); ?>
				</div><!-- #post-sidebar -->

			</div><!-- #lahg-right -->

		</div><!-- #lahg-container -->

	</div><!-- #main -->

<?php get_footer(); ?>