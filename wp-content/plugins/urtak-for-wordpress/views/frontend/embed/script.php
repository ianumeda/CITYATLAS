<!-- URTAK EMBED CODE START -->
<a name="embedded-urtak-<?php the_ID(); ?>"></a>
<script src="https://d39v39m55yawr.cloudfront.net/assets/clr.js" type='text/javascript'></script>
<div data-publication-key = '<?php esc_attr_e($publication_key, 'urtak'); ?>'
	 data-post-title      = '<?php esc_attr_e($title, 'urtak'); ?>'
	 data-post-permalink  = '<?php esc_attr_e($permalink, 'urtak'); ?>'
	 data-post-id         = '<?php esc_attr_e($post_id, 'urtak'); ?>'
	 <?php if(!empty($height)) { ?>
	 data-urtak-height    = '<?php esc_attr_e($height); ?>'
	 <?php } ?>
	 <?php if(!empty($width)) { ?>
	 data-urtak-width     = '<?php esc_attr_e($width); ?>'
	 <?php } ?>></div>
<!-- URTAK EMBED CODE END -->

<br />