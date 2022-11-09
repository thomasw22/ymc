<!-- 

    -better comments
    -format php in html
    -code time (-waka time beter)
    -project manager
    * * haaah 
    TODO: AA
 -->

<?php
session_start();
// $conn = mysqli_connect("localhost", "root", "passw", "ymc");
$conn = mysqli_connect("localhost", "u125588p130441_root", "21YMCdb#", "u125588p130441_ymc");
if(!$conn) {
    die('Error: ' . mysqli_connect_error());
}

if(isset($_SESSION["cart"])) {
    $cart = $_SESSION["cart"];
} else {
    // $cart = [[1, 2, "S"], [2, 5, "XS"]];
    $cart = [];
}
// $cart = [[1, 2, "S"], [2, 1, "XS"], [2, 1, "XS"], [2, 2, "XS"]];
// $cart =;
$_SESSION["cart"] = $cart;

// $cart=[[10, 3, "s"], [10, 3, "s"]];
// 
//
$cartsize = 0; 
foreach($cart as $item) {
    $cartsize += $item[1];
}
 
// $cartsize = 15;
// echo $cartsize;
if($cartsize > 0) {
    if($cartsize < 10) {
        $cb = $cartsize;
    } else {
        $cb = "9p";
    }
} 

$_SESSION["returl"]="checkout.php";


$carthtml = "";

if(isset($cart)) {
    if($cartsize < 1) {
        $carthtml .= "<h4>Your shopping cart is empty</h4>";
    } else {
        foreach($cart as $item) {

            $cartamounthtml = "";

            $sql = "SELECT * FROM products WHERE id=$item[0];";
            $res = mysqli_query($conn, $sql);
            if(mysqli_num_rows($res) > 0) {
                while($row = mysqli_fetch_assoc($res)) {
                    // echo var_dump($row);
                    // echo var_dump($res);

                    $available = explode(":", $row["available"])[array_search($item[2], explode(":", $row["sizes"]))];
                    
                    // echo "<script>console.log($available);</script>";
                    for($i = 1; $i < $available + 1; $i++) {
                        if($i == $item[1]) {
                            $cartamounthtml .= "<option value='$i' selected>$i</option>";
                            // echo $i;
                        } else {
                            $cartamounthtml .= "<option value='$i'>$i</option>";
                            // echo $i;
                        }
                    }

                    $carthtml .= "
                        <div class='cart-item'>
                            <div class='cart-left'>
                                <img src='".explode("::",$row["images"])[0]."' alt=''>
                            </div>
                            <div class='cart-right'>
                                <h4>".$row["name"]."</h4>
                                <p><span>&euro;</span>".number_format($item[1] * $row["price"] / 100, 2)."</p>
                                <p>Size: ".$item[2]."</p>
                                <p>Qty: ".$item[1]."</p>
                                <div>
                                    <form action='aci.php' method='POST'>
                                        <p class='cart-amount'>
                                            <form action='aci.php' method='POST'>
                                                <select name='amount'>
                                                    ".$cartamounthtml."
                                                </select>
                                                <input type='hidden' name='cartid' value='".array_search($item, $cart)."'>
                                                <input type='submit' value='change'>
                                            </form>
                                        </p>
                                    </form>
                                    <form action='rci.php' method='POST'>
                                        <p class='cart-delete'>
                                            <input type='hidden' name='id' value='".array_search($item, $cart)."'>
                                            <button type='submit'>
                                                <i class='fas fa-trash'></i>
                                            </button>
                                        </p>
                                    </form>
                                </div>
                            </div>
                        </div>
                    ";
                }
            } else {
                echo "0 results";
            }
        }
    }
} else {
    echo "no cart set";
}

$shipping = 0;
$sql = "SELECT * FROM vars WHERE name='shipping';";
$res = mysqli_query($conn, $sql);
if(mysqli_num_rows($res) > 0) {
    while($row = mysqli_fetch_assoc($res)) {
        $shipping = $row["value"];
    }
}
$total = $shipping;

