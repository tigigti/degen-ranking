<?php
include "db.php";

$offset = $_POST["offset"];
$res = $mysqli->query("SELECT * FROM game_log ORDER BY time DESC LIMIT 10 OFFSET $offset");

if ($res->num_rows == 0) {
    echo "0";
    return;
}

$messages = array();

while ($row = $res->fetch_array()) {
    $date = new DateTime($row["time"]);
    $date = $date->format("d.m.Y H:i");
    array_push($messages, array($date, $row["text"]));
}

echo json_encode($messages);
