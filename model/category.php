<?php


include_once("lib/home_category.php");


/* Variable qui stocke le parametre "p" qui sera ajouté dans l'url. 'home' est de toute façon la valeur par defaut
définie dans la variable déclarée dans index.php */
$page = "model/category";


// definition de la variable $get_action qui stocke la valeur du parametre action
$get_start        = isset($_GET["start"]) ? $_GET["start"] : "1";
$get_category_id  = isset($_GET["id"])  ? filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT) : null;


// definition de la view
$page_view = "home_category_list";



// Determine le premier article a devoir etre affiché sur la page. On multiplie par exemple "page n°3" par 30 = 90,
// puis on retire 30 et on obtient bien l'article de depart. Par défaut on commence à 0 (page 1);
$firstArticle = 0;

if($get_start > 1){
    $firstArticle = (($get_start * 30) - $get_start);
};

$result = (getHomeSortedAd($firstArticle, $get_category_id));


    /* fonction permettant de récupérer le nombre total de produit (is_visible = "1") selon l'id de la categorie
    passée en parametre */
$adLength = getCategoryLength($get_category_id);

// definition du nombre total d'articles visibles dans la table ad et stockage dans la variable totalLength
$totalLength = $adLength[0]["adCount"];

// definition du nombre total de pages divisé par le nombre d'articles par page
$totalPages = ceil($totalLength / 30);
