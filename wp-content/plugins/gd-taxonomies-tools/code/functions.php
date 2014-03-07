<?php

if (!defined('ABSPATH')) exit;

function gdtt_generate_custom_posts_options($cpt) {
    $cpt['description'] = !isset($cpt['description']) ? '' : $cpt['description'];
    $cpt['rewrite_slug'] = !isset($cpt['rewrite_slug']) ? '' : $cpt['rewrite_slug'];
    $cpt['query_slug'] = !isset($cpt['query_slug']) ? '' : $cpt['query_slug'];
    $cpt['rewrite_feeds'] = !isset($cpt['rewrite_feeds']) ? 'yes' : $cpt['rewrite_feeds'];
    $cpt['rewrite_pages'] = !isset($cpt['rewrite_pages']) ? 'yes' : $cpt['rewrite_pages'];
    $cpt['show_in_menu'] = !isset($cpt['show_in_menu']) ? 'yes' : $cpt['show_in_menu'];
    $cpt['show_in_admin_bar'] = !isset($cpt['show_in_admin_bar']) ? 'yes' : $cpt['show_in_admin_bar'];
    $cpt['menu_position'] = !isset($cpt['menu_position']) ? '__auto__' : $cpt['menu_position'];
    $cpt['menu_icon'] = !isset($cpt['menu_icon']) ? '' : $cpt['menu_icon'];

    $caps = $labels = array();

    $rewrite = $cpt['rewrite'] == 'no' ? false : true;
    if ($rewrite) {
        $rewrite = array('slug' => $cpt['rewrite'] == 'yes_custom' ? $cpt['rewrite_slug'] : $cpt['name'],
                         'with_front' => $cpt['rewrite_front'] == 'yes',
                         'feeds' => $cpt['rewrite_feeds'] == 'yes',
                         'pages' => $cpt['rewrite_pages'] == 'yes');
    }

    $query_var = false;
    if ($cpt['query'] != 'no') {
        $query_var = true;

        if ($cpt['query'] == 'yes_custom' && $cpt['query_slug'] != '') {
            $query_var = $cpt['query_slug'];
        }
    }

    $has_archive = false;
    if ($cpt['archive'] != 'no') {
        $has_archive = true;

        if ($cpt['archive'] == 'yes_custom' && $cpt['archive_slug'] != '') {
            $has_archive = $cpt['archive_slug'];
        }
    }

    if (!isset($cpt['labels'])) {
        $labels = array('name' => $cpt['label'], 'singular_name' => $cpt['label_singular']);
    } else {
        $labels = $cpt['labels'];
    }

    if (isset($cpt['caps'])) {
        $caps = $cpt['caps'];
    }

    $cpt['public'] = $cpt['public'] == 'yes';
    $cpt['ui'] = !isset($cpt['ui']) ? $cpt['public'] : $cpt['ui'] == 'yes';
    $cpt['nav_menus'] = !isset($cpt['nav_menus']) ? $cpt['public'] : $cpt['nav_menus'] == 'yes';
    $cpt['can_export'] = !isset($cpt['can_export']) ? $cpt['public'] : $cpt['can_export'] == 'yes';
    $cpt['publicly_queryable'] = !isset($cpt['publicly_queryable']) ? $cpt['public'] : $cpt['publicly_queryable'] == 'yes';
    $cpt['exclude_from_search'] = !isset($cpt['exclude_from_search']) ? !$cpt['public'] : $cpt['exclude_from_search'] == 'yes';

    $options = array(
        'labels' => $labels,
        'description' => $cpt['description'],
        'publicly_queryable' => $cpt['publicly_queryable'],
        'exclude_from_search' => $cpt['exclude_from_search'],
        'menu_icon' => $cpt['menu_icon'] == '' ? null : $cpt['menu_icon'],
        'capability_type' => $cpt['caps_type'],
        'hierarchical' => $cpt['hierarchy'] == 'yes',
        'public' => $cpt['public'],
        'rewrite' => $rewrite,
        'show_in_menu' => $cpt['show_in_menu'] == 'yes',
        'show_in_admin_bar' => $cpt['show_in_admin_bar'] == 'yes',
        'has_archive' => $has_archive,
        'query_var' => $query_var,
        'supports' => (array)$cpt['supports'],
        'taxonomies' => (array)$cpt['taxonomies'],
        'show_ui' => $cpt['ui'],
        'can_export' => $cpt['can_export'],
        'show_in_nav_menus' => $cpt['nav_menus'],
        '_edit_link' => $cpt['edit_link']
    );

    if (!in_array($cpt['menu_position'], array('__auto__'))) {
        $options['menu_position'] = intval($cpt['menu_position']);
    }

    if ($cpt['capabilites'] != 'type') {
        $options['map_meta_cap'] = true;
        $options['capabilities'] = array_values($caps);
    }

    return $options;
}

