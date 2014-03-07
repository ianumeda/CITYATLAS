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