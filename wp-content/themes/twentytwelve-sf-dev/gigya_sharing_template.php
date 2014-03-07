<?php 

if(function_exists('gigya_signup_page')) {
	// gigya_signup_page is a function defined in gigya.php 
	$shareimage=get_image_from_post_bamn($post,null,null);
	require_once('simple_html_dom.php');
	if($post_dom = str_get_html(get_the_excerpt($post->ID))) $description = str_replace('"', '“', $post_dom->plaintext);
	else $description="";
    // $shortlink=getshortlink(get_permalink($post->ID));
    $link=get_permalink($post->ID);
	if(is_numeric($shareimage[0])) $shareimage[0]=get_ngg_image_url($shareimage[0]); // is ngg image ID. get URL from NGG functions
	echo '<script type="text/javascript">
	var act = new gigya.socialize.UserAction();
	act.setUserMessage("This is the user message");
	act.setTitle("'. get_the_title($post->ID) .'");
	act.setLinkBack("'. $link .'");
	act.setDescription("'. $description .'");
	act.addActionLink("Read this", "'. $link .'");
	act.addMediaItem({ type: "image", src: "'.$shareimage[0].'", href: "'. $link .'" });
	var showShareBarUI_params=
	{ 
		containerID: "componentDiv",
		shareButtons: "Twitter-Tweet,Facebook,Pinterest,google-plusone,Share",
		userAction: act
	}
	</script>
	<div id="componentDiv"></div>
	<script type="text/javascript">
	   gigya.socialize.showShareBarUI(showShareBarUI_params);
	</script>';

} 
?>
