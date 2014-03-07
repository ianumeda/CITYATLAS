<?php
/**
 * Template Name: New Post From Form
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the wordpress construct of pages
 * and that other 'pages' on your wordpress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage Starkers
 * @since Starkers 3.0
 */

get_header(); ?>

	<?php // Create post object
		extract($_REQUEST);
		
		$currentuser=get_current_user_id();
		if($currentuser==0) $currentuser=9;
		$postcatid=1;
	  	$my_post = array(
	    'post_title' => $event_name,
	    'post_content' => $event_description,
	    'post_status' => 'pending',
	    'post_author' => $currentuser,
	    'post_category' => array($postcatid)
		// 'category_name' => array($postcat);
	 	);

		if($thenewpostid=wp_insert_post( $my_post )) { 
			header("Location: 0; url='http://newyork.thecityatlas.org/submission-successful/");
		} else { 
			header("Location: 0; url='http://newyork.thecityatlas.org/submission-failure/");
		}
	 ?>

<?php get_footer(); ?>