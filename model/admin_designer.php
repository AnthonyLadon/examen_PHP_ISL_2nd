<?php
/*vérifier si $_SESSION["admin_id"] existe
sinon, redirection vers le form login*/
adminProtection();
// ajout de la librairie de fonctions qu'on utilisera dans ce modele
include_once("lib/designer.php");

// variable qui stocke le parametre "p" qui sera ajouté dans l'url
$url_page = "admin_designer";

/*Récupération/initialisation du paramètre "action" qui sera a jouté à l'url et qui va permettre de
selectionner (grace au switch) la partie de code qui sera a exécuter (selon l'action choisie).
Si aucune action n'est définie via le paramètre _GET alors l'action "liste" sera attribuée par défaut*/
$get_action = isset($_GET["action"]) ? filter_input(INPUT_GET, 'action', FILTER_SANITIZE_SPECIAL_CHARS) : "liste";
// recuperation de l'ID passé en parametre dans l'url
$get_id     = isset($_GET["id"])     ? filter_input(INPUT_GET, 'id', FILTER_SANITIZE_SPECIAL_CHARS)      : null;
$get_alpha  = isset($_GET["alpha"])  ? filter_input(INPUT_GET, 'alpha', FILTER_SANITIZE_SPECIAL_CHARS)   : "A";

