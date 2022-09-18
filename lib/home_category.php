<?php
function getHomeSortedAd($firstArticle, $category){

    $limits = !empty($firstArticle) ? "LIMIT 1,30 " : "LIMIT ".$firstArticle.",30";
    $cond = "WHERE ad.is_visible = '1' ";
    $cond .= "AND category_level_1.category_level_1_id = ".$category."";

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
    ".$cond." ORDER BY ad_title ".$limits.";";

    $result = requeteResultat($sql);
    return $result;
}



function getCategoryLength($category=1){

    $sql = "SELECT COUNT(ad.ad_id) AS adCount 
            FROM ad
            INNER JOIN category_level_2 ON category_level_2.category_level_2_id = ad.category_level_2_id
            INNER JOIN category_level_1 ON category_level_1.category_level_1_id = category_level_2.category_level_1_id
            WHERE category_level_1.category_level_1_id = '".$category."' AND ad.is_visible = '1'";

    $result = requeteResultat($sql);
    return $result;
};
