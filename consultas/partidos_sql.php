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

// Consulta SQL para insertar un partido
function insertPartido($equipoLocal, $equipoVisitante, $fechaPartido, $resultado, $estadio) {
    global $conn;
    $sql = "INSERT INTO Partidos (Equipo_local, Equipo_visitante, Fecha_partido, Resultado, Estadio) VALUES (:equipo_local, :equipo_visitante, :fecha_partido, :resultado, :estadio)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':equipo_local', $equipoLocal);
    $stmt->bindParam(':equipo_visitante', $equipoVisitante);
    $stmt->bindParam(':fecha_partido', $fechaPartido);
    $stmt->bindParam(':resultado', $resultado);
    $stmt->bindParam(':estadio', $estadio);

    try {
        $stmt->execute();
        return true; // Operación exitosa
    } catch (PDOException $e) {
        return false; // Error en la operación
    }
}

// Consulta SQL para actualizar un partido
function updatePartido($id, $equipoLocal, $equipoVisitante, $fechaPartido, $resultado, $estadio) {
    global $conn;
    $sql = "UPDATE Partidos SET Equipo_local = :equipo_local, Equipo_visitante = :equipo_visitante, Fecha_partido = :fecha_partido, Resultado = :resultado, Estadio = :estadio WHERE ID_partido = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':equipo_local', $equipoLocal);
    $stmt->bindParam(':equipo_visitante', $equipoVisitante);
    $stmt->bindParam(':fecha_partido', $fechaPartido);
    $stmt->bindParam(':resultado', $resultado);
    $stmt->bindParam(':estadio', $estadio);
    $stmt->bindParam(':id', $id);

    try {
        $stmt->execute();
        return true; // Operación exitosa
    } catch (PDOException $e) {
        return false; // Error en la operación
    }
}

// Consulta SQL para eliminar un partido
function deletePartido($id) {
    global $conn;
    $sql = "DELETE FROM Partidos WHERE ID_partido = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);

    try {
        $stmt->execute();
        return true; // Operación exitosa
    } catch (PDOException $e) {
        return false; // Error en la operación
    }
}
