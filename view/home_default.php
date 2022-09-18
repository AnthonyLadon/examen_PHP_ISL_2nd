<div id='search' class='u-full-width'>
    <div id="trail" class="container row">
        <ul>
            <li>Vous êtes ici :</li>
            <li>Page d'accueil</li>
        </ul>
    </div>
</div>
<section class='container'>
    <div class='row'>
        <?php
/* Trouver l'astuce pour incrementer l'ouverture et la fermeture des <div class=row> tous les 3 objets tout en évitant
d'en créer une a vide avant le 1er article m'a littèralement pris des heures.. (je suis un peu fier de ma trouvaille ^^) */

        //initiation de $i
        $i = -1;

        if(is_array($result)) {

            foreach ($result as $r) {
                // incrementation de $i a chaque passage dans la boucle foreach
                $i++;
                /* Tous les 3 elements on ajoute une div qui va englober les articles par 3 (pour l'affichage)
                on évite grace à la condition "$i >2" qu'une div soit créée à vide avant le premier article */
                if ($i >2 && $i % 3 == 0) {
                    echo "</div>
                          <div class='row'>";
                }

                $ad_id = $r["ad_id"];
                $is_visible = $r["is_visible"];
                $ad_title = $r["ad_title"];
                $ad_designer = $r["designer"];
                $ad_manufacturer = $r["manufacturer"];
                $ad_description = $r["ad_description"];
                $price = $r["price"];

                if ($is_visible == "1") {
                    $txt_ad_id = $ad_id;
                    $txt_nom = $ad_title;
                    $txt_designer = $ad_designer;
                    $txt_manufacturer = $ad_manufacturer;
                    $txt_description = $ad_description;

 echo " <article class='pres_product four columns border'>
            <div class='thumb'>
                <a href='./?p=home_detail&id=$txt_ad_id' title=''>
                    <span class='rollover'><i>+</i></span>
                    <img src='upload/thumb/thumb_$txt_ad_id-1.jpg' alt='' />
                </a>
            </div>
    
          <header>
            <h4><a href='./?p=home_detail&id=$txt_ad_id' title=''>$txt_nom</a></h4>
                <div class='subheader'>
                    <span class='fa fa-bars'></span> <a href='' title=''></a>
                    <span class='separator'>|</span>
                    <span class='fa fa-pencil'></span> <a href='' title=''>$txt_designer</a>
                    <span class='separator'>|</span>
                    <span class='fa fa-building-o'></span> <a href='' title=''><small style='opacity:.5;'> $txt_manufacturer</small></a>
                </div>
            </header>
                <div class='une_txt'>
                    <p>
                        $txt_description
                        <a href='./?p=home_detail&id=$txt_ad_id' title=''>[...]</a>
                        <b>$price €</b>
                    </p>
                </div>
            </article>";
                }
            }
        }

        ?>
    </div>
    <br /><br />
    <ul class='pagination'>

        <?php
        /* Variables contenant le parametre start qu'on ajoutera a chaque lien de pagination afin d'aller a la page
        precedente. Si on se trouve à la page 1 alors les boutons previous et toFirstPage ne s'affichent pas */
        $previous = $get_start - 1;
        $previousPage = ($get_start > 1) ? "&start=$previous" : "";
        $previousSign = !empty($previous) ? "<" : "";
        $toFirstPage = !empty($previous) ? "|<<" : "";


        echo "<li><a href='./?p=home&start=1' title='premiers résultats'>$toFirstPage</a></li>
          <li><a href='./?p=home$previousPage' title='résultats précedent'>$previousSign</a></li>";


        // CREER CETTE FONCTION M'A PRIS DES HEURES!! Je la garde pour toujours dans ma librairie perso ;) ;)

        /* boucle qui affiche les onglets de pages, les "..." avant et après, et modifie la classe css de l'onglet de la page active
        grace au paramètre "start" passé dans l'url et stocké dans $get_start */
        for ($i=1; $i<=$totalPages; $i++){

            // modifie la classe css du bouton de page si $get_start = $i, sinon retourne une string vide
            $active = '';
            if ($get_start == $i) {
                $active = "active";
            }

            /* determine si la page actuelle se trouve proche du début ou de la fin de la liste des pages
            et affiche "..." en début ou en fin de liste selon le cas */
            if ($i==1 || ($get_start-11)<$i && $i<($get_start+11) || $i==$totalPages){
                // condition affichage des "..." à la fin des onglets de page (si on se trouve avant les 8 dernières pages)
                if($i==$totalPages && $get_start<($totalPages-11)){
                    echo '<li>...</li>';
                    // condition affichage des "..." au début des onglets de page (si on se trouve après les 8 dernières pages)
                }else if($i==1 && $get_start>11){
                    echo '<li>...</li>';
                    // Affichage des onglets de pages (8 avant et 8 après la page active)
                }else{
                    echo "<li><a href='./?p=home&start=$i' class=$active>$i</a></li>";
                }
            }
        };


        /* Variables contenant le parametre start qu'on ajoutera a chaque lien de pagination afin d'aller a la page
       suivante. Si on se trouve à la derniere page alors les boutons next et toLastPage ne s'affichent pas */
        $nextPage = $get_start + 1;
        $next = ($get_start < $totalPages) ? "&start=$nextPage" : "";
        $nextSign = !empty($next) ? ">" : "";
        $toLastPage = !empty($next) ? ">>|" : "";

        echo "  
            <li><a href='./?p=home$next' title='résultats suivants'>$nextSign</a></li>
            <li><a href='./?p=home&start=$totalPages' title='derniers résultats'>$toLastPage</a></li> ";

        ?>
    </ul>
</section>
