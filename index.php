<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$error = isset($_GET['error']) ? $_GET['error'] : "";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PC HUB - Login</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container">

    <div class="form-box">

        <div class="logo">💻</div>

        <h1>PNN COMPUTER
</h1>
        <p class="subtitle">Computer Shop System</p>

        <?php if ($error != "") { ?>
            <div class="error">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php } ?>

        <form action="login.php" method="POST">

            <label>Email Address</label>

            <input
                type="email"
                name="email"
                placeholder="Enter your email"
                required
            >

            <label>Password</label>

            <input
                type="password"
                name="password"
                placeholder="Enter your password"
                required
            >

            <button type="submit">LOGIN</button>

        </form>

        <p class="switch">
            Don't have an account?
            <a href="signup.php">Sign Up</a>
        </p>

    </div>

</div>

</body>
</html>