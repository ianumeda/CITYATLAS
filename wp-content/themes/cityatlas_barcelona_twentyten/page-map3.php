<?php
/**
 * Template Name: Map3
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
<script src="https://www.google.com/jsapi?key=ABQIAAAAEvHkqRebGR_B_mUGaFcxlxTCMJ0M-ulIrn6HL6orioFreNFNDBSzdieNHy86B63gJWJCfH2zBwsF1A" type="text/javascript"></script>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=true"></script>

<script type="text/javascript">
/*
*  How to see historical entries in a feed.  Usually a feed only returns x number
*  of results, and you want more.  Since the Google Feeds API caches feeds, you can
*  dig into the history of entries that it has cached.  This, paired with setNumEntries,
*  allows you to get more entries than normally possible.
*/

google.load("feeds", "1");
var data;
// Our callback function, for when a feed is loaded.
function feedLoaded(result) {
  if (result.error) {
alert("feed error: "+result.error);
  } else {
      // Grab the container we will put the results into
      //var container = document.getElementById("content");
      //container.innerHTML = '';
       data = result;
      // Loop through the feeds, putting the titles onto the page.
      // Check out the result object for a list of properties returned in each entry.
      // http://code.google.com/apis/ajaxfeeds/documentation/reference.html#JSON
      // alert(result.feed.entries.length);

  }
    initialize();
}

	function OnLoad() 
	{
		// Create a feed instance that will grab Digg's feed.
		var feed = new google.feeds.Feed("http://newyork.thecityatlas.org/lab/labfeedwithlatlontags5/");
		feed.includeHistoricalEntries(); // tell the API we want to have old entries too
		feed.setNumEntries(250); // we want a maximum of 250 entries, if they exist
		// Calling load sends the request off.  It requires a callback function.
		feed.load(feedLoaded);
	}



	      // do google call
	    // google.setOnLoadCallback(OnLoad);

	function initialize() 
	{


	    var latlng = new google.maps.LatLng(40.756054,-73.986951);

	      var ParksStyles = [
	          {
	            featureType: "all",
	            stylers: [
	              { saturation: -80 }
	            ]
	          },
	          {
	            featureType: "poi.park",
	            stylers: [
	              { hue: "#8FBE00" },
	              { saturation: 40 }
	            ]
	          }
	       ];
	    var myOptions = {
	      zoom: 12,
	      center: latlng,
	      mapTypeId: google.maps.MapTypeId.ROADMAP,
	      styles: ParksStyles
	    };


			var image = new google.maps.MarkerImage(
			  'marker-images/image.png',
			  new google.maps.Size(38,50),
			  new google.maps.Point(0,0),
			  new google.maps.Point(19,50)
			);

			var shadow = new google.maps.MarkerImage(
			  'marker-images/shadow.png',
			  new google.maps.Size(66,50),
			  new google.maps.Point(0,0),
			  new google.maps.Point(19,50)
			);

			var shape = {
			  coord: [20,2,22,3,24,4,25,5,26,6,27,7,28,8,29,9,30,10,31,11,32,12,33,13,34,14,35,15,36,16,37,17,37,18,37,19,37,20,37,21,37,22,37,23,37,24,37,25,37,26,37,27,37,28,37,29,37,30,37,31,37,32,37,33,37,34,37,35,37,36,37,37,37,38,37,39,37,40,37,41,36,42,35,43,34,44,33,45,32,46,31,47,30,48,29,49,10,49,9,48,8,47,7,46,6,45,5,44,4,43,3,42,2,41,1,40,1,39,1,38,1,37,1,36,1,35,1,34,1,33,1,32,1,31,1,30,1,29,1,28,1,27,1,26,1,25,1,24,1,23,1,22,1,21,1,20,1,19,1,18,2,17,3,16,4,15,5,14,6,13,7,12,8,11,9,10,10,9,11,8,12,7,13,6,14,5,15,4,17,3,18,2,20,2],
			  type: 'poly'
			};


	    overlayMaps = [
	    {
	        getTileUrl: function(coord, zoom) {
	        var ymax = 1 << zoom;
		    var y = ymax - coord.y - 1;
	        return "http://welikia.org/img/overlays/1609Sat/tiles/" + zoom + "/" + coord.x + "/" + y + ".png";
	        },
	        tileSize: new google.maps.Size(256, 256),
	        opacity: 0.1,
	        isPng: true
	    }
	    ];


	    var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
	     geocoder = new google.maps.Geocoder();

	    var bikeLayer = new google.maps.BicyclingLayer();
	    bikeLayer.setMap(map);

	//	for (var i = 0; i < data.feed.entries.length; i++) 
	for (var i = 0; i < 10; i++) 
	{
		var entry = data.feed.entries[i];
		//var div = document.createElement("div");
		//div.appendChild(document.createTextNode(i + ': ' + entry.title));
		var content = entry.content;

		// document.write(content.split() + "<br />");
		var spit = content.indexOf('<address>')+9;
		var spit_end = content.indexOf('</address>');
		if(spit_end-spit>0)
		{
			var address = content.substr(spit, spit_end-spit);
			var latlngStr = address.split(",",2);
		    var lat = parseFloat(latlngStr[0]);
		    var lng = parseFloat(latlngStr[1]);
		    var latlong = new google.maps.LatLng(lat, lng);

			var marker;
			geocoder.geocode({ 'latLng': latlong}, function(results, status) 
			{
				if (status == google.maps.GeocoderStatus.OK) 
				{
					//alert(i+":"+address+". "+content);
					// map.setCenter(results[0].geometry.location);
					marker = new google.maps.Marker({
						map: map,
						icon: image,
						shadow: shadow,
						shape: shape,
						position: results[0].geometry.location,
						title:entry.title
					});

					var info_window = new google.maps.InfoWindow({
						content: entry.content
					});

					google.maps.event.addListener(marker, 'click', function() 
					{
						info_window.open(map, marker);
					});

				} 
				else 
				{
					 alert("Geocode was not successful for the following reason: " + status);
				}
			});
		}
	}



	$('#map-layers .layer').click(function()
	{
		/* When I click a layer checkbox do this stuff */
		var layerID = parseInt($(this).attr('id'));
		/* Get the ID of the checkbox that was checked.
		This number corresponds to the order of the layers in our "uc.overlayMaps" array */

		if ($(this).attr('checked'))
		{
			/* Just using jQuery to see if the checkbox was checked or unchecked.
			In this case it was checked, add the layer */

			var overlayMap = new google.maps.ImageMapType(overlayMaps[layerID]);
			/* Create a new ImageMapType with the ImageMapTypeOptions (http://code.google.com/apis/maps/documentation/v3/reference.html#ImageMapTypeOptions)
			we've stored in our "uc.overlayMaps" array */

			map.overlayMapTypes.setAt(layerID,overlayMap);
			/* Use our "layerID" to tell the map where in the "overlayMapTypes" array to insert our new layer */
		}
		else
		{
			/* The checkbox was unchecked, remove the layer */
			if (map.overlayMapTypes.getLength()>0)
			{
				map.overlayMapTypes.setAt(layerID,null);
				/* Use our "layerID" to tell the map where to set to null
				If we use "removeAt" instead of "setAt" our array length gets funky so
				we just switch the layer out with a null. This effectively removes the layer.*/
			}
		}
		}).removeAttr('checked');

		for (i = 0; i < overlayMaps.length; i++)
		{
			map.overlayMapTypes.push(null);
		}

		var slide_int = null;

		$(function()
		{
			$('#slider').slider(
			{
				animate: true,
				step: 0.1,
				min: 0,
				orientation: 'horizontal',
				max: 1,
				start: function(event, ui){
					$('#current_value').empty();
					slide_int = setInterval(update_slider, 10);
				},
				slide: function(event, ui){
					setTimeout(update_slider, 10);
				},
				stop: function(event, ui){
					clearInterval(slide_int);
				    slide_int = null;
	                    if ($('#map-layers .layer').attr('checked')){
	                        var overlayMap = new google.maps.ImageMapType(overlayMaps[0]);
	                         map.overlayMapTypes.setAt(0,overlayMap);
	                    }

				}
			});
		});
		function update_slider()
		{
		    var offset = $('.ui-slider-handle').offset();
		    var value = $('#slider').slider('option', 'value');
			if ($('#map-layers .layer').attr('checked'))
			{
				overlayMaps[0].opacity = value;
				//$('#current_value').text('Value is '+value).css({top:offset.top });
				// $('#current_value').fadeIn();
			}
		}
	}


	</script>
	
	<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

	<div class="breadcrumbs">
	<?php if(function_exists('bcn_display')) { bcn_display(); } ?>
	</div>
		<div id="lead-wrap">		
			<div id="fullscreen-lead">
			 <div id="content">
			</div>
		  	<div id="map_canvas" style="width:100%; height:100%; margin-top:16px"></div>
				<div id="lead-text">
					<a href="#article"><h1 class="post-title"><?php the_title(); ?></h1>
					<?php if( $sCustomSubtitle=get_post_meta($post->ID,"subtitle",true) ) echo '<h2 class="post-subtitle">'.$sCustomSubtitle.'</h2>'; ?><h1 class="post-title">⇣</h1></a>
				</div><!-- #lead-text -->
			</div><!-- #fullscreen-lead -->
		</div><!-- #lead-wrap -->

		<div id="page-wrap" class="full-width">
		<div id="page-head" >
			<span class='title'><?php the_title(); ?></span>
			<?php if($subtitle=get_post_meta($post->ID,'subtitle',true)) { ?> 
				<span class="tagline"><?php echo $subtitle; ?></span>
			<?php } ?>
		</div><!-- #page-head -->
		<?php endwhile; ?>
		<div id="page-body" >
			<?php echo make_gridster(array('queryValue'=>array('explore'), 'gridElementBorder'=>1,'maxColumns'=>3,'numberPosts'=>10, 'totalWidth'=>960, 'margins'=>array(8), 'padding'=>6, 'gridElementHeight'=>360, 'textSpace'=>180, 'hasInfoTab'=>TRUE, 'smartImagePlacement'=>FALSE,'useEmphasis'=>TRUE,'imagePosition'=>'top', 'flexibleHeight'=>FALSE, 'morelink'=>'http://newyork.thecityatlas.org/category/explore/', 'emphasisSchedule'=>array('3x2','0') ) ); ?>
		</div><!-- #page-body -->
		<script language="javascript">
		document.onload=OnLoad();
		</script>
		</html>
	<?php get_footer(); ?>
