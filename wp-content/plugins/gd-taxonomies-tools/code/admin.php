<?php

if (!defined('ABSPATH')) exit;

class gdCPTAdmin {
    var $admin_plugin;
    var $admin_plugin_page;

    var $page_ids = array();

    var $errors;
    var $edit_cpt;
    var $edit_tax;

    function __construct() {
        add_filter('plugin_row_meta', array(&$this, 'plugin_links'), 10, 2);
        add_filter('plugin_action_links', array(&$this, 'plugin_actions'), 10, 2);

        add_action('admin_notices', array(&$this, 'admin_notice'));

        add_action('admin_init', array(&$this, 'admin_init'));
        add_action('admin_menu', array(&$this, 'admin_menu'));
        add_action('admin_head', array(&$this, 'admin_head'));
    }

    function plugin_links($links, $file) {
        static $this_plugin;
        global $gdtt;

        if (!$this_plugin) {
            $this_plugin = $gdtt->plugin_name;
        }

        if ($file == $this_plugin){
            $links[] = '<a href="admin.php?page=gdtaxtools_taxs">'.__("Custom Taxonomies", "gd-taxonomies-tools").'</a>';
            $links[] = '<a href="admin.php?page=agdtaxtools_postypes">'.__("Custom Post Types", "gd-taxonomies-tools").'</a>';
            $links[] = '<a href="http://www.gdcpttools.com/faq/">'.__("FAQ", "gd-taxonomies-tools").'</a>';
            $links[] = '<a target="_blank" style="color: #cc0000; font-weight: bold;" href="http://www.gdcpttools.com/">'.__("Upgrade to PRO", "gd-taxonomies-tools").'</a>';
        }

        return $links;
    }

    function plugin_actions($links, $file) {
        static $this_plugin;
        global $gdtt;

        if (!$this_plugin) {
            $this_plugin = $gdtt->plugin_name;
        }

        if ($file == $this_plugin){
            $settings_link = '<a href="admin.php?page=gdtaxtools_settings">'.__("Settings", "gd-taxonomies-tools").'</a>';
            array_unshift($links, $settings_link);
        }
        return $links;
    }

    function admin_notice() {
        global $gdtt;

        if ($gdtt->o['upgrade_notice_132'] == 1) {
            $no_thanks = add_query_arg('notice132gdtt', "hide");

            echo '<div class="updated">';
                echo '<strong><u>GD Custom Posts And Taxonomies Tools:</u></strong><br/>';
                _e("Due to the changes to labels and capabilities, you must check custom post types and custom taxonomies you created and save them again. All capabilities must be filled, you can use a Reset Capabailities button to fill them with default values.", "gd-taxonomies-tools");
                echo '<br/><a href="admin.php?page=gdtaxtools_postypes">'.__("Custom Post Types", "gd-taxonomies-tools")."</a>";
                echo ' | <a href="admin.php?page=gdtaxtools_taxs">'.__("Custom Taxonomies", "gd-taxonomies-tools")."</a>";
                echo ' | <a href="'.$no_thanks.'">'.__("Dismiss this message", "gd-taxonomies-tools")."</a>";
            echo '</div>';
        }
    }

    function upgrade_notice() {
        global $gdtt;

        if ($gdtt->o['upgrade_to_pro_16'] == 1) {
            $no_thanks = add_query_arg('proupgradett', 'hide');

            echo '<div class="updated">';
                echo __("Thank you for using this plugin. Please, take a few minutes and check out the Pro version of this plugin with many new and improved features.", "gd-taxonomies-tools");
                echo '<br/>'.__("Buy plugin Pro version or Dev4Press Plugins Pack and get 15% discount using this coupon:", "gd-taxonomies-tools");
                echo ' <strong style="color: #c00;">GDCPTLITETOPRO</strong><br/>';
                echo '<strong><a href="http://www.gdcpttools.com/" target="_blank">'.__("Official Website", "gd-taxonomies-tools")."</a></strong> | ";
                echo '<strong><a href="http://d4p.me/gdtt" target="_blank">'.__("Overview on Dev4Press", "gd-taxonomies-tools")."</a></strong> | ";
                echo '<strong><a href="http://d4p.me/247" target="_blank">'.__("Dev4Press Plugins Pack", "gd-taxonomies-tools")."</a></strong> | ";
                echo '<a href="'.$no_thanks.'">'.__("Don't display this message anymore", "gd-taxonomies-tools")."</a>.";
            echo '</div>';
        }
    }

