<?php
session_start();

$cart = $_SESSION["cart"];
$cartindex = $_POST["id"];
array_splice($cart, $cartindex, 1);
$_SESSION["cart"] = $cart;
header("location: " . $_SESSION["returl"]);
?>