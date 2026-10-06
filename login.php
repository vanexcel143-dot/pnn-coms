<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] != "POST") {

    header("Location: index.php");

    exit();

}

$email = trim($_POST["email"]);
$password = $_POST["password"];


/*
=================================
REQUIRED FIELD VALIDATION
=================================
*/

if (empty($email) || empty($password)) {

    header("Location: index.php?error=All fields are required.");

    exit();

}


/*
=================================
EMAIL VALIDATION
=================================
*/

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    header("Location: index.php?error=Invalid email address.");

    exit();

}


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

    die("Database connection failed.");

}


/*
=================================
FIND USER
=================================
*/

$stmt = $conn->prepare(
    "SELECT id, fullname, email, password
     FROM users
     WHERE email = ?"
);

$stmt->bind_param("s", $email);

$stmt->execute();

$result = $stmt->get_result();


if ($result->num_rows == 1) {

    $user = $result->fetch_assoc();


    /*
    =================================
    VERIFY PASSWORD
    =================================
    */

    if (password_verify($password, $user["password"])) {

        $_SESSION["user_id"] = $user["id"];

        $_SESSION["fullname"] = $user["fullname"];

        $_SESSION["email"] = $user["email"];


        header("Location: dashboard.php");

        exit();

    } else {

        header(
            "Location: index.php?error=Incorrect password."
        );

        exit();

    }

} else {

    header(
        "Location: index.php?error=Account not found."
    );

    exit();

}


$stmt->close();

$conn->close();

?>