if(!isset($_SESSION["prodss"])) {
    $_SESSION["prodss"] = [];
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
    <div class="shopping-wrapper">
        <div class="shopping">
            <div class="shopping-content">
                <h2>SHOPPING CART</h2>
                <a href="checkout.php">Go to checkout</a>
                <div class="cart-items">
                    <?=$carthtml?>
                </div>
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
        <div class="search-btn">
            <i class="fas fa-search"></i>
        </div>
        <div class="cart-btn cb-<?=$cb?>">
            <i class="fas fa-shopping-cart"></i>
            <!-- <i class="fa fa-brands fa-opencart"><  /i> -->
        </div>
        <div class="search">
            <div class="search-bar">
                <form action="shop.php" method="GET">
                    <input type="text" name="s" placeholder="search...">
                    <button type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>


    <div class="main-wrapper view-main-wrapper">
        <div class="main-img"></div>
        <div class="main">

        </div>
    </div>
    <div class="side view-side"></div>
    <div class="side2 view-side2"></div>
    <div class="info checkout">
        <h1>Checkout</h1>
        <div class="checkout-wrapper">
            <div class="checkout-items">
                <h3>Items</h3>
                <div class="checkout-items-wrapper">
                    <?php

                        if(isset($cart)) {
                            $_SESSION["prods"] = [];
                            foreach($cart as $item) {
                                $sql = "SELECT * FROM products WHERE id=$item[0];";
                                $res = mysqli_query($conn, $sql);
                                if(mysqli_num_rows($res) > 0) {
                                    while($row = mysqli_fetch_assoc($res)) {
                                        echo "
                                            <div class='checkout-item'>
                                                <div class='checkout-item-left'>
                                                    <img src='".explode("::",$row["images"])[0]."' alt=''>
                                                </div>
                                                <div class='checkout-item-right'>
                                                    <h4>".$row["name"]."</h4>
                                                    <p>Amount: $item[1]</p>
                                                    <p>Size: $item[2]</p>
                                                    <p><span>&euro;</span>".number_format($item[1] * $row["price"] / 100, 2)."</p>
                                                </div>
                                            </div>
                                        ";
                                        $total += $item[1] * $row["price"] / 100;
                                        array_push($_SESSION["prodss"], $item);
                                    }
                                } else {
                                    echo "0 results";
                                }
                            }   
                        }
                        else {
                            echo "no cart set";
                        }
                        $_SESSION["total"] = $total;
                    ?>
                </div>
                <div class="checkout-total">
                    <h4>Shipping: <span>&euro;</span><?=$shipping?></h4>
                    <h4>Total: <span>&euro;</span><?=$total?></h4>
                </div>
            </div>
            <div class="checkout-info">
                <div class="checkout-info-wrapper">
                    <form action="checkoutprocess.php" method="POST">
                        <h3>Your info</h3>
                        <div class="checkout-info-personal">
                            <div class="info-field">
                                <input type="text" name="name" id="name" placeholder=" " required>
                                <label for="name">Full name<span class="fs">*</span></label>
                            </div>
                            <div class="info-field">
                                <input type="email" name="mail" id="mail" placeholder=" " required>
                                <label for="mail">E-mail<span class="fs">*</span></label>
                            </div>
                            <div class="info-field">
                                <input type="text" name="phone" id="phone" placeholder=" ">
                                <label for="phone">Phone number</label>
                            </div>
                        </div>
                        <h3>Address</h3>
                        <div class="checkout-info-address">
                            <div class="info-field">
                                <input type="text" name="country" id="country" placeholder=" " required>
                                <label for="country">Country<span class="fs">*</span></label>
                            </div>
                            <div class="info-field">
                                <input type="text" name="state" id="state" placeholder=" " required>
                                <label for="state">State/Province<span class="fs">*</span></label>
                            </div>
                            <div class="info-field">
                                <input type="text" name="city" id="city" placeholder=" " required>
                                <label for="city">City<span class="fs">*</span></label>
                            </div>
                            <div class="info-field">
                                <input type="text" name="street" id="street" placeholder=" " required>
                                <label for="street">Street + Housenumber<span class="fs">*</span></label>
                            </div>
                            <div class="info-field">
                                <input type="text" name="postal" id="postal" placeholder=" " required>
                                <label for="postal">Zip/Postal Code<span class="fs">*</span></label>
                            </div>
                        </div>

                        <input type="checkbox" name="tac" id="tac" required>
                        <label for="tac">I have read, and accept the <a>Terms and Conditions</a></label>

                        <input type="submit" class="checkoutsubmit" value="ORDER">
                    </form>
                </div>
            </div>
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