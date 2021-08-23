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
    <title>Degen Ranking</title>
<?php
include_once "./templates/header.php";
?>
<div class="container">
    <table class="table table-dark table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Username</th>
                <th>Votes</th>
            </tr>
        </thead>
        <tbody>
            <?php
$stmt = $mysqli->prepare("SELECT * FROM user ORDER BY votes desc");
$stmt->execute();
$res = $stmt->get_result();
$rank = 1;

$users = [];
$totalVotes = 0;

while ($row = $res->fetch_array()) {
    $user = array(
        "name" => $row["username"],
        "total_votes" => $row["total_votes"],
        "last_vote" => $row["voted_at"],
        "votes" => $row["votes"],
        "top_voted" => $row["top_voted"],
        "id" => $row["id"],
        "desc" => $row["vote_desc"],
    );
    array_push($users, $user);
    $totalVotes += $row["total_votes"];
}
for ($i = 0; $i < count($users); $i++) {
    for ($j = 0; $j < count($users); $j++) {
        if ($users[$i]["top_voted"] == $users[$j]["id"]) {
            $users[$i]["top_voted_name"] = $users[$j]["name"];
        }
    }
}

foreach ($users as $user): ?>
            <tr>
                <th><?php echo $rank++; ?></th>
                <td>
                    <a href="user.php?name=<?php echo $user["name"]; ?>&id=<?php echo $user["id"]; ?>"><?php echo $user["name"]; ?></a>
                </td>
                <td><?php echo $user["votes"]; ?></td>
            </tr>
            <?php endforeach;?>
        </tbody>
    </table>
    <?php if (isset($_SESSION["username"])): ?>
    <a type="button" class="btn btn-dark" href="vote.php">Vote</a>
    <?php endif?>
</div>

<div class="container" style="margin-top: 3rem;">
    <h1>Vote Statistiken</h1>
    <div class="row">
        <div class="col-md-6">
            <div class="card" style="margin-bottom: 25px;">
                <div class="card-body">
                    <h5 div="card-title">Total Votes: <?php echo $totalVotes; ?></h5>
                        <?php
foreach ($users as $user):
    $percent = floor($user["total_votes"] * 100 / $totalVotes);?>
									                        <div class="row">
									                            <div class="col-md-6">
									                                <?php echo $user["name"]; ?>
									                            </div>
									                            <div class="col-md-6">
									                                <div class="progress">
									                                    <div class="progress-bar" style="width: <?php echo $percent; ?>%"><?php echo $percent; ?>%</div>
									                                </div>
									                            </div>
									                        </div>
									                    <?php endforeach;?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">

                <?php
foreach ($users as $user): ?>
                <div class="card profile-container">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $user["name"]; ?></h5>
                        <div class="flex-row">
                            Kann Voten:
                            <?php

$thisWeek = date("oW", time());

$row = $res->fetch_array();
$voted_at = $user["last_vote"];
$votedWeek = date("oW", strtotime($voted_at));

if ($votedWeek == $thisWeek): ?>
                                <div class="vote-icon"></div>
                            <?php else: ?>
                                <div class="vote-icon active"></div>
                            <?php endif;?>
                        </div>
                        <p>Votes abgegeben: <span class="user-votes"><?php echo $user["total_votes"]; ?></span></p>
                        <!-- Make DateTime object from "last_vote" timestamp. Format it user friendly -->
                        <p>Letzer Vote: <?php $date = new DateTime($user["last_vote"]);
echo $date->format("d.m.y");?></p>
                        <p><?php echo $user["name"]; ?> hat <b><?php echo $user["top_voted_name"]; ?></b> auf Platz 1 gewählt.</p>
                        <p style="color: darkgrey; font-style:italic">- "<?php echo $user["desc"]; ?>"</p>
                    </div>
                </div>
                <?php endforeach;?>
            </div>
        </div>
    </div>
<?php
include_once "./templates/footer.php";
?>
</html>