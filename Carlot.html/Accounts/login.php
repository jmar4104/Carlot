<?php
require "db.php";

$email = $_POST["email"];
$password = $_POST["password"];

$query = "SELECT * FROM users WHERE email = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    if (password_verify($password, $row["password"])) {
        echo "Login successful! Welcome " . $row["email"];
    } else {
        echo "Invalid password.";
    }
} else {
    echo "No user found with that email.";
}
?>
