<?php
/**
 * Template Name: Lifestyle2
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
<div id="lifestyle">
	<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
	<div class="breadcrumbs">
	<?php if(function_exists('bcn_display')) { bcn_display(); } ?>
	</div>
	<div id="page-wrap">
		<div id="page-head" class="">
			<div class="container_16">
				<span class='title'><?php the_title(); ?></span>
				<?php if($subtitle=get_post_meta($post->ID,'subtitle',true)) { ?> 
				<span class="tagline"><?php echo $subtitle; ?></span>
				<?php } ?>
			</div>
	<?php endwhile; ?>
			<div class="clear">&nbsp</div>
		</div><!-- #page-head -->
		<div id="page-body">
			<div class="container_16 fixed-width" >
				<div class="grid_11 grid_margins">
					<div class="widget-page">
						<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Page Widget') ) : ?>
						<?php endif; ?>
						<div class="clear">&nbsp</div>
					</div>
			<div id="organic-tabs" >
					<div id="ot-lifestyle" class="">
						<ul class="nav">
	                		<li class="nav"><a href="#radar" alt="Atlas Radar" class="current tab-color-1">Atlas Radar</a></li>
			                <!-- <li class="nav"><a href="#tips" alt="Tips For Today" class="tab-color-1">Tips For Today</a></li>
			                <li class="nav"><a href="#heart-ny-back" alt="Heart NY Back" class="tab-color-1"><span class="heart">♥</span> NY Back</a></li> -->
			                <li class="nav topics"><a href="#topics" alt="Topics" class="tab-color-1">Topics</a></li>
			            </ul>
			        	<div class="list-wrap">
						<ul id="radar" >
		        		<li><?php echo make_gridster( array('queryArgs'=>array('tax_query' => array('relation' => 'OR', array( 'taxonomy' => 'category', 'field' => 'slug', 'terms' => array( 'lifestyle' ) ) ) ), 'numberPosts'=>12, 'gridElementBorder'=>1, 'maxColumns'=>2, 'totalWidth'=>636, 'margins'=>6, 'padding'=>10, 'gridElementHeight'=>310, 'textSpace'=>array(240,140), 'imagePosition'=>"top", 'hasBottomInfo'=>TRUE, 'getStickies'=>TRUE, 'smartImagePlacement'=>FALSE, 'useEmphasis'=>FALSE, 'flexibleHeight'=>TRUE, 'flipFloatOnNewRow'=>TRUE, 'morelink'=>'http://newyork.thecityatlas.org/category/lifestyle/') ); ?></li>
		        		</ul>
						<ul id="tips" class="hide">
		        		<li><?php //echo make_gridster( array('queryArgs'=>array('tax_query' => array(array( 'taxonomy' => 'category', 'field' => 'slug', 'terms' => array( 'tips' ) ) ) ), 'numberPosts'=>10, 'gridElementBorder'=>1, 'maxColumns'=>2, 'totalWidth'=>636, 'margins'=>6, 'padding'=>10, 'gridElementHeight'=>310, 'textSpace'=>array(240,140), 'imagePosition'=>"top", 'hasBottomInfo'=>TRUE, 'getStickies'=>TRUE, 'smartImagePlacement'=>FALSE, 'useEmphasis'=>FALSE, 'flexibleHeight'=>TRUE, 'flipFloatOnNewRow'=>TRUE) ); ?></li>
		        		</ul>
						<ul id="heart-ny-back" class="hide">
		        		<li><?php //echo make_gridster( array('queryArgs'=>array('tax_query' => array( array( 'taxonomy' => 'category', 'field' => 'slug', 'terms' => array( 'heart-ny-back' ) ) ) ), 'numberPosts'=>10, 'gridElementBorder'=>1, 'maxColumns'=>2, 'totalWidth'=>636, 'margins'=>6, 'padding'=>10, 'gridElementHeight'=>310, 'textSpace'=>array(240,140), 'imagePosition'=>"top", 'hasBottomInfo'=>TRUE, 'getStickies'=>TRUE, 'smartImagePlacement'=>FALSE, 'useEmphasis'=>FALSE, 'flexibleHeight'=>TRUE, 'flipFloatOnNewRow'=>TRUE, 'textOptions'=>array('title','subtitle','excerpt')) ); ?></li>
		        		</ul>

		        		 	<ul id="topics" class="hide">
				        		<li style="padding:15px;">
									<?php if ( function_exists('wp_tag_cloud') ) : ?>
									<div class="section-break">&nbsp</div>
										<h3 style="font-size:12px; margin:10px 0;">Select a term below to filter by that topic</h3>
										<div id="topic-cloud">
										<ul>
										<?php wp_tag_cloud('taxonomy=top_level_topics&orderby=count&order=DESC'); ?>
										</ul>
										</div><!-- #topic-cloud -->
									<?php endif; ?>	
									<div class="section-break">&nbsp</div>
									<div class="clear">&nbsp</div>
								</li>
						</ul>
		        	 </div> <!-- END List Wrap -->
		         </div> <!-- #ot-lifestyle -->
	         </div> <!-- #organic-tabs -->
			<div class='clear'>&nbsp</div>
		</div><!-- .grid_11 -->
			<div id="sidebar" class="grid_5 grid_margins">
				<div id="organic-tabs" > 
					<div id="ot-events" class=""> 	
						<ul class="nav">
		                	<li class="nav"><a href="#all-events" alt="All Events" class="current">Upcoming Events</a></li>
			            </ul>
		        		<div class="list-wrap">
							<ul id="all-events">
									<div class="sidebar-element events">
									<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar('Lifestyle1') ) : ?>
									<?php endif; ?>
									<a href="<?php echo home_url("/events/month/"); ?>"><img style="position:absolute; bottom:0; right:0; margin:0 6px 12px 4px;" src="<?php bloginfo('stylesheet_directory'); ?>/images/calendaricon.jpg" alt="calendar"></a>
									<div class='clear'>&nbsp</div>
									</div><!--.sidebar-element-->
							</ul>
						</div> <!-- END List Wrap -->
					</div> <!-- #ot-events -->
				</div> <!-- #organic-tabs --> 
			<div class="clear">&nbsp</div>		
		</div><!-- .grid_5 -->
		<div class='clear'>&nbsp</div>
	</div>
</div><!-- #page-body -->
</div><!-- #page-wrap -->
</div><!-- #lifestyle -->
<?php get_footer(); ?>
