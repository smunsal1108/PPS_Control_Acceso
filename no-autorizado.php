<?php
session_start();
if (!isset($_SESSION['nombre'])) {
    header('Location: login.php?aviso=Debe iniciar sesión para continuar');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acceso Denegado</title>
    <style>
        body { font-family: Arial, sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; background-color: #f4f4f4; text-align: center; }
        .container { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        h1 { color: #dc3545; }
        a { color: #007bff; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Acceso No Autorizado</h1>
        <p>Lo sentimos, no tienes los permisos necesarios para acceder a esta página.</p>
        <p><a href="principal.php">Volver al Inicio</a></p>
    </div>
</body>
</html>
