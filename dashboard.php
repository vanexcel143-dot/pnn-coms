<?php

session_start();

if (!isset($_SESSION["user_id"])) {

    header("Location: index.php");

    exit();

}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>PC HUB - Dashboard</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

<div class="dashboard">

    <div class="dashboard-box">

        <div class="logo">💻</div>

        <h1>PNN COMPUTER
</h1>

        <h2>
            Welcome,
            <?php echo htmlspecialchars($_SESSION["fullname"]); ?>!
        </h2>

        <p>
            You are successfully logged in.
        </p>

        <p>
            Email:
            <?php echo htmlspecialchars($_SESSION["email"]); ?>
        </p>

        <div class="shop-menu">

            <div class="menu-card">
                🖥️
                <span>Computers</span>
            </div>

            <div class="menu-card">
                ⌨️
                <span>Accessories</span>
            </div>

            <div class="menu-card">
                🎮
                <span>Gaming</span>
            </div>

        </div>

        <a href="logout.php">

            <button class="logout">
                LOGOUT
            </button>

        </a>

    </div>

</div>

</body>

</html>