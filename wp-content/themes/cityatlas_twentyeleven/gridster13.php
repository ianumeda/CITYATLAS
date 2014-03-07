<?php
// _ _ _ _ _ _ _ _ CHANGELOG _ _ _ _ _ _ _ :
// version 10: (1) added 'customlink' feature that looks for custom field by that name. If present the post preview links to that link instead of the permalink. (2) added parameters to make-image() and get_gridster_element_text() to change the links in those bits. (3) added 'linktarget' custom field listener to allow post to link to new "_blank" or other options. 
// LR9: (1) changed string number check to is_numeric() from (int) (2) if 'totalHeight' is set then div.gridster has height fixed at that value and overflow:hidden. This enables grid_elements to have variableHeight and overflow the container without affecting page layout.
// new in version LR8: (1) reprogrammed the image sourcing to include searching for WP attachments in the post and also scanning the content for <img> tags. (2) added code to allow turning off preview images by setting "preview-image" custom filed to "none"
// version 12: (1) generate the "more" link to the archive page with the gridster query
//   
// ----------- BEGIN gridster stuff -------------->>
function check_four_element_array($aArray)
{
	// this function checks and returns the syntax for an array that defines margins, padding, border, etc... anything that can have four different properties.
	// works just like CSS formatting where if length 1 all four are the same, if 2, then 0 is top/bottom and 1 is left/right and so on...
	if(is_array($aArray)) {
		if(count($aArray)>=4) return $aArray;
		elseif(count($aArray)==1) return array($aArray[0],$aArray[0],$aArray[0],$aArray[0]);
		elseif(count($aArray)==2) return array($aArray[0],$aArray[1],$aArray[0],$aArray[1]);
		elseif(count($aArray)==3) return array($aArray[0],$aArray[1],$aArray[2],$aArray[1]);
	} elseif(is_numeric($aArray)) return array($aArray,$aArray,$aArray,$aArray);
	else return array(0,0,0,0);
}
function get_gridster_parameters( $aGP ) 
{
	// this function returns the default grid parameter array with any customized parameters that are passed in

	// the following compile the QUERYARGS...
	if(!isset($aGP['queryArgs'])) { $aGP['queryArgs']=array(); }
	if(isset($aGP['taxQuery']) && !isset($aGP['queryArgs']['taxQuery'])) { 
		$aGP['queryArgs']['taxQuery']=$aGP['taxQuery']; 
	} else { /* nothing! */ }
	if(isset($aGP['queryValue']))
	{ 
		if(!isset($aGP['queryType'])) $aGP['queryType']='category__and';
		if($aGP['queryType']=='category__and' || $aGP['queryType']=='category__in' || $aGP['queryType']=='category__not_in') 
		{
			// this routine allows for the input of category slugs for queryValue
			$aCatIDS=array();
			foreach($aGP['queryValue'] as $cat)
			{
				if( !is_int($cat) ) 
				{
					if($oCat = get_category_by_slug($cat)) $aCatIDS[]=$oCat->term_id;
					// if((int)($oCat->term_id)==$oCat->term_id) $aCatIDS[]=$oCat->term_id;	
				} else $aCatIDS[]=$cat;
			}
			$aGP['queryValue']=$aCatIDS;
		} 
		if(!isset($aGP['queryArgs'][$aGP['queryType']])) { $aGP['queryArgs'][$aGP['queryType']]=$aGP['queryValue']; }
	} 
	// else { $aGP['queryArgs']['category__and']=""; } // i don't think we need any queryType or value for a valid query
	if(isset($aGP['order'])) { $aGP['queryArgs']['order']=$aGP['order']; } elseif(!isset($aGP['queryArgs']['order'])) { $aGP['queryArgs']['order']="DESC"; } // alt: ASC
	if(isset($aGP['orderby'])) { $aGP['queryArgs']['orderby']=$aGP['orderby']; } elseif(!isset($aGP['queryArgs']['orderby'])) { $aGP['queryArgs']['orderby']="date"; } // alt: id, author, title, modified, parent, rand, comment_count, menu_order, meta_value (requires "meta_key=keyname"), meta_value_num
	if(isset( $aGP['numberPosts'])) { $aGP['queryArgs']['numberPosts']=$aGP['numberPosts']; } 		
	elseif(!isset($aGP['queryArgs']['numberPosts'])) { $aGP['queryArgs']['numberPosts']=-1; }
	if(!isset( $aGP['posts_per_page'] ) ) $aGP['queryArgs']['posts_per_page']=-1;
	else $aGP['queryArgs']['posts_per_page']=$aGP['posts_per_page'];
	
	// ... QUERYARGS above
	
	// the following are the parameters pertaining to the gridster style/dimensions...
	if(!isset( $aGP['getStickies'] )) $aGP['getStickies'] = TRUE;	
	if(!isset( $aGP['maxColumns'] ) ) $aGP['maxColumns'] = 5;
	if(!isset( $aGP['maxRows'] ) ) $aGP['maxRows'] = 0; // maxrows of 0 means no max. goes until there are no more posts
	if(!isset( $aGP['gridDimensions'] ) ) $aGP['gridDimensions'] = array('columns'=>$aGP['maxColumns'],'rows'=>$aGP['maxRows']);
	if(!isset( $aGP['minRows'] ) ) $aGP['minRows'] = 1;
	if(!isset( $aGP['totalWidth'] ) ) $aGP['totalWidth'] = 960;
	if(!isset( $aGP['totalHeight'])) $aGP['totalHeight'] = 0; // zero is no limit
	
	if(!isset( $aGP['margins'] ) ) $aGP['margins'] = 10;
	$aGP['margins']=check_four_element_array($aGP['margins']); 
	
	if(!isset( $aGP['padding'] ) ) $aGP['padding'] = 0;
	$aGP['padding']=check_four_element_array($aGP['padding']);
	
	if(!isset( $aGP['gridElementBorder'] ) ) $aGP['gridElementBorder'] = 0;
	$aGP['gridElementBorder']=check_four_element_array($aGP['gridElementBorder']);
	
	if(!isset( $aGP['imageBorder'] ) ) $aGP['imageBorder'] = 1;
	$aGP['imageBorder']=check_four_element_array($aGP['imageBorder']);
	
	if(!isset( $aGP['gridElementHeight'] ) ) $aGP['gridElementHeight'] = 225; 
	if(!isset( $aGP['flexibleHeight'] )) $aGP['flexibleHeight'] == FALSE; // this simply turns off the height parameters in the text and grid container so the excerpt doesn't get cut off. 
	
	if(!isset( $aGP['imageSpace'] ) ) $aGP['imageSpace'] = array(120,80); // not in use yet. but should be. 
	if(!isset( $aGP['textSpace'] ) ) $aGP['textSpace'] = array(180,120); // [0] Width, [1] Height
	$aGP['textSpace']=check_four_element_array($aGP['textSpace']); // we only need two elements for Width and Height but this will do...

	if(!isset( $aGP['floatOrder'] ) ) $aGP['floatOrder'] = array('left','right');
	if(!isset( $aGP['postOffset'] ) ) $aGP['postOffset'] = 0;
	if(!isset( $aGP['imagePosition'] ) ) $aGP['imagePosition'] = "top";

	if(!isset( $aGP['textBorder'] ) ) $aGP['textBorder'] = 0;
	$aGP['textBorder']=check_four_element_array($aGP['textBorder']);
	
	if( $aGP['hasTopInfo'] ) { // by default there is no infoTab
		if(!isset($aGP['topInfoHeight'])) $aGP['topInfoHeight']=24;
		if(!isset($aGP['topInfoBorder'])) $aGP['topInfoBorder']=array(0,0,1,0); // infoTab by default has a top border of 1px
		$aGP['topInfoBorder']=check_four_element_array($aGP['topInfoBorder']);
		if(!isset($aGP['topTextOptions'])) $aGP['topTextOptions']=array('category'=>array('style'=>'h6','link'=>'yes please','maxlength'=>0)); //array('taxonomy'=>array('slug'=>'category', style'=>'h6','link'=>'link to post','maxlength'=>0));
	} else {
		$aGP['hasTopInfo'] = false;
		$aGP['topInfoHeight']=0;
		$aGP['topInfoBorder']=array(0,0,0,0); 
	}

	if( $aGP['hasBottomInfo'] ) { // by default there is no infoTab
		if(!isset($aGP['bottomInfoHeight'])) $aGP['bottomInfoHeight']=20;
		if(!isset($aGP['bottomInfoBorder'])) $aGP['bottomInfoBorder']=array(1,0,0,0); // infoTab by default has a top border of 1px
		$aGP['bottomInfoBorder']=check_four_element_array($aGP['bottomInfoBorder']);
	} else {
		$aGP['hasBottomInfo'] = false;
		$aGP['bottomInfoHeight']=0;
		$aGP['bottomInfoBorder']=array(0,0,0,0); 
	}
	if(!isset( $aGP['imageOptions'])) $aGP['imageOptions']=TRUE; // options are 'TRUE','FALSE','fullbleed'...
	if(!isset( $aGP['textOptions'] )) $aGP['textOptions']=array('title'=>array('style'=>'span','link'=>'link to post','maxlength'=>0),'date'=>array('style'=>'span','link'=>'link to post','maxlength'=>0),'excerpt'=>array('style'=>'span','link'=>'link to post','maxlength'=>0));
	else
	{
		// this enables you to enter a simple array of text elements you want such as: array('title','excerpt') 
		foreach($aGP['textOptions'] as $key=>$value) 
		{
			if(!is_array($value) || $value==null) $aGP['textOptions'][$value]=array('style'=>'span','link'=>'link to post','maxlength'=>0);
		}
		// if(isset($aGP['textOptions']['title']) && !is_array($aGP['textOptions']['title'])) $aGP['textOptions']['title']=array('style'=>'h1','link'=>'link to post','maxlength'=>0);
		// if(isset($aGP['textOptions']['subtitle']) && !is_array($aGP['textOptions']['subtitle'])) $aGP['textOptions']['subtitle']=array('style'=>'h2','link'=>'link to post','maxlength'=>0);
		// if(isset($aGP['textOptions']['date']) && !is_array($aGP['textOptions']['date'])) $aGP['textOptions']['date']=array('style'=>'h3','link'=>'link to post','maxlength'=>0);
		// if(isset($aGP['textOptions']['excerpt']) && !is_array($aGP['textOptions']['excerpt'])) $aGP['textOptions']['excerpt']=array('style'=>'p','link'=>'link to post','maxlength'=>0);		
	}
	if(!isset( $aGP['minElementDimensions'])) 
	{
		$aGP['minElementDimensions']=array('columns'=>1,'rows'=>1,'emphasis'=>0); // this is for when you have so many columns that it wouldn't make sense to have a 1-column element because the conent would be unreadable, ex: 16 columns over 960px, minimum cols would be maybe 3 or 4 
	}
	else
	{
		if(!isset($aGP['minElementDimensions']['emphasis'])) $aGP['minElementDimensions']['emphasis']=0;
		if(!isset($aGP['minElementDimensions']['rows'])) $aGP['minElementDimensions']['rows']=1;
		if(!isset($aGP['minElementDimensions']['columns'])) $aGP['minElementDimensions']['columns']=1;
	}
	if(!isset( $aGP['maxElementDimensions'])) $aGP['maxElementDimensions']=array('columns'=>$aGP['maxColumns'],'rows'=>0,'emphasis'=>10); // if you want to limit the size of any element, usually set to maxColumns and no limit on rows 
	if(!isset( $aGP['dimensionPriority'])) $aGP['dimensionPriority']='columns'; // alternative is 'rows', selects which D to favor in fitting
	if(!isset( $aGP['useEmphasis'] )) $aGP['useEmphasis']=TRUE; // this option turns off resizing of grid elements, everything 1x1
	if(!isset( $aGP['smartImagePlacement'] ) ) $aGP['smartImagePlacement']=FALSE;
	if(!isset( $aGP['linkWholeElement'])) $aGP['linkWholeElement']=TRUE;
	
	if(!isset($aGP['gridUnitWidth'])) 
	{
		$aGP['gridUnitWidth']=$aGP['totalWidth']/$aGP['maxColumns'];
		$aGP['gridUnitWidth']-=($aGP['margins'][1]+$aGP['margins'][3]+$aGP['gridElementBorder'][1]+$aGP['gridElementBorder'][3]); // subtract margins from element size
	}
	if(!isset($aGP['gridUnitHeight'])) 
	{
		// i'm changing totalHeight's purpose from setting the element dimensions to setting the whole display box parameters...
		// if($aGP['totalHeight']>0 && $aGP['maxRows']>0)
		// {
		// 	$aGP['gridUnitHeight']=$aGP['totalHeight']/$aGP['maxRows'];
		// }
		// else
		// {
			$aGP['gridUnitHeight']=($aGP['gridElementHeight']+$aGP['margins'][0]+$aGP['margins'][2]); // add margins to element height to get grid unit height
		// }
		$aGP['gridUnitHeight']-=($aGP['gridElementBorder'][0]+$aGP['gridElementBorder'][2]);	
	}
		
	if(!isset($aGP['flipFloatOnNewRow'])) 
	{
		$aGP['flipFloatOnNewRow'] = FALSE; // this setting causes the float to flip when a new row is encountered in the grid (even though it's not required to fill the layout). This creates an alternating layout in some configurations
	}
	if(!isset($aGP['minVideoSize'])) $aGP['minVideoSize']=array('width'=>120,'height'=>80);
	else
	{
		if(!isset($aGP['minVideoSize']['width'])) $aGP['minVideoSize']['width']=120;
		if(!isset($aGP['minVideoSize']['height'])) $aGP['minVideoSize']['height']=80;
	}
	if(!isset($aGP['emphasisSchedule'])) {} // emphasisSchedule is not required. the list sets the emphasis for a gridster and overrides any emphasis sent in from the post or otherwise. $aGP['emphasisSchedule']=array('1x2','1x1'))
	return $aGP;
}

