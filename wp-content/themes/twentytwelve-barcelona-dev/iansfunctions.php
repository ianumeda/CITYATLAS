<?php
function get_image_from_post_bamn($post, $nImageDivWidth, $nImageDivHeight)
{
	// this function looks at a post and tries to get an image from it By Any Means Neccessary (bamn)!
	// 1) look for 'preview-image' then 'image' then 'gallery' then 'image-sequence' custom fields
	// 2) then will look for post attachments
	// note: if post image parameters change or preferences change you can always rearrange this code to fit your needs
	// returns array('imgurl or nggimgid', width, height)
	if($sImage=get_post_meta($post->ID, 'preview-image', true)) { }
	elseif($sImage=get_post_meta($post->ID, 'image', true)) {  }
	elseif($galleryID=get_post_meta($post->ID,'gallery',true)) 
	{
		$aImageList=nggdb::get_ids_from_gallery($galleryID, $ngg_options['galSort'], $ngg_options['galSortDir']);
		$sImage=$aImageList[0];
	} 
	elseif($sImageSequence=get_post_meta($post->ID,'image_sequence',true))
	{
		$aSequence=explode(',', $sImageSequence);
		$sImage=$aSequence[0];
	} 
	if(!empty($sImage))
	{
		$arrImage=explode(' | ', $sImage); // syntax: URL | widthpx | heightpx
		if(is_numeric($arrImage[0])) {
			// image is ngg gallery id so get that data...
			//$imageURL=get_ngg_image_url($arrImage[0]); 	// is ngg image ID. get URL from NGG functions
			$aImageDimensions=get_ngg_image_dimensions($arrImage[0]);
			$arrImage[1]=$aImageDimensions[0];
			$arrImage[2]=$aImageDimensions[1];
			//$arrImage[0]=$imageURL;
		}
		if(count($arrImage==3)) return $arrImage;
	}
	// if no images found in custom fields
	// 1) seach post's attachments
	// 2) search posts content for <img> tags
	$attachmentargs = array( 'post_type' => 'attachment', 'numberposts' => 1, 'post_status' => null, 'post_parent' => $post->ID );
	if($attachments=get_posts($attachmentargs))
	{
		foreach($attachments as $attachment) 
		{
			$arrImage=wp_get_attachment_image_src( $attachment->ID, array($nImageDivWidth, $nImageDivHeight));
			break;
		}
	} 
	else
	{
		$arrIMGsFromContent=get_images_from_content($post->post_content);
		if( count($arrIMGsFromContent) > 0 ) 
		{
			$arrImage=$arrIMGsFromContent[0];
		}
		else
		{
			$arrImage=null;
		}
	}
	return $arrImage;
}

