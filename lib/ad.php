<?php

// fonction de récupération globale de la table ad avec les parametre "id" et "alpha" vides par defaut
function getAd($id = null, $alpha = ""){

    if(is_null($id)){
        // création du WHERE en fonction des infos passées en paramètre (lettre ou ce qu'on a entré dans le dans le champ de recherche)
        $cond = !empty($alpha) ? "WHERE ad_title LIKE '".$alpha."%' " : "";

        $sql = " SELECT ad_id, ad.is_visible, ad.ad_title, ad_description, 
        manufacturer.manufacturer AS manufacturer, CONCAT (designer.firstname,' ', designer.lastname) AS 
        designer, ad.category_level_2_id, admin_id, shape_id, price, ad_description_detail, 
        ad.designer_id, price_htva, amount_tva, date_add, price_delivery, ad.manufacturer_id, 
        CONCAT (category_level_1.level_1,' > ', category_level_2.level_2) AS categories
 
        FROM ad
        INNER JOIN manufacturer ON manufacturer.manufacturer_id = ad.manufacturer_id
        INNER JOIN designer ON designer.designer_id = ad.designer_id
        INNER JOIN category_level_2 ON category_level_2.category_level_2_id = ad.category_level_2_id
        INNER JOIN category_level_1 ON category_level_1.category_level_1_id = category_level_2.category_level_1_id 
        ".$cond." ORDER BY ad_title;";


    }else{
        if(is_numeric($id)){
            $sql = " SELECT ad_id, ad.is_visible, ad.ad_title, ad_description, 
        manufacturer.manufacturer AS manufacturer, CONCAT (designer.firstname,' ', designer.lastname) AS 
        designer, ad.category_level_2_id, admin_id, shape_id, price, ad_description_detail, 
        ad.designer_id, price_htva, amount_tva, date_add, price_delivery, ad.manufacturer_id, 
        CONCAT (category_level_1.level_1,' > ', category_level_2.level_2) AS categories  
        FROM ad        
        INNER JOIN manufacturer ON manufacturer.manufacturer_id = ad.manufacturer_id
        INNER JOIN designer ON designer.designer_id = ad.designer_id
        INNER JOIN category_level_2 ON category_level_2.category_level_2_id = ad.category_level_2_id
        INNER JOIN category_level_1 ON category_level_1.category_level_1_id = category_level_2.category_level_1_id 
 
         WHERE ad_id = $id;";
        }
    }

    $result = requeteResultat($sql);
    return $result;
}


/* Je suis conscient qu'ue partie des fonctions suivantes font doublon, je les ai créés avant de modifier la fonction
principale de récupération dans la base de donnée "getAd()" à laquelle j'ai ajouté tous les champs necessaires pour
gérer le model ad, on apprend en se trompant ;)  */

function getAdCategories(){

    $sql = "SELECT CONCAT(category_level_1.level_1,' > ',category_level_2.level_2) AS categories,
       category_level_2.category_level_2_id
           FROM ad
           INNER JOIN category_level_2 ON category_level_2.category_level_2_id = ad.category_level_2_id
           INNER JOIN category_level_1 ON category_level_2.category_level_1_id = category_level_1.category_level_1_id
           ORDER BY categories";

    $result = requeteResultat($sql);
    return $result;
}



function getShape(){

    $sql = "SELECT shape_title, shape_id
            FROM shape";

    $result = requeteResultat($sql);
        return $result;
}


function getAdDesigner(){

        $sql = "SELECT CONCAT (firstname,' ', lastname) AS designer, designer_id
                FROM designer";

    $result = requeteResultat($sql);
        return $result;
}


function getAdManufacturer(){

    $sql = "SELECT manufacturer, manufacturer_id
            FROM manufacturer";

    $result = requeteResultat($sql);
    return $result;
}



function insertAd($data){
    $ad_title             = $data["ad_title"];
    $ad_category2_id      = $data["category_level_2_id"];
    $ad_admin_id          = 1;
    $ad_shape             = $data["shape"];
    $ad_designer          = $data["designer_id"];
    $ad_manufacturer      = $data["manufacturer_id"];
    $ad_description       = $data["ad_description"];
    $ad_price             = $data["price"];
    $ad_price_tva         = 1;
    $ad_amount_tva        = 1;
    //impossible d'inserer 'CURDATE()' qui donne automatiquement la date actuelle; Une erreur se produit à chaque essai
    // j'ai donc mis une date par défaut...
    $ad_date              = '2017-11-07 22:17:22';
    $ad_short_description = $data["ad_short_description"];
    $price_delivery       = $data["delivery_price"];



    $sql = "INSERT INTO ad 
                           (ad_title, category_level_2_id, admin_id, shape_id, designer_id, manufacturer_id, 
                            ad_description, price, price_htva, amount_tva, date_add, price_delivery, ad_description_detail) 
                   VALUES ('$ad_title', '$ad_category2_id', '$ad_admin_id', '$ad_shape', '$ad_designer', 
                           '$ad_manufacturer', '$ad_description', '$ad_price', '$ad_price_tva', '$ad_amount_tva', '$ad_date',
                           '$price_delivery', '$ad_short_description');";

    // exécution de la requête
    return ExecRequete($sql);
}




function updateAd($id, $data){
    if(!is_numeric($id)){
        return false;
    }

    $ad_title         = $data["ad_title"];
    $ad_description   = $data["ad_description"];

    $sql = "UPDATE ad 
                SET 
                    ad_title = '".$ad_title."',
                    ad_description = '".$ad_description."'
            WHERE ad_id = ".$id.";
            ";
    // exécution de la requête
    return ExecRequete($sql);
}



function showHideAd($id){
    if(!is_numeric($id)){
        return false;
    }
    // récupération de l'état avant mise à jour
    $sql = "SELECT is_visible FROM ad WHERE ad_id = ".$id.";";
    $result = requeteResultat($sql);
    if(is_array($result)){
        $etat_is_visble = $result[0]["is_visible"];

        $nouvel_etat = $etat_is_visble == "1" ? "0" : "1";
        // mise à jour vers le nouvel état
        $sql = "UPDATE ad SET is_visible = '".$nouvel_etat."' WHERE ad_id = ".$id.";";
        // exécution de la requête
        return ExecRequete($sql);

    }else{
        return false;
    }
}

?>