function array_sort_by_key($array, $on, $order=SORT_ASC){
    $new_array = array();
    $sortable_array = array();

    if (count($array) > 0) {
        foreach ($array as $k => $v) {
            if (is_array($v)) { foreach ($v as $k2 => $v2) { if ($k2 == $on) $sortable_array[$k] = $v2; } } 
			else $sortable_array[$k] = $v;
        }
        switch ($order) { 
			case SORT_ASC: asort($sortable_array); break;
            case SORT_DESC: arsort($sortable_array); break;
        }
        foreach ($sortable_array as $k => $v) $new_array[$k] = $array[$k];
    }
    return $new_array;
}
function validate_emphasis($emphasis, $aGP)
{
	$dimensions=array();
	$aDimensions=explode('x',$emphasis);
	if(count($aDimensions)==1)
	{
		// if custom field "gridster_dimensions" is a single integer it will be attributed to emphasis otherwise, when split by 'x' it is COLSxROWSxEMPHASIS
		$dimensions['emphasis']=(int)$emphasis;
		$dimensions['columns']=$aGP['minElementDimensions']['columns'];
		$dimensions['rows']=$aGP['minElementDimensions']['rows'];		
	} else {
		$dimensions['columns']=(int)$aDimensions[0];
		$dimensions['rows']=(int)$aDimensions[1];
		$dimensions['emphasis']=(int)$aDimensions[2];
	}

 	// now validate dimensions with minElementDimensions...
	
	if(!isset($dimensions['columns']) || !is_numeric($dimensions['columns'])) $dimensions['columns']=$aGP['minElementDimensions']['columns'];
	if(!isset($dimensions['rows']) || !is_numeric($dimensions['rows'])) $dimensions['rows']=$aGP['minElementDimensions']['rows'];
	if(!isset($dimensions['emphasis']) || !is_numeric($dimensions['emphasis']) || $dimensions['emphasis']<$aGP['minElementDimensions']['emphasis']) $dimensions['emphasis']=$aGP['minElementDimensions']['emphasis'];
	return $dimensions;
}
function make_gridster($aGP)
{
	// this version uses only one query call and sorts the elements with arrays before finally calling make_gridster_element()
	
	$aGP=get_gridster_parameters($aGP);

	if($aGP['maxRows']<1) $nMaxPosts=-1;
	else $nMaxPosts=$aGP['maxColumns']*$aGP['maxRows'];

	$gridster=array();
	$stickies=array();
	
	$args=$aGP['queryArgs'];	
	$gridsterQ=new WP_Query();
	$gridsterQ->query($args);

	global $post;
	
	$position=0;
	if ( $gridsterQ->have_posts() ) while ($gridsterQ->have_posts()) : $gridsterQ->the_post();
	
		$gridster_dimensions=get_post_meta($post->ID,'gridster_dimensions',true);
		// dimensions passed through from post meta act as an emphasis boosting the dimensions of the grid element by 
		// N units in one dimension or the other. Each grid element starts at the "minimumElementDimensions" in gridster parameters 
		// if actual column x row dimensions are given those could be multiplied by minElementDimensions ... or not

		$dimensions=validate_emphasis($gridster_dimensions, $aGP);

		$stickypostposition=(int)get_post_meta($post->ID,'sticky',true);
		if($aGP['getStickies'] && !empty($stickypostposition) && $stickypostposition!=0) {
			$stickies[]=array('id'=>$post->ID, 'position'=>$stickypostposition, 'dimensions'=>$dimensions, 'emphasis'=>$emphasis);
		} else {
			$gridster[]=array('id'=>$post->ID, 'position'=>$position, 'dimensions'=>$dimensions, 'emphasis'=>$emphasis);
			$position++;
		}
	
	endwhile;
	
	wp_reset_query();
	
	// sort stickies ASC by stickyID
	// echo '<li>'; foreach($stickies as $key=>$value) echo $key.':['.$value['id'].':'.$value['position'].']';  echo '</li>';
	$stickies=array_sort_by_key($stickies,'position',SORT_ASC);
	$stickies=array_merge(array(),$stickies); // this gets the array indexes into the right order otherwise everything is bonkers
	
	// make sure stickyIDs are unique...
	for($i=1; $i<count($stickies); $i++) {
		$j=$i-1;
		if($stickies[$i]['position']<=$stickies[$j]['position']) $stickies[$i]['position']=$stickies[$j]['position']+1;
	}
	
	// place stickies into $gridster @ $stikies['position']
	for($i=0; $i<count($stickies); $i++) {
		array_splice($gridster, $stickies[$i]['position']-1, 0, array($stickies[$i]));
	}
	// echo '<li>'; foreach($gridster as $key=>$value) echo $key.':['.$value['id'].':'.$value['position'].']';  echo '</li>';

	$i=$aGP['postOffset'];
	while($i>0) {
		// this drops as many elements off of the front of the grister list as specified by 'postOffset'
		$shifted=array_shift($gridster); 
		$i--;
	}
	if($aGP['numberPosts']>0) 
	{
		while(count($gridster)>$aGP['numberPosts']) 
		{
			$popped=array_pop($gridster); // this lops off any excess grid elements over max
		}
	}	
	
	if(isset($aGP['emphasisSchedule']))
	{	
		$position=0;
		for($i=0; $i<count($gridster); $i++)
		{
			$gridster[$i]['dimensions']=validate_emphasis($aGP['emphasisSchedule'][$position],$aGP); // the last element in emphasisSchedule sets the emphasis for all subsequent grid elements...
			if(count($aGP['emphasisSchedule'])>$position) { $position++; }
		}
	}

	// perform the gridmap packing validation procedure... 
	$gridster=fill_gridmap($gridster,$aGP);
	
	$htmlGridster='<div class="gridster"';
	if($aGP['totalHeight']>0 && $aGP['flexibleHeight'])
	{
		$htmlGridster.=' style="height:'.$aGP['totalHeight'].'px; overflow:hidden; border-bottom:1px solid;"';
	} 
	$htmlGridster.=" >";
	
	for($i=0; $i<count($gridster); $i++) 
	{
		$post=get_post($gridster[$i]['id']);
		setup_postdata($post); // we're outside the loop so we have to do this
		$htmlGridster.=make_gridster_element($post, $gridster[$i]['dimensions'], $aGP,$gridster[$i]['float']);
	}

	$htmlGridster.='<div class="clear">&nbsp</div>'; // clear the floated elements within the gridster container
	$htmlGridster.="</div><!-- .gridster -->";
	if(!empty($aGP['morelink'])) 
	{ 
		// $archivetype=path_through_array('queryArgs.tax_query.relation.taxonomy', $aGP);
		// $archiveterms=path_through_array('queryArgs.tax_query.relation.terms', $aGP);
		// echo "archivetype=".$archivetype;
		// if(isset($archiveterms))
		// {
		// 	if(isset($archivetype)) $morelink=homeurl('/').$archivetype.'/'.$archiveterms[0];
		// 	else $morelink=homeurl('/category/').$archiveterms[0];
		// 	$htmlGridster.='<div class="morelink"><a href="'. $morelink .'">[ more... ]</a></div>';
		// }
		$htmlGridster.='<span class="more-link"><a href="'. $aGP['morelink'] .'">[ more... ]</a></span>';
	}
	return ($htmlGridster);

}
function init_gridmapLR($dimensions) 
{
	$new_gridmap=array();
	if($dimensions['rows']<1) $row=1;
	for($i=0; $i<$row; $i++)
	{
		$new_row=array();
		for($j=0; $j<$dimensions['columns']; $j++) 
		{
//			$new_row[]="empty"; // LR oriented gridmaps don't need to be populated with 'empty' 
		}
		$new_gridmap[]=($new_row);
	}
	return $new_gridmap;
}
function get_gridmapLR($gridmapL,$gridmapR,$dimensions)
{
	// function returns a gridmap that visually reflects the L and R gridmaps combined
	// if($dimensions['rows']<1) $row=1; 
	// else 
	$row=$dimensions['rows'];
	$col=$dimensions['columns'];
	$gridmapLR=array();
	// for($i=0; $i<$row; $i++)
	$i=0;
	while(array_key_exists($i,$gridmapL) || array_key_exists($i,$gridmapR)) 
	{
		$newrow=array();
		$j=0;
		while($j<$col) 
		{
			if(isset($gridmapL[$i][$j])) $newrow[$j]=$gridmapL[$i][$j];
			elseif(isset($gridmapR[$i][$j])) $newrow[$j]=$gridmapR[$i][$j];
			else $newrow[$j]='empty';
			$j++;
		}
		$gridmapLR[]=$newrow;
		$i++;
	}
	// echo "<div style='clear:both;'>&nbsp</div>";
	// echo_gridmap($gridmapL);
	// echo_gridmap($gridmapR);
	// echo_gridmap($gridmapLR); 
	// echo "<div style='clear:both;'>&nbsp</div>";
	return $gridmapLR;
}
function fill_gridmap($gridster,$aGP) 
{
	$limit=count($gridster)*2;
	// this function fills a gridmap's elements sequentially with the post id based on emphasis
	// $element is from $gridster array containing ['id']['position']['dimensions']
	
	$floatOrder=$aGP['floatOrder'];
	$gridmapleft=init_gridmapLR($aGP['gridDimensions']);
	$gridmapright=init_gridmapLR($aGP['gridDimensions']);
	
	for($k=0; $k<count($gridster); $k++)
	{
		$gridmap=get_gridmapLR($gridmapleft,$gridmapright,$aGP['gridDimensions']);
		$position=find_element_in_gridmap($gridmap,'empty',array('col'=>0,'row'=>0),$floatOrder[0]);		
		if(!$aGP['useEmphasis']) 
		{
			$gridster[$k]['dimensions']=$aGP['minElementDimensions'];
		}
		else 
		{
			$maxElementDimensions=get_max_dimension_at_grid_location($gridmap,$position,$aGP,$floatOrder[0]);
$gridster[$k]['dimensions']=get_grid_element_dimensions($gridster[$k]['dimensions'],$maxElementDimensions,$aGP['minElementDimensions'],$aGP['dimensionPriority']);
		}
		// echo '<div style="float:left; margin-right:20px;">'.$k.'('.$limit.'):<strong>ELEMENT ['.$gridster[$k]['id'].']:</strong></div>';
		// echo '<li> position= '; print_r($position); echo '</li>';
		// echo '<li> constraints= '; print_r($maxElementDimensions); echo '</li>';
		// echo '<li> dimensions= '; print_r($gridster[$k]['dimensions']); echo '</li>';
		// print_r($maxElementDimensions);
		$gridster[$k]['position']=$position; 
		$space_left_in_row=$maxElementDimensions['columns']-$aGP['gridDimensions']['columns'];
		if($space_left_in_row>0 && $space_left_in_row<$aGP['minElementDimensions']['columns'])
		{
			$gridster[$k]['dimensions']['columns']+=$space_left_in_row;
			// echo 'SPACE FILLED:'.$space_left_in_row;
		}
		$whatToDo=array();
		$element_on_left=$gridmap[$position['row']][0];
		$element_on_right=$gridmap[$position['row']][count($gridmap[$position['row']])-1];
		if ($gridster[$k]['dimensions']['rows']==1 
		|| ($position['newrow'] && $aGP['gridDimensions']['rows']<1) )
		{
			// we don't have to worry about elements 1 row tall or the first element in the gridmap
			// also is the failsafe if the gridmap system goes on for too long
			if($position['newrow'] && $aGP['flipFloatOnNewRow']) 
			{
				$floatOrder=array_reverse($floatOrder);
			}
			$whatToDo=array('place');
		} 
		elseif($limit<=0)
		{
			$gridster[$k]['dimensions']=$aGP['minElementDimensions'];
			$whatToDo=array('place');
		}
		else 
		{
			// check the extent of the right and left ends of the row and switch float if neccessary
			$extent_of_current_element=$gridster[$k]['position']['row']+$gridster[$k]['dimensions']['rows'];			
			if(${'element_on_'.$floatOrder[0]} != 'empty') 
			{
				$extent_of_this_side = ${'element_on_'.$floatOrder[0]}['position']['row']+${'element_on_'.$floatOrder[0]}['dimensions']['rows'];
				if($extent_of_this_side < $extent_of_current_element) 
				{
					// now check if there's anything on the other side
					if(${'element_on_'.$floatOrder[1]} != 'empty')
					{
						$extent_of_other_side = ${'element_on_'.$floatOrder[1]}['position']['row']+${'element_on_'.$floatOrder[1]}['dimensions']['rows'];
						if($extent_of_other_side < $extent_of_current_element)
						{
							// now figure out which is longer, left or right and then shrink current element to fit
							if($extent_of_this_side >= $extent_of_other_side)
							{
								$gridster[$k]['dimensions']['rows']+=($extent_of_this_side-$extent_of_current_element);
							}
							else
							{
								$gridster[$k]['dimensions']['rows']+=($extent_of_other_side-$extent_of_current_element);
								$floatOrder=array_reverse($floatOrder);
							}
							$whatToDo=array('sort','place');
						}
						else
						{
							// nothing on the opposite end so flip float 
							$floatOrder=array_reverse($floatOrder);
							$whatToDo=array('sort','place');
						}
					}
					else
					{	
						// nothing on the opposite end so flip float
						$floatOrder=array_reverse($floatOrder);
						$whatToDo=array('repeat');
					}
				}
				else
				{
					// extent of this side will suit this element so sort and place
					$whatToDo=array('sort','place');
				}
			}
			else
			{
				$whatToDo=array('place');
			}
		}
		if($whatToDo[0]=='sort')
		{
			array_shift($whatToDo);
			// echo "SHIFTING ELEMENTS...";
			$m=$k; // let's not use this loop's key
			// while current element is taller than the ones before it in the same row move current element backwards 
			if($gridster[$m-1]['position']['row']==$gridster[$m]['position']['row'] 
			&& $gridster[$m-1]['dimensions']['rows'] < $gridster[$k]['dimensions']['rows'] 
			&& $gridster[$m-1]['float']==$floatOrder[0]) {
			
				while($m-1>=0 
				&& $gridster[$m-1]['position']['row']==$gridster[$m]['position']['row'] 
				&& $gridster[$m-1]['dimensions']['rows'] < $gridster[$k]['dimensions']['rows'] 
				&& $gridster[$m-1]['float']==$floatOrder[0]) {
					// searching until find a previous element that is equal or taller in rows OR until the beginning of current row...								
					$m--; 
				}
				// $m is now the index in gridster that has a lesser height than the current element. 
				// now place the current element into the location found in grister array and then map out each of the elements than now have to be shifted forward
	
				$gridster[$k]['position']=$gridster[$m]['position']; // current element's position takes found element's position
				for($q=$m; $q<$k; $q++)
				{
					${'gridmap'.$floatOrder[0]}=erase_element_from_gridmapLR(${'gridmap'.$floatOrder[0]}, $gridster[$q], $aGP['gridDimensions']);
					$gridster[$q]['position']['col']+=$gridster[$k]['dimensions']['columns']; // previous element's position is shifted up by current element's column dimension
				}
				$element_k=array_splice($gridster, $k, 1); 
				array_splice($gridster, $m, 0, $element_k); // move current element into found element's position in gridster 
			
				$k=$m-1; 	// instead of haphazardly placing the reordered elements into the gridmap at this point
							// we start iterating through this loop from the insertion point.. hopefully this won't create infinite loops :-!

				$limit--;
			}
		} 
		if($whatToDo[0]=='place')
		{
			array_shift($whatToDo);
			$gridster[$k]['float']=$floatOrder[0];
			${'gridmap'.$floatOrder[0]}=place_element_into_gridmapLR(${'gridmap'.$floatOrder[0]}, $gridster[$k], $aGP['gridDimensions']);
			// now check grid dimensions to see if full or if a new row needs to be added. 
			// compare the number of elements remaining to be placed with the number of grid spaces available -
			// $gridUnitsRemaining
			
		}
		if($whatToDo[0]=='repeat')
		{
			$k--;
		}
	}
	// $gridmap=get_gridmapLR($gridmapleft,$gridmapright,$aGP['gridDimensions']);
	return $gridster;
}

