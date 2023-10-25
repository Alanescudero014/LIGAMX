<?php
// Recuperar datos del formulario
$username = $_POST['username'];
$password = $_POST['password'];

// Usar las mismas credenciales para la conexión a la base de datos
$host = 'localhost';
$database = 'futbol';
$db_username = $username;
$db_password = $password;

try {
    $conn = new PDO("pgsql:host=$host;dbname=$database", $db_username, $db_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Aquí puedes realizar una consulta SQL para verificar las credenciales
    // Si las credenciales son correctas, configura $success y $message apropiadamente

    $success = true;
    $message = "Inicio de sesión exitoso";
} catch (PDOException $e) {
    $success = false;
    $message = "Error en la conexión a la base de datos: ";
}

if ($success) {
    echo $message;
} else {
    echo $message;
}
