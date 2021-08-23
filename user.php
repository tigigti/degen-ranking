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
    $scavenger = $row["scavenger"];
    $scraps = $row["scraps"];
    $bomber = $row["bomber"];
    $watchpost = $row["watchpost"];
    $mine = $row["mine"];

    // Timer and cooldowns
    $zone = new DateTimeZone("Europe/Berlin");
    $scavengerTimer = new DateTime($row["scavengerTimer"], $zone);
    $bomberTimer = new DateTime($row["bomberTimer"], $zone);

    $today = new DateTime("now", $zone);
    $scavengerDiff = $today->diff($scavengerTimer);
    $bomberDiff = $today->diff($bomberTimer);

    // Other User
    $userArray = array();
    $userStmt = $mysqli->prepare("SELECT id,username FROM user WHERE NOT username=? ORDER BY votes DESC;");
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
    <h1><?php echo $username; ?></h1>
    <ul class="list-group">
        <li class="list-group-item">Coins: <?php echo $coins; ?></li>
        <li class="list-group-item">Schrott: <?php echo $scraps; ?></li>
        <?php if ($watchpost > 0): ?>
        <li class="list-group-item" style="color: crimson;">Minen: <?php echo $mine; ?></li>
        <?php endif;?>
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
                <tr onClick="buy('lootbox','3',<?php echo $_SESSION['id']; ?>,'coins')">
                    <th scope="row">3 Coins</th>
                    <td>Lootbox</td>
                    <td>Beinhaltet eine zufällige Einheit für deine Gang</td>
                </tr>
                <tr onClick="buy('watchpost','1000',<?php echo $_SESSION['id']; ?>,'scraps')">
                    <th scope="row">1000 Schrott</th>
                    <td>Wachturm</td>
                    <td>Zeigt zusätzliche Informationen über deine Base an. Kann von Angreifern zerstört werden.</td>
                </tr>
            </tbody>
        </table>
    </div>
<?php endif;?>

    <!-- Unit Section -->
    <h2>Gang</h2>

    <!-- Scavenger -->
    <?php if ($scavenger > 0): ?>
    <div class="user-section">
        <h4 class="user-section__title"><?php echo $scavenger; ?> Turbo Degen</h4>
        <div class="user-section__images">
            <?php for ($i = 0; $i < $scavenger; $i++): ?>
            <img src="assets/turbo-degen.png" height=64 width=64/>
            <?php endfor;?>
        </div>
        <div class="user-section__actions">
            <?php if (($scavengerDiff->days >= 1 or $scavengerDiff->h >= 1) && $_SESSION["username"] == $username): ?>
            <button
                class="btn btn-dark action-btn"
                data-unit="scavenger"
                data-amount="<?php echo $scavenger; ?>">
                Scavenge
            </button>
            <?php endif;?>
        </div>
    </div>
    <?php endif;?>

    <!-- Bomber -->
    <?php if ($bomber > 0): ?>
    <div class="user-section">
        <h4 class="user-section__title"><?php echo $bomber; ?> Ibos Anne</h4>
        <div class="user-section__images">
            <?php for ($i = 0; $i < $bomber; $i++): ?>
            <img src="assets/ibos-anne.png" height=64 width=64/>
            <?php endfor;?>
        </div>
        <div class="user-section__actions">
            <?php if (($bomberDiff->days >= 1 or $bomberDiff->h >= 4) && $_SESSION["username"] == $username): ?>
            <button
                class="btn btn-dark action-btn"
                data-unit="bomber"
                data-amount="<?php echo $bomber; ?>">
                Mine Legen bei
            </button>
            <select id="bomber-select" class="form-select" aria-label="Default select example">
            <?php foreach ($userArray as $user): ?>
            <option value="<?php echo $user[0]; ?>"><?php echo $user[1]; ?></option>
            <?php endforeach;?>
            </select>
            <?php endif;?>
        </div>
    </div>
    <?php endif;?>
    <!-- Building Section -->
    <h2>Base</h2>
    <!-- Watchposts -->
    <?php if ($watchpost > 0): ?>
    <div class="user-section">
        <h4 class="user-section__title"><?php echo $watchpost; ?> Wachposten</h4>
        <div class="user-section__images">
            <?php for ($i = 0; $i < $watchpost; $i++): ?>
            <img src="assets/watchtower.png" height=64 width=64/>
            <?php endfor;?>
        </div>
    </div>
    <?php endif;?>

</div>

<script src="js/jquery-3.6.0.min.js" type="text/javascript"></script>
<script src="js/bootstrap.bundle.min.js" type="text/javascript"></script>

<script type="text/javascript">
    function buy(item, price, id, currency){
        var buy = confirm("Do you want to buy "+item+"?");
        if(buy){
            $.ajax({
                method: "POST",
                url: "./php/buy_item.php",
                data: {
                    id: id,
                    item: item,
                    price: price,
                    currency: currency
                }
            }).done(function(res){
                if(res === "OK") {
                    window.location = window.location;
                }
                else {
                    alert(res);
                    window.location = window.location;
                }
            });
        }
    }

    var shopBtn = document.querySelector("#shop-btn");
    var shopTable = document.querySelector("#degen-shop");
    shopBtn.addEventListener("click",function(e){
        shopTable.style.display = shopTable.style.display == "block" ? "none" : "block";
    });

    $(".action-btn").on("click",function(e){
        var unit = $(this).attr("data-unit");
        var id = <?php echo $_SESSION["id"]; ?>;
        var amount = $(this).attr("data-amount");
        var username = "<?php echo $_SESSION["username"] ?>";
        var data = {
            unit: unit,
            id: id,
            amount: amount,
            username: username
        }

        var selectField = $("#"+unit+"-select");
        if(selectField){
            data.enemyid = selectField.val();
        }

        $.ajax({
            method: "POST",
            url: "./php/action.php",
            data: data
        }).done(function(res){
            if(res){
                confirm(res);
            }
            window.location = window.location;
        });
    });
</script>

</body>
</html>