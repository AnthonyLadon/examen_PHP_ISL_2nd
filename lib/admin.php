<?php
function getAdmin($id = null, $alpha = ""){

    if(is_null($id)){
        // création de la condition WHERE en fonctions des infos passées en paramètre
        $cond = !empty($alpha) ? "WHERE pseudo LIKE '".$alpha."%' " : "";
        $sql = "SELECT admin_id, login, street, num, zip, country, pseudo, is_visible FROM admin ".$cond." ORDER BY login;";
    }else{
        if(is_numeric($id)){
            $sql = "SELECT login, street, num, zip, country, pseudo, level_access, password, is_visible FROM admin WHERE admin_id = $id;";
        }
    }

    $result = requeteResultat($sql);
    return $result;
}

function insertAdmin($data){
    $login = $data["login"];
    $pass = $data["password"];
    $level = $data["level_access"];
    $street = $data["street"];
    $num = $data["num"];
    $zip = $data["zip"];
    $country = $data["country"];
    $pseudo = $data["pseudo"];

    $sql = "INSERT INTO admin
                (pseudo, login, password, level_access, street, num, zip, country) 
            VALUES
                ('$pseudo', '$login', MD5('$pass'), '$level', '$street', '$num', '$zip', '$country');
            ";
    // exécution de la requête
    return ExecRequete($sql);
}

function updateAdmin($id, $data){
    if(!is_numeric($id)){
        return false;
    }
    $login = $data["login"];
    $password = $data["password"];
    $level = $data["level_access"];
    $street = $data["street"];
    $num = $data["num"];
    $zip = $data["zip"];
    $country = $data["country"];
    $pseudo = $data["pseudo"];

    $sql = "UPDATE admin 
                SET
                    login = '$login',
                    level_access = '$level',
                    street = '$street',
                    num = '$num',
                    zip = '$zip',
                    country = '$country',
                    pseudo = '$pseudo'
            WHERE admin_id = $id;
            ";
    if(ExecRequete($sql)){
        if(!empty($password)){
            $sql = "UPDATE admin 
                        SET
                            password = MD5('$password')
                    WHERE admin_id = $id;";
            return ExecRequete($sql);
        }else{
            return true;
        }
    }else{
        return false;
    }

}

function showHideAdmin($id){
    if(!is_numeric($id)){
        return false;
    }
    // récupération de l'état avant mise à jour
    $sql = "SELECT is_visible FROM admin WHERE admin_id = ".$id.";";
    $result = requeteResultat($sql);
    if(is_array($result)){
        $etat_is_visble = $result[0]["is_visible"];

        $nouvel_etat = $etat_is_visble == "1" ? "0" : "1";
        // mise à jour vers le nouvel état
        $sql = "UPDATE admin SET is_visible = '".$nouvel_etat."' WHERE admin_id = ".$id.";";
        // exécution de la requête
        return ExecRequete($sql);

    }else{
        return false;
    }
}

?>