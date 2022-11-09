<?php
session_start();
// $conn = mysqli_connect("localhost", "root", "passw", "ymc");
$conn = mysqli_connect("localhost", "u125588p130441_root", "21YMCdb#", "u125588p130441_ymc");
if(!$conn) {
    die('Error: ' . mysqli_connect_error());
}


$_SESSION["returl"]="index.php";


$tracking = "";
if(isset($_GET['tracking'])) {
    $tracking = $_GET["tracking"];
}

if($tracking != "") {
    $prod = [];
    $sql = "SELECT * from orders o, addresses a WHERE o.address_id = a.id AND o.tracking = '$tracking';";
    $res = mysqli_query($conn, $sql);
    if(mysqli_num_rows($res) > 0) {
        while($row = mysqli_fetch_assoc($res)) {
            // print_r($row);
            $prod['name'] = $row["name"];
            $prod['mail'] = $row['email'];
            $prod['address'] = $row['street'].', '.$row['zip'].', '.$row['city'].', '.$row['country'];
            $prod['price'] = number_format($row['total'] / 100, 2);
        }
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=M+PLUS+Rounded+1c&display=swap"
        rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@splidejs/splide@3.6.12/dist/css/splide.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/main.css">
    <link href="favicon.ico" rel="shortcut icon">
    <title>Young Messenger Club</title>
</head>

<body>
    <div class="menu-wrapper">
        <div class="menu">
            <div class="menu-content">
                <h2>MENU</h2>
                <hr />
                <ul>
                    <li>
                        <a href="index.php">HOME</a>
                    </li>
                    <li>
                        <a href="shop.php">SHOP</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="top top-big">
        <div class="ham">
            <div id="burger-menu">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
        <div class="title">
            <h1>Young Messenger Club</h1>
        </div>
    </div>


    <div class="main-wrapper view-main-wrapper">
        <div class="main-img"></div>
        <div class="main">

        </div>
    </div>
    <div class="side view-side"></div>
    <div class="side2 view-side2"></div>
    <div class="info checkout order-info">
        <h1>Hello <?=$prod['name']?>,</h1>
        <h2>We've sent you a confirmation mail to <?=$prod['mail']?>. Don't lose your tracking number!</h2>
        <div class="order-info">
            <h3>Tracking number: <?=$tracking?></h3>
            <h3>Delivery address: <?=$prod['address']?></h3>
            <h3>Total price: <span>&euro;</span><?=$prod['price']?></h3>
        </div>
    </div>
    <div class="footer">
        <div class="footer-top">
            <div class="footer-links">
                <a href="shop.php">Shop</a>
                <a href="">Privacy Policy</a>
                <a href="">Terms And Conditions</a>
            </div>
            <div class="footer-newsletter">
                <h4>Never miss a thing!</h4>
                <form class="fn" action="mailing.php" method="POST">
                    <input type="email" name="mail" id="" placeholder="Your email">
                    <input type="submit" value="SUBSCRIBE" name="mailingsubmit">
                </form>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="socials">
                <a href="https://www.instagram.com/p/CaFk8PntkIr/"><i class="fa fa-brands fa-instagram"></i></a>
                <a href=""><i class="fa fa-brands fa-facebook-f"></i></a>
            </div>
        </div>
    </div>
    <div class="credits">
        <a href="https://westerdijk.eu" target="_blank">
            <span>&copy;</span>Thomas Westerdijk
        </a>
    </div>
    <script src="https://kit.fontawesome.com/7ad0e49df2.js" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
    AOS.init();
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@3.6.12/dist/js/splide.min.js"></script>
    <script src="script/splidee.js"></script>
    <script src="script/main.js"></script>
</body>

</html>