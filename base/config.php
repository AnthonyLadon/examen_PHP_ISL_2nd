<?php
// definition les parametres d'acces à la db
define("DB_NOM", "root");
define("DB_PASS", "root");
define("DB_SERVEUR", "localhost");
define("DB_BASE", "isl_2022_sess1");

define("DEBUG", true);

include_once("fct_db.php");
include_once("fct_global.php");
include_once("lib/fonction_form.php");

$mysqli = Connexion(DB_NOM, DB_PASS, DB_BASE, DB_SERVEUR);

?>