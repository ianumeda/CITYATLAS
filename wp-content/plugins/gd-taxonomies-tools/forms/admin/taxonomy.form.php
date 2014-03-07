<form method="post" action="" onsubmit="return validate_tax_form()" style="margin-bottom: 20px;">
    <input type="hidden" name="tax[id]" value="<?php echo $tax["id"]; ?>" />
    <table class="form-table"><tbody>
    <tr><th scope="row"><?php _e("Name", "gd-taxonomies-tools"); ?></th>
        <td>
            <input maxlength="32"<?php echo $tax["id"] > 0 ? " readonly" : ""; ?> type="text" value="<?php echo $tax["name"]; ?>" id="taxname" name="tax[name]" class="input-text-middle<?php echo $tax["id"] > 0 ? " disabled" : ""; ?>" />
            <div class="gdsr-table-split"></div>
            <div class="gdsr-major-info">
                <?php _e("This must be unique name, not used by any other taxonomy.", "gd-taxonomies-tools"); ?><br />
                <?php _e("Also, use only lower case letters and no special characters except for the underscore.", "gd-taxonomies-tools"); ?>
            </div>
        </td>
    </tr>
    <tr><th scope="row"><?php _e("Status", "gd-taxonomies-tools"); ?></th>
        <td>
            <input<?php if (isset($tax["active"])) echo " checked"; ?> type="checkbox" name="tax[active]" /><label style="margin-left: 5px;"><?php _e("This taxonomy is set to active and will be registered for use.", "gd-taxonomies-tools"); ?></label>
            <div class="gdsr-table-split"></div>
            <?php _e("If you deactivate this taxonomy, all the data associated with it will remain intact.", "gd-taxonomies-tools"); ?>
        </td>
    </tr>
    <tr><th scope="row"><?php _e("Labels", "gd-taxonomies-tools"); ?></th>
        <td>
            <table cellpadding="0" cellspacing="0" class="previewtable">
                <tr>
                    <td width="150"><?php _e("Name", "gd-taxonomies-tools"); ?>:</td>
                    <td>
                        <input type="text" id="taxlabelsname" value="<?php echo $tax["labels"]["name"]; ?>" name="tax[labels][name]" class="input-text-middle" />
                    </td>
                    <td style="width: 20px;"></td>
                    <td width="150"><?php _e("Singular Name", "gd-taxonomies-tools"); ?>:</td>
                    <td>
                        <input type="text" id="taxlabelssingular_name" value="<?php echo $tax["labels"]["singular_name"]; ?>" name="tax[labels][singular_name]" class="input-text-middle" />
                    </td>
                </tr>
            </table>
            <div class="gdsr-table-split"></div>
            <div class="inputbutton"><a href="javascript:autofill_taxonomy()"><?php _e("Auto fill rest", "gd-taxonomies-tools"); ?></a></div>
            <div class="gdsr-table-split"></div>
            <table cellpadding="0" cellspacing="0" class="previewtable">
                <tr>
                    <td width="150"><?php _e("Search Items", "gd-taxonomies-tools"); ?>:</td>
                    <td>
                        <input type="text" id="taxlabelssearch_items" value="<?php echo isset($tax["labels"]["search_items"]) ? $tax["labels"]["search_items"] : ""; ?>" name="tax[labels][search_items]" class="input-text-middle" />
                    </td>
                    <td style="width: 20px;"></td>
                    <td width="150"><?php _e("Popular Items", "gd-taxonomies-tools"); ?>:</td>
                    <td>
                        <input type="text" id="taxlabelspopular_items" value="<?php echo isset($tax["labels"]["popular_items"]) ? $tax["labels"]["popular_items"] : ""; ?>" name="tax[labels][popular_items]" class="input-text-middle" />
                    </td>
                </tr>
                <tr>
                    <td width="150"><?php _e("All Items", "gd-taxonomies-tools"); ?>:</td>
                    <td>
                        <input type="text" id="taxlabelsall_items" value="<?php echo isset($tax["labels"]["all_items"]) ? $tax["labels"]["all_items"] : ""; ?>" name="tax[labels][all_items]" class="input-text-middle" />
                    </td>
                    <td style="width: 20px;"></td>
                    <td width="150"><?php _e("Parent Item", "gd-taxonomies-tools"); ?>:</td>
                    <td>
                        <input type="text" id="taxlabelsparent_item" value="<?php echo isset($tax["labels"]["parent_item"]) ? $tax["labels"]["parent_item"] : ""; ?>" name="tax[labels][parent_item]" class="input-text-middle" />
                    </td>
                </tr>
                <tr>
                    <td width="150"><?php _e("Parent Item, Colon", "gd-taxonomies-tools"); ?>:</td>
                    <td>
                        <input type="text" id="taxlabelsparent_item_colon" value="<?php echo isset($tax["labels"]["parent_item_colon"]) ? $tax["labels"]["parent_item_colon"] : ""; ?>" name="tax[labels][parent_item_colon]" class="input-text-middle" />
                    </td>
                    <td style="width: 20px;"></td>
                    <td width="150"><?php _e("Edit Item", "gd-taxonomies-tools"); ?>:</td>
                    <td>
                        <input type="text" id="taxlabelsedit_item" value="<?php echo isset($tax["labels"]["edit_item"]) ? $tax["labels"]["edit_item"] : ""; ?>" name="tax[labels][edit_item]" class="input-text-middle" />
                    </td>
                </tr>
                <tr>
                    <td width="150"><?php _e("Update Item", "gd-taxonomies-tools"); ?>:</td>
                    <td>
                        <input type="text" id="taxlabelsupdate_item" value="<?php echo isset($tax["labels"]["update_item"]) ? $tax["labels"]["update_item"] : ""; ?>" name="tax[labels][update_item]" class="input-text-middle" />
                    </td>
                    <td style="width: 20px;"></td>
                    <td width="150"><?php _e("Add New Item", "gd-taxonomies-tools"); ?>:</td>
                    <td>
                        <input type="text" id="taxlabelsadd_new_item" value="<?php echo isset($tax["labels"]["add_new_item"]) ? $tax["labels"]["add_new_item"] : ""; ?>" name="tax[labels][add_new_item]" class="input-text-middle" />
                    </td>
                </tr>
                <tr>
                    <td width="150"><?php _e("New Item Name", "gd-taxonomies-tools"); ?>:</td>
                    <td>
                        <input type="text" id="taxlabelsnew_item_name" value="<?php echo isset($tax["labels"]["new_item_name"]) ? $tax["labels"]["new_item_name"] : ""; ?>" name="tax[labels][new_item_name]" class="input-text-middle" />
                    </td>
                    <td style="width: 20px;"></td>
                    <td width="150"><?php _e("Separate with Commas", "gd-taxonomies-tools"); ?>:</td>
                    <td>
                        <input type="text" id="taxlabelsseparate_items_with_commas" value="<?php echo isset($tax["labels"]["separate_items_with_commas"]) ? $tax["labels"]["separate_items_with_commas"] : ""; ?>" name="tax[labels][separate_items_with_commas]" class="input-text-middle" />
                    </td>
                </tr>
                <tr>
                    <td width="150"><?php _e("Add or Remove Items", "gd-taxonomies-tools"); ?>:</td>
                    <td>
                        <input type="text" id="taxlabelsadd_or_remove_items" value="<?php echo isset($tax["labels"]["add_or_remove_items"]) ? $tax["labels"]["add_or_remove_items"] : ""; ?>" name="tax[labels][add_or_remove_items]" class="input-text-middle" />
                    </td>
                    <td style="width: 20px;"></td>
                    <td width="150"><?php _e("Choose from Most Used", "gd-taxonomies-tools"); ?>:</td>
                    <td>
                        <input type="text" id="taxlabelschoose_from_most_used" value="<?php echo isset($tax["labels"]["choose_from_most_used"]) ? $tax["labels"]["choose_from_most_used"] : ""; ?>" name="tax[labels][choose_from_most_used]" class="input-text-middle" />
                    </td>
                </tr>
                <tr>
                    <td width="150"><?php _e("Menu Name", "gd-taxonomies-tools"); ?>:</td>
                    <td>
                        <input type="text" id="taxlabelsmenu_name" value="<?php echo isset($tax["labels"]["menu_name"]) ? $tax["labels"]["menu_name"] : ""; ?>" name="tax[labels][menu_name]" class="input-text-middle" />
                    </td>
                    <td style="width: 20px;"></td>
                    <td width="150"><?php _e("View Item", "gd-taxonomies-tools"); ?>:</td>
                    <td>
                        <input type="text" id="taxlabelsview_item" value="<?php echo isset($tax["labels"]["view_item"]) ? $tax["labels"]["view_item"] : ""; ?>" name="tax[labels][view_item]" class="input-text-middle" />
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr><th scope="row"><?php _e("Settings", "gd-taxonomies-tools"); ?></th>
        <td>
            <table cellpadding="0" cellspacing="0" class="previewtable">
                <tr>
                    <td style="width: 150px; vertical-align: top;">
                        <?php _e("Post Types", "gd-taxonomies-tools"); ?>:
                    </td>
                    <td colspan="3">
                        <?php
                            foreach ($post_types as $pt) {
                                echo sprintf('<input%s type="checkbox" value="%s" name="tax[post_type][]" /><label>%s [%s]</label><br/>',
                                        in_array($pt->name, $tax["domain"]) ? ' checked' : "",
                                        $pt->name, $pt->label, $pt->name);
                            }
                        ?>
                    </td>
                </tr>
            </table>
            <div class="gdsr-table-split"></div>
            <table cellpadding="0" cellspacing="0" class="previewtable">
                <tr>
                    <td width="150">
                        <?php _e("Hierarchical", "gd-taxonomies-tools"); ?>:
                    </td>
                    <td colspan="4">
                        <select name="tax[hierarchy]" class="input-text-middle">
                            <option value="no"<?php echo $tax["hierarchy"] == "no" ? ' selected="selected"' : ''; ?>><?php _e("No", "gd-taxonomies-tools"); ?></option>
                            <option value="yes"<?php echo $tax["hierarchy"] == "yes" ? ' selected="selected"' : ''; ?>><?php _e("Yes", "gd-taxonomies-tools"); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="150">
                        <?php _e("Query Variable", "gd-taxonomies-tools"); ?>:
                    </td>
                    <td>
                        <select name="tax[query]" class="input-text-middle">
                            <option value="yes_name"<?php echo $tax["query"] == "yes_name" ? ' selected="selected"' : ''; ?>><?php _e("Yes, using name", "gd-taxonomies-tools"); ?></option>
                            <option value="yes_custom"<?php echo $tax["query"] == "yes_custom" ? ' selected="selected"' : ''; ?>><?php _e("Yes, custom value", "gd-taxonomies-tools"); ?></option>
                            <option value="no"<?php echo $tax["query"] == "no" ? ' selected="selected"' : ''; ?>><?php _e("No", "gd-taxonomies-tools"); ?></option>
                        </select>
                    </td>
                    <td style="width: 20px;"></td>
                    <td width="150">
                        <?php _e("Custom", "gd-taxonomies-tools"); ?>:
                    </td>
                    <td>
                        <input type="text" value="<?php echo $tax["query_custom"]; ?>" name="tax[query_custom]" class="input-text-middle" />
                    </td>
                </tr>
            </table>
            <div class="gdsr-table-split"></div>
            <?php _e("For query custom variable use only lower case letters and no special characters except for the underscore.", "gd-taxonomies-tools"); ?>
        </td>
    </tr>
    <tr><th scope="row"><?php _e("Rewrite", "gd-taxonomies-tools"); ?></th>
        <td>
            <table cellpadding="0" cellspacing="0" class="previewtable">
                <tr>
                    <td width="150">
                        <?php _e("Rewrite", "gd-taxonomies-tools"); ?>:
                    </td>
                    <td>
                        <select name="tax[rewrite]" class="input-text-middle">
                            <option value="yes_name"<?php echo $tax["rewrite"] == "yes_name" ? ' selected="selected"' : ''; ?>><?php _e("Yes, using name", "gd-taxonomies-tools"); ?></option>
                            <option value="yes_custom"<?php echo $tax["rewrite"] == "yes_custom" ? ' selected="selected"' : ''; ?>><?php _e("Yes, custom value", "gd-taxonomies-tools"); ?></option>
                            <option value="no"<?php echo $tax["rewrite"] == "no" ? ' selected="selected"' : ''; ?>><?php _e("No", "gd-taxonomies-tools"); ?></option>
                        </select>
                    </td>
                    <td style="width: 20px;"></td>
                    <td width="150">
                        <?php _e("Custom", "gd-taxonomies-tools"); ?>:
                    </td>
                    <td>
                        <input type="text" value="<?php echo $tax["rewrite_custom"]; ?>" name="tax[rewrite_custom]" class="input-text-middle" />
                    </td>
                </tr>
                <tr>
                    <td width="150">
                        <?php _e("Hierarchy", "gd-taxonomies-tools"); ?>:
                    </td>
                    <td>
                        <select name="tax[rewrite_hierarchy]" class="input-text-middle">
                            <option value="auto"<?php echo $tax["rewrite_hierarchy"] == "auto" ? ' selected="selected"' : ''; ?>><?php _e("Automatic", "gd-taxonomies-tools"); ?></option>
                            <option value="no"<?php echo $tax["rewrite_hierarchy"] == "no" ? ' selected="selected"' : ''; ?>><?php _e("No", "gd-taxonomies-tools"); ?></option>
                        </select>
                    </td>
                    <td style="width: 20px;"></td>
                    <td width="150">
                        <?php _e("With Front", "gd-taxonomies-tools"); ?>:
                    </td>
                    <td>
                        <select name="tax[rewrite_front]" class="input-text-middle">
                            <option value="yes"<?php echo $tax["rewrite_front"] == "yes" ? ' selected="selected"' : ''; ?>><?php _e("Yes", "gd-taxonomies-tools"); ?></option>
                            <option value="no"<?php echo $tax["rewrite_front"] == "no" ? ' selected="selected"' : ''; ?>><?php _e("No", "gd-taxonomies-tools"); ?></option>
                        </select>
                    </td>
                </tr>
            </table>
            <div class="gdsr-table-split"></div>
            <?php _e("For rewrite variable use only lower case letters and no special characters except for the underscore.", "gd-taxonomies-tools"); ?><br />
        </td>
    </tr>
    <?php if ($wpv > 29) { ?>
    <tr><th scope="row"><?php _e("Visibility", "gd-taxonomies-tools"); ?></th>
        <td>
            <table cellpadding="0" cellspacing="0" class="previewtable">
                <tr>
                    <td width="150">
                        <?php _e("Public", "gd-taxonomies-tools"); ?>:
                    </td>
                    <td>
                        <select name="tax[public]" class="input-text-middle">
                            <option value="yes"<?php echo $tax["public"] == "yes" ? ' selected="selected"' : ''; ?>><?php _e("Yes", "gd-taxonomies-tools"); ?></option>
                            <option value="no"<?php echo $tax["public"] == "no" ? ' selected="selected"' : ''; ?>><?php _e("No", "gd-taxonomies-tools"); ?></option>
                        </select>
                    </td>
                    <td style="width: 20px;"></td>
                    <td width="150">
                        <?php _e("Show UI", "gd-taxonomies-tools"); ?>:
                    </td>
                    <td>
                        <select name="tax[ui]" class="input-text-middle">
                            <option value="yes"<?php echo $tax["ui"] == "yes" ? ' selected="selected"' : ''; ?>><?php _e("Yes", "gd-taxonomies-tools"); ?></option>
                            <option value="no"<?php echo $tax["ui"] == "no" ? ' selected="selected"' : ''; ?>><?php _e("No", "gd-taxonomies-tools"); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="150">
                        <?php _e("Tag Cloud Widget", "gd-taxonomies-tools"); ?>:
                    </td>
                    <td>
                        <select name="tax[cloud]" class="input-text-middle">
                            <option value="yes"<?php echo $tax["cloud"] == "yes" ? ' selected="selected"' : ''; ?>><?php _e("Yes", "gd-taxonomies-tools"); ?></option>
                            <option value="no"<?php echo $tax["cloud"] == "no" ? ' selected="selected"' : ''; ?>><?php _e("No", "gd-taxonomies-tools"); ?></option>
                        </select>
                    </td>
                    <td style="width: 20px;"></td>
                    <td width="150">
                        <?php _e("Navigation Menus", "gd-taxonomies-tools"); ?>:
                    </td>
                    <td>
                        <select name="tax[nav_menus]" class="input-text-middle">
                            <option value="yes"<?php echo $tax["nav_menus"] == "yes" ? ' selected="selected"' : ''; ?>><?php _e("Yes", "gd-taxonomies-tools"); ?></option>
                            <option value="no"<?php echo $tax["nav_menus"] == "no" ? ' selected="selected"' : ''; ?>><?php _e("No", "gd-taxonomies-tools"); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="150">
                        <?php _e("Post edit column", "gd-taxonomies-tools"); ?>:
                    </td>
                    <td>
                        <select name="tax[show_admin_menu]" class="input-text-middle">
                            <option value="yes"<?php echo $tax["show_admin_menu"] == "yes" ? ' selected="selected"' : ''; ?>><?php _e("Yes", "gd-taxonomies-tools"); ?></option>
                            <option value="no"<?php echo $tax["show_admin_menu"] == "no" ? ' selected="selected"' : ''; ?>><?php _e("No", "gd-taxonomies-tools"); ?></option>
                        </select>
                    </td>
                    <td colspan="3"></td>
                </tr>
            </table>
            <div class="gdsr-table-split"></div>
            <?php _e("Public setting set to NO will hide taxonomies from the admin UI.", "gd-taxonomies-tools"); ?><br/>
            <?php _e("Tag cloud option set to NO will exclude this taxonomy from default Tag Cloud widget. Same goes for Navigation Menus.", "gd-taxonomies-tools"); ?>
        </td>
    </tr>
    <tr><th scope="row"><?php _e("Capabilities", "gd-taxonomies-tools"); ?></th>
        <td>
            <table cellpadding="0" cellspacing="0" class="previewtable">
                <tr>
                    <td width="150"><?php _e("Manage terms", "gd-taxonomies-tools"); ?>:</td>
                    <td>
                        <input type="text" id="taxcapsmanage_terms" value="<?php echo $tax["caps"]["manage_terms"]; ?>" name="tax[caps][manage_terms]" class="input-text-middle" />
                    </td>
                    <td style="width: 20px;"></td>
                    <td width="150"><?php _e("Edit terms", "gd-taxonomies-tools"); ?>:</td>
                    <td>
                        <input type="text" id="taxcapsedit_terms" value="<?php echo $tax["caps"]["edit_terms"]; ?>" name="tax[caps][edit_terms]" class="input-text-middle" />
                    </td>
                </tr>
                <tr>
                    <td width="150"><?php _e("Delete terms", "gd-taxonomies-tools"); ?>:</td>
                    <td>
                        <input type="text" id="taxcapsdelete_terms" value="<?php echo $tax["caps"]["delete_terms"]; ?>" name="tax[caps][delete_terms]" class="input-text-middle" />
                    </td>
                    <td style="width: 20px;"></td>
                    <td width="150"><?php _e("Assign terms", "gd-taxonomies-tools"); ?>:</td>
                    <td>
                        <input type="text" id="taxcapsassign_terms" value="<?php echo $tax["caps"]["assign_terms"]; ?>" name="tax[caps][assign_terms]" class="input-text-middle" />
                    </td>
                </tr>
            </table>
            <div class="gdsr-table-split"></div>
            <div class="inputbutton"><a href="javascript:capabilities_taxonomy()"><?php _e("Reset capabailities", "gd-taxonomies-tools"); ?></a></div>
            <div class="gdsr-table-split"></div>
            <?php _e("Do not change any of these if you are not sure what they are. All values must be filled.", "gd-taxonomies-tools"); ?>
        </td>
    </tr>
    <tr><th scope="row"><?php _e("Advanced", "gd-taxonomies-tools"); ?></th>
        <td>
            <table cellpadding="0" cellspacing="0" class="previewtable">
                <tr>
                    <td width="150">
                        <?php _e("Save Sort Order", "gd-taxonomies-tools"); ?>:
                    </td>
                    <td colspan="4">
                        <select name="tax[sort]" class="input-text-middle">
                            <option value="no"<?php echo $tax["sort"] == "no" ? ' selected="selected"' : ''; ?>><?php _e("No", "gd-taxonomies-tools"); ?></option>
                            <option value="yes"<?php echo $tax["sort"] == "yes" ? ' selected="selected"' : ''; ?>><?php _e("Yes", "gd-taxonomies-tools"); ?></option>
                        </select>
                    </td>
                </tr>
            </table>
            <div class="gdsr-table-split"></div>
            <?php _e("Changing some of these settings can have undesired effects and can break your website.", "gd-taxonomies-tools"); ?> <?php _e("Do not change this if you are not sure about this. Consult WordPress documentation for more details.", "gd-taxonomies-tools"); ?>
        </td>
    </tr>
    <?php } ?>
    </tbody></table>
    <input type="submit" class="inputbutton" value="<?php _e("Save Taxonomy", "gd-taxonomies-tools"); ?>" name="gdtt_savetax" style="margin-top: 10px;" />
</form>