// swich qui va definir le code a executer selon le parametre action passé dans l'url
switch($get_action){
    case "liste":

        $page_view = "designer_liste";
        $alphabet = range('A', 'Z');

        $result = getDesigner(0, $get_alpha);

        if(!is_null($get_id) && is_numeric($get_id)){
            $result_detail = getDesigner($get_id);

            $detail_prenom      = $result_detail[0]["prenom"];
            $detail_nom         = $result_detail[0]["nom"];
            $detail_description = $result_detail[0]["description"];

            $show_description = true;
        }
        break;

    case "add":

        $post_nom         = isset($_POST["nom"])          ? filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_SPECIAL_CHARS)                : null;
        $post_prenom      = isset($_POST["prenom"])       ? filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_SPECIAL_CHARS)             : null;
        $post_description = isset($_POST["description"])  ? filter_input(INPUT_POST, 'description', FILTER_SANITIZE_SPECIAL_CHARS)        : null;


        $input = [];
        // ajout des différents champs du formulaire
        $input[] = addLayout("<h4>Ajouter d'un designer</h4>");
        $input[] = addLayout("<div class='row'>");
        $input[] = addInput('Nom', ["type" => "text", "value" => $post_nom, "name" => "nom", "class" => "u-full-width"], true, "six columns");
        $input[] = addInput('Prénom', ["type" => "text", "value" => $post_prenom, "name" => "prenom", "class" => "u-full-width"], true, "six columns");
        $input[] = addLayout("</div>");
        $input[] = addLayout("<div class='row'>");
        $input[] = addTextarea('Parcours / profil', array("name" => "description", "class" => "u-full-width"), $post_description, true, "twelve columns");
        $input[] = addLayout("</div>");
        $input[] = addSubmit(["value" => "envoyer", "name" => "submit"], "\t\t<br />\n");

        $show_form = form("form_contact", "index.php?p=".$url_page."&action=add", "post", $input);

        if($show_form != false){
            // définition de la variable view qui sera utilisée pour afficher la partie du <body> du html nécessaire à l'affichage du formulaire
            $page_view = "designer_form";

            // si form() retourne false, l'insertion peut avoir lieu
        }else{
            // création d'un tableau qui contiendra les données à passer à la fonction d'insert
            $data_values                = array();
            $data_values["prenom"]      = $post_prenom;
            $data_values["nom"]         = $post_nom;
            $data_values["description"] = $post_description;
            // exécution de la requête
            if(insertDesigner($data_values)){
                // message de succes qui sera affiché dans le <body>
                $msg = "<p>données insérées avec succès</p>";
                $msg_class = "success";
            }else{
                // message d'erreur qui sera affiché dans le <body>
                $msg = "<p>erreur lors de l'insertion des données</p>";
                $msg_class = "error";
            }

            $page_view = "designer_liste";
            $result = getDesigner(0, $get_alpha);
            $alphabet = range('A', 'Z');

            if(!is_null($get_id) && is_numeric($get_id)){
                $result_detail = getDesigner($get_id);

                $detail_prenom      = $result_detail[0]["prenom"];
                $detail_nom         = $result_detail[0]["nom"];
                $detail_description = $result_detail[0]["description"];

                $show_description = true;
            }
        }
        break;

    case "update":

        $get_designer_id = isset($_GET["designer_id"]) ? filter_input(INPUT_GET, 'designer_id', FILTER_SANITIZE_NUMBER_INT) : null;

        if(empty($_POST)){
            $result = getDesigner($get_designer_id);

            $firstname    = $result[0]["prenom"];
            $lastname    = $result[0]["nom"];
            $description = $result[0]["description"];
        }else{
            $firstname    = null;
            $lastname    = null;
            $description = null;
        }

        $post_nom         = isset($_POST["nom"])          ? filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_SPECIAL_CHARS)                : $lastname;
        $post_prenom      = isset($_POST["prenom"])       ? filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_SPECIAL_CHARS)             : $firstname;
        $post_description = isset($_POST["description"])  ? filter_input(INPUT_POST, 'description', FILTER_SANITIZE_SPECIAL_CHARS)        : $description;

        $input = [];

        $input[] = addLayout("<h4>Modifier un designer</h4>");
        $input[] = addLayout("<div class='row'>");
        $input[] = addInput('Nom', ["type" => "text", "value" => $post_nom, "name" => "nom", "class" => "u-full-width"], true, "six columns");
        $input[] = addInput('Prénom', ["type" => "text", "value" => $post_prenom, "name" => "prenom", "class" => "u-full-width"], true, "six columns");
        $input[] = addLayout("</div>");
        $input[] = addLayout("<div class='row'>");
        $input[] = addTextarea('Parcours / profil', array("name" => "description", "class" => "u-full-width"), $post_description, true, "twelve columns");
        $input[] = addLayout("</div>");
        $input[] = addSubmit(["value" => "envoyer", "name" => "submit"], "\t\t<br />\n");
        // appel de la fonction form qui est chargée de générer le formulaire à partir du array de définition des champs $input ainsi que de la vérification de la validitée des données si le formulaire été soumis
        $show_form = form("form_contact", "index.php?p=".$url_page."&action=update&designer_id=".$get_designer_id."&id=".$get_id."&alpha=".$get_alpha, "post", $input);

        if($show_form != false){
            // définition de la variable view qui sera utilisée pour afficher la partie du <body> du html nécessaire à l'affichage du formulaire
            $page_view = "designer_form";
        }else{
            $data_values                = array();
            $data_values["prenom"]      = $post_prenom;
            $data_values["nom"]         = $post_nom;
            $data_values["description"] = $post_description;

            if(updateDesigner($get_designer_id, $data_values)){
                // message de succes qui sera affiché dans le <body>
                $msg = "<p>données modifiée avec succès</p>";
                $msg_class = "success";
            }else{
                // message d'erreur qui sera affiché dans le <body>
                $msg = "<p>erreur lors de la modification des données</p>";
                $msg_class = "error";
            }

            $page_view = "designer_liste";

            $result = getDesigner(0, $get_alpha);

            $alphabet = range('A', 'Z');

            if(!is_null($get_id) && is_numeric($get_id)){
                $result_detail = getDesigner($get_id);

                $detail_prenom      = $result_detail[0]["prenom"];
                $detail_nom         = $result_detail[0]["nom"];
                $detail_description = $result_detail[0]["description"];

                $show_description = true;
            }
        }

        break;

    case "showHide":

        $get_designer_id = isset($_GET["designer_id"]) ? filter_input(INPUT_GET, 'designer_id', FILTER_SANITIZE_NUMBER_INT) : null;

        if(showHideDesigner($get_designer_id)){
            // message de succes qui sera affiché dans le <body>
            $msg = "<p>mise à jour de l'état réalisée avec succès</p>";
            $msg_class = "success";
        }else{
            // message d'erreur qui sera affiché dans le <body>
            $msg = "<p>erreur lors de la mise à jour de l'état</p>";
            $msg_class = "error";
        }

        $page_view = "designer_liste";

        $result = getDesigner(0, $get_alpha);

        $alphabet = range('A', 'Z');

        if(!is_null($get_id) && is_numeric($get_id)){
            $result_detail = getDesigner($get_id);

            $detail_prenom      = $result_detail[0]["prenom"];
            $detail_nom         = $result_detail[0]["nom"];
            $detail_description = $result_detail[0]["description"];

            $show_description = true;
        }

        break;
}

?>
