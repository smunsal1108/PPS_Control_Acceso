<?php
// Iniciar sesión solo si el usuario tiene la cookie, para evitar crear una nueva al hacer logout
if (isset($_COOKIE[session_name()])) {
    session_start();
}

// Solo ejecutar logout si hay una sesión activa
if (isset($_SESSION['nombre'])) {
    session_unset();
    session_destroy();

    // Eliminar la cookie de sesión
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    header('Location: login.php');
} else {
    // Si no hay sesión, guardar aviso en sesión antes de redirigir
    // Iniciar sesión temporalmente si no hay una para guardar el aviso
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['aviso'] = 'Debe iniciar sesión para continuar';
    header('Location: login.php');
}
exit;
