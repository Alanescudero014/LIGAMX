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

        // Antes del bucle foreach que muestra los partidos, obtén la lista de equipos
        $sqlEquipos = "SELECT * FROM equipos";
        $stmtEquipos = $conn->query($sqlEquipos);
        $equipos = $stmtEquipos->fetchAll(PDO::FETCH_ASSOC);


        // Consulta SQL para seleccionar todos los partidos
        // Consulta SQL para seleccionar todos los partidos con nombres de equipos
        $sql = "SELECT partidos.*, equiposLocal.Nombre_equipo AS equipo_local_nombre, equiposVisitante.Nombre_equipo AS equipo_visitante_nombre FROM partidos 
        LEFT JOIN equipos AS equiposLocal ON partidos.Equipo_local = equiposLocal.ID_equipo
        LEFT JOIN equipos AS equiposVisitante ON partidos.Equipo_visitante = equiposVisitante.ID_equipo
        ORDER BY partidos.id_partido ASC";
        $stmt = $conn->query($sql);

        // Obtener los datos de la consulta
        $partidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        header("Location: ../menu.php?error=1");
        exit();
    }
} else {
    // El usuario no tiene permisos, muestra un mensaje de advertencia
    header("Location: ../menu.php");
    exit();
}

// Asegúrate de definir la función obtenerNombreEquipo antes de usarla en el HTML
function obtenerNombreEquipo($id_equipo)
{
    global $conn;
    $sql = "SELECT nombre_equipo FROM equipos WHERE id_equipo = :id_equipo";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_equipo', $id_equipo);
    $stmt->execute();
    $equipo = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($equipo) {
        return $equipo['nombre_equipo'];
    } else {
        return "Equipo no encontrado";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partidos</title>
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
        <!-- Botón para agregar partido (abre un modal) -->
        <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#agregarPartidoModal">
            Agregar Partido
        </button>

        <table class="mi-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Equipo Local</th>
                    <th>Equipo Visitante</th>
                    <th>Fecha del Partido</th>
                    <th>Resultado</th>
                    <th>Estadio</th>
                    <th colspan="2">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($partidos as $partido) { ?>
                    <tr>
                        <td><?php echo $partido['id_partido']; ?></td>
                        <td><?php echo $partido['equipo_local_nombre']; ?></td>
                        <td><?php echo $partido['equipo_visitante_nombre']; ?></td>
                        <td><?php echo $partido['fecha_partido']; ?></td>
                        <td><?php echo $partido['resultado']; ?></td>
                        <td><?php echo $partido['estadio']; ?></td>
                        <!-- Modificar el botón para que el data-bs-target apunte a un ID único -->
                        <td><button class="btn btn-info" type="submit" name="actualizarPartido" data-bs-toggle="modal" data-bs-target="#actualizarPartidoModal<?php echo $partido['id_partido']; ?>"><i class="fa-solid fa-pencil"></i></button></td>
                        <td><a class="btn btn-danger" href="partidos.php?eliminarPartido=<?php echo $partido['id_partido']; ?>"><i class="fa-solid fa-trash-can"></i></a></td>
                    </tr>


                    <!-- Modal para Actualizar Partido -->
                    <div class="modal fade" id="actualizarPartidoModal<?php echo $partido['id_partido']; ?>" tabindex="-1" aria-labelledby="actualizarPartidoModalLabel<?php echo $partido['id_partido']; ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <!-- Contenido del formulario de actualización aquí -->
                                <form class="mi-formulario" method="POST" action="partidos.php">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="actualizarPartidoModalLabel">Actualizar Partido</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <!-- En el formulario de actualización, asigna los valores directamente en los campos de entrada -->
                                    <div class="modal-body">
                                        <input type="hidden" id="partido-id" name="partido_id" value="<?php echo $partido['id_partido']; ?>">
                                        <label for="equipo_local">Equipo Local</label>
                                        <select name="equipo_local" id="equipo_local" required>
                                            <?php foreach ($equipos as $equipo) { ?>
                                                <option value="<?php echo $equipo['id_equipo']; ?>" <?php if ($equipo['id_equipo'] == $partido['equipo_local']) echo ' selected'; ?>>
                                                    <?php echo $equipo['nombre_equipo']; ?>
                                                </option>
                                            <?php } ?>
                                        </select>

                                        <label for="equipo_visitante">Equipo Visitante</label>
                                        <select name="equipo_visitante" id="equipo_visitante" required>
                                            <?php foreach ($equipos as $equipo) { ?>
                                                <option value="<?php echo $equipo['id_equipo']; ?>" <?php if ($equipo['id_equipo'] == $partido['equipo_visitante']) echo ' selected'; ?>>
                                                    <?php echo $equipo['nombre_equipo']; ?>
                                                </option>
                                            <?php } ?>
                                        </select>

                                        <label for="fecha_partido">Fecha del Partido</label>
                                        <input type="date" name="fecha_partido" id="fecha_partido" value="<?php echo $partido['fecha_partido']; ?>" required>

                                        <label for="resultado">Resultado</label>
                                        <input type="text" name="resultado" id="resultado" placeholder="Ejemplo: 2-1" value="<?php echo $partido['resultado']; ?>" required pattern="\d-\d">

                                        <label for="estadio">Estadio</label>
                                        <input type="text" name="estadio" id="estadio" placeholder="Nombre del estadio" value="<?php echo $partido['estadio']; ?>" required>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="submit" name="actualizarPartido" class="btn btn-primary">Actualizar Partido</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </tbody>
        </table>
    </div>



    <!-- Modal para Agregar Partido -->
    <div class="modal fade" id="agregarPartidoModal" tabindex="-1" aria-labelledby="agregarPartidoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Contenido del formulario de agregar aquí -->
                <form class="mi-formulario" method="POST" action="partidos.php">
                    <div class="modal-header">
                        <h5 class="modal-title" id="agregarPartidoModalLabel">Agregar Partido</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <label for="equipo_local">Equipo Local</label>
                        <select name="equipo_local" id="equipo_local" required>
                            <option value="" disabled selected>Selecciona un equipo</option>
                            <?php foreach ($equipos as $equipo) { ?>
                                <option value="<?php echo $equipo['id_equipo']; ?>" <?php if ($equipo['id_equipo'] == $partido['equipo_local']) echo ' selected'; ?>>
                                    <?php echo $equipo['nombre_equipo']; ?>
                                </option>
                            <?php } ?>
                        </select>

                        <label for="equipo_visitante">Equipo Visitante</label>
                        <select name="equipo_visitante" id="equipo_visitante" required>
                            <option value="" disabled selected>Selecciona un equipo</option>
                            <!-- Agrega las opciones para los equipos aquí -->
                            <?php foreach ($equipos as $equipo) { ?>
                                <option value="<?php echo $equipo['id_equipo']; ?>" <?php if ($equipo['id_equipo'] == $partido['equipo_visitante']) echo ' selected'; ?>>
                                    <?php echo $equipo['nombre_equipo']; ?>
                                </option>
                            <?php } ?>
                        </select>

                        <label for="fecha_partido">Fecha del Partido</label>
                        <input type="date" name="fecha_partido" id="fecha_partido" required>

                        <label for="resultado">Resultado</label>
                        <input type="text" name="resultado" id="resultado" required pattern="\d-\d" placeholder="Ejemplo: 2-1">

                        <label for="estadio">Estadio</label>
                        <input type="text" name="estadio" id="estadio" required placeholder="Nombre del estadio">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="agregarPartido" class="btn btn-primary">Agregar Partido</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <?php

    include('../consultas/partidos_sql.php'); // Asegúrate de incluir el archivo correcto con las funciones de SQL para Partidos

    if (isset($_POST['agregarPartido'])) {
        $equipoLocal = $_POST['equipo_local'];
        $equipoVisitante = $_POST['equipo_visitante'];
        $fechaPartido = $_POST['fecha_partido'];
        $resultado = $_POST['resultado'];
        $estadio = $_POST['estadio'];

        $resultado = insertPartido($equipoLocal, $equipoVisitante, $fechaPartido, $resultado, $estadio);

        if ($resultado) {
            // Partido insertado con éxito
            echo '<script>
        Swal.fire({
            icon: "success",
            title: "Éxito",
            text: "Partido agregado con éxito"
        }).then(function() {
            recargarPagina();
        });
    </script>';
        } else {
            // Error al insertar el partido
            echo '<script>
        Swal.fire({
            icon: "error",
            title: "Error",
            text: "No tienes permisos para agregar un partido."
        });
    </script>';
        }
    }

    // Código para eliminar un partido
    if (isset($_GET['eliminarPartido'])) {
        $partidoId = $_GET['eliminarPartido'];
        if (deletePartido($partidoId)) {
            // Partido eliminado con éxito
            echo '<script>
        Swal.fire({
            icon: "success",
            title: "Éxito",
            text: "Partido eliminado con éxito"
        }).then(function() {
            recargarPagina();
        });
    </script>';
        } else {
            // Error al eliminar el partido
            echo '<script>
        Swal.fire({
            icon: "error",
            title: "Error",
            text: "No tienes permisos para eliminar un partido."
        }).then(function() {
            recargarPagina();
        });
    </script>';
        }
    }

    // Código para actualizar un partido
    if (isset($_POST['actualizarPartido'])) {
        $partidoId = $_POST['partido_id']; // ID del partido a actualizar
        $equipoLocal = $_POST['equipo_local'];
        $equipoVisitante = $_POST['equipo_visitante'];
        $fechaPartido = $_POST['fecha_partido'];
        $resultado = $_POST['resultado'];
        $estadio = $_POST['estadio'];

        if (updatePartido($partidoId, $equipoLocal, $equipoVisitante, $fechaPartido, $resultado, $estadio)) {
            // Partido actualizado con éxito
            echo '<script>
        Swal.fire({
            icon: "success",
            title: "Éxito",
            text: "Partido actualizado con éxito"
        }).then(function() {
            recargarPagina();
        });
    </script>';
        } else {
            // Error al actualizar el partido
            echo '<script>
        Swal.fire({
            icon: "error",
            title: "Error",
            text: "No tienes permisos para actualizar un partido."
        });
    </script>';
        }
    }

    ?>

    <script>
        function recargarPagina() {
            window.location.href = 'partidos.php'; // Ajusta la URL a la página donde se encuentra la tabla de equipos y jugadores
        }
    </script>

</body>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const resultadoInput = document.getElementById('resultado');

        resultadoInput.addEventListener('input', function() {
            const regex = /^\d-\d$/;
            if (!regex.test(resultadoInput.value)) {
                resultadoInput.setCustomValidity('El formato ejemplo, "2-1".');
            } else {
                resultadoInput.setCustomValidity('');
            }
        });
    });
</script>

<script src="../js/bootstrap.bundle.min.js"></script>

</html>