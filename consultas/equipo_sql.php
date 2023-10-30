<?php

// Conexión a la base de datos
$host = 'localhost';
$database = 'futbol';
$db_username = $_SESSION['username']; // Asegúrate de tener las credenciales correctas
$db_password = $_SESSION['password'];

try {
    $conn = new PDO("pgsql:host=$host;dbname=$database", $db_username, $db_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo '<script>
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "No tienes permisos para acceder a esta página."
            }).then(function() {
                window.location.href = "../menu.php?error=1";
            });
         </script>';
    exit();
}

// Consulta SQL para insertar un equipo
function insertEquipo($nombre, $ciudad, $estadio, $capacidad) {
    global $conn;
    $sql = "INSERT INTO equipos (nombre_equipo, ciudad, estadio, capacidad_estadio) VALUES (:nombre, :ciudad, :estadio, :capacidad)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':ciudad', $ciudad);
    $stmt->bindParam(':estadio', $estadio);
    $stmt->bindParam(':capacidad', $capacidad);

    return $stmt->execute();
}

// Consulta SQL para actualizar un equipo
function updateEquipo($id, $nombre, $ciudad, $estadio, $capacidad) {
    global $conn;
    $sql = "UPDATE equipos SET nombre_equipo = :nombre, ciudad = :ciudad, estadio = :estadio, capacidad_estadio = :capacidad WHERE id_equipo = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':ciudad', $ciudad);
    $stmt->bindParam(':estadio', $estadio);
    $stmt->bindParam(':capacidad', $capacidad);
    $stmt->bindParam(':id', $id);

    return $stmt->execute();
}

// Consulta SQL para eliminar un equipo
function deleteEquipo($id) {
    global $conn;
    $sql = "DELETE FROM equipos WHERE id_equipo = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);

    return $stmt->execute();
}
