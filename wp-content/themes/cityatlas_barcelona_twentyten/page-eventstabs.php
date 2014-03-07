<?php
/**
 * Template Name: Events with tabs
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

<div id="events">

	<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

	<div class="breadcrumbs"> <?php if(function_exists('bcn_display')) { bcn_display(); } ?> </div>

	<div id="page-wrap" class="container_16 fixed-width">
	

			<div id="page-head">

				<h1 class="title"><?php the_title(); ?></h1>

			</div><!-- #page-head -->

			<div id="page-events-body">
				<div id="events" class='grid_16 grid_margins'>

				<div id="organic-tabs" >

						<div id="ot-events" class="">
							<ul class="nav">
		                		<li class="nav"><a href="#eventslist" alt="Events List" class="current tab-color-1">Events List</a></li>
				                <li class="nav"><a href="#googlecal" alt="Google Calendar" class="tab-color-1">Calendar</a></li>
				                <!-- <li class="nav"><a href="#plugincal" alt="Calendar">Calendar</a></li> -->
				            </ul>

				        	<div class="list-wrap">

							<ul id="eventslist">
			        		<li>
								<div class="events-google-calendar-link"><a href="http://www.google.com/calendar/render?cid=http%3A%2F%2Fwww.google.com%2Fcalendar%2Fembed%3Fsrc%3Dthecityatlas.org_vk2ha2gq68tknu5vqol6vbqb3s%2540group.calendar.google.com%26ctz%3DAmerica%2FNew_York" target="_blank"><img src="http://www.google.com/calendar/images/ext/gc_button6.gif" alt="0" border="0"></a></div>
								
								<?php the_content(); ?>
								
								<div class='clear'>&nbsp</div>
							</li>
			        		</ul>
							<ul id="googlecal" class="hide">
				        		<li style="padding:15px;">
									<div class="events-google-calendar-link"><a href="http://www.google.com/calendar/render?cid=http%3A%2F%2Fwww.google.com%2Fcalendar%2Fembed%3Fsrc%3Dthecityatlas.org_vk2ha2gq68tknu5vqol6vbqb3s%2540group.calendar.google.com%26ctz%3DAmerica%2FNew_York" target="_blank"><img src="http://www.google.com/calendar/images/ext/gc_button6.gif" alt="0" border="0"></a></div>
									<iframe src="http://www.google.com/calendar/embed?src=thecityatlas.org_vk2ha2gq68tknu5vqol6vbqb3s%40group.calendar.google.com&ctz=America/New_York" style="border: 0" width="100%" height="640" frameborder="0" scrolling="no"></iframe>								
								</li>
				        		</ul>
								<ul id="plugincal" class="hide">
					        		<li>
										<div class="events-google-calendar-link"><a href="http://www.google.com/calendar/render?cid=http%3A%2F%2Fwww.google.com%2Fcalendar%2Fembed%3Fsrc%3Dthecityatlas.org_vk2ha2gq68tknu5vqol6vbqb3s%2540group.calendar.google.com%26ctz%3DAmerica%2FNew_York" target="_blank"><img src="http://www.google.com/calendar/images/ext/gc_button6.gif" alt="0" border="0"></a><img src="http://www.google.com/calendar/images/ext/gc_button1.gif" alt="0" border="0"></a></div>
										<?php //echo do_shortcode('[google-calendar-events id="6" type=ajax max=64]'); ?>
										<span class="more-link"><a href="<?php echo home_url('/events/main-calendar/'); ?>">more...</a></span>
										<div class='clear'>&nbsp</div>
									</li>
					        		</ul>

				        	 </div> <!-- END List Wrap -->

				         </div> <!-- #example-one -->

		         </div> <!-- #organic-tabs -->
				</div><!-- .grid_16 -->

				<div class="clear">&nbsp</div>
				
			</div><!--#page-body-->

			<div id="page-foot">
				
				<?php wp_link_pages( array( 'before' => '' . __( 'Pages:', 'twentyten' ), 'after' => '' ) ); ?>

			</div><!--#page-foot-->

			</div><!-- #page-wrap -->

			<?php endwhile; ?>


</div><!-- #events -->

<?php get_footer(); ?>