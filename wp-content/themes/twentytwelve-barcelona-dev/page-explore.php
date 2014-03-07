<?php
/**
 * Template Name: Explore Template
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
				else $mapcenter='41.38286240274508,2.157440185546875'; // barcelona default
				if(!is_numeric($zoom)){
					if( $zoom=get_post_meta($post->ID, 'map-zoom-init', TRUE) ) { /* yay! */ }
					else { $zoom=12; }
				}
				if( !empty($showcategories) ) { /* $showcategories=explode(',',$showcategories); */ }
				else{ $showcategories=""; }
				if( !empty($initdate)) { $inittime=strtotime($initdate); }
				else $inittime=strtotime('now'); 
				if( !empty($timeframe) ) { /* requires validation */ }
				else $timeframe="1week";
				$timelimit=strtotime($timeframe, $inittime);
				if( !is_numeric($eventlimit) ) { $eventlimit=-1; }
				if( !is_numeric($offset) ) { $offset=0; } 
				$dayinseconds=86400;
				$weekinseconds=604800; // 7 days
				$monthinseconds=2592000; // 30 days
				if($timeframe=="1month") $timeframeinseconds=$monthinseconds;
				elseif($timeframe=="1day") $timeframeinseconds=$dayinseconds;
				else $timeframeinseconds=$weekinseconds;
				$mapcontrols='<span class="title">Viewing Events For: <span class="map-controls-date">'.(tribe_get_event_date_format(date('M j, Y', $inittime), date('M j, Y', ($inittime+$timeframeinseconds-$dayinseconds)))).'</span></span><span class="map-controls-section">Show:<span class="map-controls-button';
				if($timeframe=="1day") { $mapcontrols.=' selected'; $verbaltimeframe="day"; }
				$mapcontrols.='"><a href="#" onclick="updateMap(\'1day\','.date("Ymd", $inittime).');">Day</a></span><span class="map-controls-button';
				if($timeframe=="1week") { $mapcontrols.=' selected'; $verbaltimeframe="week"; }
				$mapcontrols.='"><a href="#" onclick="updateMap(\'1week\','.date("Ymd", $inittime).');">Week</a></span><span class="map-controls-button';
				if($timeframe=="1month") { $mapcontrols.=' selected'; $verbaltimeframe="month"; }
				$mapcontrols.='"><a href="#" onclick="updateMap(\'1month\','.date("Ymd",$inittime).');">Month</a></span></span><span class="map-controls-section">Starting:<span class="map-controls-button';
				if(date('Ymd')==date('Ymd',$inittime)) $mapcontrols.=' selected';
				$mapcontrols.='"><a href="#" onclick="updateMap(\''.$timeframe.'\',\'now\');">now</a></span>';
				if(date('Ymd',$inittime)>date('Ymd')) $mapcontrols.='<span class="map-controls-button"><a href="#" onclick="updateMap(\''.$timeframe.'\','.date('Ymd', $inittime-$timeframeinseconds).');">-1 '.$verbaltimeframe.'</a></span>';
				$mapcontrols.='<span class="map-controls-button"><a href="#" onclick="updateMap(\''.$timeframe.'\','.date('Ymd', $inittime+$timeframeinseconds).');">+1 '.$verbaltimeframe.'</a></span></span>';
			?>

		<script type='text/javascript'>
			makeMapControls("<?php echo escapehtmlchars($mapcontrols); ?>");
			sTimeframe="<?php echo $timeframe; ?>";
			nInitTime=<?php echo $inittime; ?>;
		</script>

			</div><!-- #content -->
		</div><!-- #primary -->
	<?php endwhile; ?>

		<?php 
		$locData = array();
		$titleData = array();
		$linkData = array();
		$blurbData = array();
		$eventdata = array();
		$todaydata = array();
		
		$postsdisplayed=array(); // to eliminate repeat posts b/c apparently TEC has a single post ID for recurring posts
		
		global $post;
		$all_events = tribe_get_events(array( 'eventDisplay'=>'all', 'posts_per_page'=>$eventlimit, 'start_date'=>date('j M Y',$inittime), 'end_date'=>date('j M Y', ($inittime+$timeframeinseconds-$dayinseconds)) ) );
		foreach($all_events as $post) {
			setup_postdata($post);
			if(!in_array($post->ID, $postsdisplayed)){
				//echo $post->ID.":";
				if($bok = get_post_meta($post->ID, 'martygeocoderlatlng')){
					array_push($postsdisplayed, $post->ID);
					array_push($locData,$bok);
					array_push($titleData,$post->post_title);
					array_push($linkData,get_permalink($post->ID));
					array_push($blurbData,get_the_excerpt());
					array_push($eventdata,tribe_get_event_date_format(tribe_get_start_date($post->ID, true, 'M j, Y'),tribe_get_end_date($post->ID, true, 'M j, Y')));
					if( tribe_get_start_date($post->ID, true, 'U')-strtotime('now') < 86400) {
						if( tribe_get_end_date($post->ID, true, 'U') < strtotime('now') ) $todaydata[]=-1; // event is over
						elseif( tribe_get_start_date($post->ID, true, 'U') < strtotime('now')) $todaydata[]=2; // event is happening now
						else /*if( tribe_get_start_date($post->ID, true, 'Ymdhi') <= date('Ymdhi', time('now')) )*/ $todaydata[]=1; // event is upcoming within the next 24 hours
					}
					else $todaydata[]=0;
				}
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
		function updateMap(gotimeframe, goinittime){
			if(gotimeframe==undefined) gotimeframe=sTimeframe;
			if(goinittime==undefined) goinittime=nInitTime;
			window.location.href=".?timeframe=" + gotimeframe + "&initdate="+ goinittime + getMapState();
		}
		
		document.getElementById("lead").style.height="100%";
		var map = L.map('lead').setView([<?php echo $mapcenter; ?>], <?php echo $zoom; ?>);		
		L.tileLayer('http://{s}.tile.cloudmade.com/119bd5b096824b6bb523402fdcf25b52/77869/256/{z}/{x}/{y}.png', { attribution: '', maxZoom: 18}).addTo(map);
		
		var cityboundsNE=new L.LatLng(41.57667280728488,2.3735618591308594);
		var cityboundsSW=new L.LatLng(41.226183305514596,1.9281005859375);
		var citybounds=new L.LatLngBounds(cityboundsSW, cityboundsNE);		
		map.setMaxBounds(citybounds);

		map.locate({setView: false });
		function onLocationFound(e) {
		    var radius = e.accuracy / 2;
			var athemapcenter=themapcenter.split(",");
			var distance = e.latlng.distanceTo(new L.LatLng(athemapcenter[0],athemapcenter[1]));
			if(citybounds.contains(e.latlng)){
			    L.marker(e.latlng).addTo(map).bindPopup("You are here").openPopup();
			    L.circle(e.latlng, radius).addTo(map);
			}
			// else {}
		}
		function onLocationError(e) {
		    alert(e.message);
		}
		map.on('locationerror', onLocationError);
		map.on('locationfound', onLocationFound);

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
				 var tdata = <?php echo json_encode($titleData); ?>;
				 var ldata = <?php echo json_encode($linkData); ?>;
				 var edata = <?php echo json_encode($blurbData); ?>;
				 var zdata = <?php echo json_encode($eventdata); ?>;
				var todaydata = <?php echo json_encode($todaydata); ?>;

				    for (var i = 0; i < xdata.length; i++) {
						var clean =  xdata[i][0].substring(1,xdata[i][0].length - 1); 
						var n = clean.split(",");
						if(todaydata[i]==1) { var marker = L.marker([n[0],n[1]], {icon: blueicon}).addTo(map); }
						else if(todaydata[i]==2) { var marker = L.marker([n[0],n[1]], {icon: redicon}).addTo(map); }
						else if(todaydata[i]==-1) { var marker = L.marker([n[0],n[1]], {icon: greyicon}).addTo(map); }
						else { var marker = L.marker([n[0],n[1]], {icon: greenicon}).addTo(map); }
						var domelem = document.createElement('a');
						domelem.href = ldata[i];
						domelem.innerHTML = '<h1>'+tdata[i]+'</h1><h4>'+zdata[i]+'</h4><p>'+edata[i]+'</p>';
						domelem.onclick = function() { };
						marker.bindPopup(domelem);
						// leafpile.addMarker(marker);
					}

				</script>
		<?php get_footer(); ?>