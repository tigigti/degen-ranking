<?php
session_set_cookie_params(86400);
session_start();
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
while ($row = $res->fetch_array()): ?>
            <tr>
                <th><?php echo $rank++; ?></th>
                <td>
                    <?php echo $row["username"]; ?>
                </td>
                <td><?php echo $row["votes"]; ?></td>
            </tr>
            <?php endwhile;?>
        </tbody>
    </table>
</div>
<?php
include_once "./templates/footer.php";
?>
</html>