<?php 
function getshortlink($url) {
    // Your bit.ly API credentials
    $bitly_login = "cityatlas";
    $bitly_key = "R_54a96df7d82286d064868007dbc08775";
    // Your twitter account names
    $twitter_via = "cityatlas";
    // optional: add a related account
    // $twitter_related = "";
    global $post;
    if (get_post_status($post->ID) == 'publish') 
	{
        if ((function_exists('curl_init') || function_exists('file_get_contents')) && function_exists('json_decode')) 
		{
            // shorten url
            if (get_post_meta($post->ID, 'bitly_short_url', true) == '') 
			{
                $short_url = null;
                $short_url = shorten_bitly($url, $bitly_key, $bitly_login);
                if ($short_url) add_post_meta($post->ID, 'bitly_short_url', $short_url);
            } else $short_url = get_post_meta($post->ID, 'bitly_short_url', true);
        }
		return $short_url;
    }
}

// convert file contents into string
function urlopen($url) {
    if (function_exists('curl_init')) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    } else {
        return file_get_contents($url);
    }
}

// bit.ly url shortening
function shorten_bitly($url, $bitly_key, $bitly_login) {
    if ($bitly_key && $bitly_login && function_exists('json_decode')) {
        $bitly_params = '?login=' . $bitly_login . '&apiKey=' .$bitly_key . '&longUrl=' . urlencode($url);
        $bitly_response = urlopen('http://api.j.mp/v3/shorten' . $bitly_params);
        if ($bitly_response) {
            $bitly_data = json_decode($bitly_response, true);
            if (isset($bitly_data['data']['url'])) {
                $bitly_url = $bitly_data['data']['url'];
            }
        }
    }
    return $bitly_url;
}

if(function_exists('gigya_signup_page')) {
	// gigya_signup_page is a function defined in gigya.php 
	$shareimage=get_image_from_post_bamn($post,null,null);
	require_once('simple_html_dom.php');
	if($post_dom = str_get_html(get_the_excerpt($post->ID))) $description = str_replace('"', '“', $post_dom->plaintext);
	else $description="";
    
	if(is_numeric($shareimage[0])) $shareimage[0]=get_ngg_image_url($shareimage[0]); // is ngg image ID. get URL from NGG functions
	echo '<script type="text/javascript">
	var act = new gigya.socialize.UserAction();
	act.setUserMessage("This is the user message");
	act.setTitle("'. get_the_title($post->ID) .'");
	act.setLinkBack("'. get_permalink($post->ID) .'");
	act.setDescription("'. $description .'");
	act.addActionLink("Read this", "'. get_permalink($post->ID) .'");
	act.addMediaItem({ type: "image", src: "'.$shareimage[0].'", href: "'. get_permalink($post->ID) .'" });
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