function make_image($sImage=null,$nW="",$nH="",$sLink="",$aCaptions=array(FALSE,'caption1','caption2'),$linkTarget="_self")
{
	// sImage can be a direct URL to the image or an image ID based on Nextgen gallery.
	if(isset($sImage))
	{
		if($nW=="" && $nH=="") $nW=500;
		if(is_numeric($sImage))
		{
			// if sImage is numeric we treat is as a nextgen gallery image ID
			if($nW!="")	$shortcode='[singlepic id='.$sImage.' w='.$nW.' link='.$sLink.']';
			else $shortcode='[singlepic id='.$sImage.' w= h='.$nH.' link='.$sLink.']';
			$image_img_tag=do_shortcode($shortcode);
		}
//		if(substr(strtolower($sImage),-3)=="jpg" || substr(strtolower($sImage),-3)=="png" || substr(strtolower($sImage),-3)=="gif") 
		else
		{
			if($sLink!="") $image_img_tag='<a href="'.$sLink.'" target="'. $linkTarget .'">';
			$image_img_tag.='<img src="'.$sImage.'" width="'.$nW.'" height="'.$nH.'" >';
			if($sLink!="") $image_img_tag.='</a>';
		} 
		if($aCaptions[0]) 
		{
			if($aCaptions[1]||$aCaptions[2]==null) 
			{
				$aDefaults=get_ngg_image_caption($sImage);
				$sCaption1=$aDefaults[0];
				$sCaption2=$aDefaults[1];
			}
			if($aCaptions[1]!=null) $sCaption1=$aCaptions[1];
			if($aCaptions[2]!=null) $sCaption2=$aCaptions[2];
			$image_img_tag.='<div class="image_subtext"><span class="caption1">'.$sCaption1.'</span><span class="caption2">'.$sCaption2.'</span></div>';
		}
		return $image_img_tag;
	} else return "";
}
function make_video($sVideo,$nWidth="",$nHeight="", $videoType=null) 
{
	if ( $sVideo ) 
	{
		if($videoType=='vimeo' || ($videoType==null && $sVideo === (int)$sVideo) )
		{
			// VIMEO video ids are only integers
			return '<iframe src="http://player.vimeo.com/video/'.$sVideo.'?title=0&amp;byline=0&amp;portrait=0" width="'.$nWidth.'" height="'.$nHeight.'" frameborder="0"></iframe>';
		}
		elseif($videoType=='stream' || ($videoType==null && substr($sVideo,0,8) == '[stream '))
		{
			// if $sVideo is a [stream] video tag
			if($nWidth!="" || $nHeight!="")
			{
				// find and replace the width and height parameters in the video tag...
				$sVideo=preg_replace("/width=[0-9]{0,4} /","width=$nWidth ",$sVideo);
				$sVideo=preg_replace("/height=[0-9]{0,4} /","height=$nHeight ",$sVideo);
			}
			return StreamVideo_Parse_content($sVideo);
		}
		elseif($videoType=='youtube' || ($videoType==null && $sVideo))
		{
			// YOUTUBE ids are string-digit combinations. Perhaps there's a better way to detect?
			return '<iframe width="'.$nWidth.'" height="'.$nHeight.'" src="http://www.youtube.com/embed/'.$sVideo.'?wmode=transparent" frameborder="0" allowfullscreen></iframe>';
		}
	}
	else return "";
}
function make_gallery($sGallery,$nImageWidth=490,$nImageHeight=640) {
	if( $sGallery ) {
		if( is_mobile() ) $sShortcode='[nggallery id="'.$sGallery.'" width="'.$nImageWidth.'"]';
		else $sShortcode='[simpleviewer id="'.$sGallery.'" width="'.$nImageWidth.'" height="'.$nImageHeight.'" ]';
		$the_gallery=do_shortcode($sShortcode);
		return $the_gallery;		
	} else return "";
}
function get_fit_image($arrImage, $fitW, $fitH, $sPermalink=null, $linkTarget="_self", $style="")
{
	if(empty($style)) { $style='width:'. $fitW .'px; height:'.$fitH.'px; -moz-box-shadow: 0px 0px 1px #000; -webkit-box-shadow: 0px 0px 1px #000; box-shadow: 0px 0px 1px #000; overflow:hidden; margin:6px;'; }
	// this function returns HTML of an <IMG> embedded within a <DIV> set to offset to make image centered in the surrounding div
	if( $arrImage[1]>0 && $arrImage[2]>0 ) 
	{
		if($fitW/$fitH >= $arrImage[1]/$arrImage[2])
		{
			// if the image div is more horizontal than the image then offset vertically...
			// find what the display height would be given the display width...
			$nImageDisplayWidth=$fitW;
			$nImageDisplayHeight=$arrImage[2]*$nImageDisplayWidth/$arrImage[1];
			// calculate the offset to center the image vertically...
			$nImageOffset=($nImageDisplayHeight-$fitH)*.5;
			$sStyleImageOffset='margin-top:-'.(int)$nImageOffset.'px;';
		} 
		else 
		{
			// otherwise the image div is more vertical than is the image, so we offset horizontally...
			// find what the display width would be given the display height...
			$nImageDisplayHeight=$fitH;
			$nImageDisplayWidth=$arrImage[1]*$fitH/$arrImage[2];
			// calculate the offset to center the image vertically...
			$nImageOffset=($nImageDisplayWidth-$fitW)*.5;
			$sStyleImageOffset='margin-left:-'.(int)$nImageOffset.'px;';
		}
	} 
	else $sStyleImageOffset=""; // i'm sure there's a jquery way of finding the image dimensions
	$htmlFitImage='<div class="fit_image" style="'. $style .'" >';
	$htmlFitImage.='<div class="image_offset" style="'.$sStyleImageOffset.'">';
	$htmlFitImage.=make_image($arrImage[0],$nImageDisplayWidth,$nImageDisplayHeight,$sPermalink,null,$linkTarget);
	$htmlFitImage.='</div><!-- .image_offset -->';
	$htmlFitImage.='</div><!-- .fit_image -->';
	return $htmlFitImage;
}

