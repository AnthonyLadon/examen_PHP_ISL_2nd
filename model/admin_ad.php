<?php
/*vérifier si $_SESSION["admin_id"] existe
sinon, redirection vers le form login*/
adminProtection();
// ajout de la librairie de fonctions qu'on utilisera dans ce modele
include_once("lib/ad.php");

// variable qui stocke le parametre "p" qui sera ajouté dans l'url
$url_page = "admin_ad";

/*Récupération/initialisation du paramètre "action" qui sera a jouté à l'url et qui va permettre de
selectionner (grace au switch) la partie de code qui sera a exécuter (selon l'action choisie).
Si aucune action n'est définie via le paramètre _GET alors l'action "liste" sera attribuée par défaut*/
$get_action     = isset($_GET["action"]) ? $_GET["action"] : "list";
// recuperation de l'ID passé en parametre dans l'url
$get_ad_id      = isset($_GET["ad_id"]) ? filter_input(INPUT_GET, 'ad_id', FILTER_SANITIZE_NUMBER_INT)      : null;
$get_alpha      = isset($_GET["alpha"]) ? filter_input(INPUT_GET, 'alpha', FILTER_SANITIZE_SPECIAL_CHARS)   : "A";


// swich qui va definir le code a executer selon le parametre action passé dans l'url
switch($get_action) {
    case "list":

       /* récupération des ad correspondant aux parametres (la chaine de caractère $alpha passe dans
        la requete SQL ---> LIKE '$alpha%' ) */
        $result = getAd($get_ad_id, $get_alpha);
       // definition de la variable alphabet qui stocke le lettres de l'alphabet quo'n affichera pour la recherche
        $alphabet = range('A', 'Z');
        $page_view = "ad_liste";
        break;

    case "add":

        /*Création de la variable stockant un tableau avec les differentes options du select, ici un array_column sur
        les résultat ($result) de la requete SQL getAdCategories(),je crée à la main l'index 0 du tableau avec
        pour valeur "=== choix ===" */
        $categories   = [0 => "=== choix ==="] + (array_column(getAdCategories(), "categories", "category_level_2_id"));

        /* La meme chose ici, je crée un tableau "ID => Nom du designer" pour envoyer dans la requete sql
        "INSERT INTO" non pas le nom selectionné mais l'id correspondant au nom selectionné */
        $designer     = [0 => "=== choix ==="] + array_column(getAdDesigner(), "designer", "designer_id");
        $manufacturer = [0 => "=== choix ==="] + array_column(getAdManufacturer(), "manufacturer", "manufacturer_id");
        $shape        = [0 => "=== choix ==="] + array_column(getShape(), "shape_title", "shape_id");
        /* >>>> Je me rend bien compte que j'ai recréé des fonctions getAdDesigner etc.. mais j'aurais pu en faire
        l'économie et réutiliser la fonction getAd(), (mais il est trop tard pour modifier) */



        // récupération / initialisation des données qui transitent via le formulaire
        $post_ad_categories_id     = isset($_POST["category_level_2_id"]) ? filter_input(INPUT_POST, 'category_level_2_id', FILTER_SANITIZE_FULL_SPECIAL_CHARS) : NULL;
        $post_shape_id             = isset($_POST["shape"]) ? filter_input(INPUT_POST, 'shape', FILTER_SANITIZE_FULL_SPECIAL_CHARS)                             : NULL;
        $post_designer_id          = isset($_POST["designer"]) ? filter_input(INPUT_POST, 'designer', FILTER_SANITIZE_FULL_SPECIAL_CHARS)                       : NULL;
        $post_manufacturer_id      = isset($_POST["manufacturer"]) ? filter_input(INPUT_POST, 'manufacturer', FILTER_SANITIZE_FULL_SPECIAL_CHARS)               : NULL;
        $post_ad_title             = isset($_POST["ad_title"]) ? filter_input(INPUT_POST, 'ad_title', FILTER_SANITIZE_SPECIAL_CHARS)                            : null;
        $post_ad_short_description = isset($_POST["ad_short_description"]) ? filter_input(INPUT_POST, 'ad_short_description', FILTER_SANITIZE_SPECIAL_CHARS)    : null;
        $post_ad_description       = isset($_POST["ad_description"]) ? filter_input(INPUT_POST, 'ad_description', FILTER_SANITIZE_SPECIAL_CHARS)                : null;
        $post_ad_price             = isset($_POST["price"]) ? filter_input(INPUT_POST, 'price', FILTER_SANITIZE_SPECIAL_CHARS)                                  : null;
        $post_ad_delivery_price    = isset($_POST["delivery_price"]) ? filter_input(INPUT_POST, 'delivery_price', FILTER_SANITIZE_SPECIAL_CHARS)                : null;


        // création du tableau vide qui va stocker tous les champs du formulaire
        $input = [];

        // ajout des différents champs du formulaire dans ce tableau avec les valeurs pas défaut et les options des select
        $input[] = addLayout("<h4>Ajouter un article</h4>");
        $input[] = addLayout("<div class='row'>");
        $input[] = addSelect("Categories associees", ["name" => "category_level_2_id", "class" => "u-full-width"],$categories, $post_ad_categories_id,true, "twelve columns");
        $input[] = addSelect("Etat de l'objet", ["name" => "shape", "class" => "u-full-width"], $shape, $post_shape_id, true, "twelve columns");
        $input[] = addSelect("Designer", ["name" => "designer", "class" => "u-full-width"], $designer, $post_designer_id, true, "twelve columns");
        $input[] = addSelect("Manufacture", [ "name" => "manufacturer", "class" => "u-full-width"], $manufacturer, $post_manufacturer_id, true, "twelve columns");
        $input[] = addInput("Nom de l'objet", ["type" => "text", "value" => $post_ad_title, "name" => "ad_title", "class" => "u-full-width"],true, "twelve columns");
        $input[] = addTextarea('Brêve description', array("name" => "ad_short_description", "class" => "u-full-width"), $post_ad_short_description, true, "twelve columns");
        $input[] = addTextarea('Description complète', array("name" => "ad_description", "class" => "u-full-width"), $post_ad_description, true, "twelve columns");
        $input[] = addInput("Prix htva", ["type" => "number", "name" => "price", "value" => $post_ad_price, "class" => "u-full-width"],true,"six columns");
        $input[] = addInput("Prix de la livraison", ["type" => "number", "value" => $post_ad_delivery_price, "name" => "delivery_price", "class" => "u-full-width"], true, "six columns");
        $input[] = addLayout("</div>");
        $input[] = addSubmit(["value" => "envoyer", "name" => "submit"], "\t\t<br />\n");
        /* Appel de la fonction form qui est chargée de générer le formulaire à partir du array de définition des
        champs $input ainsi que de la vérification de la validitée des données si le formulaire été soumis */
        $show_form = form("form_contact", "index.php?p=".$url_page."&action=add", "post", $input);



        // si form() ne retourne pas false et s'il retourne un string alors le formulaire doit être affiché
        if($show_form != false){
            /* définition de la variable view qui sera utilisée pour l'affichage du formulaire */
            $page_view = "ad_form";

            // si form() retourne false, l'insertion peut avoir lieu
        }else{
            // création d'un tableau qui contiendra les données à passer à la fonction d'insert
            $data_values = array();
            $data_values["category_level_2_id"] = $post_ad_categories_id;
            $data_values["ad_title"] = $post_ad_title;
            $data_values["shape"] = "$post_shape_id";
            $data_values["designer_id"] = $post_designer_id;
            $data_values["manufacturer_id"] = $post_manufacturer_id;
            $data_values["ad_short_description"] = $post_ad_description;
            $data_values["ad_description"] = $post_ad_description;
            $data_values["price"] = $post_ad_price;
            $data_values["delivery_price"] = $post_ad_delivery_price;


            // exécution de la requête
            if(insertAd($data_values)){
                // message de succes qui sera affiché dans le <body>
                $msg = "<p>données insérées avec succès</p>";
                $msg_class = "success";
                var_dump($_FILES);
            }else{
                // message d'erreur qui sera affiché dans le <body>
                $msg = "<p>erreur lors de l'insertion des données</p>";
                $msg_class = "error";
            }

            // récupération des ad correspondant

            $result = getAd();

            $page_view = "ad_liste";
        }
        break;


    case "addPicture":

        // recuperation de la superglobale contentan le fichier qu'on a uploadé
        if(isset($_FILES['file'])){
            $tmpName = $_FILES['file']['tmp_name'];
            $name = $_FILES['file']['name'];

            // fonction permettant de déplacer le fichier reçu dans le dossier choisi
            move_uploaded_file($tmpName,'.upload/large/'.$name);
        }



        // création du tableau vide qui va stocker tous les champs du formulaire
        $input = [];

        // ajout des différents champs du formulaire dans ce tableau avec les valeurs pas défaut et les options des select
        $input[] = addLayout("<h4>Ajouter un article</h4>");
        $input[] = addLayout("<div class='row'>");
        $input[] = addInput('Ajouter une photo',["type" => "file", "name" => "ad_title", "class" => "u-full-width"], false, "twelve columns");
        $input[] = addLayout("</div>");
        $input[] = addSubmit(["value" => "envoyer", "name" => "submit"], "\t\t<br />\n");
        /* Appel de la fonction form qui est chargée de générer le formulaire à partir du array de définition des
        champs $input ainsi que de la vérification de la validitée des données si le formulaire été soumis */
        // ajout du parametre enctype afin de pouvoir gérer les fichiers uploadés
        $show_form = form("form_photo", "index.php?p=".$url_page."&action=addPicture", "post", $input, '',"multipart/form-data");



        // si form() ne retourne pas false et s'il retourne un string alors le formulaire doit être affiché
        if($show_form != false){
            /* définition de la variable view qui sera utilisée pour l'affichage du formulaire */
            $page_view = "ad_photo_form";


            //  l'insertion peut avoir lieu
        }else{

            $result = getAd();

            $page_view = "ad_liste";


            // !!!!!!!!!!!!!!!!!!!!!!!!*******************************************************************

            /*  Malheuresement je n'ai pas eu le temps de terminer (Pour le coup j'envierais presque les chomeurs ^^),
            La prochaine étape etait de vérifier si le fichier etait bien uploadé et le déplacer dans le dossier
            upload/large grace a la fonction move_upload_file() Mais mon serveur MAMP me dit qu'il a uplodé le
            fiichier alors qu'il est introuvable
            (pour voir ce qui transite dans la super globale $_FILES ---> print_q($_FILES); )

            Les étapes suivantes auraient été:
                - creation d'une variable $maxSize = 400000 puis verification si le fichier n'est pas plus grand que $maxSize.
                - traitement du nom du fichier uploadé grace à "strtolower" pour le renommer
            en lower case.
                - creation d'une fonction afin de determiner selon la taille de l'image uplodée s'il s'agit du thumb ou
            de l'image grand format.
                - récuperation de l'id de l'article et stockage dans une variable en vue du renommage automatique
            du fichier (devra commencer par id_article).
                - création d'une fonction pour permettre d'auto-incrémenter le numéro des fichiers (id_article.numéroDePhoto).jpg
                - création d'une div dans la view "ad_liste" pour afficher l'image (thumb) au dessus de l'article auquel elle est rattachée
                - lorsqu'on clique sur l'image thumb, on ouvre dans une view dédiée l'image grande taille
                - if($heure >= $HeureApero){
            echo "Féter le fait tout fonctionne à l'aide d'un verre de Macallan 12ans Double Cask (sans glace)"
            } */

            //**********************************************************************************************

        }

        break;



    case "update":

        // je réutilise une partie du code du "add" pour l'affichage du formaulaire
        $categories   = [0 => "=== choix ==="] + (array_column(getAdCategories(), "categories", "category_level_2_id"));
        // La meme chose ici, je crée un tableau "ID => Nom du designer" pour envoyer dans la requete sql "insert" l'id correspondant au designer selectionné
        $designer     = [0 => "=== choix ==="] + array_column(getAdDesigner(), "designer", "designer_id");
        $manufacturer = [0 => "=== choix ==="] + array_column(getAdManufacturer(), "manufacturer", "manufacturer_id");
        $shape        = [0 => "=== choix ==="] + array_column(getShape(), "shape_title", "shape_id");


        if(empty($_POST)){
            $result = getAd($get_ad_id);

            /* On donne a ces variables les valeurs récupérées par getAd() afin d'avoir les champs
            select dèjà selectionnés (ces variables sont les valeur de "default" des "input select"*/
            $post_ad_categories_id = $result[0]["category_level_2_id"];
            $post_ad_title = $result[0]["ad_title"];
            $post_ad_short_description = $result[0]["ad_description"];
            $post_ad_description = $result[0]["ad_description_detail"];
            $post_shape_id = $result[0]["shape_id"];
            $post_designer_id = $result[0]["designer_id"];
            $post_manufacturer_id = $result[0]["manufacturer_id"];
            $post_ad_price = $result[0]["price"];
            $post_ad_delivery_price = $result[0]["price_delivery"];


        }else{
            // récupération / initialisation des données qui transitent via le formulaire
            $post_ad_categories_id     = isset($_POST["category_level_2_id"]) ? filter_input(INPUT_POST, 'category_level_2_id', FILTER_SANITIZE_FULL_SPECIAL_CHARS) : NULL;
            $post_shape_id             = isset($_POST["shape"]) ? filter_input(INPUT_POST, 'shape', FILTER_SANITIZE_FULL_SPECIAL_CHARS)                             : NULL;
            $post_designer_id          = isset($_POST["designer"]) ? filter_input(INPUT_POST, 'designer', FILTER_SANITIZE_FULL_SPECIAL_CHARS)                       : NULL;
            $post_manufacturer_id      = isset($_POST["manufacturer"]) ? filter_input(INPUT_POST, 'manufacturer', FILTER_SANITIZE_FULL_SPECIAL_CHARS)               : NULL;
            $post_ad_title             = isset($_POST["ad_title"]) ? filter_input(INPUT_POST, 'ad_title', FILTER_SANITIZE_SPECIAL_CHARS)                            : null;
            $post_ad_short_description = isset($_POST["ad_short_description"]) ? filter_input(INPUT_POST, 'ad_short_description', FILTER_SANITIZE_SPECIAL_CHARS)    : null;
            $post_ad_description       = isset($_POST["ad_description"]) ? filter_input(INPUT_POST, 'ad_description', FILTER_SANITIZE_SPECIAL_CHARS)                : null;
            $post_ad_price             = isset($_POST["price"]) ? filter_input(INPUT_POST, 'price', FILTER_SANITIZE_SPECIAL_CHARS)                                  : null;
            $post_ad_delivery_price    = isset($_POST["delivery_price"]) ? filter_input(INPUT_POST, 'delivery_price', FILTER_SANITIZE_SPECIAL_CHARS)                : null;
        }



        // initialisation du array qui contiendra la définitions des différents champs du formulaire
        $input = [];
        // ajout des différents champs du formulaire
        $input[] = addLayout("<h4>Ajouter un article</h4>");
        $input[] = addLayout("<div class='row'>");
        $input[] = addSelect("Categories associees", ["name" => "category_level_2_id", "class" => "u-full-width"],$categories, $post_ad_categories_id,true, "twelve columns");
        $input[] = addSelect("Etat de l'objet", ["name" => "shape", "class" => "u-full-width"], $shape, $post_shape_id, true, "twelve columns");
        $input[] = addSelect("Designer", ["name" => "designer", "class" => "u-full-width"], $designer, $post_designer_id, true, "twelve columns");
        $input[] = addSelect("Manufacture", [ "name" => "manufacturer", "class" => "u-full-width"], $manufacturer, $post_manufacturer_id, true, "twelve columns");
        $input[] = addInput("Nom de l'objet", ["type" => "text", "value" => $post_ad_title, "name" => "ad_title", "class" => "u-full-width"],true, "twelve columns");
        $input[] = addTextarea('Brêve description', array("name" => "ad_short_description", "class" => "u-full-width"), $post_ad_short_description, true, "twelve columns");
        $input[] = addTextarea('Description complète', array("name" => "ad_description", "class" => "u-full-width"), $post_ad_description, true, "twelve columns");
        $input[] = addInput("Prix htva", ["type" => "number", "name" => "price", "value" => $post_ad_price, "class" => "u-full-width"],true,"six columns");
        $input[] = addInput("Prix de la livraison", ["type" => "number", "value" => $post_ad_delivery_price, "name" => "delivery_price", "class" => "u-full-width"], true, "six columns");
        $input[] = addLayout("</div>");
        $input[] = addSubmit(["value" => "envoyer", "name" => "submit"], "\t\t<br />\n");
        // appel de la fonction form qui est chargée de générer le formulaire à partir du array de définition des champs $input ainsi que de la vérification de la validitée des données si le formulaire été soumis
        $show_form = form("form_contact", "index.php?p=".$url_page."&action=add", "post", $input);

        // si form() ne retourne pas false et retourne un string alors le formulaire doit être affiché
        if($show_form != false){
            // définition de la variable view qui sera utilisée pour afficher la partie du <body> du html nécessaire à l'affichage du formulaire
            $page_view = "ad_form";

            // si form() retourne false, l'insertion peut avoir lieu
        }else{
            $data_values = array();
/*            $data_values["item_title"] = $post_item_title;
            $data_values["item_description"] = $post_item_description;*/
            // exécution de la requête
            if(updateAd($get_ad_id, $data_values)){
                // message de succes qui sera affiché dans le <body>
                $msg = "<p>données modifiées avec succès</p>";
                $msg_class = "success";
            }else{
                // message d'erreur qui sera affiché dans le <body>
                $msg = "<p>erreur lors de la modification des données</p>";
                $msg_class = "error";
            }

            // récupération des item correspondant
            $result = getAd();

            $page_view = "ad_liste";
        }


        break;

    case "showHide":

        if(showHideAd($get_ad_id)){
            // message de succes qui sera affiché dans le <body>
            $msg = "<p>mise à jour de l'état réalisée avec succès</p>";
            $msg_class = "success";
        }else{
            // message d'erreur qui sera affiché dans le <body>
            $msg = "<p>erreur lors de la mise à jour de l'état</p>";
            $msg_class = "error";
        }

        // récupération des ad correspondant
        $result = getAd();

        $page_view = "ad_liste";

        break;

}
