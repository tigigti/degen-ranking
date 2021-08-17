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
    <title>Degen Ranking | Register</title>
<?php
include_once "./templates/header.php";

if (isset($_POST["username"]) && isset($_POST["password"])) {

    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
// Check if User exists
    $stmt = $mysqli->prepare("SELECT * FROM user WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows != 0) {
        echo "<div class='container'>";
        echo '<div class="alert alert-danger" role="alert">';
        echo "Username exists!";
        echo "</div>";
        echo "<a href='../register.php'>Back</a>";
        echo "</div>";

        return;
    }

    $stmt->close();

// Prevent Sql Injection
    // Prepare Statement
    if (!($stmt = $mysqli->prepare("INSERT INTO user (username,password) VALUES (?,?)"))) {
        echo "Preparing Failed: " . $mysqli->error;
    }

// Try Binding Parameters
    if (!($stmt->bind_param("ss", $username, $password))) {
        echo "Binding Failed: " . $mysqli->error;
    }

// Try Executing Query
    if (!($stmt->execute())) {
        echo "Execution Failed: " . $mysqli->error;
    } else {
        $id = $mysqli->insert_id;
        echo "<div class='container'>";
        echo '<div class="alert alert-success" role="alert">';
        echo "Registration successfull!";
        echo "</div>";
        echo "<a href='../login.php'>Login</a>";
        echo "</div>";
    }

    $stmt->close();
}
?>
<form method="POST" action="/register.php">
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
    <button type="submit" class="btn btn-primary">Register</button>
    </div>
</form>
<?php
include_once "./templates/footer.php";
?>
</html>