<?php



if(isset($_POST["mailingsubmit"])) {
    $mail = $_POST["mail"];
    $conn = mysqli_connect("localhost", "root", "passw", "ymc");
    if(!$conn) {
        die('Error: ' . mysqli_connect_error());
    }
    $sql = "INSERT INTO mailing ( mail ) VALUES ('$mail');";
    $res = mysqli_query($conn, $sql);
    if($res) {
        header("location: index.php");
    } else {
        header("location: index.php");
    }
}

?>