    function admin_init() {
        if (isset($_GET['page'])) {
            if (substr($_GET['page'], 0, 10) == 'gdtaxtools') {
                $this->admin_plugin = true;
                $this->admin_plugin_page = substr($_GET['page'], 11);
            }
        }

        if ($this->admin_plugin) {
            wp_enqueue_script('jquery');
        }

        $this->settings_operations();
        $this->init_operations();
        $this->init_savetax();
    }

    function admin_menu() {
        $this->page_ids[] = add_menu_page('GD CPT Tools', 'GD CPT Tools', "gdcpttools_basic", "gdtaxtools_front", array(&$this,"admin_front"), plugins_url('gd-taxonomies-tools/gfx/menu.png'));
        $this->page_ids[] = add_submenu_page('gdtaxtools_front', 'GD CPT Tools: '.__("Front Page", "gd-taxonomies-tools"), __("Front Page", "gd-taxonomies-tools"), "gdcpttools_basic", "gdtaxtools_front", array(&$this,"admin_front"));
        $this->page_ids[] = add_submenu_page('gdtaxtools_front', 'GD CPT Tools: '.__("Post Types", "gd-taxonomies-tools"), __("Post Types", "gd-taxonomies-tools"), "gdcpttools_basic", "gdtaxtools_postypes", array(&$this, "admin_postypes"));
        $this->page_ids[] = add_submenu_page('gdtaxtools_front', 'GD CPT Tools: '.__("Taxonomies", "gd-taxonomies-tools"), __("Taxonomies", "gd-taxonomies-tools"), "gdcpttools_basic", "gdtaxtools_taxs", array(&$this, "admin_taxs"));
        $this->page_ids[] = add_submenu_page('gdtaxtools_front', 'GD CPT Tools: '.__("Settings", "gd-taxonomies-tools"), __("Settings", "gd-taxonomies-tools"), "gdcpttools_basic", "gdtaxtools_settings", array(&$this, "admin_settings"));
        $this->page_ids[] = add_submenu_page('gdtaxtools_front', 'GD CPT Tools: '.__("Upgrade to Pro", "gd-taxonomies-tools"), __("Upgrade to Pro", "gd-taxonomies-tools"), "gdcpttools_basic", "gdtaxtools_gopro", array(&$this,"admin_gopro"));

        $this->admin_load_hooks();
    }

    function admin_load_hooks() {
        if (GDTAXTOOLS_WPV < 33) return;

        foreach ($this->page_ids as $id) {
            add_action('load-'.$id, array(&$this, 'load_admin_page'));
        }
    }

