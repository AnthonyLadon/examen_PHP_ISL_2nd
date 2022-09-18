<?php


include_once("lib/home.php");


/* Variable qui stocke le parametre "p" qui sera ajouté dans l'url. 'home' est de toute façon la valeur par defaut
définie dans la variable déclarée dans index.php */
$page = "home";

// definition de la variable $get_action qui stocke la valeur du parametre action
$get_start = isset($_GET["start"]) ? filter_input(INPUT_GET, 'start', FILTER_SANITIZE_NUMBER_INT) : "1";


// definition de la view
$page_view = "home_default";



// Determine le premier article a devoir etre affiché sur la page. On multiplie par exemple "page n°3" par 30 = 90,
// puis on retire 30 et on obtient bien l'article de depart. Par défaut on commence à 0 (page 1);
$firstArticle = 0;

if($get_start > 1){
    $firstArticle = (($get_start * 30) - $get_start);
};

/*récupération des articles, si la variable attendue ($startPage) n'est pas remplie donc on selectionne par defaut les
30 premiers résultats sinon les 30 résultats suivant $startPage */
$result = (getHomeAd($firstArticle));

// fonction permettant de récupérer le nombre total de produit (is_visible = "1") dans la table ad
$adLength = getAdLength();

// definition du nombre total d'articles visibles dans la table ad et stockage dans la variable totalLength
$totalLength = $adLength[0]["adCount"];

// definition du nombre total de pages divisé par le nombre d'articles par page
$totalPages = ceil($totalLength / 30);



?>
