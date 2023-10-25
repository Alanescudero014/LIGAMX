<?php
try {
    $pdo = new PDO("pgsql:host=localhost;dbname=futbol", "postgres", "root");
    // Configura el modo de error de PDO para lanzar excepciones en caso de error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Puedes usar la variable $pdo para ejecutar consultas SQL
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
