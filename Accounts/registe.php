<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "db.php";

if(isset($_POST["email"], $_POST["password"])) {

    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    // Check if user exists
    $check = "SELECT * FROM users WHERE email=?";
    $stmt = mysqli_prepare($conn, $check);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if(mysqli_num_rows($result) > 0){
        echo "Email already registered.";
        exit;
    }

    // Insert new user
    $query = "INSERT INTO users (email, password) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ss", $email, $password);

    if(mysqli_stmt_execute($stmt)){
        echo "Account created successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }

} else {
    echo "Email or password not set.";
}
?>
