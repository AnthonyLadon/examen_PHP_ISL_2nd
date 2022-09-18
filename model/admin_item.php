<?php
/*vérifier si $_SESSION["admin_id"] existe
sinon, redirection vers le form login*/
adminProtection();
include_once("lib/item.php");

$url_page = "admin_item";

// récupération/initialisation du paramètre "action" qui va permettre de diriger dans le switch vers la partie de code qui sera a exécuter
// si aucune action n'est définie via le paramètre _GET alors l'action "liste" sera attribuée par défaut
$get_action     = isset($_GET["action"]) ? $_GET["action"] : "list";
// récupération des informations passées en _GET
$get_item_id   = isset($_GET["item_id"]) ? filter_input(INPUT_GET, 'item_id', FILTER_SANITIZE_NUMBER_INT) : null;
$get_alpha      = isset($_GET["alpha"]) ? filter_input(INPUT_GET, 'alpha', FILTER_SANITIZE_SPECIAL_CHARS)   : "A";


switch($get_action){
    case "list":
        // récupération des item correspondant
        $result = getItem();

        $page_view = "item_liste";
        break;

    case "add":
        // récupération / initialisation des données qui transitent via le formulaire
        $post_item_title = isset($_POST["item_title"]) ? filter_input(INPUT_POST, 'item_title', FILTER_SANITIZE_SPECIAL_CHARS) : null;
        $post_item_description = isset($_POST["item_description"]) ? filter_input(INPUT_POST, 'item_description', FILTER_SANITIZE_SPECIAL_CHARS) : null;

        // initialisation du array qui contiendra la définitions des différents champs du formulaire
        $input = [];
        // ajout des différents champs du formulaire
        $input[] = addLayout("<h4>Ajouter une page</h4>");
        $input[] = addLayout("<div class='row'>");
        $input[] = addInput("Titre de la page", ["type" => "text", "value" => $post_item_title, "name" => "item_title", "class" => "u-full-width"], true, "twelve columns");
        $input[] = addTextarea('Texte de la page', array("name" => "item_description", "class" => "u-full-width"), $post_item_description, true, "twelve columns");
        $input[] = addLayout("</div>");
        $input[] = addSubmit(["value" => "envoyer", "name" => "submit"], "\t\t<br />\n");
        // appel de la fonction form qui est chargée de générer le formulaire à partir du array de définition des champs $input ainsi que de la vérification de la validitée des données si le formulaire été soumis
        $show_form = form("form_contact", "index.php?p=".$url_page."&action=add", "post", $input);
        // si form() ne retourne pas false et retourne un string alors le formulaire doit être affiché
        if($show_form != false){
            // définition de la variable view qui sera utilisée pour afficher la partie du <body> du html nécessaire à l'affichage du formulaire
            $page_view = "item_form";

            // si form() retourne false, l'insertion peut avoir lieu
        }else{
            // création d'un tableau qui contiendra les données à passer à la fonction d'insert
            $data_values = array();
            $data_values["item_title"] = $post_item_title;
            $data_values["item_description"] = $post_item_description;
            // exécution de la requête
            if(insertItem($data_values)){
                // message de succes qui sera affiché dans le <body>
                $msg = "<p>données insérées avec succès</p>";
                $msg_class = "success";
            }else{
                // message d'erreur qui sera affiché dans le <body>
                $msg = "<p>erreur lors de l'insertion des données</p>";
                $msg_class = "error";
            }

            // récupération des item correspondant
            $result = getItem();

            $page_view = "item_liste";
        }
        break;

    case "update":

        if(empty($_POST)){
            $result = getItem($get_item_id);

            $post_item_title = $result[0]["item_title"];
            $post_item_description = $result[0]["item_description"];
        }else{
            // récupération / initialisation des données qui transitent via le formulaire
            $post_item_title = isset($_POST["item_title"]) ? filter_input(INPUT_POST, 'item_title', FILTER_SANITIZE_SPECIAL_CHARS) : null;
            $post_item_description = isset($_POST["item_description"]) ? filter_input(INPUT_POST, 'item_description', FILTER_SANITIZE_SPECIAL_CHARS) : null;
        }



        // initialisation du array qui contiendra la définitions des différents champs du formulaire
        $input = [];
        // ajout des différents champs du formulaire
        $input[] = addLayout("<h4>Modifier un item</h4>");
        $input[] = addLayout("<div class='row'>");
        $input[] = addInput('Titre de la page', ["type" => "text", "value" => $post_item_title, "name" => "item_title", "class" => "u-full-width"], true, "twelve columns");
        $input[] = addTextarea('Texte de la page', array("name" => "item_description", "class" => "u-full-width"), $post_item_description, true, "twelve columns");
        $input[] = addLayout("</div>");
        $input[] = addSubmit(["value" => "envoyer", "name" => "submit"], "\t\t<br />\n");
        // appel de la fonction form qui est chargée de générer le formulaire à partir du array de définition des champs $input ainsi que de la vérification de la validitée des données si le formulaire été soumis
        $show_form = form("form_contact", "index.php?p=".$url_page."&action=update&item_id=".$get_item_id, "post", $input);
        // si form() ne retourne pas false et retourne un string alors le formulaire doit être affiché
        if($show_form != false){
            // définition de la variable view qui sera utilisée pour afficher la partie du <body> du html nécessaire à l'affichage du formulaire
            $page_view = "item_form";

            // si form() retourne false, l'insertion peut avoir lieu
        }else{
            $data_values = array();
            $data_values["item_title"] = $post_item_title;
            $data_values["item_description"] = $post_item_description;
            // exécution de la requête
            if(updateItem($get_item_id, $data_values)){
                // message de succes qui sera affiché dans le <body>
                $msg = "<p>données modifiées avec succès</p>";
                $msg_class = "success";
            }else{
                // message d'erreur qui sera affiché dans le <body>
                $msg = "<p>erreur lors de la modification des données</p>";
                $msg_class = "error";
            }

            // récupération des item correspondant
            $result = getItem();

            $page_view = "item_liste";
        }


        break;

    case "showHide":
        if(showHideItem($get_item_id)){
            // message de succes qui sera affiché dans le <body>
            $msg = "<p>mise à jour de l'état réalisée avec succès</p>";
            $msg_class = "success";
        }else{
            // message d'erreur qui sera affiché dans le <body>
            $msg = "<p>erreur lors de la mise à jour de l'état</p>";
            $msg_class = "error";
        }

        // récupération des item correspondant
        $result = getItem();

        $page_view = "item_liste";

        break;
}

?>