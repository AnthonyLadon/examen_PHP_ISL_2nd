<div class="row">
    <p>
        <a href="index.php?p=<?php echo $url_page; ?>&action=add" class="button"><i class="fas fa-plus"></i> Ajouter</a>
    </p>
    <div class="twelve columns">
        <?php
        if(is_array($result)) {

            echo isset($msg) && !empty($msg) ? "<div class='missingfield $msg_class'>" . $msg . "</div>" : "";
            $previous = "";

            foreach ($result as $r) {
                $category_level_2_id = $r["category_level_2_id"];
                $category_title = $r["level_1"];
                $category_2 = $r["level_2"];
                $is_visible = $r["is_visible"];


                if ($is_visible == "1") {
                    $txt_nom = $category_2;
                    $txt_visible = "<i class=\"fas fa-eye-slash\"></i>";
                    $txt_title = "Masquer cette entrée";
                } else {
                    $txt_nom = "<s style='color:#b1b1b1;'>".$category_2 . "</s>";
                    $txt_visible = "<i class=\"fas fa-eye\"></i>";
                    $txt_title = "Réactiver cette entrée";
                }

                echo $previous != $category_title ? "<h4>".$category_title."</h4>" : "";
                //echo "<h4>".$item_title."</h4>";

                echo "<p>
                <a href='index.php?p=" . $url_page . "&category_level_2_id=" . $category_level_2_id . "&action=update' title='éditer cette entrée' class='bt-action'><i class=\"far fa-edit\"></i></a> 
                <a href='index.php?p=" . $url_page . "&category_level_2_id=" . $category_level_2_id . "&action=showHide' title='" . $txt_title . "' class='bt-action'>" . $txt_visible . "</a> 
                " . $txt_nom . " 
            </p>";

                $previous = $category_title;
            }
        }else{
            echo "<p>Aucun résultat</p>";
        }

        ?>
