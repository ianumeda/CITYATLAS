<?php 

function get_cityatlas_social() 
{
	echo '<div class="cityatlas-social-buttons"> <ul> <li>';
	if (function_exists('tweet_button')) 
	{ 
		tweet_button( get_permalink($post->ID) );
	}
	echo '</li> <li> <div class="g-plusone" width="250" data-size="medium" data-annotation="bubble"></div><script type="text/javascript"> (function() { var po = document.createElement(\'script\'); po.type = \'text/javascript\'; po.async = true; po.src = \'https://apis.google.com/js/plusone.js\'; var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(po, s); })(); </script> </li> ';
	if(function_exists('selfserv_shareaholic')) 
	{
		echo '<li> <div class="shareaholic-like-buttonset"> <a class="shareaholic-fblike" shr_layout="standard" shr_showfaces="true"  shr_send="true" shr_action="like" shr_href="'. get_permalink($post->ID) .'"></a> <a class="shareaholic-fbsend" shr_href="'. get_permalink($post->ID) .'"></a> </div> </li> ';
	}
	echo '</ul></div><!-- .cityatlas-social-buttons -->';
}

?>