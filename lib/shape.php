<?php
function getShape($id = 0){
    if(!is_numeric($id)){
        return false;
    }
    // création de la condition WHERE en fonctions des infos passées en paramètre
    $cond = $id > 0 ? " shape_id = ".$id : " 1 ";

    // requete permettant de récupérer les shapes suivant le(s) filtre(s)
    $sql = "SELECT shape_id, shape_title, description, is_visible 
            FROM shape 
            WHERE ".$cond." 
            ORDER BY shape_title ASC;";

    // envoi de la requete vers le serveur de DB et stockaqge du résultat obtenu dans la variable result (array qui contiendra toutes les données récupérées)
    // renvoi de l'info
    return requeteResultat($sql);
}

function insertShape($data){
    $shape_title    = $data["shape_title"];
    $description    = $data["description"];

    $sql = "INSERT INTO shape
                        (shape_title, description) 
                    VALUES
                        ('$shape_title', '$description');
                    ";
    // exécution de la requête
    return ExecRequete($sql);
}

function updateShape($id, $data){
    if(!is_numeric($id)){
        return false;
    }

    $shape_title    = $data["shape_title"];
    $description    = $data["description"];

    $sql = "UPDATE shape 
                SET 
                    shape_title = '".$shape_title."',
                    description = '".$description."'
            WHERE shape_id = ".$id.";
            ";
    // exécution de la requête
    return ExecRequete($sql);
}

function showHideShape($id){
    if(!is_numeric($id)){
        return false;
    }
    // récupération de l'état avant mise à jour
    $sql = "SELECT is_visible FROM shape WHERE shape_id = ".$id.";";
    $result = requeteResultat($sql);
    if(is_array($result)){
        $etat_is_visble = $result[0]["is_visible"];

        $nouvel_etat = $etat_is_visble == "1" ? "0" : "1";
        // mise à jour vers le nouvel état
        $sql = "UPDATE shape SET is_visible = '".$nouvel_etat."' WHERE shape_id = ".$id.";";
        // exécution de la requête
        return ExecRequete($sql);

    }else{
        return false;
    }
}
