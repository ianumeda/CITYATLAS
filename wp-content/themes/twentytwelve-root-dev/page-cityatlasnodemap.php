<?php
/**
 * Template Name: City Atlas Node Map Template
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
$_ENV['ismappage']=TRUE;
extract($_REQUEST);

get_header(); ?>

	<?php while ( have_posts() ) : the_post(); ?>
		<div class="breadcrumbs">
		<?php if(function_exists('bcn_display')) { bcn_display(); } ?>
		</div>

		<div id="primary" class="showcase">
			<div id="content" role="main">
				
			<?php
				/**
				 * We are using a heading by rendering the_content
				 * If we have content for this page, let's display it.
				 */
				//get_template_part( 'content', 'page' );
			?>
			<?php 
				function validatemapcenter($themapcenter){
					$themapcenter=str_replace(array('(',')','[',']',' '), array('','','','',''), $themapcenter);
					$themapcenter=explode(',', $themapcenter);
					if( count( $themapcenter ) == 2 && is_numeric($themapcenter[0]) && is_numeric($themapcenter[1]) ) return $themapcenter[0] .",". $themapcenter[1];
					else return null;
				}
				
				if (validatemapcenter($mapcenter)){ /* hurray */ }
				elseif($mapcenter=get_post_meta($post->ID, 'martygeocoderlatlng',TRUE) && $mapcenter=validatemapcenter($mapcenter)) { /* default set by this page's geolocation setting */ }
				else $mapcenter='40.7143528,-74.0059731'; // other default
				if(!is_numeric($zoom)){
					if( $zoom=get_post_meta($post->ID, 'map-zoom-init', TRUE) ) { /* yay! */ }
					else { $zoom=2; }
				}
			?>

			</div><!-- #content -->
		</div><!-- #primary -->
		<?php $citiespageid=$post->ID; ?>
	<?php endwhile; ?>

		<?php 
		$locData = array();
		$titleData = array();
		$linkData = array();
		$blurbData = array();
		
		$postsdisplayed=array(); // to eliminate repeat posts b/c apparently TEC has a single post ID for recurring posts
		
		global $post;
		$args = array(
		    'posts_per_page'  => -1,
		    'post_type'       => 'page',
		    'post_parent'     => $citiespageid, /* this is the "cities" page, the parent to all these pages */
		); 
		
		$all_nodes = get_posts( $args );
		
		foreach($all_nodes as $post) {
			setup_postdata($post);
			//echo $post->ID.":";
			if($bok = get_post_meta($post->ID, 'martygeocoderlatlng')){
				array_push($locData,$bok);
				array_push($titleData,$post->post_title);
				array_push($linkData,get_permalink($post->ID));
				array_push($blurbData,get_the_excerpt());
			}
		}
		// echo "todaydata:"; print_r($todaydata);
		wp_reset_query();
		 ?>
		<script type="text/javascript">
		function getMapState(){
			thecenternow=map.getCenter();
			return "&mapcenter="+ thecenternow.lat + "," + thecenternow.lng +"&zoom="+ map.getZoom();
		}
		
		document.getElementById("lead").style.height="100%";
		var map = L.map('lead').fitWorld();
		L.tileLayer('http://{s}.tile.cloudmade.com/119bd5b096824b6bb523402fdcf25b52/77869/256/{z}/{x}/{y}.png', { attribution: '', maxZoom: 6, minZoom: 0 }).addTo(map);
		var draggable = new L.Draggable(map);
		draggable.disable();
		// map.locate({setView: true, maxZoom: 2});
		
				var greenicon = L.icon({
				    iconUrl: 'http://thecityatlas.org/images/cityatlasmapmarkergreen25x41.png',
				    iconRetinaUrl: 'http://thecityatlas.org/images/cityatlasmapmarkergreen50x82.png',
				    iconSize: [25, 41],
				    iconAnchor: [12, 41],
				    popupAnchor: [1, -41],
				    shadowUrl: 'http://thecityatlas.org/images/markershadow41x41.png',
				    shadowRetinaUrl: 'http://thecityatlas.org/images/markershadow41x41.png',
				    shadowSize: [41, 41],
				    shadowAnchor: [12, 41]
				});
				var blueicon = L.icon({
				   iconUrl: 'http://thecityatlas.org/images/cityatlasmapmarkerblue25x41.png',
				   iconRetinaUrl: 'http://thecityatlas.org/images/cityatlasmapmarkerblue50x82.png',
				   iconSize: [25, 41],
				   iconAnchor: [12, 41],
				   popupAnchor: [1, -41],
				   shadowUrl: 'http://thecityatlas.org/images/markershadow41x41.png',
				   shadowRetinaUrl: 'http://thecityatlas.org/images/markershadow41x41.png',
				   shadowSize: [41, 41],
				   shadowAnchor: [12, 41]
				});
				var redicon = L.icon({
				  iconUrl: 'http://thecityatlas.org/images/cityatlasmapmarkerred25x41.png',
				  iconRetinaUrl: 'http://thecityatlas.org/images/cityatlasmapmarkerred50x82.png',
				  iconSize: [25, 41],
				  iconAnchor: [12, 41],
				  popupAnchor: [1, -41],
				  shadowUrl: 'http://thecityatlas.org/images/markershadow41x41.png',
				  shadowRetinaUrl: 'http://thecityatlas.org/images/markershadow41x41.png',
				  shadowSize: [41, 41],
				  shadowAnchor: [12, 41]
				});
				var greyicon = L.icon({
				  iconUrl: 'http://thecityatlas.org/images/cityatlasmapmarkergrey25x41.png',
				  iconRetinaUrl: 'http://thecityatlas.org/images/cityatlasmapmarkergrey50x82.png',
				  iconSize: [25, 41],
				  iconAnchor: [12, 41],
				  popupAnchor: [1, -41],
				  shadowUrl: 'http://thecityatlas.org/images/markershadow41x41.png',
				  shadowRetinaUrl: 'http://thecityatlas.org/images/markershadow41x41.png',
				  shadowSize: [41, 41],
				  shadowAnchor: [12, 41]
				});

				// setup the leafpile
				// var leafpile = new L.Leafpile();
				// map.addLayer(leafpile);

				 var xdata = <?php echo json_encode($locData); ?>;
				 var titledata = <?php echo json_encode($titleData); ?>;
				 var linkdata = <?php echo json_encode($linkData); ?>;
				 var excerptdata = <?php echo json_encode($blurbData); ?>;
				var allpoints = new Array();

				    for (var i = 0; i < xdata.length; i++) {
						var clean =  xdata[i][0].substring(1,xdata[i][0].length - 1); 
						var n = clean.split(",");
						// if(todaydata[i]==1) { var marker = L.marker([n[0],n[1]], {icon: blueicon}).addTo(map); }
						// else if(todaydata[i]==2) { var marker = L.marker([n[0],n[1]], {icon: redicon}).addTo(map); }
						// else if(todaydata[i]==-1) { var marker = L.marker([n[0],n[1]], {icon: greyicon}).addTo(map); }
						// else { var marker = L.marker([n[0],n[1]], {icon: greenicon}).addTo(map); }
						var marker = L.marker([n[0],n[1]], {icon: greenicon}).addTo(map);
						var domelem = document.createElement('a');
						domelem.href = linkdata[i];
						domelem.innerHTML = '<h1>'+titledata[i]+'</h1><p>'+excerptdata[i]+'</p>';
						domelem.onclick = function() { };
						marker.bindPopup(domelem);
						allpoints.push(n);
						// leafpile.addMarker(marker);
					}
				</script>
		<?php get_footer(); ?>