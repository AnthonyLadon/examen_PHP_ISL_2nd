
<div class="row">
    <p>
        <a href="index.php?p=<?php echo $url_page; ?>&action=add" class="button"><i class="fas fa-plus"></i> Ajouter</a>
    </p>
    <div class="twelve columns">
<?php
if(is_array($result)) {

    echo isset($msg) && !empty($msg) ? "<div class='missingfield $msg_class'>" . $msg . "</div>" : "";

    foreach ($result as $r) {
        $item_id = $r["item_id"];
        $item_title = $r["item_title"];
        $item_description = $r["item_description"];
        $is_visible = $r["is_visible"];


        if ($is_visible == "1") {
            $txt_nom = $item_title;
            $txt_visible = "<i class=\"fas fa-eye-slash\"></i>";
            $txt_title = "Masquer cette entrée";
        } else {
            $txt_nom = "<s style='color:#b1b1b1;'>" .$item_title . "</s>";
            $txt_visible = "<i class=\"fas fa-eye\"></i>";
            $txt_title = "Réactiver cette entrée";
        }

        echo "<p>
                <a href='index.php?p=" . $url_page . "&item_id=" . $item_id . "&action=update' title='éditer cette entrée' class='bt-action'><i class=\"far fa-edit\"></i></a> 
                <a href='index.php?p=" . $url_page . "&item_id=" . $item_id . "&action=showHide' title='" . $txt_title . "' class='bt-action'>" . $txt_visible . "</a> 
                " . $txt_nom . " 
            </p>";

    }
}else{
    echo "<p>Aucun résultat</p>";
}

?>