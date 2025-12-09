<?php
require_once "db.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: index.php");
  exit;
}

$auto_id = (int)($_POST['auto_id'] ?? 0);
$tipo = trim($_POST['tipo'] ?? "");
$fecha_vencimiento = $_POST['fecha_vencimiento'] ?: null;
$descripcion = trim($_POST['descripcion'] ?? "");
$errores = [];

if ($auto_id <= 0) $errores[] = "Selecciona un auto.";
if ($tipo === "") $errores[] = "Selecciona el tipo.";
if (!isset($_FILES['archivo']) || $_FILES['archivo']['error'] !== UPLOAD_ERR_OK) {
  $errores[] = "Sube un archivo válido.";
}

$permitidos = [
  'image/jpeg' => '.jpg',
  'image/png'  => '.png',
  'application/pdf' => '.pdf'
];

if (empty($errores)) {
  $tmp = $_FILES['archivo']['tmp_name'];
  $mime = mime_content_type($tmp);
  if (!isset($permitidos[$mime])) {
    $errores[] = "Tipo no permitido (solo JPG, PNG o PDF).";
  } else {
    $ext = $permitidos[$mime];
    $safe = bin2hex(random_bytes(8));
    $destFile = $safe . $ext;

    $dir = __DIR__ . "/uploads";
    if (!is_dir($dir)) { mkdir($dir, 0775, true); }

    $destPath = $dir . "/" . $destFile;
    $publicUrl = "uploads/" . $destFile;

    if (!move_uploaded_file($tmp, $destPath)) {
      $errores[] = "No se pudo guardar el archivo.";
    }
  }
}

if (!empty($errores)) {
  echo "<h3>Errores</h3><ul>";
  foreach ($errores as $e) echo "<li>" . htmlspecialchars($e) . "</li>";
  echo "</ul><p><a href='index.php'>Volver</a></p>";
  exit;
}

$stmt = $mysqli->prepare("
  INSERT INTO documentos_auto (auto_id, tipo_documento, fecha_vencimiento, archivo_url, descripcion)
  VALUES (?, ?, ?, ?, ?)
");
$stmt->bind_param("issss", $auto_id, $tipo, $fecha_vencimiento, $publicUrl, $descripcion);
$stmt->execute();

header("Location: index.php");
exit;
