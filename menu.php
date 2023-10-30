<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LIGA MX</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/custom.css"> <!-- Agrega tu archivo de estilos personalizados si es necesario -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<style>
    body {
    background-image: url('img/futbol.jpg');
    background-size: cover;
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-position: 100%;
}

</style>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">LIGA MX</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="vistas/equipos.php">Equipos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="vistas/jugadores.php">Jugadores</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="vistas/entrenadores.php">Entrenadores</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="vistas/clasificacion.php">Clasificación</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="vistas/partidos.php">Partidos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="vistas/temporadas.php">Temporadas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="salir.php">Salir</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <h1 class="text-center" style="color: white;">BIENVENIDO A LA LIGA MX</h1>
    </div>

    <script>
    // Verificar si hay un parámetro "error" en la URL y mostrar el mensaje de error
    const urlParams = new URLSearchParams(window.location.search);
    const errorParam = urlParams.get("error");

    if (errorParam === "1") {
        Swal.fire({
            icon: "error",
            title: "Error",
            text: "No tienes permisos para acceder a esta página."
        });
    }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
