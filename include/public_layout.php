<?php



?>

<body>
    <div class="main_content">
        <header class="u-full-width">
            <div class="container row">
                <h2 class="six columns" id="logo">
                    <a href="./" title=""><img src="images/content/logo.png" alt="" /></a>
                </h2>

                <div class="six columns">
                    <form action="./" method="get">
                        <input type="text" name="q" value="" placeholder="Que recherchez-vous ?" />
                        <input type="hidden" name="p" value="search" />
                        <input type="submit" name="submit" value="OK" />
                    </form>
                </div>
            </div>
        </header>
        <nav class="container" id="nav">
            <ul class="row">
                <li class="three columns"><a href="./?p=category&id=1" title="">Meubles</a></li>
                <li class="three columns"><a href="./?p=category&id=2" title="">Luminaires</a></li>
                <li class="three columns"><a href="./?p=category&id=3" title="">Accessoires</a></li>
                <li class="three columns"><a href="./?p=category&id=4" title="">Les exceptionnels</a></li>
            </ul>
        </nav>

        <?php
        $page_view = file_exists("view/" . $page_view . ".php") ? $page_view : exit("view non définie ou inexistante");
        include_once("view/" . $page_view . ".php");
        ?>


        <footer class="u-full-width">
            <div id="footer" class="container row">
                <ul class="six columns" id="submenu">
                    <li><a href="./?p=category&id=1" title="">Meubles</a></li>
                    <li><a href="./?p=category&id=2" title="">Luminaires</a></li>
                    <li><a href="./?p=category&id=3" title="">Accessoires</a></li>
                    <li><a href="./?p=category&id=4" title="">Les exceptionnels</a></li>
                </ul>
                <ul class="six columns" id="legal_ul">
                    <li id="legal">Légal</li>
                    <li><a href="./" title="Termes et conditions">Termes et conditions</a></li>
                    <li><a href="./" title="Politique sur les cookies">Politique sur les cookies</a></li>
                    <li><a href="./" title="F.A.Q.">F.A.Q.</a></li>
                </ul>
            </div>
            <p>© Copyright - Vintage Lab</p>
        </footer>

    </div>

    <link rel="stylesheet" type="text/css" href="css/public/normalize.css" media="screen" defer="true" />
    <link rel="stylesheet" type="text/css" href="css/public/skeleton.css" media="screen" defer="true" />
    <link rel="stylesheet" type="text/css" href="css/public/skeleton_collapse.css" media="screen" defer="true" />
    <link rel="stylesheet" type="text/css" href="css/admin/font-awesome.css" media="screen" defer="true" />
    <link rel="stylesheet" type="text/css" href="css/public/custom.css" media="screen" defer="true" />

    <script src="js/ScatteredPolaroidsGallery/classie.js"></script>
    <script src="js/ScatteredPolaroidsGallery/photostack.js"></script>
    <script>
        new Photostack(document.getElementById('photostack-1'), {
            callback: function(item) {
                //console.log(item)
            }
        });
    </script>

</body>

</html>