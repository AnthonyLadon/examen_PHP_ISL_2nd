<?php
function getDesigner($id = 0, $alpha = ""){
    if(!is_numeric($id)){
        return false;
    }
    // création de la condition WHERE en fonctions des infos passées en paramètre
    $cond = !empty($alpha) ? " lastname LIKE '".$alpha."%' " : " 1 ";
    $cond .= $id > 0 ? " AND designer_id = ".$id : "";

    // requete permettant de récupérer les designers suivant le(s) filtre(s)
    $sql = "SELECT designer_id as id, firstname as prenom, UPPER(lastname) as nom, CONCAT(UPPER(lastname),' ',firstname) as full_name, description, is_visible 
                FROM designer 
                WHERE ".$cond." 
                ORDER BY lastname ASC, firstname ASC;";
    // envoi de la requete vers le serveur de DB et stockaqge du résultat obtenu dans la variable result (array qui contiendra toutes les données récupérées)
    // renvoi de l'info
    return requeteResultat($sql);
}

function insertDesigner($data){
    $prenom         = $data["prenom"];
    $nom            = $data["nom"];
    $description    = $data["description"];

    $sql = "INSERT INTO designer
                        (firstname, lastname, description) 
                    VALUES
                        ('$prenom', '$nom', '$description');
                    ";
    // exécution de la requête
    return ExecRequete($sql);
}

function updateDesigner($id, $data){
    if(!is_numeric($id)){
        return false;
    }

    $prenom         = $data["prenom"];
    $nom            = $data["nom"];
    $description    = $data["description"];

    $sql = "UPDATE designer 
                SET 
                    firstname = '".$prenom."',
                    lastname = '".$nom."',
                    description = '".$description."'
            WHERE designer_id = ".$id.";
            ";
    // exécution de la requête
    return ExecRequete($sql);
}

function showHideDesigner($id){
    if(!is_numeric($id)){
        return false;
    }
    // récupération de l'état avant mise à jour
    $sql = "SELECT is_visible FROM designer WHERE designer_id = ".$id.";";
    $result = requeteResultat($sql);
    if(is_array($result)){
        $etat_is_visble = $result[0]["is_visible"];

        $nouvel_etat = $etat_is_visble == "1" ? "0" : "1";
        // mise à jour vers le nouvel état
        $sql = "UPDATE designer SET is_visible = '".$nouvel_etat."' WHERE designer_id = ".$id.";";
        // exécution de la requête
        return ExecRequete($sql);

    }else{
        return false;
    }
}

?>