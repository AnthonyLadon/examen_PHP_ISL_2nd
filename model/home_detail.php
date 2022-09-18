<?php

include_once("lib/home.php");

$page = "home_detail";

$page_view = "home_detail";


$get_ad_id  = isset($_GET["id"]) ? filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT) : null;


$result = (getHomeAd(0, $get_ad_id));

if(is_array($result)) {

    foreach ($result as $r) {
        $ad_id = $r["ad_id"];
        $is_visible = $r["is_visible"];
        $ad_title = $r["ad_title"];
        $ad_designer = $r["designer"];
        $ad_manufacturer = $r["manufacturer"];
        $ad_description = $r["ad_description"];
        $price = $r["price"];
        $ad_description_detail = $r["ad_description_detail"];
        $categorie1 = $r["level_1"];
        $categorie2 = $r["level_2"];

        if ($is_visible == "1") {
            $txt_ad_id = $ad_id;
            $txt_nom = $ad_title;
            $txt_designer = $ad_designer;
            $txt_manufacturer = $ad_manufacturer;
            $txt_description = $ad_description;
            $txt_description_detail = $ad_description_detail;
            $txt_categorie1 = $categorie1;
            $txt_categorie2 = $categorie2;
        }
    }
}

// fonction permettant de récupérer le nombre total de produit (is_visible = "1") dans la table ad
$adLength = getAdLength();

// definition du nombre total d'articles visibles dans la table ad et stockage dans la variable totalLength
$totalLength = $adLength[0]["adCount"];
