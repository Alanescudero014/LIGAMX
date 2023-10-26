<?php
// Inicia una sesión
session_start();

// Destruye la sesión actual
session_destroy();

// Redirige al usuario a la página de inicio de sesión u otra página, por ejemplo, index.html
header("Location: index.html");
exit();
?>
