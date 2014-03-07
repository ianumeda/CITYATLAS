<?php
/**
 * TwentyTen functions and definitions
 *
 * Sets up the theme and provides some helper functions. Some helper functions
 * are used in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * The first function, twentyten_setup(), sets up the theme by registering support
 * for various features in WordPress, such as post thumbnails, navigation menus, and the like.
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook. The hook can be removed by using remove_action() or
 * remove_filter() and you can attach your own function to the hook.
 *
 * We can remove the parent theme's hook only after it is attached, which means we need to
 * wait until setting up the child theme:
 *
 * <code>
 * add_action( 'after_setup_theme', 'my_child_theme_setup' );
 * function my_child_theme_setup() {
 *     // We are providing our own filter for excerpt_length (or using the unfiltered value)
 *     remove_filter( 'excerpt_length', 'twentyten_excerpt_length' );
 *     ...
 * }
 * </code>
 *
 * For more information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.
 *
 * @package WordPress
 * @subpackage Starkers
 * @since Starkers 3.0
 */

include 'gridster13.php'; // Ian's gridster!

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * Used to set the width of images and content. Should be equal to the width the theme
 * is designed for, generally via the style.css stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 540;

/** Tell WordPress to run twentyten_setup() when the 'after_setup_theme' hook is run. */
add_action( 'after_setup_theme', 'twentyten_setup' );

if ( ! function_exists( 'twentyten_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * To override twentyten_setup() in a child theme, add your own twentyten_setup to your child theme's
 * functions.php file.
 *
 * @uses add_theme_support() To add support for post thumbnails and automatic feed links.
 * @uses register_nav_menus() To add support for navigation menus.
 * @uses add_custom_background() To add support for a custom background.
 * @uses add_editor_style() To style the visual editor.
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_custom_image_header() To add support for a custom header.
 * @uses register_default_headers() To register the default custom header images provided with the theme.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since Twenty Ten 1.0
 */
function twentyten_setup() {

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// This theme uses post thumbnails
	add_theme_support( 'post-thumbnails' );

	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );

	// Make theme available for translation
	// Translations can be filed in the /languages/ directory
	load_theme_textdomain( 'twentyten', TEMPLATEPATH . '/languages' );

	$locale = get_locale();
	$locale_file = TEMPLATEPATH . "/languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Navigation', 'twentyten' ),
	) );

	// This theme allows users to set a custom background
	add_custom_background();

	// Your changeable header business starts here
	define( 'HEADER_TEXTCOLOR', '' );
	// No CSS, just IMG call. The %s is a placeholder for the theme template directory URI.
	define( 'HEADER_IMAGE', '%s/images/headers/path.jpg' );

	// The height and width of your custom header. You can hook into the theme's own filters to change these values.
	// Add a filter to twentyten_header_image_width and twentyten_header_image_height to change these values.
//	define( 'HEADER_IMAGE_WIDTH', apply_filters( 'twentyten_header_image_width', 940 ) );
//	define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'twentyten_header_image_height', 198 ) );

	// We'll be using post thumbnails for custom header images on posts and pages.
	// We want them to be 940 pixels wide by 198 pixels tall.
	// Larger images will be auto-cropped to fit, smaller ones will be ignored. See header.php.
	set_post_thumbnail_size( HEADER_IMAGE_WIDTH, HEADER_IMAGE_HEIGHT, true );

	// Don't support text inside the header image.
	define( 'NO_HEADER_TEXT', true );

	// Add a way for the custom header to be styled in the admin panel that controls
	// custom headers. See twentyten_admin_header_style(), below.
	add_custom_image_header( '', 'twentyten_admin_header_style' );

	// ... and thus ends the changeable header business.

	// Default custom headers packaged with the theme. %s is a placeholder for the theme template directory URI.
	// register_default_headers( array(
	// 	'berries' => array(
	// 		'url' => '%s/images/headers/starkers.png',
	// 		'thumbnail_url' => '%s/images/headers/starkers-thumbnail.png',
	// 		/* translators: header image description */
	// 		'description' => __( 'Starkers', 'twentyten' )
	// 	)
	// ) );
}
endif;

