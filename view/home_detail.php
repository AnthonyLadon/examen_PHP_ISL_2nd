    <div id='search' class='u-full-width'>
        <div id="trail" class="container row">
            <ul>
                <li>Vous êtes ici :</li>
                <li><a href='#' title=''><?php echo $txt_categorie1 ?></a></li>
                <li><a href='#' title=''><?php echo $txt_categorie2 ?></a></li>
                <li><?php echo $txt_nom ?></li>
            </ul>
        </div>
    </div>
    <section id="photostack-1" class="photostack photostack-start u-full-width">
        <div>
<?php


/* Utilisation de la fonction glob() à qui on donne " *.* " en pattern pour dire que ce dossier contient une
extension (par exemple .jpg, .php, etc... (retourne un tableau)*/
$files = glob("upload/thumb/*.*");
$TotalPhotocount = count($files);// Variable $count pour compter le nombre d'entrées dans le tableau $files */
// echo "il y a: ".$count." fichiers dans le dossier 'upload/thumb'";


/* Definition du nombre de photo disponibles pour cet article (on passe $ad_id) pour selctionnner les
photos correspondantes à cet article et on boucle sur les numéro de photo ($i) */
$photoArticleCount = 0;

for ($i=0; $i<$TotalPhotocount; $i++){
    if (file_exists('upload/thumb/thumb_'.$txt_ad_id.'-'.$i.'.jpg')){
        $photoArticleCount++;
    }
};


/* On boucle sur les numéros de photo (sachant grace à $photoCount combien on en recherche, ça rend la
requete plus éffiicace)
Je me suis permi de créér un model/view pour afficher l'image sur laquelle on clique ne version 'large'
On envoi l'itteration de $i dans le parametre 'num' que le modele photo_large récupérera */

for ($i=0; $i<$photoArticleCount; $i++) {
    if (file_exists('upload/thumb/thumb_'.$txt_ad_id.'-'.$i.'.jpg')) {
        echo "        <figure>
                        <a href='./?p=photo_large&id=$txt_ad_id&num=$i'>
                            <img src='upload/thumb/thumb_$txt_ad_id-$i.jpg' alt=''/>
                        </a>
                        <figcaption>
                            <h2 class='photostack-title'>$txt_nom</h2>
                        </figcaption>
                      </figure>";
    }
}
?>

    </section>
    <section class="container" id="detail_ad">
        <h1><?php echo $txt_nom ?></h1>
        <p id="short-description"><?php echo $txt_description ?><p>
<!-- Je n'ai pas trouvé mieux que la fonction nl2br() pour ajouter des sauts de ligne dans le
texte brut récupéré de la BD. Malheuresement je n'ai pas réussi à la configurer mieux que ça -->
        <p id="long-description"><?php echo nl2br($txt_description_detail); ?></p>
    </section>

