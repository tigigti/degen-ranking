<?php
session_set_cookie_params(86400);
session_start();

if (isset($_GET["name"]) && isset($_GET["id"])) {
    $username = $_GET["name"];
    $id = $_GET["id"];
}
?>

<!DOCTYPE html>

<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Degen Ranking | <?php echo $username; ?></title>
<?php
include_once "./templates/header.php";
if ($id) {
    // Get user
    $stmt = $mysqli->prepare(
        "SELECT * FROM user WHERE id=?"
    );
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_array();

    // Get base information
    $coins = $row["coins"];

    // Timer and cooldowns
    $zone = new DateTimeZone("Europe/Berlin");
    $scavengerTimer = new DateTime($row["scavengerTimer"], $zone);

    // Other User
    $userArray = array();
    $userStmt = $mysqli->prepare("SELECT id,username FROM user WHERE NOT username=?");
    $userStmt->bind_param("s", $username);
    $userStmt->execute();
    if (!($userRes = $userStmt->get_result())) {
        echo $mysqli->error;
    }
    while ($userRow = $userRes->fetch_array()) {
        array_push($userArray, array($userRow["id"], $userRow["username"]));
    }
}
?>
<div class="container">
    <ul class="list-group">
        <li class="list-group-item">Coins: <?php echo $coins; ?></li>
    </ul>

<?php
// Degen Shop
if (isset($_SESSION["username"]) && $_SESSION["username"] == $_GET["name"]): ?>
    <button id="shop-btn" class="btn btn-dark" style="margin-top: 1rem;">Enter Shop</button>
    <div id="degen-shop" style="display: none">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Preis</th>
                    <th scope="col">Name</th>
                    <th scope="col">Beschreibung</th>
                </tr>
            </thead>
            <tbody>
                <tr onClick="foo('buy')">
                    <th scope="row">3 Coins</th>
                    <td>Lootbox</td>
                    <td>Beinhaltet eine zufällige Einheit für deine Gang</td>
                </tr>
            </tbody>
        </table>
    </div>
<?php endif;?>


</div>
<script type="text/javascript">
    function foo(text){
        var buy = confirm("Do you want to buy shit?");
        if(buy){
            console.log(text);
        }
    }

    var shopBtn = document.querySelector("#shop-btn");
    var shopTable = document.querySelector("#degen-shop");
    shopBtn.addEventListener("click",function(e){
        shopTable.style.display = shopTable.style.display == "block" ? "none" : "block";
    });
</script>
<?php
include_once "./templates/footer.php";
?>
</html>