if ( ! function_exists( 'twentyten_admin_header_style' ) ) :
/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 *
 * Referenced via add_custom_image_header() in twentyten_setup().
 *
 * @since Twenty Ten 1.0
 */
function twentyten_admin_header_style() {
?>
<style type="text/css">
/* Shows the same border as on front end */
#headimg {
	border-bottom: 1px solid #000;
	border-top: 4px solid #000;
}
/* If NO_HEADER_TEXT is false, you would style the text with these selectors:
	#headimg #name { }
	#headimg #desc { }
*/
</style>
<?php
}
endif;

/**
 * Makes some changes to the <title> tag, by filtering the output of wp_title().
 *
 * If we have a site description and we're viewing the home page or a blog posts
 * page (when using a static front page), then we will add the site description.
 *
 * If we're viewing a search result, then we're going to recreate the title entirely.
 * We're going to add page numbers to all titles as well, to the middle of a search
 * result title and the end of all other titles.
 *
 * The site title also gets added to all titles.
 *
 * @since Twenty Ten 1.0
 *
 * @param string $title Title generated by wp_title()
 * @param string $separator The separator passed to wp_title(). Twenty Ten uses a
 * 	vertical bar, "|", as a separator in header.php.
 * @return string The new title, ready for the <title> tag.
 */
function twentyten_filter_wp_title( $title, $separator ) {
	// Don't affect wp_title() calls in feeds.
	if ( is_feed() )
		return $title;

	// The $paged global variable contains the page number of a listing of posts.
	// The $page global variable contains the page number of a single post that is paged.
	// We'll display whichever one applies, if we're not looking at the first page.
	global $paged, $page;

	if ( is_search() ) {
		// If we're a search, let's start over:
		$title = sprintf( __( 'Search results for %s', 'twentyten' ), '"' . get_search_query() . '"' );
		// Add a page number if we're on page 2 or more:
		if ( $paged >= 2 )
			$title .= " $separator " . sprintf( __( 'Page %s', 'twentyten' ), $paged );
		// Add the site name to the end:
		$title .= " $separator " . get_bloginfo( 'name', 'display' );
		// We're done. Let's send the new title back to wp_title():
		return $title;
	}

	// Otherwise, let's start by adding the site name to the end:
	$title .= get_bloginfo( 'name', 'display' );

	// If we have a site description and we're on the home/front page, add the description:
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title .= " $separator " . $site_description;

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		$title .= " $separator " . sprintf( __( 'Page %s', 'twentyten' ), max( $paged, $page ) );

	// Return the new title to wp_title():
	return $title;
}
add_filter( 'wp_title', 'twentyten_filter_wp_title', 10, 2 );

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * To override this in a child theme, remove the filter and optionally add
 * your own function tied to the wp_page_menu_args filter hook.
 *
 * @since Twenty Ten 1.0
 */
function twentyten_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'twentyten_page_menu_args' );

/**
 * Sets the post excerpt length to 40 characters.
 *
 * To override this length in a child theme, remove the filter and add your own
 * function tied to the excerpt_length filter hook.
 *
 * @since Twenty Ten 1.0
 * @return int
 */
function twentyten_excerpt_length( $length ) {
	return 40;
}
add_filter( 'excerpt_length', 'twentyten_excerpt_length' );

/**
 * Returns a "Continue Reading" link for excerpts
 *
 * @since Twenty Ten 1.0
 * @return string "Continue Reading" link
 */
function twentyten_continue_reading_link() {
	return '<span class="more-link"> <a href="'. get_permalink() . '">' . __( '<span class="more-link">Leer más </span><span class="meta-nav">&rarr;</span>', 'twentyten' ) . '</a></span>';
}

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and twentyten_continue_reading_link().
 *
 * To override this in a child theme, remove the filter and add your own
 * function tied to the excerpt_more filter hook.
 *
 * @since Twenty Ten 1.0
 * @return string An ellipsis
 */
function twentyten_auto_excerpt_more( $more ) {
	return ' &hellip;' . twentyten_continue_reading_link();
}
add_filter( 'excerpt_more', 'twentyten_auto_excerpt_more' );

/**
 * Adds a pretty "Continue Reading" link to custom post excerpts.
 *
 * To override this link in a child theme, remove the filter and add your own
 * function tied to the get_the_excerpt filter hook.
 *
 * @since Twenty Ten 1.0
 * @return string Excerpt with a pretty "Continue Reading" link
 */
