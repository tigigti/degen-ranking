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
include_once "./units.php";

if ($coins >= $price) {
    // User bezahlen lassen
    $mysqli->query("UPDATE user SET $currency=$currency-$price WHERE id=$userid");

    // In die jeweilige Tabelle gehen und Produkt hinzufügen

    if ($item == "lootbox") {
        $randInt = random_int(1, count($unitArray));
        $unit = $unitArray[$randInt];
        $unitType = $unit["type"];
        $unitName = $unit["name"];

        $mysqli->query("UPDATE user SET $unitType=$unitType+1 WHERE id=$userid");
        $mysqli->query("INSERT INTO game_log VALUES (0,now(),'In $username Lootbox war $unitName!')");

        echo "Du hast $unitName erhalten!";
        return;

    } else {
        $mysqli->query("UPDATE user SET $item=$item+1 WHERE id=$userid");

        if ($item != "money") {
            $mysqli->query("INSERT INTO game_log VALUES (0,now(),'$username hat sich $item besorgt')");
        }
        echo "Du hast $item gekauft";
        return;
    }
}

echo "Not enough $currency boi";
