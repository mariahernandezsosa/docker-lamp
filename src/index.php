<?php
// El nombre del host coincide con el nombre del servicio en docker-compose ('db')
$host = 'db';
$dbname = 'practica1';
$user = 'maria';
$pass = 'medac26';

$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

echo "<h1>Entorno Docker funcionando</h1>";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    echo "<p><strong>Conexión correcta a la base de datos MySQL con PDO.</strong></p>";

} catch (PDOException $e) {
    echo "<p><strong>Error al conectar a la base de datos:</strong> "
       . htmlspecialchars($e->getMessage())
       . "</p>";
}