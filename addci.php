<?php
session_start();

$prod = $_POST["prod"];
$amount = $_POST["amount"];
$size = $_SESSION["size"];

$it = [$prod, $amount, $size];
array_push($_SESSION["cart"], $it);
header("location: ". $_SESSION["returl"]);
// print_r($_SESSION["cart"]);
// print_r($prod); 
?>