function leadingZeros($number,$totaldigits=3){
	for($i=strlen($number); $i<$totaldigits; $i++){
		$number='0'.$number;
	}
	return $number;
}
function echo_gridmap($gridmap)
{
	echo '<div style="float:left; width:300px; border:1px solid black;"><ul style="list-style:none;">';
	ksort($gridmap);
	foreach($gridmap as $key=>$value) 
	{ 
		echo '<li><span style="color:#e0e0e0;">'.$key.':</span>';
		ksort($value);
		foreach($value as $element=>$foo) 
		{
			$bar=leadingZeros($foo['id'],0);
			echo '<span style="color:#e0e0e0;">'.$element.':</span>['.$bar.']';
		}
		echo '</li>'; 
	}
	echo '</ul></div>';
}
function place_element_into_gridmapLR($gridmap,$element,$gridDimensions)
{
	if($element['float']=='right')
	{
		for($h=$element['position']['row']; $h<$element['position']['row']+$element['dimensions']['rows']; $h++)
		{
			for($i=$element['position']['col']-$element['dimensions']['columns']+1; $i<=$element['position']['col']; $i++) 
			{
				if(!isset($gridmap[$h])) $gridmap[$h]=array();
				$gridmap[$h][$i]=$element;
			}
		}
	}
	else
	{
		for($h=$element['position']['row']; $h<$element['position']['row']+$element['dimensions']['rows']; $h++)
		{
			for($i=$element['position']['col']; $i<$element['position']['col']+$element['dimensions']['columns']; $i++) 
			{
				if(!isset($gridmap[$h])) $gridmap[$h]=array();
				$gridmap[$h][$i]=$element;
			}
		}
	}
	return $gridmap;
}
function erase_element_from_gridmapLR($gridmap,$element,$gridDimensions)
{
	if($element['float']=='right')
	{
		for($h=$element['position']['row']; $h<$element['position']['row']+$element['dimensions']['rows']; $h++)
		{
			for($i=$element['position']['col']-$element['dimensions']['columns']+1; $i<=$element['position']['col']; $i++) 
			{
				unset($gridmap[$h][$i]);
			}
		}
	}
	else
	{
		for($h=$element['position']['row']; $h<=$element['position']['row']+$element['dimensions']['rows']; $h++)
		{
			for($i=$element['position']['col']; $i<=$element['position']['col']+$element['dimensions']['columns']; $i++) 
			{
				unset($gridmap[$h][$i]);
			}
		}
	}
	return $gridmap;
}
function element_fits_at_position($gridmap, $element_emphasis=1,$position=array('col'=>0,'row'=>0)) 
{
	$max=get_max_dimensions_at_grid_location($gridmap, $position);
}
function find_element_in_gridmap($gridmap,$needle='empty',$startposition=array('col'=>0,'row'=>0),$searchDirection='left') 
{
	if($searchDirection=='right')
	{
		// this searches for the last instance of a needle in a row
		for($i=$startposition['row']; $i<count($gridmap); $i++) 
		{
			for($j=$startposition['col']; $j<count($gridmap[$i]); $j++) 
			{
				if($gridmap[$i][$j]==$needle && $gridmap[$i][$j+1]!=$needle) break;
			}
			if($gridmap[$i][$j]==$needle && $gridmap[$i][$j+1]!=$needle) 
			{
				$newrow=FALSE;
				break;
			}
			else $startposition['col']=0; // reset col startposition
		}
		if($i>=count($gridmap) && $gridmap[$i][$j]!=$needle)
		{
			$i=count($gridmap);
			$j=count($gridmap[0])-1;
			$newrow=TRUE;
		}
	}
	else
	{
		for($i=$startposition['row']; $i<count($gridmap); $i++) 
		{
			for($j=$startposition['col']; $j<count($gridmap[$i]); $j++) 
			{
				if($gridmap[$i][$j]==$needle) break;
			}
			if($gridmap[$i][$j]==$needle) 
			{
				$newrow=FALSE;
				break;
			}
			else $startposition['col']=0; // reset col startposition
		}
		if($i>=count($gridmap) && $gridmap[$i][$j]!=$needle)
		{
			$i=count($gridmap);
		 	$j=0;
			$newrow=TRUE;
		}
	}
	// echo '<ul>FOUND "'.$needle.'" at ['.$i.']['.$j.']: '; echo_gridmap($gridmap); echo '</ul>';
	return array('col'=>$j,'row'=>$i,'newrow'=>$newrow);
}

