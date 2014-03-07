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
				else $mapcenter='40.7143528,-74.0059731'; // other default
				if(!is_numeric($zoom)){
					if( $zoom=get_post_meta($post->ID, 'map-zoom-init', TRUE) ) { /* yay! */ }
					else { $zoom=10; }
				}
				if( !empty($showcategories) ) { /* $showcategories=explode(',',$showcategories); */ }
				else{ $showcategories=""; }
				if( !empty($initdate)) { $inittime=strtotime($initdate); }
				else $inittime=strtotime('now'); 
				if( !empty($timeframe) ) { /* requires validation */ }
				else $timeframe="1month";
				$timelimit=strtotime($timeframe, $inittime);
				if( !is_numeric($eventlimit) ) { $eventlimit=-1; }
				if( !is_numeric($offset) ) { $offset=0; } 
				$dayinseconds=86400;
				$weekinseconds=604800; // 7 days
				$monthinseconds=2592000; // 30 days
				if($timeframe=="1month") $timeframeinseconds=$monthinseconds;
				elseif($timeframe=="1day") $timeframeinseconds=$dayinseconds;
				else $timeframeinseconds=$weekinseconds;
				if($viewing=='sirr') { $posttype='post'; $tag='sirr-interviews'; }
				elseif($viewing=='greenmarkets') { $posttype='post'; $tag='greenmarkets'; }
				else { $posttype='events'; }
				$mapcontrols='<span class="title">MAP:</span><span class="map-controls-section">Viewing ( <span class="map-controls-button';
				if($viewing==null || $viewing=="events") { $mapcontrols.=' selected'; }
				$mapcontrols.='"><a href="#" onclick="updateMap(\''.$timeframe.'\', '.date("Ymd", $inittime).');">Events</a></span>';
				// '<span class="map-controls-button';
				// if($viewing=="greenmarkets") { $mapcontrols.=' selected'; }
				// $mapcontrols.='"><a href="#" onclick="updateMap(\''.$timeframe.'\', '.date("Ymd", $inittime).', \'greenmarkets\');">GREENMARKETS</a></span><span class="map-controls-button';
				// if($viewing=="sirr") { $mapcontrols.=' selected'; }
				// $mapcontrols.='"><a href="#" onclick="updateMap(\''.$timeframe.'\', '.date("Ymd", $inittime).', \'sirr\');">SIRR</a></span>
				$mapcontrols.=' )</span>';
				if($posttype=='events'){
					$mapcontrols.='<span class="title">Timeframe :</span> <span class="map-controls-date">'.(tribe_get_event_date_format(date('M j, Y', $inittime), date('M j, Y', ($inittime+$timeframeinseconds-$dayinseconds)))).'</span></span><span class="map-controls-section">Show ( <span class="map-controls-button';
					if($timeframe=="1day") { $mapcontrols.=' selected'; $verbaltimeframe="day"; }
					$mapcontrols.='"><a href="#" onclick="updateMap(\'1day\','.date("Ymd", $inittime).');">Day</a></span><span class="map-controls-button';
					if($timeframe=="1week") { $mapcontrols.=' selected'; $verbaltimeframe="week"; }
					$mapcontrols.='"><a href="#" onclick="updateMap(\'1week\','.date("Ymd", $inittime).');">Week</a></span><span class="map-controls-button';
					if($timeframe=="1month") { $mapcontrols.=' selected'; $verbaltimeframe="month"; }
					$mapcontrols.='"><a href="#" onclick="updateMap(\'1month\','.date("Ymd",$inittime).');">Month</a></span> )</span><span class="map-controls-section">Starting ( <span class="map-controls-button';
					if(date('Ymd')==date('Ymd',$inittime)) $mapcontrols.=' selected';
					$mapcontrols.='"><a href="#" onclick="updateMap(\''.$timeframe.'\',\'now\');">now</a></span>';
					if(date('Ymd',$inittime)>date('Ymd')) $mapcontrols.='<span class="map-controls-button"><a href="#" onclick="updateMap(\''.$timeframe.'\','.date('Ymd', $inittime-$timeframeinseconds).');">-1 '.$verbaltimeframe.'</a></span>';
					$mapcontrols.='<span class="map-controls-button"><a href="#" onclick="updateMap(\''.$timeframe.'\','.date('Ymd', $inittime+$timeframeinseconds).');">+1 '.$verbaltimeframe.'</a></span> )</span>';
				} 
			?>

		<script type='text/javascript'>
			makeMapControls("<?php echo escapehtmlchars($mapcontrols); ?>");
			sTimeframe="<?php echo $timeframe; ?>";
			nInitTime=<?php echo $inittime; ?>;
			var themapcenter="<?php echo $mapcenter; ?>";
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
		if($posttype=='post'){
			$args=array();
			if(!empty($category) && $catobject=get_category_by_slug($category)) {
				$catid=$catobject->term_id;
				$args['category']=$catid;
			}
			if(!empty($tag)) $args['taxquery']=array( array( 'taxonomy' => 'tag', 'field' => 'slug', 'terms' => $tag ) );
			$allposts=get_posts($args);
			foreach($allposts as $post) {
				setup_postdata($post);
				if($bok = get_post_meta($post->ID, 'martygeocoderlatlng')){
					array_push($postsdisplayed, $post->ID);
					array_push($locData,$bok);
					array_push($titleData,$post->post_title);
					array_push($linkData,get_permalink($post->ID));
					array_push($blurbData,get_the_excerpt());
					array_push($eventdata,get_the_time('d-m-Y', $post->ID));
					$todaydata[]=0;
				}
			}
			// echo "todaydata:"; print_r($todaydata);
		} else {
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
		}
		wp_reset_query();
		 ?>
		<script type="text/javascript">
		function getMapState(){
			thecenternow=map.getCenter();
			return "&mapcenter="+ thecenternow.lat + "," + thecenternow.lng +"&zoom="+ map.getZoom();
		}
		function updateMap(gotimeframe, goinittime, viewing){
			if(viewing=="sirr") {
				window.location.href=".?viewing=sirr" + getMapState();
			} else if(viewing=="greenmarkets") {
				window.location.href=".?viewing=greenmarkets" + getMapState();
			} else {
				if(gotimeframe==undefined) gotimeframe=sTimeframe;
				if(goinittime==undefined) goinittime=nInitTime;
				window.location.href=".?viewing=events&timeframe=" + gotimeframe + "&initdate="+ goinittime + getMapState();
			}
		}
		
		<?PHP if(is_mobile()) { ?>  
			document.getElementById("lead").style.height="110%";
		<?PHP } else { ?>
			document.getElementById("lead").style.height="100%";
		<?PHP } ?>
		var map = L.map('lead').setView([<?php echo $mapcenter; ?>], <?php echo $zoom; ?>);		
		L.tileLayer('http://{s}.tile.cloudmade.com/119bd5b096824b6bb523402fdcf25b52/77869/256/{z}/{x}/{y}.png', { attribution: '', maxZoom: 18 }).addTo(map);

		var cityboundsNE=new L.LatLng(41.04828819952275,-73.52874755859375);
		var cityboundsSW=new L.LatLng(40.364857898336325,-74.4876480102539);
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
			<?php
			$colors=array("green","blue","greeninverse","grey");
			foreach($colors as $color){
				for($i=0; $i<=99; $i++){
					echo "this.marker".$color.$i." = L.icon({ iconUrl: 'http://thecityatlas.org/images/lwt_map_icons/".$color."/".$i.".png', iconSize: [32, 36], iconAnchor: [16, 36], popupAnchor: [-1, -34], shadowUrl: 'http://thecityatlas.org/images/markershadow41x41.png', shadowRetinaUrl: 'http://thecityatlas.org/images/markershadow41x41.png', shadowSize: [41, 41], shadowAnchor: [15, 44] });";
				}
				for($i=ord('A'); $i<=ord('Z'); $i++){
					echo "this.marker".$color.chr($i)." = L.icon({ iconUrl: 'http://thecityatlas.org/images/lwt_map_icons/".$color."/".chr($i).".png', iconSize: [32, 36], iconAnchor: [16, 36], popupAnchor: [-1, -34], shadowUrl: 'http://thecityatlas.org/images/markershadow41x41.png', shadowRetinaUrl: 'http://thecityatlas.org/images/markershadow41x41.png', shadowSize: [41, 41], shadowAnchor: [15, 44] });";
				}
			}
			?>

			function ord (string) {
			  // http://kevin.vanzonneveld.net
			  // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
			  // +   bugfixed by: Onno Marsman
			  // +   improved by: Brett Zamir (http://brett-zamir.me)
			  // +   input by: incidence
			  // *     example 1: ord('K');
			  // *     returns 1: 75
			  // *     example 2: ord('\uD800\uDC00'); // surrogate pair to create a single Unicode character
			  // *     returns 2: 65536
			  var str = string + '',
			    code = str.charCodeAt(0);
			  if (0xD800 <= code && code <= 0xDBFF) { // High surrogate (could change last hex to 0xDB7F to treat high private surrogates as single characters)
			    var hi = code;
			    if (str.length === 1) {
			      return code; // This is just a high surrogate with no following low surrogate, so we return its value;
			      // we could also throw an error as it is not a complete character, but someone may want to know
			    }
			    var low = str.charCodeAt(1);
			    return ((hi - 0xD800) * 0x400) + (low - 0xDC00) + 0x10000;
			  }
			  if (0xDC00 <= code && code <= 0xDFFF) { // Low surrogate
			    return code; // This is just a low surrogate with no preceding high surrogate, so we return its value;
			    // we could also throw an error as it is not a complete character, but someone may want to know
			  }
			  return code;
			}
			function chr (codePt) {
			  // http://kevin.vanzonneveld.net
			  // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
			  // +   improved by: Brett Zamir (http://brett-zamir.me)
			  // *     example 1: chr(75);
			  // *     returns 1: 'K'
			  // *     example 1: chr(65536) === '\uD800\uDC00';
			  // *     returns 1: true
			  if (codePt > 0xFFFF) { // Create a four-byte string (length 2) since this code point is high
			    //   enough for the UTF-16 encoding (JavaScript internal use), to
			    //   require representation with two surrogates (reserved non-characters
			    //   used for building other characters; the first is "high" and the next "low")
			    codePt -= 0x10000;
			    return String.fromCharCode(0xD800 + (codePt >> 10), 0xDC00 + (codePt & 0x3FF));
			  }
			  return String.fromCharCode(codePt);
			}
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
						if(i>99){
							var markerid=chr(ord('A')+(i-100));
						} else var markerid=i;
						if(todaydata[i]==1) { 
							var marker = L.marker([n[0],n[1]], {icon: this['markergreen'+markerid]}).addTo(map); 
							var markerbubblecontent='<span class="map-marker-content-topleft-corner today">Today!</span>';
						}
						else if(todaydata[i]==2) { 
							var marker = L.marker([n[0],n[1]], {icon: this['markergreeninverse'+markerid]}).addTo(map); 
							var markerbubblecontent='<span class="map-marker-content-topleft-corner now">Now!</span>';
						}
						else if(todaydata[i]==-1) { 
							var marker = L.marker([n[0],n[1]], {icon: this['markergrey'+markerid]}).addTo(map); 
							var markerbubblecontent='<span class="map-marker-content-topleft-corner passed">Passed</span>';
						}
						else { 
							var marker = L.marker([n[0],n[1]], {icon: this['markerblue'+markerid]}).addTo(map); 
							// var markerbubblecontent='<span class="map-marker-content-topleft-corner soon">Soon</span>';
							var markerbubblecontent='';
						}
						var domelem = document.createElement('a');
						domelem.href = ldata[i];
						domelem.innerHTML = '<h1>'+tdata[i]+'</h1><h4>'+zdata[i]+'</h4><p>'+edata[i]+'</p>'+markerbubblecontent;
						domelem.onclick = function() { };
						marker.bindPopup(domelem);
						// leafpile.addMarker(marker);
					}

				</script>
		<?php get_footer(); ?>