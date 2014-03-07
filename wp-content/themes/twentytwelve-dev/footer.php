<?php
/**
 * The template for displaying the footer.
 *
 * Contains footer content and the closing of the
 * #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?>
	</div><!-- #main .wrapper -->
	<footer id="colophon" role="contentinfo" <?php if (is_home() || is_front_page()) { echo 'class="footer-front"'; } ?>">
		<div class="site-info">
		<p>Copyright © <?PHP echo date("Y"); ?> <a href="<?php echo home_url( '/' ) ?>" title="City Atlas <?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">City Atlas</a> &bull; <?php if ( is_user_logged_in() ) { ?><a href="<?php echo home_url( '/wp-admin/' ) ?>">Dashboard</a> | <a href="<?PHP echo wp_logout_url(); ?>">Logout</a><?PHP } else { ?><a href="<?PHP echo wp_login_url(); ?>">Login</a><?PHP } ?></p>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->
<?php 
	$thesections=array();
	$thesections[]=array('sectiontitle'=>'EXPLORE', 'slug'=>'explore', 'query'=>array('post_type'=>'tribe_events', 'meta_key'=>'_EventEndDate', 'orderby'=>'meta_value_num', 'order'=>'ASC','meta_value' => date('Y-m-d H:i', time()), 'meta_compare' => '>'  ), 'blurb'=>null);
	$thesections[]=array('sectiontitle'=>'LIFESTYLE', 'slug'=>'lifestyle', 'query'=>null, 'blurb'=>null);
	$thesections[]=array('sectiontitle'=>'PEOPLE', 'slug'=>'people', 'query'=>null, 'blurb'=>null);
	$thesections[]=array('sectiontitle'=>'LAB', 'slug'=>'lab', 'query'=>array('post_parent'=>42, 'post_type'=>'page'), 'blurb'=>null);

	echo '<script type="text/javascript">';
	echo getjsonsectiondata( $thesections ); 
	echo '</script>';
?>
<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-25730713-1']);
  _gaq.push(['_setDomainName', '.thecityatlas.org']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>    	

<?php wp_footer(); ?>
</body>
</html>