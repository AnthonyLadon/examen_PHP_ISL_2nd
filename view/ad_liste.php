<div class="row">
    <div class="six columns">
        <?php
        echo "<h5>Recherche alphabétique :</h5>";
        echo "<p>";
        // on utilise ici notre variable alphabet contenant toues les lettres de l'alphabet
        foreach($alphabet as $lettre){
            echo "<a href='index.php?p=".$url_page."&alpha=".$lettre."' class='bt-action'>".$lettre."</a> ";
        }
        echo "</p>";
        ?>
    </div>
    <div class="six columns">
        <form action="index.php?p=<?php echo $url_page; ?>" method="get" id="search">

            <div>
                <input type="hidden" name="p" value="<?php echo $url_page; ?>" />
                <input type="text" id="recherche" name="alpha" value="" placeholder="Tapez votre recherche ici" />
                <input type="submit" value="trouver" />
                <a href="index.php?p=<?php echo $url_page; ?>&action=add" class="button"><i class="fas fa-user-plus"></i> Ajouter</a>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="twelve columns">


<?php
if(is_array($result)) {



    echo isset($msg) && !empty($msg) ? "<div class='missingfield $msg_class'>" . $msg . "</div>" : "";

    foreach ($result as $r) {
        $ad_id = $r["ad_id"];
        $is_visible = $r["is_visible"];
        $ad_title = $r["ad_title"];
        $ad_categories = $r["categories"];
        $ad_designer = $r["designer"];
        $ad_manufacturer = $r["manufacturer"];


       if ($is_visible == "1") {
            $txt_nom = $ad_title;
            $txt_categories = $ad_categories;
            $txt_designer =  $ad_designer;
            $txt_manufacturer = $ad_manufacturer;
            $txt_visible = "<i class=\"fas fa-eye-slash\"></i>";
            $txt_title = "Masquer cette entrée";
        } else {
            $txt_nom = "<s style='color:#b1b1b1;'>" .$ad_title . "</s>";
            $txt_visible = "<i class=\"fas fa-eye\"></i>";
            $txt_title = "Réactiver cette entrée";
        }


// affichage des differentes icones edit, rendre visible ou invisible, et le texte (nom, categories, designer, manufactuer..)
        echo "<p>
                <a href='index.php?p=" . $url_page . "&ad_id=" . $ad_id . "&action=addPicture&alpha=" . $get_alpha . "' title='Ajouter une photo' class='bt-action'>Ajouter une photo</a> 
                <a href='index.php?p=" . $url_page . "&ad_id=" . $ad_id . "&action=update&alpha=" . $get_alpha . "' title='éditer cette entrée' class='bt-action'><i class=\"far fa-edit\"></i></a> 
                <a href='index.php?p=" . $url_page . "&ad_id=" . $ad_id . "&action=showHide&alpha=" . $get_alpha . "' title='" . $txt_title . "' class='bt-action'>" . $txt_visible . "</a> 
                " . "<b>".$txt_nom."</b>" ."<br/>"."<p>Catégorie : ". $txt_categories."</p>". "<p>Designer : ". $txt_designer. "</p>"."<p>Manufacturer : ".$txt_manufacturer."</p>" ."
             </p>";

    }
}else{
    echo "<p>Aucun résultat pour la lettre ".$get_alpha."</p>";
}

?>