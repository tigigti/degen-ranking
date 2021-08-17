<?php
include "./db.php";

$userid = $_POST["id"];
$item = $_POST["item"];
$price = $_POST["price"];
$currency = $_POST["currency"];

$result = $mysqli->query("SELECT * FROM user WHERE id=$userid");
$row = $result->fetch_array();
$coins = $row["$currency"];
$username = $row["username"];

if ($coins >= $price) {
    // User bezahlen lassen
    $mysqli->query("UPDATE user SET $currency=$currency-$price WHERE id=$userid");

    // In die jeweilige Tabelle gehen und Produkt hinzufügen

    $mysqli->query("UPDATE user SET $item=$item+1 WHERE id=$userid");

    if ($item != "money") {
        $mysqli->query("INSERT INTO game_log VALUES (0,now(),'$username hat sich $item besorgt')");
    }
    echo "OK";
    return;
}

echo "Not enough $currency boi";
