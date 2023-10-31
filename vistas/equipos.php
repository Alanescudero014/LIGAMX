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
        $sql = "SELECT * FROM equipos ORDER BY id_equipo ASC";
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
    <link rel="stylesheet" href="../css/estilos.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- our project just needs Font Awesome Solid + Brands -->
    <link href="../fontawesome/css/fontawesome.css" rel="stylesheet">
    <link href="../fontawesome/css/brands.css" rel="stylesheet">
    <link href="../fontawesome/css/solid.css" rel="stylesheet">
</head>

<body>
<style>
        * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: sans-serif;
}

body {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    background: linear-gradient(#60FD00, #173D00);
}

.container {
    border: solid 1px rgba(255, 255, 255, 0.2);
}

.spaced {
    margin: 20px;
}


table {
    color: #fff;
    font-size: 14px;
    table-layout: fixed;
    border-collapse: collapse;
}

thead {
    background: rgba(243, 140, 210, 0.4);
}

th {
    padding: 20px 15px ;
    font-weight: 700;
    text-transform: uppercase;
}

td {
    padding: 15px;
    border-bottom: solid 1px rgba(255, 255, 255, 0.2);
}


tbody tr {
    cursor: pointer;
}

tbody tr:hover {
    background: rgba(243, 103, 199, 0.4);
}

input {
 border: 1px solid #ccc;
 border-radius: 4px;
 padding: 10px;
 width: 95%;
 box-sizing: border-box;
 margin-bottom: 6px;
}

.btn {
            font-size: 18px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            position: relative;
            overflow: hidden;
            transition: all 0.2s ease;
        }

        .btn:hover {
            background-color: #45a049;
        }

        .btn:focus {
            outline: none;
        }

        .btn:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.5);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.2s ease;
        }

        .btn:hover:before,
        .btn:focus:before {
            transform: scaleX(1);
        }

        .btn-close {
    border: none;
    font-size: 20px;
    color: #fff;
    background-color: black;
    padding: 3px;
    margin: 3px;
    cursor: pointer;
}

.btn-close:hover {
    background-color: rgba(255, 255, 255, 0.1);
}