function get_ngg_image_dimensions($imageID){
	// this function gets the stored image dimensions of a nextgen gallery image
	$meta = new nggMeta($imageID);
    $foo = $meta->get_saved_meta();
	if($foo){
		$aDimensions=array();
		foreach($foo as $key => $value){
			if($key=='width') $aDimensions[0]=$value;
			if($key=='height') $aDimensions[1]=$value;
		}
		return $aDimensions;
	} else return null;
}
function get_ngg_image_caption($imageID){
	// this function gets the caption and description texts of a nextgen gallery image
    // get picturedata
    $picture = nggdb::find_image($imageID);
	$sByline=$picture->alttext = html_entity_decode( stripslashes(nggGallery::i18n($picture->alttext)) );
    $sCaption=$picture->description = html_entity_decode( stripslashes(nggGallery::i18n($picture->description)) );
	return array($sByline,$sCaption);
}
function get_ngg_image_url($imageID)
{
	// this function gets the stored image dimensions of a nextgen gallery image
    // get picturedata
    $picture = nggdb::find_image($imageID);
	$theURL=html_entity_decode( stripslashes(nggGallery::i18n($picture->imageURL)) );
	return $theURL;
}
function is_mobile(){
	$container = $_SERVER['HTTP_USER_AGENT'];
	$useragents = array("iphone", "ipod", "aspen", "dream", "incognito", "webmate");
	foreach ($useragents as $useragent) {
		if (eregi($useragent, $container)) {
			return true;
		}
	}
	return false;
}
function cant_handle_flash(){
	$container = $_SERVER['HTTP_USER_AGENT'];
	$useragents = array("iphone", "ipod", "ipad");
	foreach ($useragents as $useragent) {
		if (eregi($useragent, $container)) {
			return true;
		}
	}
	return false;
}

function list_terms ($postID=null, $tax=null, $taxname="", $html="")
{
	if($postID!=null)
	{
		$terms=wp_get_object_terms($postID,$tax);
	 	if(count($terms)>0)
		{
			$html='<ul>'.$taxname;
			foreach($terms as $term)
			{
				$html.='<li><a href="'.home_url('/'.$tax.'/').$term->slug.'">'.$term->name.'</a></li>';
			}
			$html.='</ul>';
		}
	}
	else
	{
		$terms=get_terms($tax);
	 	if(count($terms)>0)
		{
			$html='<ul>'.$taxname;
			foreach($terms as $term)
			{
				$html.='<li><a href="'.home_url('/'.$tax.'/').$term->slug.'">'.$term->name.'</a></li>';
			}
			$html.='</ul>';
		}
	}
	return $html;
}
function get_google_map_embed($postID, $nWidth=450)
{
	$nHeight=$nWidth;
	if(isset($postID))
	{
		$html="";
		$customfields=get_post_custom($postID);
		foreach($customfields as $key => $value) 
		{
			if ($key=="latlon")
			{
				foreach($value as $latlon)
				{
					$html.= '<iframe width="'.$nWidth.'" height="'.$nHeight.'" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.com/maps?q='.$latlon.'&amp;output=embed"></iframe><br /><small><a href="http://maps.google.com/maps?q='.$latlon.'&amp;source=embed" style="color:#0000FF;text-align:left">View Larger Map</a></small>';
				}
			}
			elseif($key=="address")
			{
				foreach($value as $address)
				{
					$html.= '<iframe width="'.$nWidth.'" height="'.$nHeight.'" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q='.$address.'&amp;output=embed"></iframe><br /><small><a href="http://maps.google.com/maps?f=q&amp;source=embed&amp;hl=en&amp;geocode=&amp;q='.$address.'" style="color:#0000FF;text-align:left">View Larger Map</a></small>';
				}
			}
		}
		return $html;
	}
	return null;
}

