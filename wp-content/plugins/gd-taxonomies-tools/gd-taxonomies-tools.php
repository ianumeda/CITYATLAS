<?php

/*
Plugin Name: GD Custom Posts And Taxonomies Tools
Plugin URI: http://www.gdcpttools.com/
Description: GD Custom Posts And Taxonomies Tools is plugin for management and tools collection for working with custom posts and taxonomies.
Version: 1.6.0.1
Author: Milan Petrovic
Author URI: http://www.dev4press.com/

== Copyright ==
Copyright 2008 - 2013 Milan Petrovic (email: milan@gdragon.info)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if (!defined('GDTAXTOOLS_EOL')) define('GDTAXTOOLS_EOL', "\r\n");
if (!defined('GDTAXTOOLS_LOG_PATH')) define('GDTAXTOOLS_LOG_PATH', dirname(__FILE__).'/debug.txt');

$gdtt_dirname_basic = dirname(__FILE__);

require_once($gdtt_dirname_basic.'/code/defaults.php');
require_once($gdtt_dirname_basic.'/code/functions.php');
require_once($gdtt_dirname_basic.'/code/database.php');
require_once($gdtt_dirname_basic.'/code/classes.php');
require_once($gdtt_dirname_basic.'/code/widget.php');
require_once($gdtt_dirname_basic.'/gdragon/gd_debug.php');
require_once($gdtt_dirname_basic.'/gdragon/gd_functions.php');
require_once($gdtt_dirname_basic.'/gdragon/gd_wordpress.php');
require_once($gdtt_dirname_basic.'/widgets/gdtt-terms-cloud.php');
require_once($gdtt_dirname_basic.'/widgets/gdtt-terms-list.php');

define('GDTAXTOOLS_WP_ADMIN', defined('WP_ADMIN') && WP_ADMIN);

if (!class_exists('GDTaxonomiesTools')) {
    class GDTaxonomiesTools {
        var $plugin_url;
        var $plugin_path;
        var $plugin_name;

        var $wp_version;
        var $script;
        var $taxes;
        var $posts;

        var $o;
        var $t;
        var $p;
        var $l;

        var $on_tinymce_main = false;

        var $default_options;
        var $default_taxonomies;
        var $post_type_caps;
        var $taxonomy_caps;

        function __construct() {
            $this->plugin_name = plugin_basename(__FILE__);

            $gdd = new GDTTDefaults();
            $this->default_options = $gdd->default_options;
            $this->default_taxonomies = $gdd->default_taxonomies;
            $this->post_type_caps = $gdd->post_type_caps;
            $this->taxonomy_caps = $gdd->taxonomy_caps;
            define('GDTAXONOMIESTOOLS_INSTALLED', $this->default_options['version'].' '.$this->default_options['status']);

            $this->plugin_path_url();
            $this->install_plugin();
            $this->features_testing();
            $this->actions_filters();
        }

        function plugin_path_url() {
            $this->plugin_url = plugins_url('/gd-taxonomies-tools/');
            $this->plugin_path = dirname(__FILE__).'/';

            define('GDTAXTOOLS_URL', $this->plugin_url);
            define('GDTAXTOOLS_PATH', $this->plugin_path);
        }

        function install_plugin() {
            global $wp_version;
            $this->wp_version = substr(str_replace('.', '', $wp_version), 0, 2);
            define('GDTAXTOOLS_WPV', intval($this->wp_version));

            $new_install = false;

            $role = get_role('administrator');
            $role->add_cap('gdcpttools_basic');

            $this->o = get_option('gd-taxonomy-tools');
            $this->t = get_option('gd-taxonomy-tools-tax');
            $this->p = get_option('gd-taxonomy-tools-cpt');

            if (!is_array($this->t)) {
                $new_install = true;
                $this->t = array();
                update_option('gd-taxonomy-tools-tax', $this->t);
            }

            if (!is_array($this->p)) {
                $new_install = true;
                $this->p = array();
                update_option('gd-taxonomy-tools-cpt', $this->p);
            }

            if (!is_array($this->o)) {
                $this->o = $this->default_options;
                update_option('gd-taxonomy-tools', $this->o);
            } else if ($this->o['build'] != $this->default_options['build'] ||
                $this->o['edition'] != $this->default_options['edition']) {
                $this->o = gdFunctionsGDTT::upgrade_settings($this->o, $this->default_options);

                $this->o['version'] = $this->default_options['version'];
                $this->o['date'] = $this->default_options['date'];
                $this->o['status'] = $this->default_options['status'];
                $this->o['build'] = $this->default_options['build'];
                $this->o['edition'] = $this->default_options['edition'];

                update_option('gd-taxonomy-tools', $this->o);

                $this->reindex_and_save();
            }

            if ($this->o['upgrade_notice_132'] == 1 && $new_install) {
                $this->o['upgrade_notice_132'] = 0;
                update_option('gd-taxonomy-tools', $this->o);
            }

            $this->taxes = array();
            $this->posts = array();

            foreach ($this->t as $tx) {
                $this->taxes[] = $tx['name'];
            }
            foreach ($this->p as $pt) {
                $this->posts[] = $pt['name'];
            }

            $this->script = $_SERVER['PHP_SELF'];
            $this->script = end(explode('/', $this->script));
        }

        function reindex_and_save() {
            $count_cpt = 0; $count_tax = 0;

            foreach ($this->p as $p) {
                if ((int)$p['id'] > $count_cpt) {
                    $count_cpt = (int)$p['id'];
                }
            }

            foreach ($this->t as $p) {
                if ((int)$p['id'] > $count_tax) {
                    $count_tax = (int)$p['id'];
                }
            }

            $this->o['cpt_internal'] = $count_cpt;
            $this->o['tax_internal'] = $count_tax;

            update_option('gd-taxonomy-tools', $this->o);
        }

        function features_testing() {
            $this->on_tinymce_main = file_exists($this->plugin_path.'tinymce3/gdtt_main/');
        }

        function actions_filters() {
            add_action('init', array(&$this, 'init'));

            add_action('init', array(&$this, 'register_custom_posts'), 1);
            add_action('init', array(&$this, 'register_taxonomies'), 2);
            add_action('init', array(&$this, 'register_for_object_types'), 3);

            add_action('widgets_init', array(&$this, 'widgets_init'));
        }

        function get($setting) {
            return $this->o[$setting];
        }

        function get_defaults_count() {
            return $this->default_taxonomies['wp'.$this->wp_version];
        }

        function widgets_init() {
            register_widget('gdttTermsCloud');
            register_widget('gdttTermsList');
        }

        function init() {
            $this->l = get_locale();

            if(!empty($this->l)) {
                load_plugin_textdomain('gd-taxonomies-tools', false, 'gd-taxonomies-tools/languages');
            }

            if ($this->o['force_rules_flush'] == 1) {
                global $wp_rewrite;
                $wp_rewrite->flush_rules();

                $this->o['force_rules_flush'] = 0;
                update_option('gd-taxonomy-tools', $this->o);
            }
        }

        function register_custom_posts() {
            foreach ($this->p as $cpt) {
                if (!isset($cpt['active']) || (isset($cpt['active']) && $cpt['active'] == 1)) {
                    $options = gdtt_generate_custom_posts_options($cpt);
                    register_post_type($cpt['name'], $options);
                }
            }
        }

        function register_taxonomies() {
            foreach ($this->t as $tax) {
                if (isset($tax['active'])) {
                    $domains = explode(',', $tax['domain']);
                    $options = gdtt_generate_custom_taxonomies_options($tax);
                    register_taxonomy($tax['name'], $domains, $options);
                }
            }
        }

        function register_for_object_types() {
            foreach ($this->t as $tax) {
                if (isset($tax['active'])) {
                    $domains = explode(',', $tax['domain']);
                    foreach ($domains as $post_type) {
                        register_taxonomy_for_object_type($tax['name'], $post_type);
                    }
                }
            }

            foreach ($this->p as $cpt) {
                if (!isset($cpt['active']) || (isset($cpt['active']) && $cpt['active'] == 1)) {
                    if (is_array($cpt['taxonomies'])) {
                        foreach ($cpt['taxonomies'] as $tax) {
                            register_taxonomy_for_object_type($tax, $cpt['name']);
                        }
                    }
                }
            }
        }

        function is_term_valid($term, $check_empty = false) {
            if (trim($term) == '' && $check_empty) {
                return false;
            } else {
                return strtolower($term) == sanitize_title_with_dashes($term);
            }
        }

        function is_taxonomy_valid($tax_name) {
            global $wp_taxonomies;
            $tax_names = array_keys($wp_taxonomies);
            return !in_array(strtolower($tax_name), $tax_names);
        }

        function find_taxonomy($id) {
            $found = array();

            for ($i = 0; $i < count($this->t); $i++) {
                if ($this->t[$i]['id'] == $id) {
                    $found = $this->t[$i];
                    break;
                }
            }

            return $found;
        }

        function find_postype($id) {
            $found = array();

            for ($i = 0; $i < count($this->p); $i++) {
                if ($this->p[$i]['id'] == $id) {
                    $found = $this->p[$i];
                    break;
                }
            }

            return $found;
        }

        function prepare_inactive() {
            $found = array();

            foreach ($this->t as $tax) {
                if (!isset($tax['active'])) {
                    $found[$tax['name']] = new gdtt_Taxonomy($tax);
                }
            }

            return $found;
        }

        function prepare_inactive_cpt() {
            $found = array();

            foreach ($this->p as $cpt) {
                if (isset($cpt['active']) && $cpt['active'] == 0) {
                    $found[$cpt['name']] = new gdtt_CustomPost($cpt);
                }
            }

            return $found;
        }

        function find_custompost_pos($id) {
            $found = -1;

            for ($i = 0; $i < count($this->p); $i++) {
                if ($this->p[$i]['id'] == $id) {
                    $found = $i;
                    break;
                }
            }

            return $found;
        }

        function find_taxonomy_pos($id) {
            $found = -1;

            for ($i = 0; $i < count($this->t); $i++) {
                if ($this->t[$i]['id'] == $id) {
                    $found = $i;
                    break;
                }
            }

            return $found;
        }
    }

    global $gdtt_debug;
    $gdtt_debug = new gdDebugGDTT(GDTAXTOOLS_LOG_PATH);

    function wp_gdtt_dump($msg, $obj, $block = 'none', $mode = 'a+') {
        global $gdtt_debug;
        $gdtt_debug->dump($msg, $obj, $block, $mode);
    }

    global $gdtt, $gdtt_admin;
    $gdtt = new GDTaxonomiesTools();

    if (GDTAXTOOLS_WP_ADMIN) {
        require_once($gdtt_dirname_basic.'/code/admin.php');

        $gdtt_admin = new gdCPTAdmin();

        function gdtt_upgrade_notice() {
            global $gdtt_admin;
            $gdtt_admin->upgrade_notice();
        }
    } else {
        $gdtt_admin = false;
    }

    include(GDTAXTOOLS_PATH.'code/fnc/general.php');
    include(GDTAXTOOLS_PATH.'code/fnc/filters.php');
    include(GDTAXTOOLS_PATH.'code/fnc/display.php');
}

?>