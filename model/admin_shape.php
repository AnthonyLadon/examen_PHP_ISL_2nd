<?php
/*vérifier si $_SESSION["admin_id"] existe
sinon, redirection vers le form login*/
adminProtection();
// ajout de la librairie de fonctions qu'on utilisera dans ce modele
include_once("lib/shape.php");

// variable qui stocke le parametre "p" qui sera ajouté dans l'url
$url_page = "admin_shape";

/*Récupération/initialisation du paramètre "action" qui sera a jouté à l'url et qui va permettre de
selectionner (grace au switch) la partie de code qui sera a exécuter (selon l'action choisie).
Si aucune action n'est définie via le paramètre _GET alors l'action "liste" sera attribuée par défaut*/
$get_action     = isset($_GET["action"]) ? $_GET["action"] : "list";
// recuperation de l'ID passé en parametre dans l'url
$get_shape_id   = isset($_GET["shape_id"]) ? filter_input(INPUT_GET, 'shape_id', FILTER_SANITIZE_NUMBER_INT) : null;
$get_alpha      = isset($_GET["alpha"]) ? filter_input(INPUT_GET, 'alpha', FILTER_SANITIZE_SPECIAL_CHARS)   : "A";

// swich qui va definir le code a executer selon le parametre action passé dans l'url
switch($get_action){
    case "list":
        $result = getShape();

        $page_view = "shape_liste";
        break;

    case "add":

        $post_shape_title = isset($_POST["shape_title"]) ? filter_input(INPUT_POST, 'shape_title', FILTER_SANITIZE_SPECIAL_CHARS) : null;
        $post_description = isset($_POST["description"]) ? filter_input(INPUT_POST, 'description', FILTER_SANITIZE_SPECIAL_CHARS) : null;


        $input = [];

        $input[] = addLayout("<h4>Ajouter d'un état</h4>");
        $input[] = addLayout("<div class='row'>");
        $input[] = addInput("Etat", ["type" => "text", "value" => $post_shape_title, "name" => "shape_title", "class" => "u-full-width"], true, "four columns");
        $input[] = addTextarea('Description', array("name" => "description", "class" => "u-full-width"), $post_description, true, "eight columns");
        $input[] = addLayout("</div>");
        $input[] = addSubmit(["value" => "envoyer", "name" => "submit"], "\t\t<br />\n");

        $show_form = form("form_contact", "index.php?p=".$url_page."&action=add", "post", $input);

        if($show_form != false){
            $page_view = "shape_form";
        }else{
            $data_values = array();
            $data_values["shape_title"] = $post_shape_title;
            $data_values["description"] = $post_description;

            if(insertShape($data_values)){
                $msg = "<p>données insérées avec succès</p>";
                $msg_class = "success";
            }else{
                $msg = "<p>erreur lors de l'insertion des données</p>";
                $msg_class = "error";
            }

            $result = getShape();

            $page_view = "shape_liste";
        }
        break;

    case "update":

        if(empty($_POST)){
            $result = getShape($get_shape_id);

            $post_shape_title = $result[0]["shape_title"];
            $post_description = $result[0]["description"];
        }else{
            $post_shape_title = isset($_POST["shape_title"]) ? filter_input(INPUT_POST, 'shape_title', FILTER_SANITIZE_SPECIAL_CHARS) : null;
            $post_description = isset($_POST["description"]) ? filter_input(INPUT_POST, 'description', FILTER_SANITIZE_SPECIAL_CHARS) : null;
        }

        $input = [];
        $input[] = addLayout("<h4>Modifier un shape</h4>");
        $input[] = addLayout("<div class='row'>");
        $input[] = addInput('Etat', ["type" => "text", "value" => $post_shape_title, "name" => "shape_title", "class" => "u-full-width"], true, "four columns");
        $input[] = addTextarea('Description', array("name" => "description", "class" => "u-full-width"), $post_description, true, "eight columns");
        $input[] = addLayout("</div>");
        $input[] = addSubmit(["value" => "envoyer", "name" => "submit"], "\t\t<br />\n");

        $show_form = form("form_contact", "index.php?p=".$url_page."&action=update&shape_id=".$get_shape_id, "post", $input);

        if($show_form != false){
            $page_view = "shape_form";
        }else{
            $data_values = array();
            $data_values["shape_title"] = $post_shape_title;
            $data_values["description"] = $post_description;

            if(updateShape($get_shape_id, $data_values)){
                $msg = "<p>données modifiées avec succès</p>";
                $msg_class = "success";
            }else{
                $msg = "<p>erreur lors de la modification des données</p>";
                $msg_class = "error";
            }

            $result = getShape();

            $page_view = "shape_liste";
        }


        break;

    case "showHide":

        if(showHideShape($get_shape_id)){
            $msg = "<p>mise à jour de l'état réalisée avec succès</p>";
            $msg_class = "success";
        }else{
            $msg = "<p>erreur lors de la mise à jour de l'état</p>";
            $msg_class = "error";
        }

        $result = getShape();

        $page_view = "shape_liste";

        break;
}

?>