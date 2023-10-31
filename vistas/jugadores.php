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

        // Consulta SQL para obtener la lista de equipos
        $sqlEquipos = "SELECT id_equipo, nombre_equipo FROM equipos";
        $stmtEquipos = $conn->query($sqlEquipos);

        if (!$stmtEquipos) {
            echo "Error en la consulta de equipos: " . print_r($conn->errorInfo(), true);
        }

        $equipos = $stmtEquipos->fetchAll(PDO::FETCH_ASSOC);


        // Consulta SQL para seleccionar todos los jugadores
        $sql = "SELECT jugadores.*, equipos.Nombre_equipo AS equipo_nombre FROM jugadores LEFT JOIN equipos ON jugadores.Equipo_actual = equipos.ID_equipo ORDER BY jugadores.id_jugador ASC";
        $stmt = $conn->query($sql);

        // Obtener los datos de la consulta
        $jugadores = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <title>Jugadores</title>
    <!-- Agrega los enlaces a Bootstrap y SweetAlert2 -->
    <link rel="stylesheet" href="../css/bootstrap.css">
    <link rel="stylesheet" href="../css/estilos.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- our project just needs Font Awesome Solid + Brands -->
    <link href="../fontawesome/css/fontawesome.css" rel="stylesheet">
    <link href="../fontawesome/css/brands.css" rel="stylesheet">
    <link href="../fontawesome/css/solid.css" rel="stylesheet">
</head>