function get_max_dimension_at_grid_location($gridmap,$position,$aGP,$searchDirection='left') 
{
	// gridmap is a multi-dimensional array of rows containing columns: row0[col[0] col[1] ...] row1[...]
	// this function finds the biggest rectangle in $gridmap that can fit starting with its top left corner at $position
	$startrow=$position['row'];//floor($position/count($gridmap[0])); // zero-based
	$startcol=$position['col'];//($position%count($gridmap[0]));
	$i=$startrow;
	if($searchDirection=="right")
	{
		$maxcol=1; // one-based
		$maxrow=count($gridmap); // start out with maximum possible dimensions given the start point
		while($i<$maxrow && $gridmap[$i][$startcol]=='empty') 
		{
			// echo '<li>['.$i.']['.$startcol.']=empty</li>';
			$j=$startcol-1;
			while($gridmap[$i][$j]=='empty' && $j>=0) 
			{ 
				// echo '<li>['.$i.']['.$j.']=empty </li>';
				$j--; 
			}
			if($maxcol<$j) $maxcol=$j; 
			$i++;
		}
		$returncols=abs($startcol-$maxcol);
	}
	else 
	{
		$maxcol=count($gridmap[$startrow]); // one-based
		$maxrow=count($gridmap); // start out with maximum possible dimensions given the start point
		while($i<$maxrow && $gridmap[$i][$startcol]=='empty') 
		{
			// echo '<li>['.$i.']['.$startcol.']=empty</li>';
			$j=$startcol+1;
			while($gridmap[$i][$j]=='empty' && $j<$maxcol) 
			{ 
				// echo '<li>['.$i.']['.$j.']=empty </li>';
				$j++; 
			}
			if($maxcol>$j) $maxcol=$j; 
			$i++;
		}
		$returncols=$maxcol-$startcol;
	}
	if($maxrow>$i) $maxrow=$i; // if the above while loop cuts out before count($gridmap) 
	if($aGP['maxElementDimensions']['rows']<1) $returnrows=0; else $returnrows=$maxrow-$startrow;
	if($aGP['maxElementDimensions']['columns']<1) $returncols=0;
	return array('columns'=>$returncols,'rows'=>$returnrows);
}
function get_grid_position_format($gridmap,$position,$format='ARRAY') {
	// this function returns $position in the $format selected. Used to validate grid position.
	if(is_array($position)) {
		if($format=="ARRAY") return $position;
		else return ( count($gridmap[0]) * $position['row'] + $position['col'] );
	} elseif (is_int($position)) {
		if($format=="ARRAY") {
			$startrow=floor($position/count($gridmap[0])); // zero-based
			$startcol=($position%count($gridmap[0]));
			return array('col'=>$startcol,'row'=>$startrow);
		} else return $position;
	} elseif($format=="INT") return 0;
	else return array('col'=>0,'row'=>0);
}
function validate_min_dimensions($dimensions,$minElementDimensions){
	if($dimensions['columns']<$minElementDimensions['columns']) $dimensions['columns']=$minElementDimensions['columns'];
	if($dimensions['rows']<$minElementDimensions['rows']) $dimensions['rows']=$minElementDimensions['rows'];
	return $dimensions;
}
function apply_emphasis($dimensions,$maxElementDimensions,$addPattern=array('columns','rows'))
{
	$addPattern=array('columns','rows');
	$theotherdimension=array_reverse($addPattern); // just to track what the dimensions are so we can refer to the other dimension
	// we'll start with the smaller dimension to make the shape more square... 
	if($dimensions[$addPattern[0]]>$dimensions[$theotherdimension[0]]) 
	{
		while($addPattern[0]!=$theotherdimension[0]) 
		{
			$shifted=array_shift($addPattern);
			$addPattern[]=$shifted;
			// WARNING THIS WILL FAIL IF THERE IS ONLY ONE DIMENSION
		}
	}
	$e=$dimensions['emphasis']; 
	while($e>0) 
	{
		// while($addPattern[0]!='columns' && $addPattern[0]!='rows') $erased=array_shift($addPattern);
		$shifted=array_shift($addPattern);
		if($shifted==$theotherdimension[0]) array_reverse($theotherdimension);
		if( $maxElementDimensions[$shifted]<1 || $maxElementDimensions[$shifted]-$dimensions[$shifted]>=1)  
		{
			// if there's space in the dimension we're trying to add to...
			if($e >= $dimensions[$theotherdimension[0]])
			{
				// if emphasis is >= the size of the dimension we're trying to fill
				$dimensions[$shifted]++;
				$e-=$dimensions[$theotherdimension[0]];
				$addPattern[]=$shifted; // if this dimension worked this time then add it to the end of the addPatten again otherwise leave it out 
			} 
			elseif (count($addPattern)==0) $e=-$e;
		} 
		elseif (count($addPattern)==0) $e=-$e;
	}
	// echo '<li> EMPHASIS_1: e='.$e; print_r($dimensions); echo '</li>';
	return $dimensions;
}

