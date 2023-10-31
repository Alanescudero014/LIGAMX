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

// Consulta SQL para insertar un jugador
function insertJugador($nombre, $nacionalidad, $posicion, $fechaNacimiento, $equipoActual) {
    global $conn;
    $sql = "INSERT INTO Jugadores (Nombre_jugador, Nacionalidad, Posicion, Fecha_nacimiento, Equipo_actual) VALUES (:nombre, :nacionalidad, :posicion, :fecha_nacimiento, :equipo_actual)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':nacionalidad', $nacionalidad);
    $stmt->bindParam(':posicion', $posicion);
    $stmt->bindParam(':fecha_nacimiento', $fechaNacimiento);
    $stmt->bindParam(':equipo_actual', $equipoActual);

    return $stmt->execute();
}

// Consulta SQL para actualizar un jugador
function updateJugador($id, $nombre, $nacionalidad, $posicion, $fechaNacimiento, $equipoActual) {
    global $conn;
    $sql = "UPDATE Jugadores SET Nombre_jugador = :nombre, Nacionalidad = :nacionalidad, Posicion = :posicion, Fecha_nacimiento = :fecha_nacimiento, Equipo_actual = :equipo_actual WHERE ID_jugador = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':nacionalidad', $nacionalidad);
    $stmt->bindParam(':posicion', $posicion);
    $stmt->bindParam(':fecha_nacimiento', $fechaNacimiento);
    $stmt->bindParam(':equipo_actual', $equipoActual);
    $stmt->bindParam(':id', $id);

    return $stmt->execute();
}

// Consulta SQL para eliminar un jugador
function deleteJugador($id) {
    global $conn;
    $sql = "DELETE FROM jugadores WHERE id_jugador = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);

    return $stmt->execute();
}
