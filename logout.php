<?php
session_start();

// Solo ejecutar logout si hay una sesión activa
if (isset($_SESSION['nombre'])) {
    session_unset();
    session_destroy();
    header('Location: login.php');
} else {
    // Si no hay sesión, redirigir con aviso
    header('Location: login.php?aviso=Debe iniciar sesión para continuar');
}
exit;
