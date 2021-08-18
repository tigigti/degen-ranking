<?php
session_set_cookie_params(86400);
session_start();
date_default_timezone_set("Europe/Berlin");
?>

<!DOCTYPE html>

<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Degen Ranking | Logs</title>
<?php
include_once "./templates/header.php";
$res = $mysqli->query("SELECT * FROM game_log ORDER BY time DESC LIMIT 10");
?>

<div class="container">
    <div id="log-container">
        <?php while ($row = $res->fetch_array()): ?>
        <div class="card text-center" style="margin: 1rem 0;">
        <div class="card-body">
            <p class="card-text">
                <?php echo $row["text"]; ?>
            </p>
        </div>
        <div class="card-footer text-muted">
        <?php
$date = new DateTime($row["time"]);
echo $date->format("d.m.Y H:i");
?>
        </div>
        </div>
        <?php endwhile;?>
    </div>
    <button id="load-logs-btn" class="btn btn-primary">Ältere Laden</button>
</div>
<script src="js/jquery-3.6.0.min.js" type="text/javascript"></script>
<script src="js/bootstrap.bundle.min.js" type="text/javascript"></script>



<script type="text/javascript">
var logBtn = $("#load-logs-btn");
var logOffset = 0;
var logContainer = $("#log-container");

logBtn.on("click", function() {
    logOffset += 10;
    $.ajax({
        method: "POST",
        url: "./php/logs.php",
        data: {
            offset: logOffset
        }
    }).done(function(res) {
        if (res == "0") {
            alert("Keine älteren Logs Mehr");
            return;
        }
        res = JSON.parse(res);
        for (var i = 0; i < res.length; i++) {
            console.log(res[i][0],res[i][1]);
            logContainer.append(`
            <div class="card text-center" style="margin: 1rem 0;">
                <div class="card-body">
                    <p class="card-text">
                        ${res[i][0]}
                    </p>
                </div>
                <div class="card-footer text-muted">${res[i][1]}</div>
            </div>`);
        }
    });
});
</script>
</body>
</html>