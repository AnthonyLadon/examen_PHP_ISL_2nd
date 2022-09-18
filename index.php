<?php
session_start();
include_once('base/config.php');

// initialisation des variables
$page = isset($_GET["p"]) ? $_GET["p"] : "home";

if (file_exists("model/" . $page . ".php")) {
    include_once("model/" . $page . ".php");
} else {
    echo "<b>ERREUR !</b><br />Le model \"<b>" . $page . "</b>\" n'existe pas";
    exit;
}



if (preg_match("/^admin/i", $page)) {
    $section = "admin";
    include_once('include/admin_head.php');
} else {
    if (preg_match("/^login/i", $page)) {
        $section = "admin";
        include_once('include/admin_head.php');
    } else {
        $section = "public";
        include_once('include/public_head.php');
    }
}


switch ($section) {
    case "public":
        include_once("include/public_layout.php");
        break;
    case "admin":
        include_once("include/admin_layout.php");
        break;
}
