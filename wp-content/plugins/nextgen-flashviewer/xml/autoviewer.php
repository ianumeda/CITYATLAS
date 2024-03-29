<?php

/*
+----------------------------------------------------------------+
+	AutoViewer-XML V1.00
+	by Boris Glumpler
+   	required for NextGEN Gallery FlashViewer
+----------------------------------------------------------------+
*/

$wpconfig = realpath("../../../../wp-config.php");
if (!file_exists($wpconfig)) die; // stop when wp-config is not there

require_once($wpconfig);

function get_out_now() { exit; }
add_action('shutdown', 'get_out_now', -1);

global $wpdb;

$ngg_options    = get_option('ngg_options');
$ngg_fv_options = get_option('ngg_fv_options');
$siteurl	    = get_option('siteurl');


// get the gallery id
$galleryID = (int) attribute_escape($_GET['gid']);

// get the pictures
$thepictures = $wpdb->get_results("SELECT t.*, tt.* FROM $wpdb->nggallery AS t INNER JOIN $wpdb->nggpictures AS tt ON t.gid = tt.galleryid WHERE t.gid = '$galleryID' AND tt.exclude != 1 ORDER BY tt.$ngg_options[galSort] $ngg_options[galSortDir] ");
// no images, no output
if (!is_array($thepictures)) die;

// Create XML output
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("content-type:text/xml;charset=utf-8");

echo '<?xml version="1.0" encoding="UTF-8" ?>';
echo '<gallery frameColor="0x'.$ngg_fv_options['ngg_av_frame_color'].'" frameWidth="'.$ngg_fv_options['ngg_av_frame_width'].'" imagePadding="'.$ngg_fv_options['ngg_av_image_padding'].'" displayTime="'.$ngg_fv_options['ngg_av_display_time'].'" enableRightClickOpen="'.$ngg_fv_options['ngg_fv_enable_right_click_open'].'">';

if (is_array ($thepictures)){
	foreach ($thepictures as $picture) {

			$image = ABSPATH.$picture->path.'/'.$picture->filename;
			$size = getimagesize($image);
			$width = $size[0];
			$height = $size[1];
	
			echo "<image>";
			echo '<url>'.$siteurl.'/'.$picture->path.'/'.$picture->filename.'</url>';
			echo '<caption><![CDATA['.strip_tags(nggflash::internationalize($picture->description)).']]></caption>';
			echo '<width>'.$width.'</width>';
			echo '<height>'.$height.'</height>';
			echo "</image>\n";
	}
}

echo "</gallery>\n";
?>

