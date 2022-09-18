<?php
function verifLogin($l, $p){
    if(empty($l) || empty($p) || !is_good_password($p)){
        return false;
    }
    
    $sql = "SELECT admin_id, pseudo, level_access, is_visible 
            FROM admin 
            WHERE login LIKE '$l' 
            AND password LIKE MD5('$p');
        ";
    
    return requeteResultat($sql);
}



?>