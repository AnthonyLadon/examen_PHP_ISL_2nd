<?php
adminProtection();
include_once("lib/manufacturer.php");

$url_page = "admin_manufacturer";

$get_action = isset($_GET["action"]) ? filter_input(INPUT_GET, 'action', FILTER_SANITIZE_SPECIAL_CHARS) : "liste";


$get_id     = isset($_GET["id"])    ? filter_input(INPUT_GET, 'id', FILTER_SANITIZE_SPECIAL_CHARS)      : null;
$get_alpha  = isset($_GET["alpha"]) ? filter_input(INPUT_GET, 'alpha', FILTER_SANITIZE_SPECIAL_CHARS)   : "A";

switch($get_action){
    case "liste":
        $page_view = "manufacturer_liste";

        $alphabet = range('A', 'Z');

        $result = getManufacturer(0, $get_alpha);

        if(!is_null($get_id) && is_numeric($get_id)){
            $result_detail = getManufacturer($get_id);
            $detail_manufacturer   = $result_detail[0]["manufacturer"];
            $detail_description    = $result_detail[0]["description"];
            $show_description = true;
        }
        break;

    case "add":
        $post_manufacturer  = isset($_POST["manufacturer"])   ? filter_input(INPUT_POST, 'manufacturer', FILTER_SANITIZE_SPECIAL_CHARS)             : null;
        $post_description   = isset($_POST["description"])  ? filter_input(INPUT_POST, 'description', FILTER_SANITIZE_SPECIAL_CHARS)    : null;

        // initialisation du array qui contiendra la définitions des différents champs du formulaire
        $input = [];
        // ajout des différents champs du formulaire
        $input[] = addLayout("<h4>Ajouter d'un manufacturer</h4>");
        $input[] = addLayout("<div class='row'>");
        $input[] = addInput('Manufacture', ["type" => "text", "value" => $post_manufacturer, "name" => "manufacturer", "class" => "u-full-width"], true, "twelve columns");
        $input[] = addLayout("</div>");
        $input[] = addLayout("<div class='row'>");
        $input[] = addTextarea('Parcours / profil', array("name" => "description", "class" => "u-full-width"), $post_description, true, "twelve columns");
        $input[] = addLayout("</div>");
        $input[] = addSubmit(["value" => "envoyer", "name" => "submit"], "\t\t<br />\n");

        $show_form = form("form_contact", "index.php?p=".$url_page."&action=add", "post", $input);

        if($show_form != false){
            $page_view = "manufacturer_form";
        }else{
            $data_values                    = array();
            $data_values["manufacturer"]    = $post_manufacturer;
            $data_values["description"]     = $post_description;
            if(insertManufacturer($data_values)){
                $msg = "<p>données insérées avec succès</p>";
                $msg_class = "success";
            }else{
                $msg = "<p>erreur lors de l'insertion des données</p>";
                $msg_class = "error";
            }
            $page_view = "manufacturer_liste";

            $alphabet = range('A', 'Z');

            $result = getManufacturer(0, $get_alpha);

            if(!is_null($get_id) && is_numeric($get_id)){
                $result_detail = getManufacturer($get_id);
                $detail_manufacturer   = $result_detail[0]["manufacturer"];
                $detail_description    = $result_detail[0]["description"];
                $show_description = true;
            }
        }
        break;

    case "update":
        $get_manufacturer_id = isset($_GET["manufacturer_id"]) ? filter_input(INPUT_GET, 'manufacturer_id', FILTER_SANITIZE_NUMBER_INT) : null;

        if(empty($_POST)){
            $result = getManufacturer($get_manufacturer_id);

            $manufacturer      = $result[0]["manufacturer"];
            $description    = $result[0]["description"];
        }else{
            $manufacturer      = null;
            $lastname       = null;
            $description    = null;
        }

        $post_manufacturer        = isset($_POST["manufacturer"])   ? filter_input(INPUT_POST, 'manufacturer', FILTER_SANITIZE_SPECIAL_CHARS)             : $manufacturer;
        $post_description   = isset($_POST["description"])  ? filter_input(INPUT_POST, 'description', FILTER_SANITIZE_SPECIAL_CHARS)    : $description;

        $input = [];
        $input[] = addLayout("<h4>Modifier un manufacturer</h4>");
        $input[] = addLayout("<div class='row'>");
        $input[] = addInput('Manufacture', ["type" => "text", "value" => $post_manufacturer, "name" => "manufacturer", "class" => "u-full-width"], true, "twelve columns");
        $input[] = addLayout("</div>");
        $input[] = addLayout("<div class='row'>");
        $input[] = addTextarea('Parcours / profil', array("name" => "description", "class" => "u-full-width"), $post_description, true, "twelve columns");
        $input[] = addLayout("</div>");
        $input[] = addSubmit(["value" => "envoyer", "name" => "submit"], "\t\t<br />\n");
        $show_form = form("form_contact", "index.php?p=".$url_page."&action=update&manufacturer_id=".$get_manufacturer_id."&id=".$get_id."&alpha=".$get_alpha, "post", $input);

        if($show_form != false){
            $page_view = "manufacturer_form";
        }else{
            $data_values                 = array();
            $data_values["manufacturer"] = $post_manufacturer;
            $data_values["description"]  = $post_description;

            if(updateManufacturer($get_manufacturer_id, $data_values)){
                // message de succes qui sera affiché dans le <body>
                $msg = "<p>données modifiée avec succès</p>";
                $msg_class = "success";
            }else{
                // message d'erreur qui sera affiché dans le <body>
                $msg = "<p>erreur lors de la modification des données</p>";
                $msg_class = "error";
            }
            $page_view = "manufacturer_liste";

            $alphabet = range('A', 'Z');

            $result = getManufacturer(0, $get_alpha);

            if(!is_null($get_id) && is_numeric($get_id)){
                $result_detail = getManufacturer($get_id);
                $detail_manufacturer   = $result_detail[0]["manufacturer"];
                $detail_description    = $result_detail[0]["description"];
                $show_description = true;
            }
        }

        break;

    case "showHide":
        $get_manufacturer_id = isset($_GET["manufacturer_id"]) ? filter_input(INPUT_GET, 'manufacturer_id', FILTER_SANITIZE_NUMBER_INT) : null;

        if(showHideManufacturer($get_manufacturer_id)){
            // message de succes qui sera affiché dans le <body>
            $msg = "<p>mise à jour de l'état réalisée avec succès</p>";
            $msg_class = "success";
        }else{
            // message d'erreur qui sera affiché dans le <body>
            $msg = "<p>erreur lors de la mise à jour de l'état</p>";
            $msg_class = "error";
        }
        $page_view = "manufacturer_liste";

        $alphabet = range('A', 'Z');

        $result = getManufacturer(0, $get_alpha);

        if(!is_null($get_id) && is_numeric($get_id)){
            $result_detail = getManufacturer($get_id);
            $detail_manufacturer   = $result_detail[0]["manufacturer"];
            $detail_description    = $result_detail[0]["description"];
            $show_description = true;
        }

        break;
}

?>