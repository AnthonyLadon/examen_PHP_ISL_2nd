<?php
function getItem($id = 0){
    if(!is_numeric($id)){
        return false;
    }
    // création de la condition WHERE en fonctions des infos passées en paramètre
    $cond = $id > 0 ? " item_id = ".$id : " 1 ";

    // requete permettant de récupérer les items suivant le(s) filtre(s)
    $sql = "SELECT item_id, item_title, item_description, is_visible 
            FROM item 
            WHERE ".$cond." 
            ORDER BY item_title ASC;";

    // envoi de la requete vers le serveur de DB et stockaqge du résultat obtenu dans la variable result (array qui contiendra toutes les données récupérées)
    // renvoi de l'info
    return requeteResultat($sql);
}

function insertItem($data){
    $item_title         = $data["item_title"];
    $item_description   = $data["item_description"];

    $sql = "INSERT INTO item
                        (item_title, item_description) 
                    VALUES
                        ('$item_title', '$item_description');
                    ";
    // exécution de la requête
    return ExecRequete($sql);
}

function updateItem($id, $data){
    if(!is_numeric($id)){
        return false;
    }

    $item_title         = $data["item_title"];
    $item_description   = $data["item_description"];

    $sql = "UPDATE item 
                SET 
                    item_title = '".$item_title."',
                    item_description = '".$item_description."'
            WHERE item_id = ".$id.";
            ";
    // exécution de la requête
    return ExecRequete($sql);
}

function showHideItem($id){
    if(!is_numeric($id)){
        return false;
    }
    // récupération de l'état avant mise à jour
    $sql = "SELECT is_visible FROM item WHERE item_id = ".$id.";";
    $result = requeteResultat($sql);
    if(is_array($result)){
        $etat_is_visble = $result[0]["is_visible"];

        $nouvel_etat = $etat_is_visble == "1" ? "0" : "1";
        // mise à jour vers le nouvel état
        $sql = "UPDATE item SET is_visible = '".$nouvel_etat."' WHERE item_id = ".$id.";";
        // exécution de la requête
        return ExecRequete($sql);

    }else{
        return false;
    }
}

?>