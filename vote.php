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
    <title>Degen Ranking | Vote</title>
<?php
include_once "./templates/header.php";

if (!isset($_SESSION["username"])) {
    echo "<a href='login.php'>Please log in</a>";
    return;
}

// Get All users and save them in an Array
$stmt = $mysqli->prepare("SELECT * FROM user");
$stmt->execute();
$res = $stmt->get_result();
$numberUsers = $res->num_rows;

$userArray = [];

for ($i = 0; $i < $numberUsers; $i++) {
    $row = $res->fetch_array();
    $userArray[$i] = array(
        "id" => $row["id"],
        "name" => $row["username"],
    );
}

$stmt->close();
// Check if there are duplicates or no votes
if (isset($_POST["vote-1"]) && isset($_POST["vote-2"]) && isset($_POST["vote-3"])) {
    $hasErrors = false;
    $voteArr = [];
    $voteArr = [$_POST["vote-1"], $_POST["vote-2"], $_POST["vote-3"]];
    for ($i = 0; $i < count($voteArr); $i++) {
        if ($voteArr[$i] == 0) {
            $hasErrors = true;
        }
        for ($j = $i + 1; $j < count($voteArr); $j++) {
            if ($voteArr[$i] == $voteArr[$j]) {
                $hasErrors = true;
            }
        }
    }if ($hasErrors) {
        echo "<div class='container'>";
        echo "<div class='alert alert-danger'>Keine doppelte Benennungen!</div>";
        echo "</div>";
    } else {
        // Cast votes
        // Check if player can vote this week
        $thisWeek = date("oW", time());
        $id = $_SESSION["id"];

        $stmt = $mysqli->prepare("SELECT * FROM user where id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();

        $row = $res->fetch_array();
        $voted_at = $row["voted_at"];

        $votedWeek = date("oW", strtotime($voted_at));
        if ($votedWeek == $thisWeek && false) {
            echo "<div class='container'>";
            echo "<div class='alert alert-danger'>Du hast diese Woche bereits gevoted!</div>";
            echo "</div>";
        } else {
            // player can vote this week
            $top_vote = $voteArr[0];
            $vote_desc = $_POST["reasoning"];
            $points = 3;
            for ($i = 0; $i < count($voteArr); $i++) {
                $vote = $voteArr[$i];
                $mysqli->query("UPDATE user SET votes=votes+$points,coins=coins+$points  WHERE id=$vote");
                $points--;
            }

            $mysqli->query("UPDATE user SET voted_at=now(), total_votes=total_votes+1, top_voted=$top_vote WHERE id=$id");

            if (!($stmt = $mysqli->prepare("UPDATE user SET vote_desc=? WHERE id=?"))) {
                echo "Preparation Failed: " . $mysqli->error;
                exit;
            }

            $stmt->bind_param("si", $vote_desc, $id);
            if (!($stmt->execute())) {
                echo "Execution Failed: " . $mysqli->error;
                exit;
            }
            echo "<div class='container'>";
            echo "<div class='alert alert-success'>Abgestimmt</div>";
            echo "</div>";
            $stmt->close();
        }
    }
}

?>
<form method="POST" action="vote.php">
    <div class="container">
        <?php for ($i = 1; $i <= 3; $i++): ?>
            <div class="row picker-row" style="margin-bottom: 1rem;">
                <div class="col-md-3">
                    <span>Platz <?php echo $i; ?></span>
                </div>
                <div class="col-md-9">
                    <select class="form-select" id="<?php echo "select-rank-" . $i; ?>" data-is-chosen=0 name="vote-<?php echo $i; ?>">
                        <option value="0">Wähle...</option>
                        <!-- Iterate Over Array and Display Username and ID -->
                        <?php foreach ($userArray as $user): ?>
                        <option value="<?php echo $user['id']; ?>"><?php echo $user["name"]; ?></option>
                        <?php endforeach;?>
                    </select>
                </div>
            </div>
        <?php endfor;?>
        <div class="mb-3">
            <label for="reasoning" class="form-label">Begründung (optional)</label>
            <textarea class="form-control" id="reasoning" rows="3" name="reasoning"></textarea>
        </div>
        <button type="submit" class="btn btn-dark">Vote</button>
    </div>
</form>
<?php
include_once "./templates/footer.php";
?>
</html>