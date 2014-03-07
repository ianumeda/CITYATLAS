<?php 
if(function_exists('gigya_signup_page')) {
	// gigya_signup_page is a function defined in gigya.php 
	$shareimage=get_image_from_post_bamn($post, null,null);
	require_once('simple_html_dom.php');
	if($post_dom = str_get_html(get_the_excerpt($post->ID))) $description = $post_dom->plaintext;
	else $description="";
    
	if(is_numeric($shareimage[0])) $shareimage[0]=get_ngg_image_url($shareimage[0]); // is ngg image ID. get URL from NGG functions
	echo '<script type="text/javascript">
	var act = new gigya.socialize.UserAction();
	act.setUserMessage("This is the user message");
	act.setTitle("City Atlas '. esc_attr( get_bloginfo( 'name', 'display' ) ) .'");
	act.setLinkBack("'. home_url( '/' ) .'");
	act.setDescription("'. esc_attr( get_bloginfo( 'name', 'display' ) ) .'");
	act.addActionLink("Your guide to sustainable living", "'. home_url('/') .'");
	act.addMediaItem({ type: "image", src: "http://sanfrancisco.thecityatlas.org/wp-content/themes/images/thecityatlaslogo-nocity.png", href: "'. home_url('/') .'" });
	var showShareBarUI_params_top=
	{ 
		containerID: "componentDivTop",
		shareButtons: "Twitter,Facebook,google-plusone,Share",
		showCounts: "none",
		iconsOnly: "true",
		userAction: act
	}
	</script>
	<div id="componentDivTop"></div>
	<script type="text/javascript">
	   gigya.socialize.showShareBarUI(showShareBarUI_params_top);
	</script>';

} 
?>
