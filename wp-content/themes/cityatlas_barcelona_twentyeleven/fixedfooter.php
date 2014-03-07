<?php
	
	function get_menu_item($post_id, $title, $cat="", $customargs=null, $item_class="", $title_style="", $customlink=null, $blurb="")
	{
		$post=get_post($post_id);
		if(!isset($title)) $title=$post->post_title;
		if(!isset($cat)) $cat=get_the_category($post_id);
		else if($cat=="no cat") $cat="";
		if(empty($blurb)) $blurb=get_post_meta($post->ID, 'subtitle', true);
		if(!empty($customlink)) $link=$customlink;
		else $link=get_permalink($post->ID);
		$standardargs=array('queryValue'=>array($cat), 'numberPosts'=>5, 'maxColumns'=>3, 'totalWidth'=>960, 'margins'=>array(0,10,6,0), 'padding'=>array(4), 'gridElementHeight'=>60, 'textSpace'=>array(230,60), 'imagePosition'=>'left', 'gridElementBorder'=>1, 'hasInfoTab'=>false, 'useEmphasis'=>TRUE, 'textOptions'=>array('title'=>array('style'=>'h2','link'=>'yes please'), 'excerpt'=>array('style'=>'p', 'maxlength'=>50)), 'emphasisSchedule'=>array('1x2','0') );
		if(is_array($customargs)) $queryargs=$customargs;
		else $queryargs=$standardargs;
		if($title_style!="") $htmlMenuItem='<div class="menu-item "><span class="'.$title_style.'"><a class="menu-item" href="'. $link .'">'. $title .'</a></span><div class="sub-menu fader '.$item_class.'"><div id="post-'.$post_id.'" class="menu-content-popover">';
		else $htmlMenuItem='<div class="menu-item "><a class="menu-item" href="'. $link .'">'. $title .'</a><div class="sub-menu fader '.$item_class.'"><div id="post-'.$post_id.'" class="menu-content-popover">';
		
		$htmlMenuItem.='<div class="menu-content container_16">
							<div class="head"><span class="blurb">'. $blurb .'</span></div>
            				<div class="grid"> '. make_gridster( $queryargs ) .'</div> <!-- .grid_7 --> </div><!--.container_16-->';
		$htmlMenuItem.='</div></div></div>';
		return $htmlMenuItem;
	}

?>

<div id='fixedfooter'>

	<div class="menu_background ">
		<div id="fixedfooter-menu" class="sf-menu">
	<div id="bottom-logo"><a href="<?php echo home_url('/'); ?>">City Atlas</a></div><div id="bottom-logo-beta"><a href="<?php echo home_url('/beta-testing-feedback/'); ?>">[BETA]</a></div><!-- #bottom-logo -->
				<ul id="" class="" style="float:left; margin-left:20px;">
					<?php echo get_menu_item(34, null, "explore"); ?>
					<?php echo get_menu_item(36, null, "lifestyle"); ?>
					<?php echo get_menu_item(32, null, "people"); ?>
					<?php echo get_menu_item(42, "LAB", "atlas-lab", array('queryArgs'=>array('category_name'=>'atlas-lab','post_type'=>'post'), 'numberPosts'=>5, 'maxColumns'=>3, 'maxRows'=>2, 'totalWidth'=>960, 'margins'=>array(0,10,6,0), 'padding'=>array(4), 'gridElementHeight'=>60, 'textSpace'=>array(230,60), 'imagePosition'=>'left', 'gridElementBorder'=>1, 'hasInfoTab'=>false, 'useEmphasis'=>TRUE, 'textOptions'=>array('title'=>array('style'=>'h2','link'=>'yes please'), 'excerpt'=>array('style'=>'p', 'maxlength'=>50)), 'emphasisSchedule'=>array('1x2','0') ) ); ?>
					<?php echo get_menu_item(40, null, "archive"); ?>
				</ul><!--#menu-sites-->
		</div><!-- #site-menu -->
	</div><!-- #menu_background -->
	<div class='clear'>&nbsp</div>
	<div id="fixed-second-row" class="inner-shadow">

		<?php if ( function_exists('insert_newsticker') ) { insert_newsticker(); } ?>

	</div><!-- #fixed-second-row -->

</div><!-- #fixedfooter -->
