<?php

session_start();

$name = $_POST["name"];
$mail = $_POST["mail"];
$phone = $_POST["phone"] ?: "";

$country = $_POST["country"];
$state = $_POST["state"];
$city = $_POST["city"];
$street = $_POST["street"];
$zip = $_POST["postal"];

date_default_timezone_set("Europe/Amsterdam");
$date = Date("Y-m-d H:i:s");

// $tracking = uniqid(chr(rand(65,90)).chr(rand(65,90))."_", true);
function t($c) {
    if($c) {
        return chr(rand(65, 90));
    } else {
        return strval(rand(1, 9));
    }
}
$tracking = t(1).t(0).t(0).t(0).t(0);


$total = $_SESSION["total"] * 100;
// $prods = $_SESSION["prodss"];
$prods = $_SESSION["cart"];

$_SESSION["mail"] = $mail;

// $conn = mysqli_connect("localhost", "root", "passw", "ymc");
$conn = mysqli_connect("localhost", "u125588p130441_root", "21YMCdb#", "u125588p130441_ymc");
if(!$conn) {
    die('Error: ' . mysqli_connect_error());
}

// print_r($name."<br>".$mail."<br>".$phone."<br>".$country."<br>".$state."<br>".$city."<br>".$street."<br>".$zip."<br>".$date."<br>".$tracking."<br>".$total."<br>");
// print_r($prods);

$sql = "INSERT INTO addresses (country, state, city, street, zip) VALUES ('$country', '$state', '$city', '$street', '$zip');";
$res = mysqli_query($conn, $sql);
if($res) {
    // echo 'yes';
}
$sql = "SELECT id FROM addresses ORDER BY id DESC LIMIT 1;";
$res = mysqli_query($conn, $sql);
if(mysqli_num_rows($res) > 0) {
    while($row = mysqli_fetch_assoc($res)) {
        $addressid = $row["id"];
    }
}
// echo "hihih";
// $prods = [[1, 2, 'cs'], [2,3, 'hk']];
$prods = array_map(fn($i) => implode(":", $i), $prods);
$prods = implode("[]", $prods);
// print_r($prods);
$sql = "INSERT INTO orders (tracking, name, email, phone, products, total, created, address_id) VALUES ('$tracking', '$name', '$mail', '$phone', '$prods', '$total', '$date', '$addressid');";
$res = mysqli_query($conn, $sql);
// echo "hihi";
// print_r($res);
if($res) {
    // echo 'yes';
} else {
    print_r(mysqli_error($conn));
}
$sql = "SELECT id FROM orders ORDER BY id DESC LIMIT 1;";
$res = mysqli_query($conn, $sql);
if(mysqli_num_rows($res) > 0) {
    while($row = mysqli_fetch_assoc($res)) {
        $orderid = $row["id"];
    }
}
//pas hoeveelheid producten aan

//stuur naar betalingen


$ch = curl_init();
$data = array(
    "amount" => [
        "value" => strval(number_format($total / 100, 2)),        
        "currency" => "EUR",
    ],
    // "method"=>"ideal",
    "redirectUrl" => "https://youngmessengerclub.com/test/orderprocess.php?tracking=".$tracking,
    "description"=>"ORDER #".$orderid,
    // "Authorization"=>"test_VfjaqcGFpwrA8DkEa5PmWzz84rA2cb",
    // "Authorization: Bearer test_VfjaqcGFpwrA8DkEa5PmWzz84rA2cb",
);
$apikey = "test_VfjaqcGFpwrA8DkEa5PmWzz84rA2cb";
$payload = json_encode($data);
curl_setopt($ch, CURLOPT_URL, "https://api.mollie.com/v2/payments");
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_USERPWD, $apikey);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Content-Length: '.strlen($payload)));

// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

$res = array();
$res = curl_exec($ch);

$httpCode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);

curl_close($ch);
// print_r($httpCode);

// print_r($res);

$url = explode('","type":', explode('"checkout":{"href":"', $res)[1])[0];
// echo "<br /><br />$url";

if ( $httpCode != 201 ){
    // echo "Return code is {$httpCode} \n"
    //     .curl_error($ch);
} else {
    // $res = strval($res);
    // $res = explode("{", $res);
    // echo "<br /><br />";
    // echo var_dump($res);
    // echo "<br /><br />";

    // header("location: ".$url);
}
header("location: $url");
exit;

?>