<body>
    <header>
        <a href="../menu.php" class="btn btn-outline-dark"><i class="fa-solid fa-arrow-left"></i></a>
    </header>

    <div class="container mt-5">
        <!-- Botón para agregar equipo (abre un modal) -->
        <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#agregarJugadorModal">
            Agregar Jugador
        </button>

        <table class="mi-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre del Jugador</th>
                    <th>Nacionalidad</th>
                    <th>Posición</th>
                    <th>Nació</th>
                    <th>Equipo Actual</th>
                    <th colspan="2">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($jugadores as $jugador) { ?>
                    <tr>
                        <td><?php echo $jugador['id_jugador']; ?></td>
                        <td><?php echo $jugador['nombre_jugador']; ?></td>
                        <td><?php echo $jugador['nacionalidad']; ?></td>
                        <td><?php echo $jugador['posicion']; ?></td>
                        <td><?php echo $jugador['fecha_nacimiento']; ?></td>
                        <td><?php echo $jugador['equipo_nombre']; ?></td>
                        <!-- Modificar el botón para que el data-bs-target apunte a un ID único -->
                        <td><button class="btn btn-info" type="submit" name="actualizarJugador" data-bs-toggle="modal" data-bs-target="#actualizarJugadorModal<?php echo $jugador['id_jugador']; ?>"><i class="fa-solid fa-pencil"></i></button></td>
                        <td><a class="btn btn-danger" href="jugadores.php?eliminarJugador=<?php echo $jugador['id_jugador']; ?>"><i class="fa-solid fa-trash-can"></i></a></td>
                    </tr>

                    <!-- Modal para Actualizar Jugador -->
                    <!-- Modificar el modal para incluir el ID del jugador en su ID -->
                    <div class="modal fade" id="actualizarJugadorModal<?php echo $jugador['id_jugador']; ?>" tabindex="-1" aria-labelledby="actualizarJugadorModalLabel<?php echo $jugador['id_jugador']; ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <!-- Contenido del formulario de actualización aquí -->
                                <form class="mi-formulario" method="POST" action="jugadores.php">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="actualizarJugadorModalLabel">Actualizar Equipo</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <!-- En el formulario de actualización, asigna los valores directamente en los campos de entrada -->
                                    <div class="modal-body">
                                        <input type="hidden" id="jugador-id" name="jugador_id" value="<?php echo $jugador['id_jugador']; ?>">
                                        <label for="nombre">Nombre del jugador</label>
                                        <input type="text" name="nombre" id="nombre" placeholder="Nombre del jugador" value="<?php echo $jugador['nombre_jugador']; ?>" required>

                                        <label for="nacionalidad">Nacionalidad</label>
                                        <input type="text" name="nacionalidad" id="nacionalidad" placeholder="Nacionalidad" value="<?php echo $jugador['nacionalidad']; ?>" required>

                                        <label for="posicion">Posición</label>
                                        <input type="text" name="posicion" id="posicion" placeholder="Posición" value="<?php echo $jugador['posicion']; ?>" required>

                                        <label for="fecha_nacimiento">Fecha de nacimiento</label>
                                        <input type="date" max="2008-01-01" name="fecha_nacimiento" id="fecha_nacimiento" placeholder="Fecha de nacimiento" value="<?php echo $jugador['fecha_nacimiento']; ?>" required>

                                        <!-- En el formulario de actualizar jugador (asegúrate de tener un campo oculto para guardar el ID actual) -->
                                        <input type="hidden" name="jugador_id" value="<?php echo $jugador['id_jugador']; ?>">
                                        <label for="equipo_actual">Equipo Actual</label>
                                        <select name="equipo_actual" required>
                                            <?php foreach ($equipos as $equipo) { ?>
                                                <option value="<?php echo $equipo['id_equipo']; ?>" <?php if ($equipo['id_equipo'] == $jugador['equipo_actual']) echo 'selected'; ?>><?php echo $equipo['nombre_equipo']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="submit" name="actualizarJugador" class="btn btn-primary">Actualizar Jugador</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </tbody>
        </table>
    </div>


    <!-- Modal para Agregar Jugador -->
    <div class="modal fade" id="agregarJugadorModal" tabindex="-1" aria-labelledby="agregarJugadorModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Contenido del formulario de agregar aquí -->
                <form class="mi-formulario" method="POST" action="jugadores.php">
                    <!-- Campos de entrada para los datos del equipo -->
                    <div class="modal-header">
                        <h5 class="modal-title" id="agregarJugadorModalLabel">Agregar Jugador</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <label for="nombre">Nombre del jugador</label>
                        <input type="text" name="nombre" placeholder="Nombre del jugador" required>

                        <label for="nacionalidad">Nacionalidad</label>
                        <input type="text" name="nacionalidad" placeholder="Nacionalidad" required>

                        <label for="posicion">Posición</label>
                        <input type="text" name="posicion" placeholder="Posición" required>

                        <label for="fecha_nacimiento">Fecha de nacimiento</label>
                        <input type="date" max="2008-01-01" id="fecha_nacimiento" name="fecha_nacimiento" placeholder="Fecha de nacimiento" required>

                        <!-- En el formulario de agregar jugador -->
                        <label for="equipo_actual">Equipo Actual</label>
                        <select name="equipo_actual" required>
                            <option value="" disabled selected>Selecciona un equipo</option>
                            <?php foreach ($equipos as $equipo) { ?>
                                <option value="<?php echo $equipo['id_equipo']; ?>"><?php echo $equipo['nombre_equipo']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <!-- Botón para enviar el formulario -->
                        <button type="submit" name="agregarJugador" class="btn btn-primary">Agregar Jugador</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php

    include('../consultas/jugadores_sql.php');

    if (isset($_POST['agregarJugador'])) {
        $nombre = $_POST['nombre'];
        $nacionalidad = $_POST['nacionalidad'];
        $posicion = $_POST['posicion'];
        $fecha_nacimiento = $_POST['fecha_nacimiento'];
        $equipo_actual = $_POST['equipo_actual'];

        $resultado = insertJugador($nombre, $nacionalidad, $posicion, $fecha_nacimiento, $equipo_actual);

        if ($resultado) {
            // Jugador insertado con éxito
            echo '<script>
                Swal.fire({
                    icon: "success",
                    title: "Éxito",
                    text: "Jugador agregado con éxito"
                }).then(function () {
                    recargarPagina();
                });
            </script>';
        } else {
            // Error al insertar el jugador
            echo '<script>
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "No tienes permisos para agregar un jugador."
                });
            </script>';
        }
    }

    // Código para eliminar un jugador
    if (isset($_GET['eliminarJugador'])) {
        $jugadorId = $_GET['eliminarJugador'];
        if (deleteJugador($jugadorId)) {
            // Jugador eliminado con éxito
            echo '<script>
            Swal.fire({
                icon: "success",
                title: "Éxito",
                text: "Jugador eliminado con éxito"
            }).then(function () {
                recargarPagina();
            });
         </script>';
        } else {
            // Error al eliminar el jugador
            echo '<script>
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "No tienes permisos para eliminar un jugador."
            }).then(function () {
                recargarPagina();
            });
         </script>';
        }
    }

    // Código para actualizar un jugador
    if (isset($_POST['actualizarJugador'])) {
        $jugadorId = $_POST['jugador_id']; // ID del jugador a actualizar
        $nombre = $_POST['nombre'];
        $nacionalidad = $_POST['nacionalidad'];
        $posicion = $_POST['posicion'];
        $fechaNacimiento = $_POST['fecha_nacimiento'];
        $equipoActual = $_POST['equipo_actual'];

        if (updateJugador($jugadorId, $nombre, $nacionalidad, $posicion, $fechaNacimiento, $equipoActual)) {
            // Jugador actualizado con éxito
            echo '<script>
            Swal.fire({
                icon: "success",
                title: "Éxito",
                text: "Jugador actualizado con éxito"
            }).then(function () {
                recargarPagina();
            });
        </script>';
        } else {
            // Error al actualizar el jugador
            echo '<script>
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "No tienes permisos para actualizar un jugador."
            });
        </script>';
        }
    }

    ?>

    <script>
        function recargarPagina() {
            window.location.href = 'jugadores.php'; // Ajusta la URL a la página donde se encuentra la tabla de equipos y jugadores
        }
    </script>

</body>


<!-- Agrega el enlace a los scripts de Bootstrap 5 (jQuery y Popper.js) -->
<script src="../js/bootstrap.bundle.min.js"></script>

</html>