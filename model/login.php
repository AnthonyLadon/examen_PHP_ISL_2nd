<?php
include_once("lib/login.php");
include_once("lib/item.php");

$result_menu = getItem(0, "1");

$url_page = "login";

// récupération / initialisation des données qui transitent via le formulaire
$post_login    = isset($_POST["login"])      ? filter_input(INPUT_POST, 'login', FILTER_SANITIZE_SPECIAL_CHARS)      : null;
$post_password = isset($_POST["password"])   ? filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS)   : null;

// initialisation de la variable msg
$msg = "";

// initialisation du array qui contiendra la définitions des différents champs du formulaire
$input = [];
// ajout des différents champs du formulaire
$input[] = addLayout("<h4>Identification</h4>");
$input[] = addLayout("<div class='row'>");
$input[] = addInput('Identifiant', ["type" => "text", "value" => $post_login, "name" => "login", "class" => "u-full-width"], true, "six columns");
$input[] = addInput('Mot de passe', ["type" => "password", "value" => $post_password, "name" => "password", "class" => "u-full-width"], true, "six columns");
$input[] = addLayout("</div>");
$input[] = addSubmit(["value" => "envoyer", "name" => "submit"], "\t\t<br />\n");
// appel de la fonction form qui est chargée de générer le formulaire à partir du array de définition des champs $input ainsi que de la vérification de la validitée des données si le formulaire été soumis
$show_form = form("form_contact", "index.php?p=".$url_page, "post", $input);

if($show_form != false){
    // définition de la variable view qui sera utilisée pour afficher la partie du <body> du html nécessaire à l'affichage du formulaire
    $page_view = "login_form";

    // si form() retourne false, l'acces peut être donné
}else{
    $result = verifLogin($post_login, $post_password);

    if (is_array($result) && sizeof($result) > 0){

        $l_admin_id     = $result[0]["admin_id"];
        $l_pseudo       = $result[0]["pseudo"];
        $l_level_access = $result[0]["level_access"];
        $l_is_visible   = $result[0]["is_visible"];

        if($l_is_visible == "1"){
            $_SESSION["admin_id"]       = $l_admin_id;
            $_SESSION["admin_login"]    = $post_login;
            $_SESSION["admin_pseudo"]   = $l_pseudo;
            $_SESSION["admin_level"]    = $l_level_access;

            $msg .= "&rarr; Connexion établie avec succès.<br />\n";

            // définition de la vue à afficher
            $page_view = "admin_home";

        }else{
            $msg .= "<i class=\"fa fa-exclamation-triangle\"></i> <b>Information(s) manquante(s) ou erronée(s)</b><br />";
            $msg .= "&rarr; Le compte lié au login $post_login a été désactivé !<br />\n";
            // définition de la vue à afficher
            $page_view = "login_form";
        }


    }else{
        $msg .= "<i class=\"fa fa-exclamation-triangle\"></i> <b>Information(s) manquante(s) ou erronée(s)</b><br />";
        $msg .= "&rarr; Oupsss... utilisateur non valide....<br />\n";
        // définition de la vue à afficher
        $page_view = "login_form";
    }

}

?>
