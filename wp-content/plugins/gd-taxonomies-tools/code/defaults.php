<?php

if (!defined('ABSPATH')) exit;

class GDTTDefaults {
    var $default_options = array(
        'version' => '1.6.0.1',
        'date' => '2013.04.28.',
        'status' => 'Stable',
        'build' => 1601,
        'product_id' => 'gd-taxonomies-tools',
        'edition' => 'lite',
        'upgrade_notice_132' => 1,
        'upgrade_to_pro_16' => 1,
        'force_rules_flush' => 0,
        'tax_internal' => 0,
        'cpt_internal' => 0,
        'delete_taxonomy_db' => 0,
        'tinymce_auto_create' => 1,
        'tinymce_search_limit' => 5
    );

    var $default_taxonomies = array(
        'wp30' => 4,
        'wp31' => 5,
        'wp32' => 5,
        'wp33' => 5,
        'wp34' => 5,
        'wp35' => 5
    );

    var $default_posttypes = array(
        'wp30' => 5,
        'wp31' => 5,
        'wp32' => 5,
        'wp33' => 5,
        'wp34' => 5,
        'wp35' => 5
    );

    var $post_type_caps = array(
        'edit_post' => 'edit_post',
        'read_post' => 'read_post',
        'delete_post' => 'delete_post',
        'edit_posts' => 'edit_posts',
        'edit_others_posts' => 'edit_others_posts',
        'publish_posts' => 'publish_posts',
        'read_private_posts' => 'read_private_posts',
        'read' => 'read',
        'delete_posts' => 'delete_posts',
        'delete_private_posts' => 'delete_private_posts',
        'delete_published_posts' => 'delete_published_posts',
        'delete_others_posts' => 'delete_others_posts',
        'edit_private_posts' => 'edit_private_posts',
        'edit_published_posts' => 'edit_published_posts'
    );

    var $taxonomy_caps = array(
        'manage_terms' => 'manage_categories',
        'edit_terms' => 'manage_categories',
        'delete_terms' => 'manage_categories',
        'assign_terms' => 'edit_posts'
    );

    function GDTTDefaults() { }
}

?>