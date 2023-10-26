<?php
// Inicia una sesión
session_start();

// Verifica si el usuario tiene permisos (por ejemplo, un rol o nivel de acceso adecuado)
if (isset($_SESSION['username'])) {
    // Conexión a la base de datos y consulta de datos
    
    $username = $_SESSION['username'];
    $password = $_SESSION['password'];

    $host = 'localhost';
    $database = 'futbol';
    $db_username = $_SESSION['username']; // Usamos el usuario autenticado
    $db_password = $_SESSION['password']; // Asegúrate de guardar la contraseña en la sesión o recuperarla de manera segura

    try {
        $conn = new PDO("pgsql:host=$host;dbname=$database", $db_username, $db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Consulta SQL para seleccionar todos los equipos
        $sql = "SELECT * FROM Equipos";
        $stmt = $conn->query($sql);

        // Obtener los datos de la consulta
        $equipos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        header("Location: ../menu.php?error=1");
        exit();
    }
} else {
    // El usuario no tiene permisos, muestra un mensaje de advertencia
    header("Location: ../menu.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equipos</title>
    <!-- Agrega los enlaces a Bootstrap y SweetAlert2 -->
    <link rel="stylesheet" href="../css/bootstrap.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="container mt-5">
        <!-- Botón para agregar equipo (abre un modal) -->
        <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#agregarEquipoModal">
            Agregar Equipo
        </button>

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre del Equipo</th>
                    <th>Ciudad</th>
                    <th>Estadio</th>
                    <th>Capacidad del Estadio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($equipos as $equipo) { ?>
                    <tr>
                        <td><?php echo $equipo['id_equipo']; ?></td>
                        <td><?php echo $equipo['nombre_equipo']; ?></td>
                        <td><?php echo $equipo['ciudad']; ?></td>
                        <td><?php echo $equipo['estadio']; ?></td>
                        <td><?php echo $equipo['capacidad_estadio']; ?></td>
                        <td>
                            <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#actualizarEquipoModal">Actualizar</button>
                            <button class="btn btn-danger" onclick="eliminarEquipo(<?php echo $equipo['id_equipo']; ?>)">Eliminar</button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Modal para Agregar Equipo -->
    <div class="modal fade" id="agregarEquipoModal" tabindex="-1" aria-labelledby="agregarEquipoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Contenido del formulario de agregar aquí -->
            </div>
        </div>
    </div>

    <!-- Modal para Actualizar Equipo -->
    <div class="modal fade" id="actualizarEquipoModal" tabindex="-1" aria-labelledby="actualizarEquipoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Contenido del formulario de actualización aquí -->
            </div>
        </div>
    </div>

    <!-- Script para manejar el formulario de agregar y notificaciones -->
    <script>
        $(document).ready(function() {
            // Función para mostrar una notificación de SweetAlert2
            function mostrarNotificacion(success, message) {
                Swal.fire({
                    icon: success ? 'success' : 'error',
                    title: success ? 'Éxito' : 'Error',
                    text: message,
                });
            }

            // Función para eliminar un equipo
            function eliminarEquipo(id) {
                // Implementa aquí la lógica para eliminar un equipo y mostrar notificaciones
                // ...
                // Al eliminar exitosamente, muestra una notificación de éxito:
                // mostrarNotificacion(true, "Equipo eliminado con éxito");
                // En caso de error, muestra una notificación de error:
                // mostrarNotificacion(false, "Error al eliminar equipo");
            }


            // Agregar más eventos y lógica según sea necesario
        });
    </script>

<script>
        $(document).ready(function() {
            // Función para mostrar una notificación de SweetAlert2
            function mostrarNotificacion(success, message) {
                Swal.fire({
                    icon: success ? 'success' : 'error',
                    title: success ? 'Éxito' : 'Error',
                    text: message,
                });
            }

            // Verifica si hay un mensaje de error en la sesión y muestra la alerta
            let errorMessage = "<?php echo isset($_SESSION['error_message']) ? $_SESSION['error_message'] : ''; ?>";
            if (errorMessage) {
                mostrarNotificacion(false, errorMessage);
            }

            // Verifica si hay un mensaje de advertencia en la sesión y muestra la alerta
            let warningMessage = "<?php echo isset($_SESSION['warning_message']) ? $_SESSION['warning_message'] : ''; ?>";
            if (warningMessage) {
                mostrarNotificacion(false, warningMessage);
            }
        });
    </script>

    <!-- Agrega el enlace a los scripts de Bootstrap 5 (jQuery y Popper.js) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