function twentyten_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= twentyten_continue_reading_link();
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'twentyten_custom_excerpt_more' );

/**
 * Remove inline styles printed when the gallery shortcode is used.
 *
 * Galleries are styled by the theme in Twenty Ten's style.css.
 *
 * @since Twenty Ten 1.0
 * @return string The gallery style filter, with the styles themselves removed.
 */
function twentyten_remove_gallery_css( $css ) {
	return preg_replace( "#<style type='text/css'>(.*?)</style>#s", '', $css );
}
add_filter( 'gallery_style', 'twentyten_remove_gallery_css' );

if ( ! function_exists( 'twentyten_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own twentyten_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since Twenty Ten 1.0
 */
function twentyten_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case '' :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<div id="comment-<?php comment_ID(); ?>">
<!--
	>		<div class="comment-author vcard">
			<?php echo get_avatar( $comment, 40 ); ?>
		</div><!-- .comment-author .vcard -->
		
		<?php if ( $comment->comment_approved == '0' ) : ?>
			<em><?php _e( 'Your comment is awaiting moderation.', 'twentyten' ); ?></em>
			<br />
		<?php endif; ?>
		
		<div class="comment-body">
		<div class="comment-by"><?php printf( __( '%s <span class="says">says:</span>', 'twentyten' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?></div>
		<?php comment_text(); ?></div>

		<div class="comment-meta commentmetadata"><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
			<?php
				/* translators: 1: date, 2: time */
				printf( __( '%1$s at %2$s', 'twentyten' ), get_comment_date(),  get_comment_time() ); ?></a><?php edit_comment_link( __( '(Edit)', 'twentyten' ), ' ' );
			?>
		</div><!-- .comment-meta .commentmetadata -->

<!--  
		<div class="reply">
			<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
		</div>

.reply -->
	
	</div><!-- #comment-##  -->

	<?php
			break;
		case 'pingback'  :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'twentyten' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __('(Edit)', 'twentyten'), ' ' ); ?></p>
	<?php
			break;
	endswitch;
}
endif;

/**
 * Register widgetized areas, including two sidebars and four widget-ready columns in the footer.
 *
 * To override twentyten_widgets_init() in a child theme, remove the action hook and add your own
 * function tied to the init hook.
 *
 * @since Twenty Ten 1.0
 * @uses register_sidebar
 */
function twentyten_widgets_init() {
	// Area 1, located at the top of the sidebar.
	register_sidebar( array(
		'name' => __( 'Primary Widget Area', 'twentyten' ),
		'id' => 'primary-widget-area',
		'description' => __( 'The primary widget area', 'twentyten' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 2, located below the Primary Widget Area in the sidebar. Empty by default.
	register_sidebar( array(
		'name' => __( 'Secondary Widget Area', 'twentyten' ),
		'id' => 'secondary-widget-area',
		'description' => __( 'The secondary widget area', 'twentyten' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar(array('name'=>'Meta Widget',));
	register_sidebar(array('name'=>'Twitter Bubble',));
	register_sidebar(array('name'=>'Main Menu Events',));
	register_sidebar(array('name'=>'Events1',));
	register_sidebar(array('name'=>'Events2',));
	register_sidebar(array('name'=>'Events3',));
	register_sidebar(array('name'=>'Textile Lab',));
	register_sidebar(array('name'=>'Page Sidebar Widgets'));
}
/** Register sidebars by running twentyten_widgets_init() on the widgets_init hook. */
add_action( 'widgets_init', 'twentyten_widgets_init' );

/**
 * Removes the default styles that are packaged with the Recent Comments widget.
 *
 * To override this in a child theme, remove the filter and optionally add your own
 * function tied to the widgets_init action hook.
 *
 * @since Twenty Ten 1.0
 */
function twentyten_remove_recent_comments_style() {
	global $wp_widget_factory;
	remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
}
add_action( 'widgets_init', 'twentyten_remove_recent_comments_style' );

if ( ! function_exists( 'twentyten_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post—date/time and author.
 *
 * @since Twenty Ten 1.0
 */
function twentyten_posted_on() {
	printf( __( '<span class="%1$s">Publicado</span> %2$s <span class="meta-sep">por</span> %3$s', 'twentyten' ),
		'meta-prep meta-prep-author',
		sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><span class="entry-date">%3$s</span></a>',
			get_permalink(),
			esc_attr( get_the_time() ),
			get_the_date()
		),
		sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>',
			get_author_posts_url( get_the_author_meta( 'ID' ) ),
			sprintf( esc_attr__( 'View all posts by %s', 'twentyten' ), get_the_author() ),
			get_the_author()
		)
	);
}
endif;

if ( ! function_exists( 'twentyten_posted_in' ) ) :
/**
 * Prints HTML with meta information for the current post (category, tags and permalink).
 *
 * @since Twenty Ten 1.0
 */
function twentyten_posted_in() {
	// Retrieves tag list of current post, separated by commas.
	$tag_list = get_the_tag_list( '', ', ' );
	if ( $tag_list ) {
		$posted_in = __( 'This entry was posted in %1$s and tagged %2$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'twentyten' );
	} elseif ( is_object_in_taxonomy( get_post_type(), 'category' ) ) {
		$posted_in = __( 'This entry was posted in %1$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'twentyten' );
	} else {
		$posted_in = __( 'Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'twentyten' );
	}
	// Prints the string, replacing the placeholders.
	printf(
		$posted_in,
		get_the_category_list( ', ' ),
		$tag_list,
		get_permalink(),
		the_title_attribute( 'echo=0' )
	);
}
endif;


function get_image_from_post_bamn($post, $nImageDivWidth, $nImageDivHeight)
{
	// this function looks at a post and tries to get an image from it By Any Means Neccessary (bamn)!
	// 1) look for 'preview-image' then 'image' then 'gallery' then 'image-sequence' custom fields
	// 2) then will look for post attachments
	// note: if post image parameters change or preferences change you can always rearrange this code to fit your needs
	// returns array('imgurl or nggimgid', width, height)
	if($sImage=get_post_meta($post->ID, 'preview-image', true)) { }
	elseif($sImage=get_post_meta($post->ID, 'image', true)) {  }
	elseif($galleryID=get_post_meta($post->ID,'gallery',true)) 
	{
		$aImageList=nggdb::get_ids_from_gallery($galleryID, $ngg_options['galSort'], $ngg_options['galSortDir']);
		$sImage=$aImageList[0];
	} 
	elseif($sImageSequence=get_post_meta($post->ID,'image_sequence',true))
	{
		$aSequence=explode(',', $sImageSequence);
		$sImage=$aSequence[0];
	} 
	if(!empty($sImage))
	{
		$arrImage=explode(' | ', $sImage); // syntax: URL | widthpx | heightpx
		if(is_numeric($arrImage[0])) {
			// image is ngg gallery id so get that data...
			//$imageURL=get_ngg_image_url($arrImage[0]); 	// is ngg image ID. get URL from NGG functions
			$aImageDimensions=get_ngg_image_dimensions($arrImage[0]);
			$arrImage[1]=$aImageDimensions[0];
			$arrImage[2]=$aImageDimensions[1];
			//$arrImage[0]=$imageURL;
		}
		if(count($arrImage==3)) return $arrImage;
	}
	// if no images found in custom fields
	// 1) seach post's attachments
	// 2) search posts content for <img> tags
	$attachmentargs = array( 'post_type' => 'attachment', 'numberposts' => 1, 'post_status' => null, 'post_parent' => $post->ID );
	if($attachments=get_posts($attachmentargs))
	{
		foreach($attachments as $attachment) 
		{
			$arrImage=wp_get_attachment_image_src( $attachment->ID, array($nImageDivWidth, $nImageDivHeight));
			break;
		}
	} 
	else
	{
		$arrIMGsFromContent=get_images_from_content($post->post_content);
		if( count($arrIMGsFromContent) > 0 ) 
		{
			$arrImage=$arrIMGsFromContent[0];
		}
		else
		{
			$arrImage=null;
		}
	}
	return $arrImage;
}

function make_image($sImage=null,$nW="",$nH="",$sLink="",$aCaptions=array(FALSE,'caption1','caption2'),$linkTarget="_self")
{
	// sImage can be a direct URL to the image or an image ID based on Nextgen gallery.
	if(isset($sImage))
	{
		if($nW=="" && $nH=="") $nW=500;
		if(is_numeric($sImage))
		{
			// if sImage is numeric we treat is as a nextgen gallery image ID
			if($nW!="")	$shortcode='[singlepic id='.$sImage.' w='.$nW.' link='.$sLink.']';
			else $shortcode='[singlepic id='.$sImage.' w= h='.$nH.' link='.$sLink.']';
			$image_img_tag=do_shortcode($shortcode);
		}
//		if(substr(strtolower($sImage),-3)=="jpg" || substr(strtolower($sImage),-3)=="png" || substr(strtolower($sImage),-3)=="gif") 
		else
		{
			if($sLink!="") $image_img_tag='<a href="'.$sLink.'" target="'. $linkTarget .'">';
			$image_img_tag.='<img src="'.$sImage.'" width="'.$nW.'" height="'.$nH.'" >';
			if($sLink!="") $image_img_tag.='</a>';
		} 
		if($aCaptions[0]) 
		{
			if($aCaptions[1]||$aCaptions[2]==null) 
			{
				$aDefaults=get_ngg_image_caption($sImage);
				$sCaption1=$aDefaults[0];
				$sCaption2=$aDefaults[1];
			}
			if($aCaptions[1]!=null) $sCaption1=$aCaptions[1];
			if($aCaptions[2]!=null) $sCaption2=$aCaptions[2];
			$image_img_tag.='<div class="image_subtext"><span class="caption1">'.$sCaption1.'</span><span class="caption2">'.$sCaption2.'</span></div>';
		}
		return $image_img_tag;
	} else return "";
}
function make_video($sVideo,$nWidth="",$nHeight="", $videoType=null) 
{
	if ( $sVideo ) 
	{
		if($videoType=='vimeo' || ($videoType==null && $sVideo === (int)$sVideo) )
		{
			// VIMEO video ids are only integers
			return '<iframe src="http://player.vimeo.com/video/'.$sVideo.'?title=0&amp;byline=0&amp;portrait=0" width="'.$nWidth.'" height="'.$nHeight.'" frameborder="0"></iframe>';
		}
		elseif($videoType=='stream' || ($videoType==null && substr($sVideo,0,8) == '[stream '))
		{
			// if $sVideo is a [stream] video tag
			if($nWidth!="" || $nHeight!="")
			{
				// find and replace the width and height parameters in the video tag...
				$sVideo=preg_replace("/width=[0-9]{0,4} /","width=$nWidth ",$sVideo);
				$sVideo=preg_replace("/height=[0-9]{0,4} /","height=$nHeight ",$sVideo);
			}
			return StreamVideo_Parse_content($sVideo);
		}
		elseif($videoType=='youtube' || ($videoType==null && $sVideo))
		{
			// YOUTUBE ids are string-digit combinations. Perhaps there's a better way to detect?
			return '<iframe width="'.$nWidth.'" height="'.$nHeight.'" src="http://www.youtube.com/embed/'.$sVideo.'?wmode=transparent" frameborder="0" allowfullscreen></iframe>';
		}
	}
	else return "";
}
function make_gallery($sGallery,$nImageWidth=490,$nImageHeight=640) {
	if( $sGallery ) {
		if( is_mobile() ) $sShortcode='[nggallery id="'.$sGallery.'" width="'.$nImageWidth.'"]';
		else $sShortcode='[simpleviewer id="'.$sGallery.'" width="'.$nImageWidth.'" height="'.$nImageHeight.'" ]';
		$the_gallery=do_shortcode($sShortcode);
		return $the_gallery;		
	} else return "";
}
function get_fit_image($arrImage, $fitW, $fitH, $sPermalink=null, $linkTarget="_self", $style="")
{
	if(empty($style)) { $style='width:'. $fitW .'px; height:'.$fitH.'px; -moz-box-shadow: 0px 0px 1px #000; -webkit-box-shadow: 0px 0px 1px #000; box-shadow: 0px 0px 1px #000; overflow:hidden; margin:6px;'; }
	// this function returns HTML of an <IMG> embedded within a <DIV> set to offset to make image centered in the surrounding div
	if( $arrImage[1]>0 && $arrImage[2]>0 ) 
	{
		if($fitW/$fitH >= $arrImage[1]/$arrImage[2])
		{
			// if the image div is more horizontal than the image then offset vertically...
			// find what the display height would be given the display width...
			$nImageDisplayWidth=$fitW;
			$nImageDisplayHeight=$arrImage[2]*$nImageDisplayWidth/$arrImage[1];
			// calculate the offset to center the image vertically...
			$nImageOffset=($nImageDisplayHeight-$fitH)*.5;
			$sStyleImageOffset='margin-top:-'.(int)$nImageOffset.'px;';
		} 
		else 
		{
			// otherwise the image div is more vertical than is the image, so we offset horizontally...
			// find what the display width would be given the display height...
			$nImageDisplayHeight=$fitH;
			$nImageDisplayWidth=$arrImage[1]*$fitH/$arrImage[2];
			// calculate the offset to center the image vertically...
			$nImageOffset=($nImageDisplayWidth-$fitW)*.5;
			$sStyleImageOffset='margin-left:-'.(int)$nImageOffset.'px;';
		}
	} 
	else $sStyleImageOffset=""; // i'm sure there's a jquery way of finding the image dimensions
	$htmlFitImage='<div class="fit_image" style="'. $style .'" >';
	$htmlFitImage.='<div class="image_offset" style="'.$sStyleImageOffset.'">';
	$htmlFitImage.=make_image($arrImage[0],$nImageDisplayWidth,$nImageDisplayHeight,$sPermalink,null,$linkTarget);
	$htmlFitImage.='</div><!-- .image_offset -->';
	$htmlFitImage.='</div><!-- .fit_image -->';
	return $htmlFitImage;
}

function get_ngg_image_dimensions($imageID){
	// this function gets the stored image dimensions of a nextgen gallery image
	$meta = new nggMeta($imageID);
    $foo = $meta->get_saved_meta();
	if($foo){
		$aDimensions=array();
		foreach($foo as $key => $value){
			if($key=='width') $aDimensions[0]=$value;
			if($key=='height') $aDimensions[1]=$value;
		}
		return $aDimensions;
	} else return null;
}
function get_ngg_image_caption($imageID){
	// this function gets the caption and description texts of a nextgen gallery image
    // get picturedata
    $picture = nggdb::find_image($imageID);
	$sByline=$picture->alttext = html_entity_decode( stripslashes(nggGallery::i18n($picture->alttext)) );
    $sCaption=$picture->description = html_entity_decode( stripslashes(nggGallery::i18n($picture->description)) );
	return array($sByline,$sCaption);
}
function get_ngg_image_url($imageID)
{
	// this function gets the stored image dimensions of a nextgen gallery image
    // get picturedata
    $picture = nggdb::find_image($imageID);
	$theURL=html_entity_decode( stripslashes(nggGallery::i18n($picture->imageURL)) );
	return $theURL;
}

function get_post_PDFs($nPostID=null,$width="100%",$height=700,$html="") {
	if(isset($nPostID)) 
	{
		$aCustomFields=get_post_custom($nPostID);
		foreach($aCustomFields as $key => $value) 
		{
			if ($key=="pdf")
			{
				if(function_exists('gde_init'))
				{
					foreach($value as $pdf)
					{
						$html.='<div class="single_content_element">';
						$html.=do_shortcode('[gview file="'.$pdf.'" width="'.$width.'" height="'.$height.'"]');
						$html.='</div>';
					}
				} 
				else
				{
					$html.='<div class="single_content_element"><h2>PDF Documents:</h2>';
					foreach($value as $pdf)
					{
						$html.='<li class="pdf"><a href="'.$pdf.'" target="_blank">Download PDF</a></li>';
					}
					$html.='</div>';
				}
			} 
		}
		return $html;
	} else return null;
}
function get_post_galleries($nPostID=null,$width=540,$height=620) {
	if(isset($nPostID)) {
		$aCustomFields=get_post_custom($nPostID);
		foreach($aCustomFields as $key => $value) {
			if ($key=="gallery"){
				foreach($value as $gallery){
					$html.='<div class="single_content_element" style="width:'.$width.'px;">';
					$html.=make_gallery( $gallery ,$width,$height);
					$html.='</div>';
				}
			} 
		}
		return $html;
	} else return null;
}
function get_post_videos($nPostID=null,$width=540,$height=null) 
{
	if(isset($nPostID)) 
	{
		if($aCustomFields=get_post_custom($nPostID))
		{
			if($height==null) $height=$width/1.5;
			foreach($aCustomFields as $key => $value) 
			{
				if($key=='vimeo' || $key=='youtube')
				{
					foreach($value as $video)
					{
						$html.='<div class="single_content_element">';
						$html.=make_video( $video,$width,$height,$key);
						$html.='</div>';
					}
				}
				elseif($key=="video") 
				{
					foreach($value as $video)
					{
						if(function_exists('StreamVideo_Parse'))
						{
							$html.='<div class="single_content_element">';
							$html.=make_video( $video,$width,$height,$key );
							$html.='</div>';
						}
						else
						{
							// this is for use with ngg video extend using ngg image ID numbers
							$html.='<div class="single_content_element">';
							$html.='<span class="ngg-gallery-thumbnail nggve">[singlepic id='.$video.' width='.$width.' height='.$height.']</span>';
							$html.='</div>';
						}
					}
				}
			}
		}
		// these filter the content so shortcodes work...
		$html=apply_filters('the_content', $html);
		$html = str_replace(']]>', ']]&gt;', $html);
		
		return $html;
	} else return null;
}

function get_post_images($nPostID=null,$width=540,$height="") {
	if(isset($nPostID)) {
		$aCustomFields=get_post_custom($nPostID);
		foreach($aCustomFields as $key => $value) {
			if ($key=="image"){
				foreach($value as $image){
					$html.='<div class="post-image">';
					$html.=make_image( $image ,$width,null,null,array(TRUE));
					$html.='</div>';
				}
			} 
		}
		return $html;
	} else return null;
}
function get_post_image_sequence($nPostID=null,$width=540,$height="",$html=""){
	if(isset($nPostID) && $sSequence=get_post_meta($nPostID,'image_sequence',true)) {
		$aSequence=explode(',', $sSequence);
		if(count($aSequence)==1) $aSequence = nggdb::get_ids_from_gallery($galleryID, $ngg_options['galSort'], $ngg_options['galSortDir']);
		// the line above sets the image list based on an NGG gallery ID if the list contains only one element  
		foreach($aSequence as $nImageID) {
			$html.='<div class="single_content_element">';
			$html.=make_image( $nImageID ,$width,null,null,array(TRUE));
			$html.='</div>';
		}
		return $html;
	} else return null;	
}

function is_mobile(){
	$container = $_SERVER['HTTP_USER_AGENT'];
	$useragents = array("iphone", "ipod", "aspen", "dream", "incognito", "webmate");
	foreach ($useragents as $useragent) {
		if (eregi($useragent, $container)) {
			return true;
		}
	}
	return false;
}
function cant_handle_flash(){
	$container = $_SERVER['HTTP_USER_AGENT'];
	$useragents = array("iphone", "ipod", "ipad");
	foreach ($useragents as $useragent) {
		if (eregi($useragent, $container)) {
			return true;
		}
	}
	return false;
}

function getTwitterStatus($userid)
{
	$url = "http://twitter.com/statuses/user_timeline/$userid.xml?count=5";
	$xml = simplexml_load_file($url) or die("could not connect");
	if($xml!="could not connect") foreach($xml->status as $status) $text = $status->text;
	else $text=$xml;
	echo $text;
}

function list_terms ($postID=null, $tax=null, $taxname="", $html="")
{
	if($postID!=null)
	{
		$terms=wp_get_object_terms($postID,$tax);
	 	if(count($terms)>0)
		{
			$html='<ul>'.$taxname;
			foreach($terms as $term)
			{
				$html.='<li><a href="'.home_url('/'.$tax.'/').$term->slug.'">'.$term->name.'</a></li>';
			}
			$html.='</ul>';
		}
	}
	else
	{
		$terms=get_terms($tax);
	 	if(count($terms)>0)
		{
			$html='<ul>'.$taxname;
			foreach($terms as $term)
			{
				$html.='<li><a href="'.home_url('/'.$tax.'/').$term->slug.'">'.$term->name.'</a></li>';
			}
			$html.='</ul>';
		}
	}
	return $html;
}
// 
// function create_my_customfeed() {
// load_template( TEMPLATEPATH . 'customfeed.php'); // You'll create a your-custom-feed.php file in your theme's directory
// }
// add_action('do_feed_customfeed', 'create_my_customfeed', 10, 1); // Make sure to have 'do_feed_customfeed'
// 
// function custom_feed_rewrite($wp_rewrite) {
// $feed_rules = array(
// 'feed/(.+)' => 'index.php?feed=' . $wp_rewrite->preg_index(1),
// '(.+).xml' => 'index.php?feed='. $wp_rewrite->preg_index(1)
// );
// $wp_rewrite->rules = $feed_rules + $wp_rewrite->rules;
// }
// add_filter('generate_rewrite_rules', 'custom_feed_rewrite');
// 
function get_google_map_embed($postID, $nWidth=450)
{
	$nHeight=$nWidth;
	if(isset($postID))
	{
		$html="";
		$customfields=get_post_custom($postID);
		foreach($customfields as $key => $value) 
		{
			if ($key=="latlon")
			{
				foreach($value as $latlon)
				{
					$html.= '<iframe width="'.$nWidth.'" height="'.$nHeight.'" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.com/maps?q='.$latlon.'&amp;output=embed"></iframe><br /><small><a href="http://maps.google.com/maps?q='.$latlon.'&amp;source=embed" style="color:#0000FF;text-align:left">View Larger Map</a></small>';
				}
			}
			elseif($key=="address")
			{
				foreach($value as $address)
				{
					$html.= '<iframe width="'.$nWidth.'" height="'.$nHeight.'" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q='.$address.'&amp;output=embed"></iframe><br /><small><a href="http://maps.google.com/maps?f=q&amp;source=embed&amp;hl=en&amp;geocode=&amp;q='.$address.'" style="color:#0000FF;text-align:left">View Larger Map</a></small>';
				}
			}
		}
		return $html;
	}
	return null;
}

/* IMPORTANT !
* This function registers the same custom post type as the The Events Calendar plugin
* http://wordpress.org/extend/plugins/the-events-calendar/
* This also registers WordPress' native categories and tags while associating them with the Events Calendar Plugin
*/
add_action( 'init', 'add_calendar_taxonomy', 0 );
function add_calendar_taxonomy() {
register_post_type('tribe_events',array( // Registers Events Calendar Custom Post Type
'taxonomies' => array('category', 'post_tag', 'top_level_topics') // This registers the native WordPress taxonomies with The Events Calendar
));
}

function tribe_get_event_date_format($start, $end)
{
	// this expects $start and $end to be in the format of $event->StartDate and $event->EndDate
	$timestart=strtotime($start);
	$timeend=strtotime($end);
	if(is_same_day($timestart,$timeend)) return '<span class="date">'.date('jS M Y',$timestart).'</span>, <span class="time">'.date('g:ia',$timestart).'-'.date('g:ia',$timeend).'</span>';
	elseif(is_same_month($timestart,$timeend)) return '<span class="date">'.date('jS',$timestart).'-'.date('jS M Y',$timeend).'</span>, <span class="time">'.date('g:ia',$timestart).'-'.date('g:ia',$timeend).'</span>';
	elseif(is_same_year($timestart,$timeend)) return '<span class="date">'.date('jS M',$timestart).'-'.date('jS M Y',$timeend).'</span>';
	else return '<span class="date">'.date('jS M Y',$timestart).'-'.date('jS M Y',$timeend).'</span>';
}
function is_same_day($time1, $time2)
{
	if(date('j',$time1) == date('j',$time2) && abs($time2-$time1)<86400) return TRUE;
	else return FALSE;
}
function is_same_month($time1, $time2)
{
	if(date('M',$time1) == date('M',$time2) && abs($time2-$time1)<2629744) return TRUE;
	else return FALSE;
}
function is_same_year($time1, $time2)
{
	if(date('Y',$time1) == date('Y',$time2) && abs($time2-$time1)<31556926) return TRUE;
	else return FALSE;
}
	