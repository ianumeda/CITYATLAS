<?php

global $gdtt;

$post_types = get_post_types(array(), "objects");
$post_count = gdttDB::get_post_types_counts();
$post_inactive = $gdtt->prepare_inactive_cpt();
$count_custom = 0;

echo '<h3>'.__("Default Post Types:", "gd-taxonomies-tools").'</h3>';
include(GDTAXTOOLS_PATH."forms/render/cpt.header.php");
foreach ($post_types as $cpt_data) {
    $default = true;
    $cpt_name = $cpt_data->name;
    if ($cpt_data->_builtin) {
        include(GDTAXTOOLS_PATH."forms/render/cpt.item.php");
    } else $count_custom++;
}
include(GDTAXTOOLS_PATH."forms/render/cpt.footer.php");

if ($count_custom > 0) {
    echo '<h3>'.__("Custom Post Types:", "gd-taxonomies-tools").'</h3>';
    include(GDTAXTOOLS_PATH."forms/render/cpt.header.php");
    foreach ($post_types as $cpt_data) {
        $default = false;
        $cpt_name = $cpt_data->name;
        if (!$cpt_data->_builtin) {
            include(GDTAXTOOLS_PATH."forms/render/cpt.item.php");
        }
    }
    include(GDTAXTOOLS_PATH."forms/render/cpt.footer.php");
}

if (count($post_inactive) > 0) {
    echo '<h3>'.__("Inactive Custom Post Types:", "gd-taxonomies-tools").'</h3>';
    include(GDTAXTOOLS_PATH."forms/render/cpt.header.php");
    foreach ($post_inactive as $cpt_data) {
        $default = false;
        $cpt_name = $cpt_data->name;
        include(GDTAXTOOLS_PATH."forms/render/cpt.item.php");
    }
    include(GDTAXTOOLS_PATH."forms/render/cpt.footer.php");
}

?>
<div class="inputbutton" style="margin-top: 10px;"><a href="admin.php?page=gdtaxtools_postypes&action=addnew"><?php _e("Add New", "gd-taxonomies-tools"); ?></a></div>
