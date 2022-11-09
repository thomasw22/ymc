<?php
session_start();

$tracking = $_GET["tracking"];

$prod = [];
$conn = mysqli_connect("localhost", "u125588p130441_root", "21YMCdb#", "u125588p130441_ymc");
if(!$conn) {
    die('Error: ' . mysqli_connect_error());
}
$sql = "SELECT * FROM orders WHERE orders.tracking='$tracking';";
$res = mysqli_query($conn, $sql);
if(mysqli_num_rows($res) > 0) {
    while($row = mysqli_fetch_assoc($res)) {
        // print_r($row);
        $prod['name'] = $row['name'];
        $prod['prods'] = $row['products'];
    }
}

$to = $_SESSION["mail"];
$subject = "Your ymc order!";
// body moet ook product shit hebben!!
$message = "
<html><body>
<h1>Thanks for your order ".$prod['name']."!</h1>
<h2>Your tracking number is <span style='color: white; background-color: black; padding: 5px; font-weight: bold;'>$tracking</span></h2>
</body></html>
";

$headers = ["From" => "noreply@youngmessengerclub.com", "Reply-To" => "contact@youngmessengerclub.com", "MIME-Version" => '1.0', "Content-type" => "text/html; charset=iso-8859-1", "X-Mailer" => "PHP/".phpversion()];

mail($to,$subject,$message,$headers);

//beschikbaarheid producten aanppassen
//  -alle bestelde producten in variabele
//  -aanpassen in database


$prod['prods']; //[1,1,s] [id, amount, size]
foreach($prod['prods'] as $p) {
    $currentamount = 0;
    $newamount = 0;
    $sql = "SELECT available from products WHERE id='".$prod[0]."';";
    $res = mysqli_query($conn, $sql);
    if(mysqli_num_rows($res) > 0) {
        while($row = mysqli_fetch_assoc($res)) {
            // print_r($row);
            $currentamountarray = $row['available'];
            $currentamount = $currentamountarray[array_search($prod[2], $currentamountarray)];
        }
    }
    $newamount = $currentamount - $p[1];
    $sql = "UPDATE products SET available='$newamount' WHERE id='".$prod[0]."';";
    $res = mysqli_query($conn, $sql);
    if($res) {
        
    } else {
        echo "Something went wrong";
    }
}

$_SESSION["cart"] = [];


header("location: order.php?tracking=$tracking");

?>