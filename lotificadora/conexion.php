<?php
// Conexion con la base de datos de phpMyAdmin.
try {
    $pdo = new PDO(
        "mysql:host=127.0.0.1;port=3307;dbname=lotificadora;charset=utf8mb4",
        "lotes_web",
        "Lotes2026_Clave!"
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
    die("No se pudo conectar. Revisa el usuario, la clave y el puerto de MySQL.");
}
?>
