<?php
/*
Template Name: Custom Feed Events
*/

function yoast_rss_date( $timestamp = null ) {
  $timestamp = ($timestamp==null) ? time() : $timestamp;
  echo date(DATE_RSS, $timestamp);
}

function yoast_rss_text_limit($string, $length, $replacer = '...') {
  $string = strip_tags($string);
  if(strlen($string) > $length)
    return (preg_match('/^(.*)\W.*$/', substr($string, 0, $length+1), $matches) ? $matches[1] : substr($string, 0, $length)) . $replacer;
  return $string;
}

if ( have_posts() ) while ( have_posts() ) : the_post();

	$feedcategory=get_post_meta($post->ID, 'feedcategory', TRUE);
	if(empty($feedcategory)) $feedcategory=null;
	$numposts=get_post_meta($post->ID, 'feedpostcount', TRUE);
	if(empty($numposts)) $numposts=-1;

endwhile;

$posts = query_posts( array('category_name'=>$feedcategory, 'posts_per_page'=>$numposts));

$lastpost = $numposts - 1;

header("Content-Type: application/rss+xml; charset=UTF-8");
echo '<?xml version="1.0"?>';
?><rss version="2.0">
<channel>
  <title>City Atlas <?php if(!empty($feedcategory)) { echo "'".$feedcategory."' "; } ?>RSS Feed</title>
  <link>http://newyork.thecityatlas.org/</link>
  <description>The latest from The City Atlas<?php if(!empty($feedcategory)) { echo " in the '".$feedcategory."' category"; } ?>.</description>
  <language>en-us</language>
  <pubDate><?php yoast_rss_date( strtotime($ps[$lastpost]->post_date_gmt) ); ?></pubDate>
  <lastBuildDate><?php yoast_rss_date( strtotime($ps[$lastpost]->post_date_gmt) ); ?></lastBuildDate>
  <managingEditor>info@thecityatlas.org</managingEditor>
<?php foreach ($posts as $post) { ?>
  <item>
    <title><?php echo get_the_title($post->ID); ?></title>
    <link><?php echo get_permalink($post->ID); ?></link>
    <description><?php echo '<![CDATA['.yoast_rss_text_limit($post->post_content, 500).'<br/><br/>Keep on reading: <a href="'.get_permalink($post->ID).'">'.get_the_title($post->ID).'</a>';
	if($address=get_post_meta($post->ID, 'address', TRUE) ) { echo '<span class="address">'. $address .'</span><!-- .address -->'; }
	//if($latlon=get_post_meta($post->ID, 'latlon', TRUE) ) { echo '<span class="latlon">'. $latlon .'</span>'; }
	echo ']]>';  ?></description>
    <pubDate><?php yoast_rss_date( strtotime($post->post_date_gmt) ); ?></pubDate>
    <guid><?php echo get_permalink($post->ID); ?></guid>
  </item>
<?php } ?>
</channel>
</rss>