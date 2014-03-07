<?php

global $gdtt;
$editor = true;

$cpt = array(
    'id' => 0,
    'name' => '',
    'active' => 1,
    'description' => '',
    'public' => 'yes',
    'archive' => 'yes',
    'archive_slug' => '',
    'ui' => 'yes',
    'nav_menus' => 'yes',
    'hierarchy' => 'no',
    'rewrite' => 'yes',
    'rewrite_slug' => '',
    'rewrite_front' => 'no',
    'exclude_from_search' => 'no',
    'publicly_queryable' => 'yes',
    'show_in_menu' => 'yes',
    'show_in_admin_bar' => 'yes',
    'can_export' => 'yes',
    'rewrite_feeds' => 'yes',
    'rewrite_pages' => 'yes',
    'query' => 'yes',
    'query_slug' => '',
    'edit_link' => 'post.php?post=%d',
    'menu_position' => '__auto__',
    'menu_icon' => '',
    'supports' => array(
        'title', 'editor', 'excerpts',
        'trackbacks', 'custom-fields',
        'comments', 'revisions',
        'post-thumbnails'),
    'taxonomies' => array(
        'category', 'post_tag'),
    'labels' => array(
        'name' => '', 'singular_name' => '',
        'add_new' => '', 'add_new_item' => '',
        'edit_item' => '', 'new_item' => '',
        'view_item' => '', 'search_items' => '',
        'not_found' => '', 'not_found_in_trash' => '',
        'parent_item_colon' => '', 'all_items' => '', 
        'menu_item' => ''),
    'capabilites' => 'type',
    'caps_type' => 'post',
    'caps' => $gdtt->post_type_caps
);

if ($errors == "name") {
    gdtt_render_alert("Error", __("Name for the custom post type is invalid. Fix before proceeding.", "gd-taxonomies-tools"));
}

if (isset($_POST["gdtt_savecpt"])) {
    $gdtt->o["force_rules_flush"] = 1;
    update_option('gd-taxonomy-tools', $gdtt->o);

    $cpt = $this->edit_cpt;
    if ($errors == "") {
        $editor = false;
        gdtt_render_notice("Custom Post Type", __("New custom post type added.", "gd-taxonomies-tools"));
        ?>
        <div class="inputbutton" style="margin-top: 10px;"><a href="admin.php?page=gdtaxtools_postypes"><?php _e("Go Back", "gd-taxonomies-tools"); ?></a></div>
        <?php
    }
}

if ($editor) include(GDTAXTOOLS_PATH."forms/admin/custompost.form.php");

?>