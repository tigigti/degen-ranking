<?php
include "db.php";

$unit = $_POST["unit"];
$id = $_POST["id"];
$amount = $_POST["amount"];
$username = $_POST["username"];
$timer = $unit . "Timer";

if ($unit == "scavenger") {
    // calculate random gain
    $randomGain = random_int(1, 100) * $amount;

    // Insert into db
    $mysqli->query("UPDATE user SET scraps=scraps+$randomGain WHERE id=$id");
    $mysqli->query("INSERT INTO game_log VALUES (0,now(),'$username's Scavenger fanden $randomGain Schrott')");
    echo "Du fandest $randomGain Schrott!";
}

$mysqli->query("UPDATE user SET $timer=now() WHERE id=$id");