    function load_admin_page() {
        $screen = get_current_screen();
        $page_id = $screen->id == 'toplevel_page_gdtaxtools_front' ? 'front' : substr($screen->id, 29);

        $screen->set_help_sidebar('
            <p><strong>Dev4Press:</strong></p>
            <p><a target="_blank" href="http://www.dev4press.com/">'.__("Website", "gd-taxonomies-tools").'</a></p>
            <p><a target="_blank" href="http://twitter.com/dev4press">'.__("On Twitter", "gd-taxonomies-tools").'</a></p>
            <p><a target="_blank" href="http://facebook.com/dev4press">'.__("On Facebook", "gd-taxonomies-tools").'</a></p>');

        $screen->add_help_tab(array(
            "id" => "gdpt-screenhelp-help",
            "title" => __("Get Help", "gd-taxonomies-tools"),
            "content" => '<h5>'.__("General plugin information", "gd-taxonomies-tools").'</h5>
                <p><a href="http://www.gdcpttools.com/" target="_blank">'.__("Plugin Website", "gd-taxonomies-tools").'</a> | 
                <a href="http://www.gdcpttools.com/faq/" target="_blank">'.__("Frequently asked questions", "gd-taxonomies-tools").'</a> | 
                <a href="http://www.gdcpttools.com/development-roadmap/" target="_blank">'.__("Development roadmap", "gd-taxonomies-tools").'</a></p>
                <h5>'.__("Support for the plugin on Dev4Press", "gd-taxonomies-tools").'</h5>
                <p>'.__("Support is available only for Pro version of this plugin.", "gd-taxonomies-tools").'</p>
                <p><a href="http://www.dev4press.com/plugins/gd-taxonomies-tools/support/" target="_blank">'.__("Support Overview", "gd-taxonomies-tools").'</a> | 
                <a href="http://www.dev4press.com/forums/forum/plugins/gd-taxonomies-tools/" target="_blank">'.__("Support Forum", "gd-taxonomies-tools").'</a> | 
                <a href="http://www.dev4press.com/documentation/product/plg-gd-taxonomies-tools/" target="_blank">'.__("Documentation", "gd-taxonomies-tools").'</a> | 
                <a href="http://www.dev4press.com/category/tutorials/plugins/gd-taxonomies-tools/" target="_blank">'.__("Tutorials", "gd-taxonomies-tools").'</a></p>'));

        $screen->add_help_tab(array(
            "id" => "gdpt-screenhelp-website",
            "title" => "Dev4Press", "sfc",
            "content" => '<p>'.__("On Dev4Press website you can find many useful plugins, themes and tutorials, all for WordPress. Please, take a few minutes to browse some of these resources, you might find some of them very useful.", "gd-taxonomies-tools").'</p>
                <p><a href="http://www.dev4press.com/plugins/" target="_blank"><strong>'.__("Plugins", "gd-taxonomies-tools").'</strong></a> - '.__("We have more than 10 plugins available, some of them are commercial and some are available for free.", "gd-taxonomies-tools").'</p>
                <p><a href="http://www.dev4press.com/themes/" target="_blank"><strong>'.__("Themes", "gd-taxonomies-tools").'</strong></a> - '.__("All our themes are based on our own xScape Theme Framework, and only available as premium.", "gd-taxonomies-tools").'</p>
                <p><a href="http://www.dev4press.com/category/tutorials/" target="_blank"><strong>'.__("Tutorials", "gd-taxonomies-tools").'</strong></a> - '.__("Premium and free tutorials for our plugins themes, and many general and practical WordPress tutorials.", "gd-taxonomies-tools").'</p>
                <p><a href="http://www.dev4press.com/documentation/" target="_blank"><strong>'.__("Central Documentation", "gd-taxonomies-tools").'</strong></a> - '.__("Growing collection of functions, classes, hooks, constants with examples for our plugins and themes.", "gd-taxonomies-tools").'</p>
                <p><a href="http://www.dev4press.com/forums/" target="_blank"><strong>'.__("Support Forums", "gd-taxonomies-tools").'</strong></a> - '.__("Premium support forum for all with valid licenses to get help. Also, report bugs and leave suggestions.", "gd-taxonomies-tools").'</p>'));
    }

    function admin_head() {
        global $gdtt;

        if ($this->admin_plugin) {
            echo('<link rel="stylesheet" href="'.GDTAXTOOLS_URL.'css/admin_main.css" type="text/css" media="screen" />');

            $default_caps_cpt = json_encode($gdtt->post_type_caps);
            $default_caps_tax = json_encode($gdtt->taxonomy_caps);

            include(GDTAXTOOLS_PATH.'/code/js.php');
        }

        if ($gdtt->script == 'widgets.php' || $gdtt->script == 'themes.php' || $gdtt->script == 'plugins.php') {
            echo('<link rel="stylesheet" href="'.GDTAXTOOLS_URL.'css/admin_widgets.css" type="text/css" media="screen" />');
        }

        include(GDTAXTOOLS_PATH.'code/go_pro.php');
    }

    function init_operations() {
        global $gdtt;

        if (isset($_GET['proupgradett']) && $_GET['proupgradett'] == 'hide') {
            $gdtt->o['upgrade_to_pro_16'] = 0;

            update_option('gd-taxonomy-tools', $gdtt->o);

            wp_redirect(remove_query_arg('proupgradett'));
            exit;
        }

        if (isset($_GET['notice132gdtt']) && $_GET['notice132gdtt'] == 'hide') {
            $gdtt->o['upgrade_notice_132'] = 0;

            update_option('gd-taxonomy-tools', $gdtt->o);

            wp_redirect(remove_query_arg('notice132gdtt'));
            exit;
        }

        if (isset($_GET['action'])) {
            $action = $_GET['action'];
            $url = remove_query_arg('action');

            switch ($action) {
                case 'delcpt':
                    $id = $gdtt->find_custompost_pos($_GET['pid']);

                    if ($id > -1) {
                        unset($gdtt->p[$id]);
                        $gdtt->p = array_values($gdtt->p);

                        update_option('gd-taxonomy-tools-cpt', $gdtt->p);
                    }

                    $url = remove_query_arg('pid', $url);
                    wp_redirect($url);
                    exit;
                    break;
                case 'deltax':
                    $id = $gdtt->find_taxonomy_pos($_GET['tid']);

                    if ($id > -1) {
                        $tax_name = $gdtt->t[$id]['name'];
                        unset($gdtt->t[$id]);
                        $gdtt->t = array_values($gdtt->t);

                        update_option('gd-taxonomy-tools-tax', $gdtt->t);
                        gdttDB::delete_taxonomy_terms($tax_name);
                    }

                    $url = remove_query_arg('tid', $url);
                    wp_redirect($url);
                    exit;
                    break;
            }
        }
    }

    function init_savetax() {
        if (isset($_POST['gdtt_savecpt'])) {
            global $gdtt;

            $cpt = $_POST['cpt'];
            $this->errors = "";

            $cpt['name'] = strtolower(sanitize_user($cpt['name'], true));
            $cpt['supports'] = isset($cpt['supports']) ? (array)$cpt['supports'] : array();
            $cpt['taxonomies'] = isset($cpt['taxonomies']) ? (array)$cpt['taxonomies'] : array();
            $cpt['description'] = trim(strip_tags($cpt['description']));
            $cpt['active'] = isset($cpt['active']) ? 1 : 0;
            $cpt['caps_type'] = trim(strip_tags($cpt['caps_type']));
            $cpt['capabilites'] = trim(strip_tags($cpt['capabilites']));

            $cpt['archive_slug'] = trim(str_replace(' ', '', strip_tags($cpt['archive_slug'])));
            $cpt['rewrite_slug'] = trim(str_replace(' ', '', strip_tags($cpt['rewrite_slug'])));
            $cpt['query_slug'] = trim(strtolower(gdtt_sanitize_value($cpt['query_slug'], array('replacement' => '-'))));

            if (!$gdtt->is_term_valid($cpt['name'])) {
                $this->errors = 'name';
            } else {
                if (trim($cpt['labels']['name']) == '') {
                    $cpt['labels']['name'] = $cpt['name'];
                }
            }

            if ($cpt['id'] == 0) {
                if ($this->errors == '') {
                    $gdtt->o['cpt_internal']++;
                    $cpt['id'] = $gdtt->o['cpt_internal'];
                    $gdtt->p[] = $cpt;

                    update_option('gd-taxonomy-tools', $gdtt->o);
                    update_option('gd-taxonomy-tools-cpt', $gdtt->p);
                }
            } else {
                $id = $gdtt->find_custompost_pos($cpt['id']);

                if ($id > -1 && $this->errors == '') {
                    $gdtt->p[$id] = $cpt;

                    update_option('gd-taxonomy-tools-cpt', $gdtt->p);
                }
            }

            $this->edit_cpt = $cpt;
        }

        if (isset($_POST['gdtt_savetax'])) {
            global $gdtt;

            $tax = $_POST['tax'];
            $this->errors = '';

            $post_types = isset($tax['post_type']) ? (array)$tax['post_type'] : array();
            $tax['name'] = strtolower(sanitize_user($tax['name'], true));
            $tax['domain'] = join(',', $post_types);

            $tax['rewrite_custom'] = trim(str_replace(' ', '', strip_tags($tax['rewrite_custom'])));
            $tax['query_custom'] = trim(strtolower(gdtt_sanitize_value($tax['query_custom'], array('replacement' => '-'))));

            if (trim($tax['labels']['name']) == '') {
                $tax['labels']['name'] = $tax['name'];
            }

            if (!$gdtt->is_term_valid($tax['name'])) {
                $this->errors = 'name';
            } else {
                if (trim($tax['rewrite_custom']) == '') $tax['rewrite_custom'] = $tax['name'];
                if (trim($tax['query_custom']) == '') $tax['query_custom'] = $tax['name'];
            }

            if ($tax['id'] == 0) {
                if (!$gdtt->is_taxonomy_valid($tax['name'])) {
                    $this->errors = 'name';
                }
                if ($this->errors == '') {
                    $gdtt->o['tax_internal']++;
                    $tax['id'] = $gdtt->o['tax_internal'];
                    $gdtt->t[] = $tax;

                    update_option('gd-taxonomy-tools', $gdtt->o);
                    update_option('gd-taxonomy-tools-tax', $gdtt->t);
                }
            } else {
                $id = $gdtt->find_taxonomy_pos($tax['id']);

                if ($id > -1 && $this->errors == '') {
                    $gdtt->t[$id] = $tax;

                    update_option('gd-taxonomy-tools-tax', $gdtt->t);
                }
            }

            $this->edit_tax = $tax;
        }
    }

    function settings_operations() {
        if (isset($_POST["gdtt_saving"])) {
            global $gdtt;

            $gdtt->o['delete_taxonomy_db'] = isset($_POST['delete_taxonomy_db']) ? 1 : 0;

            update_option('gd-taxonomy-tools', $gdtt->o);

            wp_redirect(add_query_arg('settings', 'saved'));
            exit();
        }
    }

    function admin_front() {
        global $gdtt;

        $options = $gdtt->o;
        $errors = $this->errors;
        $wpv = $gdtt->wp_version;

        include(GDTAXTOOLS_PATH.'forms/shared/front.header.php');
        include(GDTAXTOOLS_PATH.'forms/admin/front.php');
        include(GDTAXTOOLS_PATH.'forms/shared/all.footer.php');
    }

    function admin_settings() {
        global $gdtt;

        $options = $gdtt->o;
        $errors = $this->errors;

        $page_title = __("Settings", "gd-taxonomies-tools");
        include(GDTAXTOOLS_PATH.'forms/shared/all.header.php');
        include(GDTAXTOOLS_PATH.'forms/admin/settings.php');
        include(GDTAXTOOLS_PATH.'forms/shared/all.footer.php');
    }

    function admin_postypes() {
        global $gdtt, $wp_taxonomies;

        $post_features = array(
            'title' => __("Title", "gd-taxonomies-tools"),
            'editor' => __("Editor", "gd-taxonomies-tools"),
            'excerpt' => __("Excerpts", "gd-taxonomies-tools"),
            'trackbacks' => __("Trackbacks", "gd-taxonomies-tools"),
            'custom-fields' => __("Custom Fields", "gd-taxonomies-tools"),
            'comments' => __("Comments", "gd-taxonomies-tools"),
            'revisions' => __("Revisions", "gd-taxonomies-tools"),
            'thumbnail' => __("Post Thumbnails", "gd-taxonomies-tools"),
            'author' => __("Author", "gd-taxonomies-tools"),
            'page-attributes' => __("Page Attributes", "gd-taxonomies-tools")
        );

        $options = $gdtt->o;
        $wpv = $gdtt->wp_version;
        $gdcpost = $gdtt->posts;
        $gdcpall = $gdtt->p;
        $errors = $this->errors;
        $action = isset($_GET['action']) ? $_GET['action'] : 'list';

        if ($action == 'list') {
            $page_title = __("Custom Posts", "gd-taxonomies-tools");

            include(GDTAXTOOLS_PATH.'forms/shared/all.header.php');
            include(GDTAXTOOLS_PATH.'forms/admin/customposts.php');
        } else if ($action == 'addnew') {
            $page_title = __("Add new Custom Posts", "gd-taxonomies-tools");

            include(GDTAXTOOLS_PATH.'forms/shared/all.header.php');
            include(GDTAXTOOLS_PATH.'forms/admin/custompost.add.php');
        } else if ($action == 'edit') {
            $page_title = __("Edit Custom Posts", "gd-taxonomies-tools");
            $cpt = $gdtt->find_postype($_GET['pid']);

            if (!is_array($cpt['supports'])) $cpt['supports'] = array();
            if (!is_array($cpt['taxonomies'])) $cpt['taxonomies'] = array();

            if (!isset($cpt['description'])) $cpt['description'] = '';
            if (!isset($cpt['rewrite_slug'])) $cpt['rewrite_slug'] = '';
            if (!isset($cpt['rewrite_front'])) $cpt['rewrite_front'] = 'no';
            if (!isset($cpt['nav_menus'])) $cpt['nav_menus'] = 'yes';
            if (!isset($cpt['menu_position'])) $cpt['menu_position'] = '__auto__';
            if (!isset($cpt['menu_icon'])) $cpt['menu_icon'] = '';
            if (!isset($cpt['exclude_from_search'])) $cpt['exclude_from_search'] = 'no';
            if (!isset($cpt['publicly_queryable'])) $cpt['publicly_queryable'] = 'yes';
            if (!isset($cpt['can_export'])) $cpt['can_export'] = 'yes';
            if (!isset($cpt['active'])) $cpt['active'] = 1;
            if (!isset($cpt['show_in_menu'])) $cpt['show_in_menu'] = 'yes';
            if (!isset($cpt['show_in_admin_bar'])) $cpt['show_in_admin_bar'] = 'yes';

            if (!isset($cpt['labels'])) {
                $cpt['labels'] = array('name' => $cpt['label'],
                    'singular_name' => $cpt['label_singular'],
                    'add_new' => '', 'add_new_item' => '',
                    'edit_item' => '', 'edit' => '', 'all_items' => '',
                    'new_item' => '', 'view_item' => '',
                    'search_items' => '', 'not_found' => '',
                    'not_found_in_trash' => '', 'view' => '',
                    'parent_item_colon' => '');
            }

            if (!isset($cpt['capabilites'])) {
                $cpt['capabilites'] = 'list';
                $cpt['caps_type'] = 'post';
            }

            if (!isset($cpt['caps'])) {
                $cpt['caps'] = array(
                    'edit_post' => 'edit_post',
                    'edit_posts' => 'edit_posts',
                    'edit_others_posts' => 'edit_others_posts',
                    'publish_posts' => 'publish_posts',
                    'read_post' => 'read_post',
                    'read_private_posts' => 'read_private_posts',
                    'delete_post' => 'delete_post');
            }

            include(GDTAXTOOLS_PATH.'forms/shared/all.header.php');
            include(GDTAXTOOLS_PATH.'forms/admin/custompost.edit.php');
        }

        include(GDTAXTOOLS_PATH.'forms/shared/all.footer.php');
    }

    function admin_taxs() {
        global $gdtt;

        $options = $gdtt->o;
        $wpv = $gdtt->wp_version;
        $gdtttax = $gdtt->taxes;
        $gdtxall = $gdtt->t;
        $errors = $this->errors;
        $action = isset($_GET['action']) ? $_GET['action'] : 'list';

        $post_types = get_post_types(array(), 'objects');

        if ($action == 'list') {
            $page_title = __("Taxonomies", "gd-taxonomies-tools");

            include(GDTAXTOOLS_PATH.'forms/shared/all.header.php');
            include(GDTAXTOOLS_PATH.'forms/admin/taxonomies.php');
        } else if ($action == "addnew") {
            $page_title = __("Add new Taxonomy", "gd-taxonomies-tools");

            include(GDTAXTOOLS_PATH.'forms/shared/all.header.php');
            include(GDTAXTOOLS_PATH.'forms/admin/taxonomy.add.php');
        } else if ($action == "edit") {
            $page_title = __("Edit Taxonomy", "gd-taxonomies-tools");
            $tax = $gdtt->find_taxonomy($_GET["tid"]);
            $tax['domain'] = explode(',', $tax['domain']);

            if (!isset($tax['nav_menus'])) $tax['nav_menus'] = 'yes';
            if (!isset($tax['show_admin_menu'])) $tax['show_admin_menu'] = 'yes';
            if (!isset($tax['sort'])) $tax['sort'] = 'no';

            if (!isset($tax['labels'])) {
                $tax['labels'] = array('name' => $tax['label'],
                    'singular_name' => $tax['label_singular'],
                    'search_items' => '', 'popular_items' => '',
                    'all_items' => '', 'parent_item' => '',
                    'view_item' => '', 'edit_item' => '', 'update_item' => '',
                    'add_new_item' => '', 'new_item_name' => '');
            }

            if (!isset($tax['caps'])) {
                $tax['caps'] = array(
                    'manage_terms' => 'manage_categories',
                    'edit_terms' => 'manage_categories',
                    'delete_terms' => 'manage_categories',
                    'assign_terms' => 'edit_posts');
            }

            include(GDTAXTOOLS_PATH.'forms/shared/all.header.php');
            include(GDTAXTOOLS_PATH.'forms/admin/taxonomy.edit.php');
        }

        include(GDTAXTOOLS_PATH.'forms/shared/all.footer.php');
    }

    function admin_gopro() {
        $response = get_site_transient('gdcpttools_gopro_16');

        if ($response == '') {
            $load = 'http://www.dev4press.com/wp-content/plugins/gd-product-central/get_lite.php?name=gdtt';
            $response = wp_remote_retrieve_body(wp_remote_get($load));
            set_site_transient('gdcpttools_gopro_16', $response, 604800);
        }
        echo($response);
    }
}

?>