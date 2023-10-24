<?php
$host = "localhost"; // Reemplaza con el nombre del host de tu base de datos PostgreSQL
$port = "5432"; // Puerto predeterminado de PostgreSQL
$dbname = "futbol"; // Nombre de la base de datos
$user = "postgres"; // Nombre de usuario de PostgreSQL
$password = "root"; // Contraseña de PostgreSQL

// Intenta establecer la conexión
$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

// Verifica si la conexión fue exitosa
if (!$conn) {
    die("Error al conectar a la base de datos: " . pg_last_error());
}else{
    echo("Conectado a la BD");
}


// Cierra la conexión cuando hayas terminado
pg_close($conn);
?>
