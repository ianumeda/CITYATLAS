<?php 
/**
 * This is the template for the output of the events list widget. 
 * All the items are turned on and off through the widget admin.
 * There is currently no default styling, which is highly needed.
 *
 * You can customize this view by putting a replacement file of the same name (events-list-load-widget-display.php) in the events/ directory of your theme.
 *
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
		<span class="title"><a href="<?php echo get_permalink($post->ID) ?>"><?php echo $post->post_title; ?></a></span>
		<span class="excerpt"><a href="<?php echo get_permalink($post->ID) ?>"><?php echo $post->post_excerpt; ?></a></span>
		<span class="when"><a href="<?php echo get_permalink($post->ID) ?>"><?php 
			echo tribe_get_event_date_format($event->StartDate, $event->EndDate); 
			if($event->AllDay) { echo ' <span class="time">('.__('All Day','tribe-events-calendar').')</span>'; }
		 ?></a></span>
	<?php if(tribe_get_venue()) : ?><span class="venue"><a href="<?php echo get_permalink($post->ID) ?>"><?php echo tribe_get_venue( get_the_ID() ); ?></a></span><?php endif; ?>
	<?php if(tribe_address_exists( get_the_ID() )) : ?><span class="address"><a href="<?php echo get_permalink($post->ID) ?>"><?php echo tribe_get_full_address( get_the_ID() ); ?></a></span><?php endif; ?>
	</div>
</li>
<?php $alt_text = ( empty( $alt_text ) ) ? 'alt' : ''; ?>
