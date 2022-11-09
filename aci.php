<?php
session_start();

$cart = $_SESSION["cart"];
$cartid = $_POST["cartid"];
$newamount = $_POST["amount"];

echo $cartid;

$cart[$cartid][1] = $newamount;
$_SESSION["cart"] = $cart;
// echo var_dump($cart);
$_SESSION["cartopen"] = true;
header("location: " . $_SESSION["returl"]);

?>