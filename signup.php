<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fullname = trim($_POST["fullname"]);
    $age = trim($_POST["age"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    /*
    =================================
    REQUIRED FIELD VALIDATION
    =================================
    */

    if (
        empty($fullname) ||
        empty($age) ||
        empty($email) ||
        empty($password) ||
        empty($confirm_password)
    ) {

        $error = "All fields are required.";

    }

    /*
    =================================
    TEXT INPUT VALIDATION
    =================================
    */

    elseif (!preg_match("/^[a-zA-Z ]+$/", $fullname)) {

        $error = "Full name must contain letters and spaces only.";

    }

    /*
    =================================
    NUMBER VALIDATION
    =================================
    */

    elseif (!filter_var($age, FILTER_VALIDATE_INT)) {

        $error = "Age must be a valid number.";

    }

    elseif ($age < 1 || $age > 100) {

        $error = "Please enter a valid age.";

    }

    /*
    =================================
    EMAIL VALIDATION
    =================================
    */

    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Please enter a valid email address.";

    }

    /*
    =================================
    PASSWORD VALIDATION
    =================================
    */

    elseif (strlen($password) < 6) {

        $error = "Password must be at least 6 characters.";

    }

    elseif ($password !== $confirm_password) {

        $error = "Passwords do not match.";

    }

    else {

        /*
        =================================
        DATABASE CONNECTION
        =================================
        */

        $conn = new mysqli(
            "localhost",
            "root",
            "",
            "computer_shop"
        );

        if ($conn->connect_error) {

            die("Database connection failed: " . $conn->connect_error);

        }

        /*
        =================================
        CHECK EMAIL
        =================================
        */

        $check = $conn->prepare(
            "SELECT id FROM users WHERE email = ?"
        );

        $check->bind_param("s", $email);

        $check->execute();

        $result = $check->get_result();

        if ($result->num_rows > 0) {

            $error = "Email is already registered.";

        } else {

            /*
            =================================
            HASH PASSWORD
            =================================
            */

            $hashed_password = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            /*
            =================================
            INSERT USER
            =================================
            */

            $stmt = $conn->prepare(
                "INSERT INTO users
                (fullname, age, email, password)
                VALUES (?, ?, ?, ?)"
            );

            $stmt->bind_param(
                "siss",
                $fullname,
                $age,
                $email,
                $hashed_password
            );

            if ($stmt->execute()) {

                header("Location: index.php?error=Account created successfully! Please login.");

                exit();

            } else {

                $error = "Registration failed.";

            }

            $stmt->close();
        }

        $check->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>PNN COMPUTER
 - Sign Up</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

<div class="container">

    <div class="form-box">

        <div class="logo">💻</div>

        <h1>PNN COMPUTER
</h1>

        <p class="subtitle">
            Create Your Account
        </p>

        <?php if ($error != "") { ?>

            <div class="error">

                <?php echo htmlspecialchars($error); ?>

            </div>

        <?php } ?>


        <form action="signup.php" method="POST">

            <label>Full Name</label>

            <input
                type="text"
                name="fullname"
                placeholder="Enter your full name"
                required
            >


            <label>Age</label>

            <input
                type="number"
                name="age"
                placeholder="Enter your age"
                min="1"
                max="100"
                required
            >


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
                placeholder="Enter password"
                required
            >


            <label>Confirm Password</label>

            <input
                type="password"
                name="confirm_password"
                placeholder="Confirm password"
                required
            >


            <button type="submit">
                SIGN UP
            </button>

        </form>


        <p class="switch">

            Already have an account?

            <a href="index.php">
                Login
            </a>

        </p>

    </div>

</div>

</body>
</html>