/* IMPORTANT !
* This function registers the same custom post type as the The Events Calendar plugin
* http://wordpress.org/extend/plugins/the-events-calendar/
* This also registers WordPress' native categories and tags while associating them with the Events Calendar Plugin
*/
add_action( 'init', 'add_calendar_taxonomy', 0 );
function add_calendar_taxonomy() {
register_post_type('tribe_events',array( // Registers Events Calendar Custom Post Type
'taxonomies' => array('category', 'post_tag', 'top_level_topics') // This registers the native WordPress taxonomies with The Events Calendar
));
}

function tribe_get_event_date_format($start, $end, $isintimeformatalready=FALSE)
{
	if(!$isintimeformatalready){
		// this expects $start and $end to be in the format of $event->StartDate and $event->EndDate
		$timestart=strtotime($start);
		$timeend=strtotime($end);
	}
	if(is_same_day($timestart,$timeend)) return '<span class="date">'.date('jS M Y',$timestart).'</span><span class="comma">, </span><span class="time">'.date('g:ia',$timestart).'-'.date('g:ia',$timeend).'</span>';
	elseif(is_same_month($timestart,$timeend)) return '<span class="date">'.date('jS',$timestart).'-'.date('jS M Y',$timeend).'</span><span class="comma">, </span><span class="time">'.date('g:ia',$timestart).'-'.date('g:ia',$timeend).'</span>';
	elseif(is_same_year($timestart,$timeend)) return '<span class="date">'.date('jS M',$timestart).'-'.date('jS M Y',$timeend).'</span>';
	else return '<span class="date">'.date('jS M Y',$timestart).'-'.date('jS M Y',$timeend).'</span>';
}
function is_same_day($time1, $time2)
{
	if(date('j',$time1) == date('j',$time2) && abs($time2-$time1)<86400) return TRUE;
	else return FALSE;
}
function is_same_month($time1, $time2)
{
	if(date('M',$time1) == date('M',$time2) && abs($time2-$time1)<2629744) return TRUE;
	else return FALSE;
}
function is_same_year($time1, $time2)
{
	if(date('Y',$time1) == date('Y',$time2) && abs($time2-$time1)<31556926) return TRUE;
	else return FALSE;
}
function get_images_from_content($html)
{
    require_once('simple_html_dom.php');
	if($post_dom = str_get_html($html))
	{
		$img_tags = $post_dom->find('img');
		$images = array();
		foreach($img_tags as $image) 
		{
			$images[] = array($image->src, $image->width, $image->height);
		}
		$post_dom->clear();
		return $images;
	}
	else return null;
}
// the following is to find keys in multidimensional arrays using a path like notation such as foo.bar.foo2.bar2
function path_through_array($path, $array, $delimiter = '.', $strict = false)
{
  $path_token = explode($delimiter, $path);
  $head = array_shift($path_token);

  if (isset($array[$head]) && (0 == count($path_token)))
  {
    return $array[$head];
  }
  else if (isset($array[$head]))
  {
    return path_through_array(implode($delimiter, $path_token), $array[$head], $delimiter, $strict);
  }
  else if ($strict == true)
  {
    return false;
  }

  foreach ($array as $key=>$value)
  {
    if (is_array($value))
    {
      $found = path_through_array($path, $value, $delimiter, $strict);

      if(false != $found)
      {
        return $found;
      }
    }
  }
  return false;
}
function escapehtmlchars($html){
	return str_replace(array('&','<','>','"','\''), array('\&','\<','\>','\"','\\\''), $html);
}
?>