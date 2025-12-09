<?php
// Conexión a MySQL con XAMPP
$mysqli = new mysqli("localhost", "root", "", "autosdb");

// Verificar conexión
if ($mysqli->connect_errno) {
    die("Error de conexión: " . $mysqli->connect_error);
}
?>

