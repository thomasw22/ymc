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

$_SESSION["returl"]="shop.php";


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

// if(isset($_SESSION["cartopen"])) {
//     if($_SESSION["cartopen"]) {
//         echo "<script>os('0.5s');shoppingOpened = !shoppingOpened</script>";
//         echo "HHHHH";
//         $_SESSION["cartopen"] = false;
//     }
// }
$sort = "";
if(isset($_GET["sort"])) {
    $sort = $_GET["sort"];
} else {
    $sort="relevant";
}
$orderby = "";
$pr = "";
$pa = "";
$pd = "";
if ($sort == "pricea") {
    $orderby = "price ASC";
    $pa = "selected";
} else if($sort == "priced") {
    $orderby = "price DESC";
    $pd = "selected";
} else {
    $orderby = "id";
    $pr = "selected";
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
                    <!-- <div class="cart-item">
                        <div class="cart-left">
                            <img src="images/shirt.jpg" alt="">
                        </div>
                        <div class="cart-right">
                            <h4>Cool sweater</h4>
                            <p><span>&euro;</span>75.00</p>
                            <p>Size: S</p>
                            <p>Qty: 1</p>
                            <form action="deletee.php" method="POST">
                                <p class="cart-delete">
                                    <button type="submit">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </p>
                            </form>
                        </div>
                    </div> -->
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
        <div class="search-btn" style="display: none;">
            <i class="fas fa-search"></i>
        </div>
        <div class="cart-btn cb-<?=$cb?>">
            <i class="fas fa-shopping-cart"></i>
            <!-- <i class="fa fa-brands fa-opencart"><  /i> -->
        </div>
        <!-- <div class="search">
            <div class="search-bar">
                <form action="shop.php" method="GET">
                    <input type="text" name="s" placeholder="search...">
                    <button type="submit">
                    <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div> -->
    </div>


    <div class="main-wrapper shop-main-wrapper">
        <div class="main-img"></div>
        <div class="main shop-main">
            <form action="" method="GET">
                <input type="text" name="s" placeholder="Search...">
                <input type="submit" value="" style="display: none;">
            </form>
        </div>
    </div>
    <div class="side view-side"></div>
    <div class="side2 view-side2"></div>
    <div class="info shop-prods">
        <div class="shop-prods-menu">
            <div class="shop-sortfilter-wrapper">
                <div class="shop-sort">
                    <h3>Sort by:</h3>
                    <form action="" method="GET">
                        <select name="sort" onchange="this.form.submit();">
                            <option value="relevant" <?=$pr?>>Most relevant</option>
                            <option value="pricea" <?=$pa?>>Price ascending</option>
                            <option value="priced" <?=$pd?>>Price descending</option>
                        </select>
                    </form>
                </div>

                <div class="shop-selected">
                    <?php
                        if(isset($_GET["s"]) && $_GET["s"] != "") {
                            $searchquery = filter_input(INPUT_GET, 's', FILTER_SANITIZE_SPECIAL_CHARS);
                            echo "
                                <form action='' method='GET'>
                                    <input type='hidden' name='s' value=''>
                                    <div class='shop-selected-filter'>
                                        <input type='submit' value='X'>
                                        <p>$searchquery</p>
                                    </div>
                                </form>
                            ";
                        }
                    ?>

                    <!-- <div class="shop-selected-filter">
                        <h3>X</h3>
                        <p>Shirts</p>
                    </div>
                    <div class="shop-selected-filter">
                        <h3>X</h3>
                        <p>S</p>
                    </div>
                    <div class="shop-selected-filter">
                        <h3>X</h3>
                        <p>M</p>
                    </div> -->
                    <?php
                        // echo var_dump($_GET["f"]);
                        // if(isset($_SESSION["filters"])) {
                        //     if(count($_SESSION["filters"]) > 0) {
                        //         if($_SESSION["filters"] == $_GET["f"]) {
                        //             $filters = $_SESSION["filters"];
                        //         } else {
                        //             $filters = $_GET["f"];
                        //             $_SESSION["filters"] = $filters;
                        //         }
                        //     } else {
                        //         $filters = $_GET["f"];
                        //         $_SESSION["filters"] = $filters;
                        //     }
                        // } else {
                        //     $filters = $_GET["f"];
                        // }
                        // if(isset($_GET["removefilter"])) {
                        //     // echo $_GET["removefilter"];
                        //     $newf = [];
                        //     foreach($filters as $filter) {
                        //         if($filter == $_GET["removefilter"]) {
                        //             //remove
                        //         } else {
                        //             array_push($newf, $filter);
                        //         }
                        //     }
                        //     $filters = $newf;
                        //     $_SESSION["filters"] = $filters;
                        // }
                        // echo var_dump($filters);
                        // foreach($filters as $f) {
                        //     echo "
                        //         <div class='shop-selected-filter'>
                        //             <form action='' method='GET'><input type='hidden' name='removefilter' value='$f'><input type='submit' value='X'></form>
                        //             <p>$f</p>
                        //         </div>
                        //     ";
                        // }
                    ?>
                </div>

                <div class="shop-filter">
                    <!-- <p onclick="showFilterPopup();">Filter</p> -->
                    <div class="shop-filter-popup">
                        <form action="" method="GET">
                            <p>Type</p>
                            <label for="prodshirts">Shirts</label>
                            <input id="prodshirts" type="checkbox" name="f" value="shirts">
                            <label for="prodsweaters">Sweaters</label>
                            <input id="prodsweaters" type="checkbox" name="f" value="sweaters">
                            <label for="prodjeans">Jeans</label>
                            <input id="prodjeans" type="checkbox" name="f" value="jeans">

                            <p>Size</p>
                            <label for="sizexs">XS</label>
                            <input id="sizexs" type="checkbox" name="f[]" value="XS">
                            <label for="sizes">S</label>
                            <input id="sizes" type="checkbox" name="f[]" value="S">
                            <label for="sizem">M</label>
                            <input id="sizem" type="checkbox" name="f[]" value="M">
                            <label for="sizel">L</label>
                            <input id="sizel" type="checkbox" name="f[]" value="L">
                            <label for="sizexl">XL</label>
                            <input id="sizexl" type="checkbox" name="f[]" value="XL">

                            <br />
                            <input type="submit" value="SAVE">
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="shop-prods-wrapper">
            <?php
                $filter = false; // query
                if(isset($_GET["s"]) && $_GET["s"] != "") {
                    $search = true;
                } else {
                    $search = false;
                }
                
                $prodhtml = "";
                if($search) {
                    $searchquery = filter_input(INPUT_GET, 's', FILTER_SANITIZE_SPECIAL_CHARS);
                    // echo $searchquery;
                    $sql = "SELECT * FROM products WHERE name LIKE '%$searchquery%' ORDER BY $orderby;";
                    $res = mysqli_query($conn, $sql);
                    if(!$res) {
                        echo "No products found";
                    } else {
                        if(mysqli_num_rows($res) > 0) {
                            while($row = mysqli_fetch_assoc($res)) {
                                // print_r($row);
                                $prodhtml .= "
                                    <div class='prod'>
                                        <img src='".explode("::",$row["images"])[0]."' alt='".$row["name"]."'>
                                        <h3>".$row["name"]."</h3>
                                        <p>Sizes ".implode("/", explode(":", $row["sizes"]))."</p>
                                        <p><span>&euro;</span>".number_format($row["price"] / 100, 2)."</p>
                                        <a href='view.php?p=".$row["id"]."'>View more</a>
                                    </div>
                                ";
                            }
                        } else {
                            //0 results
                            echo "No products found";
                        }
                    }
                } else {
                    // echo $orderby;
                    $sql = "SELECT * FROM products ORDER BY $orderby;";
                    $res = mysqli_query($conn, $sql);
                    if(mysqli_num_rows($res) > 0) {
                        while($row = mysqli_fetch_assoc($res)) {
                            // print_r($row);
                            $prodhtml .= "
                                <div class='prod'>
                                    <img src='".explode("::",$row["images"])[0]."' alt='".$row["name"]."'>
                                    <h3>".$row["name"]."</h3>
                                    <p>Sizes ".implode("/", explode(":", $row["sizes"]))."</p>
                                    <p><span>&euro;</span>".number_format($row["price"] / 100, 2)."</p>
                                    <a href='view.php?p=".$row["id"]."'>View more</a>
                                </div>
                            ";
                        }
                    } else {
                        //0 results
                    }
                }
            ?>
            <?=$prodhtml?>
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
    <script src="script/main.js"></script>
</body>

</html>