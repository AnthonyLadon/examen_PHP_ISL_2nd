<?php
include_once("lib/product.php");

$get_item_id   = isset($_GET["item_id"]) ? filter_input(INPUT_GET, 'item_id', FILTER_SANITIZE_NUMBER_INT) : 1;

$result_menu = getItem(0, "1");
$result_content = getItem($get_item_id);

$page_view = "item_public_content";