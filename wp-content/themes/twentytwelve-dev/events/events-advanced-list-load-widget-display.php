<?php 
/**
 * This is the template for the output of the events list widget. 
 * All the items are turned on and off through the widget admin.
 * There is currently no default styling, which is highly needed.
 *
 * You can customize this view by putting a replacement file of the same name (events-list-load-widget-display.php) in the events/ directory of your theme.
 *
 * When the template is loaded, the following vars are set: $start, $end, $venue, $address, $city, $state, $province'], $zip, $country, $phone, $cost
 * @return string
 */

// Vars set:
// '$event->AllDay',
// '$event->StartDate',
// '$event->EndDate',
// '$event->ShowMapLink',
// '$event->ShowMap',
// '$event->Cost',
// '$event->Phone',

// Don't load directly
if ( !defined('ABSPATH') ) { die('-1'); }

$event = array();
$tribe_ecp = TribeEvents::instance();
reset($tribe_ecp->metaTags); // Move pointer to beginning of array.
foreach($tribe_ecp->metaTags as $tag){
	$var_name = str_replace('_Event','',$tag);
	$event[$var_name] = tribe_get_event_meta( $post->ID, $tag, true );
}

$event = (object) $event; //Easier to work with.

ob_start();
if ( !isset($alt_text) ) { $alt_text = ''; }
post_class($alt_text,$post->ID);
$class = ob_get_contents();
ob_end_clean();
?>
<li <?php echo $class ?>>
	<div class="event">
	<?php 
		$thumbSize=60;
		$arrImage=get_image_from_post_bamn($post, $thumbSize, $thumbSize);
		if(!empty($arrImage)) { echo get_fit_image($arrImage,$thumbSize,$thumbSize,get_permalink($post->ID),"_self",'width:'.$thumbSize.'px; height:'.$thumbSize.'px; overflow:hidden;'); }
	?>
			<?php
				$space = false;
				$output = '';
			?> 
		<span class="title"><a href="<?php echo tribe_get_event_link($post); ?>"><?php echo $post->post_title ?></a></span>
		<span class="when">
			<?php echo tribe_get_event_date_format( tribe_get_start_date( $post->ID, $start ), tribe_get_end_date($post->ID) ); ?>
		</span>
		<span class="excerpt"><a href="<?php echo get_permalink($post->ID); ?>"><?php echo $post->post_excerpt; ?></a></span>
		<span class="loc"><a href="<?php echo tribe_get_event_link($post); ?>">
		<?php
			if ( $venue && tribe_get_venue() != '') {
				$output .= ( $space ) ? '<br />' : '';
				$output .= tribe_get_venue(); 
				$space = true;
			}

			if ( $address && tribe_get_address()) {
				$output .= ( $space ) ? '<br />' : '';
				$output .= tribe_get_address();
				$space = true;
			}

			if ( $city && tribe_get_city() != '' ) {
				$output .= ( $space ) ? '<br />' : '';
				$output .= tribe_get_city() . ', ';
				$space = true;
			}
			if ( $region && tribe_get_region()) {
				$output .= ( !$city ) ? '<br />' : '';
				$space = true;
				$output .= tribe_get_region();
			} else {
				$output = rtrim( $output, ', ' );
			}
			if ( $zip && tribe_get_zip() != '') {
				$output .= ( $space ) ? '<br />' : '';
				$output .= tribe_get_zip();
				$space = true;
			}
			if ( $country && tribe_get_country() != '') {
				$output .= ( $space ) ? '<br />' : ' ';
				$output .= tribe_get_country(); 
			}
			if ( $phone && tribe_get_phone() != '') {
				if($output) 
					$output .= '<br/>';
				$output .= tribe_get_phone(); 
			}
			if ( $cost && tribe_get_cost() != '') {		
				if($output) 
					$output .= '<br/>';
				$output .= __('Price:', 'tribe-events-calendar-pro') . ' ' . tribe_get_cost(); 
			}
			$output.='</a>';
			echo $output;
		?>
		</span><!-- .loc -->
		<div class="more"><a href="<?php echo tribe_get_event_link($post); ?>">read more &rarr;</a></div>
	</div><!-- .event -->
</li>
<?php $alt_text = ( empty( $alt_text ) ) ? 'alt' : ''; ?>
