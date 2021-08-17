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
    <title>Degen Ranking | Login</title>
<?php
include_once "./templates/header.php";

if (isset($_POST["username"]) && isset($_POST["password"])) {

    $username = $_POST["username"];
    $password = $_POST["password"];

// Prepare Query
    if (!($stmt = $mysqli->prepare("SELECT * FROM user WHERE username=?"))) {
        echo "Preparing Failed: " . $mysqli->error;
    }

// Bind Parameters
    if (!($stmt->bind_param("s", $username))) {
        echo "Binding Failed: " . $mysqli->error;
    }

// Execute Query
    if (!($stmt->execute())) {
        echo "Executing Failed: " . $mysqli->error;
    }

    $res = $stmt->get_result();
    if ($res->num_rows == 0) {
        echo "<div class='container'>";
        echo "<div class='alert alert-danger'>User not found!</div>";
        echo "<a href='../login.php'>Back</a>";
        echo "</div>";
        return;
    }
    $user = $res->fetch_array();
    if (!(password_verify($password, $user["password"]))) {
        echo "<div class='container'>";
        echo "<div class='alert alert-danger'>Password incorrect!</div>";
        echo "<a href='../login.php'>Back</a>";
        echo "</div>";
        return;
    } else {
        $_SESSION["username"] = $user["username"];
        $_SESSION["id"] = $user["id"];
        ?>
    <script type="text/javascript">
    // Redirect to Homepage
        var homeLocation = window.location.protocol + "//" +
            window.location.hostname + "/degen-ranking/index.php";
        window.location = homeLocation;
    </script>
    <?php
}
    $mysqli->close();
}
?>
<form method="POST" action="login.php">
    <div class="container">
    <div class="mb-3 row">
        <label for="username" class="col-sm-2 col-form-label">Username</label>
        <div class="col-sm-10">
        <input type="text" class="form-control" id="username" name="username" required>
        </div>
    </div>
    <div class="mb-3 row">
        <label for="password" class="col-sm-2 col-form-label">Password</label>
        <div class="col-sm-10">
        <input type="password" class="form-control" id="password" name="password" required>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Login</button>
    </div>
</form>
<?php
include_once "./templates/footer.php";
?>
</html>