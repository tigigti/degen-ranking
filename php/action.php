<?php
include "db.php";

$unit = $_POST["unit"];
$id = $_POST["id"];
$amount = $_POST["amount"];
$username = $_POST["username"];
$timer = $unit . "Timer";
if (isset($_POST["enemyid"])) {
    $enemyID = $_POST["enemyid"];
}

// Get User
$stmt = $mysqli->prepare(
    "SELECT * FROM user WHERE id=?"
);
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_array();

// Get base information
$mines = $row["mine"];

if ($unit == "scavenger") {
    // calculate random gain
    $randomGain = random_int(1, 100) * $amount;
    if ($mines) {
        $deathChance = random_int(1, 100);
        if ($mines >= $deathChance) {
            // Scavenger died

            $mysqli->query("UPDATE user SET scavenger=scavenger-1,mine=0 WHERE id=$id");
            $mysqli->query("INSERT INTO game_log VALUES (0,now(),'$username Scavenger ist auf eine Mine getreten. RIP.')");
            echo "Dein Scavenger ist auf eine Mine getreten und gestorben :(";
            $mysqli->query("UPDATE user SET $timer=now() WHERE id=$id");
            return;
        }
    }

    // Insert into db
    $mysqli->query("UPDATE user SET scraps=scraps+$randomGain WHERE id=$id");
    $mysqli->query("INSERT INTO game_log VALUES (0,now(),'$username Scavenger fanden $randomGain Schrott')");
    echo "Du fandest $randomGain Schrott!";
}

if ($unit == "bomber") {
    // Plant a mine at another players base
    $mysqli->query("UPDATE user SET mine=mine+$amount WHERE id=$enemyID");
    $mysqli->query("INSERT INTO game_log VALUES (0,now(),'$username Bomber haben eine Mine gelegt...')");
    echo "Du hast eine Mine gelegt";
}

$mysqli->query("UPDATE user SET $timer=now() WHERE id=$id");