function get_grid_element_dimensions($dimensions,$maxElementDimensions,$minElementDimensions,$D1='columns')
{
	// this function returns a validated dimensions ARRAY of an element based on an emphasis integer or dimensions array and a $space contsraint
	// $dimensions can be an INT or ARRAY('columns','rows').
	// this also sets up the preferences for which size option the system will choose, wide vs tall
	// and if the dimensions can't be factored down to something that fits into the column width the dimensions will be reduced to one that can
	// echo '<li>get dimensions()...</li>';
	if(!isset($dimensions['columns0']))
	{
		// this saves the initial values of these guys in case a second round of sizing needs to happen for this element
		$dimensions['columns0']=$dimensions['columns'];
		$dimensions['rows0']=$dimensions['rows'];
		$dimensions['emphasis0']=$dimensions['emphasis'];
	} 
	else 
	{
		// if this is not the first time at getting dimensions reset to the initial values
		$dimensions['columns']=$dimensions['columns0'];
		$dimensions['rows']=$dimensions['rows0'];
		$dimensions['emphasis']=$dimensions['emphasis0'];
	}
	if($D1=='rows') { $D1=='rows'; $D2='columns'; }
	else { $D1=='columns'; $D2='rows'; } // primary and secondary dimensions govern which dimension the system tries to satisfy first

	// validate max min dimensions: if min is less than max then set min=max so that element fits within given space
	if($maxElementDimensions['columns']>0 && $minElementDimensions['columns']>$maxElementDimensions['columns']) 
	{
		$minElementDimensions['columns']=$maxElementDimensions['columns'];
	}
	if($maxElementDimensions['rows']>0 && $minElementDimensions['rows']>$maxElementDimensions['rows'])
	{
		$minElementDimensions['rows']=$maxElementDimensions['rows'];
	} 
	
	// first try applying emphasis
	$dimensionsE=apply_emphasis($dimensions,$maxElementDimensions);
	
	if( ($maxElementDimensions[$D1]<1 
		|| $maxElementDimensions[$D1] >= $dimensionsE[$D1])
		&& ( $maxElementDimensions[$D2]<1 || $maxElementDimensions[$D2]>=$dimensionsE[$D2] )) 
		{
		$dimensionsE=validate_min_dimensions($dimensionsE,$minElementDimensions);
		// echo '<li> EXIT_1: '; print_r($dimensionsE); echo '</li>';
		return $dimensionsE;
	} else {
		// if either of the dimension constraints is smaller than the corresponding element dimension (and not infinite) then calculate by emphasis
		// otherwise just return $dimensions
		$nDimensions=$dimensions['columns']*$dimensions['rows']+$dimensions['emphasis']; 
	}

	if((int)$nDimensions==1 || !is_numeric($nDimensions) ) {
		// echo '<li>[exit 2]: '.($dimensions==1).' || '.(!is_int($dimensions)).' </li>';
		return validate_min_dimensions(array('columns'=>1,'rows'=>1),$minElementDimensions);
	} elseif($maxElementDimensions[$D2]<1){
		// if the non-favored dimension is infinite...
		if($nDimensions<=$maxElementDimensions[$D1]) {
			// if emphasis is less or equal to D1 then return nD1=emphasis
			$nD1=$nDimensions;
			$nD2=1; 
		} else {
			// otherwise return nD2 = emphasis divided by D1 
			$nD1=$maxElementDimensions[$D1];
			$nD2=round($nDimensions/$maxElementDimensions[$D1]);
		}	
	} elseif($maxElementDimensions[$D1]<1) {
		// if the favored dimension is infinite then just return emphasis in that dimension
		$nD1=$nDimensions;
		$nD2=1; 
	} elseif($maxElementDimensions[$D1]*$maxElementDimensions[$D2]<=$nDimensions) {
		// if gridster dimension limits is less than emphasis then return limits
		$nD1=$maxElementDimensions[$D1];
		$nD2=$maxElementDimensions[$D2];
	} else {
		$nDivisibleBy=0;
		$dimensions_init=$nDimensions;
		while( $nDimensions>$maxElementDimensions[$D1] && $nDivisibleBy==0 ){
			for($i=$maxElementDimensions[$D1]; $i>1; $i--){
				if($nDimensions%$i==0 && $nDimensions/$i<=$maxElementDimensions[$D2]){
					$nDivisibleBy=$i;
					// echo '<li>[3]1 '.$dimensions.' is divisible by '.$i.'</li>';
					break;
				}
			}
			$nDimensions--; // size doesn't fit in space. reduce emphasis by one...
		} 
		if($nDivisibleBy!=0) $nDimensions++;
		$dimensions_1=$nDimensions; // this is stored in case the second search doesn't find anything
		if($nDivisibleBy==0) {
			// if nothing was divisible in the primary dimension try the secondary dimension
			$nDimensions=$dimensions_init; 
			while( $nDimensions>$maxElementDimensions[$D2] && $nDivisibleBy==0 ){
				for($i=$maxElementDimensions[$D2]; $i>1; $i--){
					if($nDimensions%$i==0 && $nDimensions/$i<=$maxElementDimensions[$D1]){
						$nDivisibleBy=$i;
						// reverse D1 and D2 to report correctly
						$switch=$D1;
						$D1=$D2;
						$D2=$switch;
						// echo '<li>[3]2 swtich,'.$dimensions.' is divisible by '.$i.'</li>';
						break;
					}
				}
				$nDimensions--; // size doesn't fit in space. reduce emphasis by one...
			}
			if($nDivisibleBy!=0) $nDimensions++;
			$dimensions_2=$nDimensions; 
		}
		if( $nDivisibleBy==0 ) {
			if($dimensions_1<$dimensions_2) {
				$switch=$D1;
				$D1=$D2;
				$D2=$switch;
				$nDimensions=$dimensions_2;
			} else $nDimensions=$dimensions_1;
			$nD1=$nDimensions;
			$nD2=1;
		} else {
			$nD1=$nDivisibleBy;
			$nD2=($dimensions/$nDivisibleBy);
		}
	}
	// echo '<li>'.$dimensions.', '.$columns.', '.$rows.' => '.$return.'</li>';
	// echo '<li>[exit 3]: </li>';
	$dimensions=array($D1=>$nD1, $D2=>$nD2, 'emphasis'=>$nEmphasis);
	// $dimensions=validate_min_dimensions($dimensions,$minElementDimensions);
	return $dimensions;
}

