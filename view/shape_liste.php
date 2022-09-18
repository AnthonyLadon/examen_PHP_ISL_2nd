
<div class="row">
    <div class="twelve columns">
<?php
if(is_array($result)) {

    echo isset($msg) && !empty($msg) ? "<div class='missingfield $msg_class'>" . $msg . "</div>" : "";

    foreach ($result as $r) {
        $shape_id = $r["shape_id"];
        $shape_title = $r["shape_title"];
        $description = $r["description"];
        $is_visible = $r["is_visible"];


        if ($is_visible == "1") {
            $txt_nom = $shape_title;
            $txt_visible = "<i class=\"fas fa-eye-slash\"></i>";
            $txt_title = "Masquer cette entrée";
        } else {
            $txt_nom = "<s style='color:#b1b1b1;'>" .$shape_title . "</s>";
            $txt_visible = "<i class=\"fas fa-eye\"></i>";
            $txt_title = "Réactiver cette entrée";
        }

        echo "<p>
                <a href='index.php?p=" . $url_page . "&shape_id=" . $shape_id . "&action=update&alpha=" . $get_alpha . "' title='éditer cette entrée' class='bt-action'><i class=\"far fa-edit\"></i></a> 
                <a href='index.php?p=" . $url_page . "&shape_id=" . $shape_id . "&action=showHide&alpha=" . $get_alpha . "' title='" . $txt_title . "' class='bt-action'>" . $txt_visible . "</a> 
                " . $txt_nom . " 
            </p>";

    }
}else{
    echo "<p>Aucun résultat</p>";
}

?>