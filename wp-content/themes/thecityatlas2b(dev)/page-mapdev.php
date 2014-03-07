<?php
/**
 * Template Name: mapdev
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

		<div id="page-wrap">

			<div id="page-head">
				<div class="container_16">
					<span class='title'><?php the_title(); ?></span><span class="tagline"> <?php if($tagline=get_post_meta($post->ID,'subtitle', true)) { echo $tagline; } ?> </span>
				<?php endwhile; ?>
				</div>

				</div><!-- #page-head -->
				
				<div id="page-body">

					<?php 
					$locData = array();
					$titleData = array();
					$linkData = array();
					$blurbData = array();
					$args = array( 'posts_per_page' => 50 );
					$my_query = new WP_Query( $args );


						while ($my_query->have_posts()) : $my_query->the_post(); 

						$bok = get_post_meta($post->ID, 'martygeocoderlatlng');

						array_push($locData,$bok);
						array_push($titleData,$post->post_title);
						array_push($linkData,get_permalink($post->ID));
						array_push($blurbData,get_the_excerpt());

					      endwhile;
					 ?>

					<div id="map" style="height:800px;"></div>

					<script type="text/javascript">

					var map = L.map('map').setView([40.7198,-73.9510], 13);
					L.tileLayer('http://{s}.tile.cloudmade.com/119bd5b096824b6bb523402fdcf25b52/77869/256/{z}/{x}/{y}.png', {
					    attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery <a href="http://cloudmade.com">CloudMade</a>',
					    maxZoom: 18
					}).addTo(map);
					 var xdata = <?php echo json_encode($locData); ?>;
					 var tdata = <?php echo json_encode($titleData); ?>;
					 var ldata = <?php echo json_encode($linkData); ?>;
					 var edata = <?php echo json_encode($blurbData); ?>;

					    for (var i = 0; i < xdata.length; i++) {
					    	   var clean =  xdata[i][0].substring(1,xdata[i][0].length - 1);
					   	 	   var n = clean.split(",");

					   	 	  var marker = L.marker([n[0],n[1]]).addTo(map);

					   	 	  	var domelem = document.createElement('a');
								domelem.href = ldata[i];
								domelem.innerHTML = '<h1>'+tdata[i]+'</h1><p>'+edata[i]+'</p>';
								domelem.onclick = function() {

								};

						  	 marker.bindPopup(domelem);

					    //Do something
						}


					</script>
				</div><!--#page-body-->
				
	</div><!-- .page-wrap -->

</div><!-- #people -->

<?php get_footer(); ?>