function make_gridster_element ($post, $grid_element_dimensions=array('columns'=>1,'rows'=>1), $aGP, $grid_element_float="") 
{
	if(!is_array($grid_element_dimensions)) 
	{
		// validates old string dimensions format "##x##"
		if(strrpos($grid_element_dimensions, "x")) 
		{
			$breakup=implode('x',$grid_element_dimensions);
			$grid_element_dimensions=array('columns'=>$breakup[0],'rows'=>$breakup[1]);	
		}
		else $grid_element_dimensions=array('columns'=>1,'rows'=>1);
	} 
	global $post;
	setup_postdata($post);
	$customLink=get_post_meta($post->ID,'customlink', TRUE);
	$customLinkTarget=get_post_meta($post->ID, 'linktarget', TRUE);
	if(!empty($customLink)) { $sPermalink=$customLink; }
	else { $sPermalink=get_permalink($post->ID); }
	if(!empty($customLinkTarget)) { $linkTarget=$customLinkTarget; }
	else { 
		if(!empty($customLink)) { $linkTarget="_blank"; /*default target for customlinks is _blank */ }
		else $linkTarget="_self";
	}
	if( $aGP['hasBottomInfo'] ) 
	{
		$aGP['textOptions']['date']['hide']=true;
		$sText=get_gridster_element_text( $post, $aGP['textOptions'], $customLink, $linkTarget);
	} 
	else $sText=get_gridster_element_text($post, $aGP['textOptions'], $customLink, $linkTarget);
	$nColumns=$grid_element_dimensions['columns'];
	$nRows=$grid_element_dimensions['rows'];

	$nThisGridElementWidth=$aGP['gridUnitWidth']*$nColumns+($nColumns-1)*($aGP['margins'][1]+$aGP['margins'][3]+$aGP['gridElementBorder'][1]+$aGP['gridElementBorder'][3]); // add margins and borders to compensate for what would be spaces between grid elements additional grid width 
	$nThisGridElementHeight=$aGP['gridUnitHeight']*$nRows+($nRows-1)*($aGP['margins'][0]+$aGP['margins'][2]+$aGP['gridElementBorder'][0]+$aGP['gridElementBorder'][2]); // add margins to compensate for additional grid height

	// starting point image dimensions, then we subtract depending on configuration ...
	$nImageDivWidth=$nThisGridElementWidth-$aGP['padding'][1]-$aGP['padding'][3]-$aGP['imageBorder'][1]-$aGP['imageBorder'][3]; 
	$nImageDivHeight=$nThisGridElementHeight-$aGP['padding'][0]-$aGP['padding'][2]-$aGP['imageBorder'][1]-$aGP['imageBorder'][3] -$aGP['bottomInfoHeight']-$aGP['bottomInfoBorder'][0]-$aGP['bottomInfoBorder'][2]-$aGP['topInfoHeight']-$aGP['topInfoBorder'][0]-$aGP['topInfoBorder'][2];

	if($aGP['imageOptions']!=FALSE)
	{
		$arrImage=get_image_from_post_bamn($post,$nDivImageWidth,$nDivImageHeight);
		if(empty($arrImage)) { $aGP['imageOptions']=FALSE; }
		if($sVideo=get_post_meta($post->ID, 'video', true)) 
		{
			$videoType='video';
		}
		elseif($sVideo=get_post_meta($post->ID, 'vimeo', true))
		{
			$videoType='vimeo';
		}
		elseif($sVideo=get_post_meta($post->ID, 'youtube', true))
		{
			$videoType='youtube';
		}
	} 
	
	if($aGP['smartImagePlacement'] && $aGP['imageOptions'] && $arrImage[1]>0 && $arrImage[2]>0)
	{
		// NOTE: text size taken as minimum
		// 1) figure out where image should be given its dimensions compared to grid element box
		// 2) calculate image size as scaled and if text should grow to fill space or if image div needs squeezing
		
		if($arrImage[1]/$arrImage[2] < ($nImageDivWidth-$aGP['textSpace'][0])/($nImageDivHeight-$aGP['textSpace'][1]))
		{
			if($aGP['imagePosition']!='right') $aGP['imagePosition']='left'; // placement default is "left" unless "right" is selected
		}
		else
		{
			// image is wider than box - position is top or bottom
			if($aGP['imagePosition']!='bottom') $aGP['imagePosition']='top'; // placement default is "top" unless "bottom" is selected
		}
		
		if($aGP['imagePosition']=="left"||$aGP['imagePosition']=="right")
		{
			// image and text are side-by-side so we subtract the text-space from the grid width to get the image width and the image height is the height of the grid element...
			$nImageDivWidth -= ($aGP['textSpace'][0]+$aGP['textBorder'][1]+$aGP['textBorder'][3]); // because the grid element's padding is translated to the content wrapper's margins we must subtract the padding amounts from the image div dimensions		
			$imageWidthAsScaled=$arrImage[1]*($nImageDivHeight/$arrImage[2]);
			$imageHeightAsScaled=$nImageDivHeight;
			if($imageWidthAsScaled<$nImageDivWidth)
			{
				$aGP['textSpace'][0]+=$nImageDivWidth-$imageWidthAsScaled;
				$nImageDivWidth=$imageWidthAsScaled;
			}
			if(isset($aGP['nImageDivHeight']) && is_numeric($aGP['nImageDivHeight'])) 
			{
				// if we have a custom imageDivHeight then we must base the textHeight off of what would have been the image height
				$nImageDivHeight=$aGP['nImageDivHeight'];
				$aGP['textSpace'][1]=$nThisGridElementHeight-$aGP['imageBorder'][0]-$aGP['imageBorder'][2]; 
			}
			else 
			{
				$aGP['textSpace'][1]=$nImageDivHeight+$aGP['padding'][2]+$aGP['imageBorder'][0]+$aGP['imageBorder'][2]; // text should run all the way to infoTab unlike imageDiv
			}
		} 
		else 
		{
			// when the image and text are on top of one another the grid element height is divided between the image and text...
			$nImageDivHeight -= ($aGP['textSpace'][1]+$aGP['textBorder'][0]+$aGP['textBorder'][2]); 
			$nImageDivHeight+=$aGP['padding'][2]; // this is because the padding from the content wrapper is transferred to the infoTab div when present.
			$aGP['textSpace'][0]=$nImageDivWidth;
			$imageWidthAsScaled=$nImageDivWidth;
			$imageHeightAsScaled=$arrImage[2]*($nImageDivWidth/$arrImage[1]);
			if($imageHeightAsScaled<$nImageDivHeight)
			{
				$aGP['textSpace'][1]+=$nImageDivHeight-$imageHeightAsScaled;
				$nImageDivHeight=$imageHeightAsScaled;
			}
		}		
	}
	else 
	{
		// THIS IS FOR STANDARDIZED IMAGE SIZING AND PLACEMENT (NOT SMART)
		if($aGP['imagePosition']=="left"||$aGP['imagePosition']=="right")
		{
			// image and text are side-by-side so we subtract the text-space from the grid width to get the image width and the image height is the height of the grid element...
			$nImageDivWidth -= ($aGP['textSpace'][0]+$aGP['textBorder'][1]+$aGP['textBorder'][3]); // because the grid element's padding is translated to the content wrapper's margins we must subtract the padding amounts from the image div dimensions		
			if(isset($aGP['nImageDivHeight']) && is_numeric($aGP['nImageDivHeight'])) {
				$nImageDivHeight=$aGP['nImageDivHeight'];
				$aGP['textSpace'][1]=$nThisGridElementHeight-$aGP['imageBorder'][0]-$aGP['imageBorder'][2]; // if we have a custom imageDivHeight then we must base the textHeight off of what would have been the image height
			} else {
				// $nImageDivHeight-=// image div height reduced by 2x padding on bottom to create even space between infoTab and image element
				if($aGP['imageOptions']) $aGP['textSpace'][1]=$nImageDivHeight+$aGP['padding'][2]+$aGP['imageBorder'][0]+$aGP['imageBorder'][2]; // text should run all the way to infoTab unlike imageDiv
				else $aGP['textSpace'][1]=$nImageDivHeight+$aGP['imageBorder'][0]+$aGP['imageBorder'][2]+$aGP['padding'][2];
			}
		} else {
			// when the image and text are on top of one another the grid element height is divided between the image and text...
			$nImageDivHeight -= ($aGP['textSpace'][1]+$aGP['textBorder'][0]+$aGP['textBorder'][2]); 
			$aGP['textSpace'][0]=$nImageDivWidth;
			$nImageDivHeight+=$aGP['padding'][2]; // this is because the padding from the content wrapper is transferred to the infoTab div when present.
			if(!$aGP['imageOptions']||empty($arrImage[0])) $aGP['textSpace'][1]+=$nImageDivHeight+$aGP['imageBorder'][0]+$aGP['imageBorder'][2]; // if there is no image text will take its space 
		}
	}
	
	
	if(!isset($grid_element_float) || $grid_element_float=="") $sFloat="left";
	else $sFloat=$grid_element_float;//$aGP['floatOrder'][0];
	// if($grid_element_dimensions['columns']==1 && $grid_element_dimensions['rows']==1) 
	// {
	// 	$nImageWidth=$nImageDivWidth;
	// 	$nImageHeight=$nImageDivHeight;
	// } 
	// elseif( $nColumns>$nRows )
	// {
	// 	$nImageWidth=$nImageDivWidth;
	// 	$nImageHeight=$nImageDivHeight;
	// } 
	// else 
	// {
	// 	$nImageWidth="";
	// 	$nImageHeight=$nImageDivHeight; // make_image() shouldn't have both width and height passed in
	// } 
	// markup grid element with category ids for styling
	$aCategorylist=wp_get_post_categories($post->ID);
	$sCategorylist=implode(" category-", $aCategorylist);
	$sCategorylist=' category-'.$sCategorylist;
	if($aGP['flexibleHeight']) 
	{
		$nThisGridElementHeight="";
		$aGP['textSpace'][1]="";
	}
	$htmlGridElement='<div class="grid_element'.$sCategorylist.'" style="border-width:'.$aGP['gridElementBorder'][0].'px '.$aGP['gridElementBorder'][1].'px '.$aGP['gridElementBorder'][2].'px '.$aGP['gridElementBorder'][3].'px; width:'.$nThisGridElementWidth.'px; height:'.$nThisGridElementHeight.'px; margin:'.$aGP['margins'][0].'px '.$aGP['margins'][1].'px '.$aGP['margins'][2].'px '.$aGP['margins'][3].'px; padding:0; float: '.$sFloat.'; overflow:hidden;';
	if($aGP['imageOptions']==='fullbleed' && empty($sVideo))
	{
		$htmlGridElement.=' background:url('.$arrImage[0].') no-repeat center center; -webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover; background-size: cover;';
	}
	$htmlGridElement.=' " >';
	// the grid element's "padding" is translated to the content wrapper's "margins" to fulfill the design requirements associated with the info_tab and if hasbottomInfo the padding will be moved from the content_wrapper to the info_tab DIV

	if($aGP['linkWholeElement']!=FALSE) 
	{
		if($linkTarget=="_self") $linkWholeElement=' onClick="document.location=\''. $sPermalink .'\';"';
		else $linkWholeElement=' onClick="window.open(\''. $sPermalink .'\');"';
	}
	if($aGP['hasTopInfo'])
	{
		$htmlGridElement.='<div class="grid_top_info" style="width:'.($nThisGridElementWidth).'px; height:'.$aGP['topInfoHeight'].'px; border-width:'.$aGP['topInfoBorder'][0].'px '.$aGP['topInfoBorder'][1].'px '.$aGP['topInfoBorder'][2].'px '.$aGP['topInfoBorder'][3].'px ;">';
		$htmlGridElement.=get_gridster_element_text($post,$aGP['topTextOptions'], $customLink, $linkTarget);
		$htmlGridElement.='</div><!-- .grid_top_info -->';
	}
	
	if($aGP['hasBottomInfo'] && !$aGP['flexibleHeight']) $htmlGridElement.='<div class="grid_element_content_wrapper" style="margin:'.$aGP['padding'][0].'px '.$aGP['padding'][1].'px 0px '.$aGP['padding'][3].'px; padding:0;" '.$linkWholeElement.' >'; 
	else $htmlGridElement.='<div class="grid_element_content_wrapper" style="margin:'.$aGP['padding'][0].'px '.$aGP['padding'][1].'px '.$aGP['padding'][2].'px '.$aGP['padding'][3].'px; padding:0;" '.$linkWholeElement.' >'; 

	$sFloatStyle="left";
	$imageStyle='float:'. $sFloatStyle .'; width:'. $nImageDivWidth .'px; height:'.$nImageDivHeight.'px; border-width:'.$aGP['imageBorder'][0].'px '.$aGP['imageBorder'][1].'px '.$aGP['imageBorder'][2].'px '.$aGP['imageBorder'][3].'px; overflow:hidden;';

	if($sVideo && $nImageDivWidth >= $aGP['minVideoSize']['width'] && $nImageDivHeight >= $aGP['minVideoSize']['height'])
	{
		// default on video display if size requirements are met
		// videos are displayed at imageDiv dimensions not with offset so there's nothing hidden.	
		
		$htmlArt='<div class="fit_image" style="'. $imageStyle .'" >';
		$htmlArt.=make_video($sVideo,$nImageDivWidth,$nImageDivHeight,$videoType);
		$htmlArt.='</div><!-- .fit_image -->';
	}
	elseif( isset($arrImage[0]) && $aGP['imageOptions']===TRUE )
	{ 
		if($linkWholeElement) $htmlArt=get_fit_image($arrImage,$nImageDivWidth,$nImageDivHeight,null,null, $imageStyle);
		else $htmlArt=get_fit_image($arrImage,$nImageDivWidth,$nImageDivHeight,$sPermalink,$linkTarget, $imageStyle);
	}
	if(isset($htmlArt))
	{
		// GRID ELEMENT HAS ART...
		if($aGP['imagePosition']=="bottom"||$aGP['imagePosition']=="right")
		{
			$htmlGridElement.='<div class="grid_text_wrapper" style="border-width:'.$aGP['textBorder'][0].'px '.$aGP['textBorder'][1].'px '.$aGP['textBorder'][2].'px '.$aGP['textBorder'][3].'px; float:'.$sFloatStyle.'; width:'.$aGP['textSpace'][0].'px; height:'.$aGP['textSpace'][1].'px; overflow:hidden;" ><div class="grid_element_text image_on_'.$aGP['imagePosition'].'">'.$sText.'</div><!-- .grid_element_text --></div><!-- .grid_text_wrapper -->';
			$htmlGridElement.=$htmlArt;
		} 
		else 
		{
			$htmlGridElement.=$htmlArt;
			$htmlGridElement.='<div class="grid_text_wrapper" style="border-width:'.$aGP['textBorder'][0].'px '.$aGP['textBorder'][1].'px '.$aGP['textBorder'][2].'px '.$aGP['textBorder'][3].'px; float:'.$sFloatStyle.'; width:'.$aGP['textSpace'][0].'px; height:'.$aGP['textSpace'][1].'px; overflow:hidden;" ><div class="grid_element_text image_on_'.$aGP['imagePosition'].'">'.$sText.'</div><!-- .grid_element_text --></div><!-- .grid_text_wrapper -->';
		}
	} 
	else 
	{
		// GRID ELEMENT TEXT_ONLY ...
		$htmlGridElement.='<div class="grid_text_wrapper" style="border-width:'.$aGP['textBorder'][0].'px '.$aGP['textBorder'][1].'px '.$aGP['textBorder'][2].'px '.$aGP['textBorder'][3].'px; width:'.($nThisGridElementWidth-$aGP['padding'][1]-$aGP['padding'][3]).'px; height:'.($aGP['textSpace'][1]).'px; overflow:hidden;" ><div class="grid_element_text_only">'.$sText.'</div><!-- .grid_element_text_only --></div><!-- .grid_text_wrapper -->';
	} 
	$htmlGridElement.='<div class="clear">&nbsp</div>'; // clear the floated elements within the gridster element
	$htmlGridElement.='</div><!-- .grid_element_content_wrapper -->'; 
	if($aGP['hasBottomInfo'])
	{
		$aComments=get_comments(array('post_id'=>$post->ID));
		$nComments=count($aComments);
		$htmlGridElement.='<div class="grid_bottom_info" style="width:'.($nThisGridElementWidth).'px; height:'.$aGP['bottomInfoHeight'].'px; ';
		if(!$aGP['flexibleHeight'])	$htmlGridElement.='margin:0 0 '.$aGP['padding'][2].'px;">';
		else $htmlGridElement.='margin:0;">';
		$htmlGridElement.='<span class="byline">By</span><a href="'.home_url('/author/').get_the_author($post->ID).'"><span class="author">'.get_the_author($post->ID).'</span></a>';
//		$htmlGridElement.='<span class="dateline">'. get_the_date('n M Y') .'</span>';
		if(comments_open($post->ID)) {$htmlGridElement.='<span class="comments-count">'.$nComments.'</span>';}
		$htmlGridElement.='<span class="continue-reading-meta-nav"><a href="'.$sPermalink.'" target="'.$linkTarget.'">&rarr;</a></span><span class="continue-reading"><a href="'.$sPermalink.'" target="'.$linkTarget.'">Continue reading</a></span>';
		$htmlGridElement.='</div><!-- .grid_bottom_info -->';
	}
	$htmlGridElement.='<div class="clear">&nbsp</div>'; // clear the floated elements within the gridster element
	$htmlGridElement.="</div><!-- .grid_element -->";
	
	return $htmlGridElement;
}


