<?php

if (!defined('ABSPATH')) exit;

class gdtt_CustomPost {
    var $name;
    var $label;
    var $public;
    var $show_ui;
    var $hierarchical;

    function gdtt_CustomPost($saved_cpt) {
        $this->name = $saved_cpt["name"];
        $this->label = $saved_cpt["labels"]["name"];
        $this->public = $saved_cpt["public"] == "yes";
        $this->show_ui = !isset($saved_cpt["ui"]) ? $saved_cpt["public"] : $saved_cpt["ui"] == "yes";
        $this->hierarchical = $saved_cpt["hierarchy"] == "yes";
    }
}

class gdtt_Taxonomy {
    var $name;
    var $label;
    var $object_type;
    var $hierarchical;
    var $rewrite;
    var $query_var;

    function gdtt_Taxonomy($saved_tax) {
        $this->name = $saved_tax["name"];
        $this->label = $saved_tax["label"];
        $this->object_type = $saved_tax["domain"];
        $this->hierarchical = $saved_tax["hierarchy"];
        $this->rewrite = $rewrite;
        $this->query_var = $query_var;
    }
}

?>