<?php


// fonction de récupération globale de la table ad avec les parametre "id" et "alpha" vides par defaut
function getHomeAd($firstArticle = "", $id = null, $alpha = ""){

    $limits = empty($firstArticle) ? "LIMIT 1,30 " : "LIMIT ".$firstArticle.",30";

    if(is_null($id)){
        // création du WHERE en fonction des infos passées en paramètre (lettre ou ce qu'on a entré dans le dans le champ de recherche)
        $cond = !empty($alpha) ? "AND ad_title LIKE '".$alpha."%' " : "";

        $sql = " SELECT ad_id, ad.is_visible, ad.ad_title, SUBSTRING(ad_description, 1, 100) AS ad_description, 
        manufacturer.manufacturer AS manufacturer, CONCAT (designer.firstname,' ', designer.lastname) AS 
        designer, ad.category_level_2_id, admin_id, shape_id, price, ad_description_detail, 
        ad.designer_id, price_htva, amount_tva, date_add, price_delivery, ad.manufacturer_id, 
        category_level_1.level_1, category_level_2.level_2,
        CONCAT (category_level_1.level_1,' > ', category_level_2.level_2) AS categories
 
        FROM ad
        INNER JOIN manufacturer ON manufacturer.manufacturer_id = ad.manufacturer_id
        INNER JOIN designer ON designer.designer_id = ad.designer_id
        INNER JOIN category_level_2 ON category_level_2.category_level_2_id = ad.category_level_2_id
        INNER JOIN category_level_1 ON category_level_1.category_level_1_id = category_level_2.category_level_1_id 
        WHERE ad.is_visible = '1'".$cond." ORDER BY ad_title ".$limits." ;";


    }else{
        if(is_numeric($id)){
            $sql = " SELECT ad_id, ad.is_visible, ad.ad_title, SUBSTRING(ad_description, 1, 100) AS ad_description, 
        manufacturer.manufacturer AS manufacturer, CONCAT (designer.firstname,' ', designer.lastname) AS 
        designer, ad.category_level_2_id, admin_id, shape_id, price, ad_description_detail, 
        ad.designer_id, price_htva, amount_tva, date_add, price_delivery, ad.manufacturer_id,
        category_level_1.level_1, category_level_2.level_2,
        CONCAT (category_level_1.level_1,' > ', category_level_2.level_2) AS categories  
        FROM ad        
        INNER JOIN manufacturer ON manufacturer.manufacturer_id = ad.manufacturer_id
        INNER JOIN designer ON designer.designer_id = ad.designer_id
        INNER JOIN category_level_2 ON category_level_2.category_level_2_id = ad.category_level_2_id
        INNER JOIN category_level_1 ON category_level_1.category_level_1_id = category_level_2.category_level_1_id 
 
        WHERE ad_id = $id AND ad.is_visible = '1' ".$limits.";";
        }
    }

    $result = requeteResultat($sql);
    return $result;
}


function getAdLength(){

    $sql = "SELECT COUNT(ad.is_visible) AS adCount 
            FROM ad
            WHERE ad.is_visible = '1' ";

    $result = requeteResultat($sql);
    return $result;
};