function get_gridster_element_text ($post,$aTextOptions=array('title'=>array('style'=>'h1','link'=>'link to post','maxlength'=>0),'date'=>array('style'=>'h3','link'=>'link to post','maxlength'=>0),'excerpt'=>array('style'=>'p','link'=>'link to post','maxlength'=>0)), $customLink=null, $linkTarget="_self") 
{
	// other options: taxonomy with an additional key=>slug
	// the third element in each text option array is the character limit on that text
	// --- this function returns the post text content in the order and in the style specified by aTextOptions

	$excerpt=get_the_excerpt($post->ID);
	$title=get_the_title($post->ID);
	$date=get_the_date();

	foreach($aTextOptions as $key=>$value)
	{
		if(!isset($value['style'])) $value['style']="span";
		if(!isset($value['maxlength'])) $value['maxlength']=0; // zero length is no limit
		elseif($value['maxlength']>strlen($$key)) $$key=substr($$key,0,$value['maxlength']);

		if($value['link']=="FALSE") $link=false; // text is not linked
		else 
		{
			if(!empty($customLink)) { $link=$customLink; }
			else $link=get_permalink($post->ID);
		}
		
		if($key==='taxonomy')
		{	
			$terms=wp_get_object_terms($post->ID,$value['slug']);
			if(!is_object($terms)) 
			{
				$termobject=$terms[0];
				$taxonomy=$termobject->name;
			}
		}
		elseif($key=='category')
		{
			$aCategorylist=wp_get_post_categories($post->ID);
			$category=get_cat_name($aCategorylist[0]);
		}
		elseif($key=='categoryroot')
		{
			// $aCatlist=get_category_parents(category,FALSE,',',TRUE); 
		}
		elseif($key=='tec_date')
		{
			$tec_date=the_event_start_date( $post->ID );
		}		
	}

	if( $subtitle=get_post_meta($post->ID,"subtitle",true) ) $bHasSubtitle=true; 
	$sText='';

	foreach($aTextOptions as $key => $value)
	{
		if($key!='subtitle' || $bHasSubtitle)
		{
			if(!$value['hide']) 
			{
				if($value['link']!=false) $sText.='<a href="'.$link.'" target="'. $linkTarget .'">';
				$sText.='<'.$value['style'].' class="gridster-'.$key.'">'.$$key.'</'.$value['style'].'>';
				if($value['link']!=false) $sText.='</a>';
			}
		}
	}
	return $sText;
}
// ----------- END gridster stuff ------------||

?>