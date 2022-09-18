<?php

include_once ('lib/home.php');


$page = 'photo_large';

// passage de l'id de l'article en parametre
$get_id = isset($_GET["id"]) ? filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT) : null;
// passage du numéro de la photo en parametre
$get_num = isset($_GET["num"]) ? filter_input(INPUT_GET, 'num', FILTER_SANITIZE_NUMBER_INT) : null;

$page_view = 'photo_large';