.btn-close:focus {
    outline: none;
    box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.5);
}
        
    </style>
    <header>
        <a href="../menu.php" class="btn btn-outline-dark"><i class="fa-solid fa-arrow-left"></i></a>
    </header>
    <div class="container spaced">
        <!-- Botón para agregar equipo (abre un modal) -->
        <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#agregarEquipoModal">
            Agregar Equipo
        </button>

        <table class="mi-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre del Equipo</th>
                    <th>Ciudad</th>
                    <th>Estadio</th>
                    <th>Capacidad del Estadio</th>
                    <th colspan="2">Acciones</th>
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
                        <!-- Modificar el botón para que el data-bs-target apunte a un ID único -->
                        <td><button class="btn btn-info" type="submit" name="actualizarEquipo" data-bs-toggle="modal" data-bs-target="#actualizarEquipoModal<?php echo $equipo['id_equipo']; ?>"><i class="fa-solid fa-pencil"></i></button></td>
                        <td><a class="btn btn-danger" href="equipos.php?eliminar=<?php echo $equipo['id_equipo']; ?>"><i class="fa-solid fa-trash-can"></i></a></td>
                    </tr>

                    <!-- Modal para Actualizar Equipo -->
                    <!-- Modificar el modal para incluir el ID del equipo en su ID -->
                    <div class="modal fade" id="actualizarEquipoModal<?php echo $equipo['id_equipo']; ?>" tabindex="-1" aria-labelledby="actualizarEquipoModalLabel<?php echo $equipo['id_equipo']; ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <!-- Contenido del formulario de actualización aquí -->
                                <form class="mi-formulario" method="POST" action="equipos.php">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="actualizarEquipoModalLabel">Actualizar Equipo</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <!-- En el formulario de actualización, asigna los valores directamente en los campos de entrada -->
                                    <div class="modal-body">
                                        <input type="hidden" id="equipo-id" name="equipo_id" value="<?php echo $equipo['id_equipo']; ?>">
                                        <label for="nombre">Nombre del equipo</label>
                                        <input type="text" name="nombre" id="nombre" placeholder="Nuevo nombre del equipo" value="<?php echo $equipo['nombre_equipo']; ?>" required>

                                        <label for="ciudad">Ciudad</label>
                                        <input type="text" name="ciudad" id="ciudad" placeholder="Nueva ciudad" value="<?php echo $equipo['ciudad']; ?>" required>

                                        <label for="estadio">Estadio</label>
                                        <input type="text" name="estadio" id="estadio" placeholder="Nuevo estadio" value="<?php echo $equipo['estadio']; ?>" required>

                                        <label for="capacidad">Capacidad del estadio</label>
                                        <input type="number" name="capacidad" id="capacidad" placeholder="Nueva capacidad del estadio" value="<?php echo $equipo['capacidad_estadio']; ?>" required>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="submit" name="actualizarEquipo" class="btn btn-primary">Actualizar Equipo</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Modal para Agregar Equipo -->
    <div class="container spaced">
    <div class="modal fade" id="agregarEquipoModal" tabindex="-1" aria-labelledby="agregarEquipoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <br>
                <br>
                <br>
                <!-- Contenido del formulario de agregar aquí -->
                <form class="mi-formulario" method="POST" action="equipos.php">
                    <!-- Campos de entrada para los datos del equipo -->
                    <div class="modal-header">
                        <h5 class="modal-title" id="agregarEquipoModalLabel">Agregar Equipo</h5>
                        <button type="button" class="btn-close btn-lg" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <label for="nombre">Nombre del equipo</label>
                        <input type="text" name="nombre" placeholder="Nombre del equipo" required>

                        <label for="ciudad">Ciudad</label>
                        <input type="text" name="ciudad" placeholder="Ciudad" required>

                        <label for="estadio">Estadio</label>
                        <input type="text" name="estadio" placeholder="Estadio" required>

                        <label for="capacidad">Capacidad del estadio</label>
                        <input type="number" name="capacidad" placeholder="Capacidad del estadio" required>
                    </div>
                    <div class="modal-footer">
                        <!-- Botón para enviar el formulario -->
                        <button type="submit" name="agregarEquipo" class="btn btn-primary">Agregar Equipo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <?php
    include('../consultas/equipo_sql.php');

    if (isset($_POST['agregarEquipo'])) {
        $nombre = $_POST['nombre'];
        $ciudad = $_POST['ciudad'];
        $estadio = $_POST['estadio'];
        $capacidad = $_POST['capacidad'];

        if (insertEquipo($nombre, $ciudad, $estadio, $capacidad)) {
            // Equipo insertado con éxito
            echo '<script>
                Swal.fire({
                    icon: "success",
                    title: "Éxito",
                    text: "Equipo agregado con éxito"
                }).then(function () {
                    recargarPagina(); // Recargar la página después de cerrar el SweetAlert
                });
             </script>';
        } else {
            // Error al insertar el equipo
            echo '<script>
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Hubo un problema al agregar el equipo"
                });
             </script>';
        }
    }


    // Después de la consulta y antes de mostrar la tabla
    if (isset($_GET['eliminar'])) {
        $equipoId = $_GET['eliminar'];
        if (deleteEquipo($equipoId)) {
            // Equipo eliminado con éxito
            echo '<script>
                Swal.fire({
                    icon: "success",
                    title: "Éxito",
                    text: "Equipo eliminado con éxito"
                }).then(function () {
                    recargarPagina(); // Recargar la página después de cerrar el SweetAlert
                });
             </script>';
        } else {
            // Error al eliminar el equipo
            echo '<script>
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Hubo un problema al eliminar el equipo"
                }).then(function () {
                    recargarPagina(); // Recargar la página después de cerrar el SweetAlert
                });
             </script>';
        }
    }

    if (isset($_POST['actualizarEquipo'])) {
        $equipo_id = $_POST['equipo_id']; // ID del equipo a actualizar
        $nombre = $_POST['nombre'];
        $ciudad = $_POST['ciudad'];
        $estadio = $_POST['estadio'];
        $capacidad = $_POST['capacidad'];

        if (updateEquipo($equipo_id, $nombre, $ciudad, $estadio, $capacidad)) {
            // Equipo actualizado con éxito
            echo '<script>
                Swal.fire({
                    icon: "success",
                    title: "Éxito",
                    text: "Equipo actualizado con éxito"
                }).then(function () {
                    recargarPagina(); // Recargar la página después de cerrar el SweetAlert
                });
            </script>';
        } else {
            // Error al actualizar el equipo
            echo '<script>
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Hubo un problema al actualizar el equipo"
                });
            </script>';
        }
    }

    ?>

    <!-- JavaScript para redirigir a la página sin recargar -->
    <script>
        function recargarPagina() {
            window.location.href = 'equipos.php';
        }
    </script>







    <!-- Agrega el enlace a los scripts de Bootstrap 5 (jQuery y Popper.js) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
