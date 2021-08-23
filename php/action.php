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

$stmt->close();

// Enemy player
if (isset($_POST["enemyid"])) {
    $stmt2 = $mysqli->prepare(
        "SELECT * FROM user WHERE id=?"
    );
    $stmt2->bind_param("i", $enemyID);
    $stmt2->execute();
    $res2 = $stmt2->get_result();
    $row2 = $res2->fetch_array();

    $watchpost = $row2["watchpost"];
    $enemyName = $row2["username"];

    $stmt2->close();
}

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
    if ($watchpost > 0 && ($amount / $watchpost <= 5)) {
        $mysqli->query("UPDATE user SET watchpost=watchpost-1 WHERE id=$enemyID");
        $mysqli->query("INSERT INTO game_log VALUES (0,now(),'$username Bomber haben einen Wachturm von $enemyName zerstört')");
        echo "Du hast einen Wachturm zerstört";
        return;
    }
    $mysqli->query("UPDATE user SET mine=mine+$amount WHERE id=$enemyID");
    $mysqli->query("INSERT INTO game_log VALUES (0,now(),'$username Bomber haben eine Mine gelegt...')");
    echo "Du hast eine Mine gelegt";
}

$mysqli->query("UPDATE user SET $timer=now() WHERE id=$id");
