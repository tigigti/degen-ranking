<link rel="stylesheet" href="css/bootstrap.min.css"/>
<link rel="stylesheet" href="css/main.css"/>
</head>
<?php include_once "./php/db.php";?>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="index.php">Degen Ranking</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav">
            <?php if (!isset($_SESSION["username"])): ?>
                <a class="nav-link" href="login.php">Login</a>
                <a class="nav-link" href="register.php">Register</a>
            <?php else: ?>
                <a class="nav-link" href="user.php?name=<?php echo $_SESSION['username']; ?>&id=<?php echo $_SESSION['id']; ?>"><?php echo $_SESSION["username"]; ?></a>
                <a class="nav-link" href="vote.php">Vote</a>
                <a class="nav-link" href="logout.php">Logout</a>
            <?php endif;?>
            <a class="nav-link" href="logs.php">Logs</a>
        </div>
        </div>
    </div>
</nav>