function gdtt_generate_custom_taxonomies_options($tax) {
    $rewrite = $query_var = true;
    if ($tax['rewrite'] == 'no') {
        $rewrite = false;
    } else {
        $tax['rewrite_hierarchy'] = !isset($tax['rewrite_hierarchy']) ? 'auto' : $tax['rewrite_hierarchy'];
        $tax['with_front'] = !isset($tax['with_front']) ? 'yes' : $tax['with_front'];

        $rewrite = array('hierarchical' => $tax['rewrite_hierarchy'] == 'no' ? false : true,
                         'with_front' => $tax['with_front'] == 'yes');
        if ($tax['rewrite'] == 'yes_name') {
            $rewrite['slug'] = $tax['name'];
        } else {
            $rewrite['slug'] = $tax['rewrite_custom'];
        }
    }

    if ($tax['query'] == 'no') {
        $query_var = false;
    }

    if ($tax['query'] == 'yes_custom') {
        $query_var = $tax['query_custom'];
    }

    $tax['public'] = !isset($tax['public']) ? true : ($tax['public'] == 'yes');
    $tax['ui'] = !isset($tax['ui']) ? $tax['public'] : ($tax['ui'] == 'yes');
    $tax['nav_menus'] = !isset($tax['nav_menus']) ? $tax['public'] : $tax['nav_menus'] == 'yes';
    $tax['cloud'] = !isset($tax['cloud']) ? $tax['public'] : $tax['cloud'] == 'yes';
    $tax['show_admin_menu'] = !isset($tax['show_admin_menu']) ? $tax['public'] : $tax['show_admin_menu'] == 'yes';

    if (!isset($tax['labels'])) {
        $labels = array('name' => $tax['label'], 'singular_name' => $tax['label_singular']);
    } else {
        $labels = $tax['labels'];
        $labels['parent_item_colon'] = $labels['parent_item'].':';
    }

    if (!isset($tax['caps'])) {
        $caps = array();
    } else {
        $caps = $tax['caps'];
    }

    $options = array(
        'hierarchical' => $tax['hierarchy'] == 'yes',
        'rewrite' => $rewrite,
        'query_var' => $query_var,
        'public' => $tax['public'],
        'show_ui' => $tax['ui'],
        'show_tagcloud' => $tax['cloud'],
        'show_admin_menu' => $tax['show_admin_menu'],
        'show_in_nav_menus' => $tax['nav_menus'],
        'labels' => $labels,
        'capabilities' => $caps
    );

    if (isset($tax['sort']) && $tax['sort'] == 'yes') {
        $options['sort'] = true;
    }

    return $options;
}

function gdtt_sanitize_value($name, $args = array()) {
    $defaults = array('strip_spaces' => false, 'replacement' => '-');
    $args = wp_parse_args($args, $defaults);
    extract($args);

    $name = trim(strip_tags($name));
    $name = strtolower($name);
    $name = sanitize_user($name, true);
    $name = str_replace(array("'", '"'), '', $name);

    if ($strip_spaces) {
        $name = str_replace(' ', '', $name);
    }

    $name = str_replace(array('.', '-', '_', ' '), $replacement, $name);

    return $name;
}
function gdtt_render_taxonomies($tax = "") {
    global $wp_taxonomies;
    foreach ($wp_taxonomies as $taxonomy => $cnt) {
        $current = $tax == $taxonomy ? ' selected="selected"' : $current = '';
        echo "\t<option value='".$taxonomy."'".$current.">".$cnt->label."</option>\r\n";
    }
}

function gdtt_render_alert($title, $content) {
    ?>
    <div>
        <div class="gdtt-state-error">
            <p>
                <span style="float: left; margin-right: 0.3em;" class="ui-icon ui-icon-alert"></span>
                <strong><?php echo $title; ?>:</strong> <?php echo $content; ?>
            </p>
        </div>
    </div>
    <?php
}

function gdtt_render_notice($title, $content) {
    ?>
    <div class="ui-widget">
        <div class="ui-state-highlight ui-corner-all" style="padding: 0pt 0.7em; margin: 10px 0;">
            <p>
                <span style="float: left; margin-right: 0.3em;" class="ui-icon ui-icon-info"></span>
                <strong><?php echo $title; ?>:</strong> <?php echo $content; ?>
            </p>
        </div>
    </div>
    <?php
}

?>