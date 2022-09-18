<?php
/*vérifier si $_SESSION["admin_id"] existe
sinon, redirection vers le form login*/
adminProtection();
// ajout de la librairie de fonctions qu'on utilisera dans ce modele
include_once("lib/admin.php");

// variable qui stocke le parametre "p" qui sera ajouté dans l'url
$url_page = "admin_admin";

/*Récupération/initialisation du paramètre "action" qui sera a jouté à l'url et qui va permettre de
selectionner (grace au switch) la partie de code qui sera a exécuter (selon l'action choisie).
Si aucune action n'est définie via le paramètre _GET alors l'action "liste" sera attribuée par défaut*/
$get_action     = isset($_GET["action"]) ? $_GET["action"] : "list";
// recuperation de l'ID passé en parametre dans l'url
$get_admin_id   = isset($_GET["admin_id"]) ? filter_input(INPUT_GET, 'admin_id', FILTER_SANITIZE_NUMBER_INT) : null;
$get_alpha      = isset($_GET["alpha"]) ? filter_input(INPUT_GET, 'alpha', FILTER_SANITIZE_SPECIAL_CHARS)   : "A";

// swich qui va definir le code a executer selon le parametre action passé dans l'url
switch($get_action){
    case "list":

        $alphabet = range('A', 'Z');
        $result = getAdmin(null, $get_alpha);

        $page_view = "admin_liste";
        break;

    case "add":

        $post_login        = isset($_POST["login"]) ? filter_input(INPUT_POST, 'login', FILTER_SANITIZE_SPECIAL_CHARS) : null;
        $post_password     = isset($_POST["password"]) ? filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS) : null;
        $post_level_access = isset($_POST["level_access"]) ? filter_input(INPUT_POST, 'level_access', FILTER_SANITIZE_SPECIAL_CHARS) : null;
        $post_pseudo       = isset($_POST["pseudo"]) ? filter_input(INPUT_POST, 'pseudo', FILTER_SANITIZE_SPECIAL_CHARS) : null;
        $post_street       = isset($_POST["street"]) ? filter_input(INPUT_POST, 'street', FILTER_SANITIZE_SPECIAL_CHARS) : null;
        $post_num          = isset($_POST["num"]) ? filter_input(INPUT_POST, 'num', FILTER_SANITIZE_SPECIAL_CHARS) : null;
        $post_zip          = isset($_POST["zip"]) ? filter_input(INPUT_POST, 'zip', FILTER_SANITIZE_SPECIAL_CHARS) : null;
        $post_country      = isset($_POST["country"]) ? filter_input(INPUT_POST, 'country', FILTER_SANITIZE_SPECIAL_CHARS) : null;

        $input = [];
        $input[] = addLayout("<h4>Ajouter d'un admin</h4>");
        $input[] = addLayout("<div class='row'>");
        $input[] = addInput('Identifiant / E-mail', ["type" => "email", "value" => $post_login, "name" => "login", "class" => "u-full-width"], true, "four columns");
        $input[] = addInput('Mot-de-passe', ["type" => "password", "value" => $post_password, "name" => "password", "class" => "u-full-width"], true, "four columns");
        $input[] = addInput('Pseudo', ["type" => "text", "value" => $post_pseudo, "name" => "pseudo", "class" => "u-full-width"], true, "four columns");
        $input[] = addLayout("</div>");
        $input[] = addLayout("<div class='row'>");
        $input[] = addRadioCheckbox('Niveau d\'acces', ["name" => $post_level_access], ["1" => "Super administrateur", "2" => "simple admin"], "2", true, "radio", "twelves columns");
        $input[] = addLayout("</div>");
        $input[] = addLayout("<h5>Adresse postale</h5>");
        $input[] = addLayout("<div class='row'>");
        $input[] = addInput('Rue', array("type" => "text", "value" => $post_street, "name" => "street", "class" => "u-full-width"), true, "eight columns");
        $input[] = addInput('Numéro', array("type" => "text", "value" => $post_num, "name" => "num", "class" => "u-full-width"), true, "four columns");
        $input[] = addLayout("</div>");
        $input[] = addLayout("<div class='row'>");
        $input[] = addInput('Code postal', array("type" => "text", "value" => $post_zip, "name" => "zip", "class" => "u-full-width"), true, "four columns");
        $input[] = addInput('Localité', array("type" => "text", "value" => $post_country, "name" => "country", "class" => "u-full-width"), true, "eight columns");
        $input[] = addLayout("</div>");
        $input[] = addSubmit(["value" => "envoyer", "name" => "submit"], "\t\t<br />\n");

        $show_form = form("form_contact", "index.php?p=".$url_page."&action=add"."&alpha=".$get_alpha, "post", $input);

        if($show_form != false){
            $page_view = "admin_form";
        }else{
            $data_values = array();
            $data_values["login"] = $post_login;
            $data_values["password"] = $post_password;
            $data_values["level_access"] = $post_level_access;
            $data_values["street"] = $post_street;
            $data_values["num"] = $post_num;
            $data_values["zip"] = $post_zip;
            $data_values["country"] = $post_country;
            $data_values["pseudo"] = $post_pseudo;

            if(insertAdmin($data_values)){
                $msg = "<p>données insérées avec succès</p>";
                $msg_class = "success";
            }else{
                $msg = "<p>erreur lors de l'insertion des données</p>";
                $msg_class = "error";
            }

            $alphabet = range('A', 'Z');
            $result = getAdmin(null, $get_alpha);

            $page_view = "admin_liste";
        }
        break;

    case "update":

        if(empty($_POST)){
            $result = getAdmin($get_admin_id);

            $post_login = $result[0]["login"];
            $post_pseudo = $result[0]["pseudo"];
            $post_level_access = $result[0]["level_access"];
            $post_street = $result[0]["street"];
            $post_num = $result[0]["num"];
            $post_zip = $result[0]["zip"];
            $post_country = $result[0]["country"];
            $post_password = "";
        }else{
            $post_login        = isset($_POST["login"])        ? filter_input(INPUT_POST, 'login', FILTER_SANITIZE_SPECIAL_CHARS)        : null;
            $post_password     = isset($_POST["password"])     ? filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS)     : null;
            $post_level_access = isset($_POST["level_access"]) ? filter_input(INPUT_POST, 'level_access', FILTER_SANITIZE_SPECIAL_CHARS) : null;
            $post_pseudo       = isset($_POST["pseudo"])       ? filter_input(INPUT_POST, 'pseudo', FILTER_SANITIZE_SPECIAL_CHARS)       : null;
            $post_street       = isset($_POST["street"])       ? filter_input(INPUT_POST, 'street', FILTER_SANITIZE_SPECIAL_CHARS)       : null;
            $post_num          = isset($_POST["num"])          ? filter_input(INPUT_POST, 'num', FILTER_SANITIZE_SPECIAL_CHARS)          : null;
            $post_zip          = isset($_POST["zip"])          ? filter_input(INPUT_POST, 'zip', FILTER_SANITIZE_SPECIAL_CHARS)          : null;
            $post_country      = isset($_POST["country"])      ? filter_input(INPUT_POST, 'country', FILTER_SANITIZE_SPECIAL_CHARS)      : null;
        }

        $input = [];
        // ajout des différents champs du formulaire
        $input[] = addLayout("<h4>Modifier un admin</h4>");
        $input[] = addLayout("<div class='row'>");
        $input[] = addInput('Identifiant / E-mail', ["type" => "email", "value" => $post_login, "name" => "login", "class" => "u-full-width"], true, "four columns");
        $input[] = addInput('Mot-de-passe', ["type" => "password", "value" => $post_password, "name" => "password", "class" => "u-full-width"], false, "four columns");
        $input[] = addInput('Pseudo', ["type" => "text", "value" => $post_pseudo, "name" => "pseudo", "class" => "u-full-width"], true, "four columns");
        $input[] = addLayout("</div>");
        $input[] = addLayout("<div class='row'>");
        $input[] = addRadioCheckbox('Niveau d\'acces', ["name" => $post_level_access], ["1" => "Super administrateur", "2" => "simple admin"], "2", true, "radio", "twelves columns");
        $input[] = addLayout("</div>");
        $input[] = addLayout("<h5>Adresse postale</h5>");
        $input[] = addLayout("<div class='row'>");
        $input[] = addInput('Rue', array("type" => "text", "value" => $post_street, "name" => "street", "class" => "u-full-width"), true, "eight columns");
        $input[] = addInput('Numéro', array("type" => "text", "value" => $post_num, "name" => "num", "class" => "u-full-width"), true, "four columns");
        $input[] = addLayout("</div>");
        $input[] = addLayout("<div class='row'>");
        $input[] = addInput('Code postal', array("type" => "text", "value" => $post_zip, "name" => "zip", "class" => "u-full-width"), true, "four columns");
        $input[] = addInput('Localité', array("type" => "text", "value" => $post_country, "name" => "country", "class" => "u-full-width"), true, "eight columns");
        $input[] = addLayout("</div>");
        $input[] = addSubmit(["value" => "envoyer", "name" => "submit"], "\t\t<br />\n");

        $show_form = form("form_contact", "index.php?p=".$url_page."&action=update&admin_id=".$get_admin_id."&alpha=".$get_alpha, "post", $input);

        if($show_form != false){
            $page_view = "admin_form";
        }else{
            $data_values = array();
            $data_values["login"] = $post_login;
            $data_values["password"] = $post_password;
            $data_values["level_access"] = $post_level_access;
            $data_values["street"] = $post_street;
            $data_values["num"] = $post_num;
            $data_values["zip"] = $post_zip;
            $data_values["country"] = $post_country;
            $data_values["pseudo"] = $post_pseudo;

            if(updateAdmin($get_admin_id, $data_values)){
                $msg = "<p>données modifiées avec succès</p>";
                $msg_class = "success";
            }else{
                $msg = "<p>erreur lors de la modification des données</p>";
                $msg_class = "error";
            }
            $alphabet = range('A', 'Z');
            $result = getAdmin(null, $get_alpha);

            $page_view = "admin_liste";
        }


        break;

    case "showHide":

        if(showHideAdmin($get_admin_id)){
            $msg = "<p>mise à jour de l'état réalisée avec succès</p>";
            $msg_class = "success";
        }else{
            $msg = "<p>erreur lors de la mise à jour de l'état</p>";
            $msg_class = "error";
        }

        $alphabet = range('A', 'Z');

        $result = getAdmin(null, $get_alpha);

        $page_view = "admin_liste";

        break;
}
