<?php
session_start();

// 1. Control de acceso: si no hay sesión, redirigir a login
if (!isset($_SESSION['nombre'])) {
    header('Location: login.php?aviso=Debe iniciar sesión para continuar');
    exit;
}

// 2. Control de rol: solo ROLE_ADMIN puede entrar
if ($_SESSION['rol'] !== 'ROLE_ADMIN') {
    header('Location: no-autorizado.php');
    exit;
}

$nombre = htmlspecialchars($_SESSION['nombre']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 2rem; background-color: #f4f4f4; }
        .container { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .nav { margin-bottom: 2rem; }
        .nav a { margin-right: 1rem; text-decoration: none; color: #007bff; }
        .logout { color: red !important; }
        .admin-box { background-color: #e3f2fd; border-left: 5px solid #007bff; padding: 1rem; margin-top: 1rem; }
    </style>
</head>
<body>
    <div class="container">
        <div class="nav">
            <a href="principal.php">Inicio</a>
            <a href="admin.php">Administración</a>
            <a href="logout.php" class="logout">Cerrar Sesión</a>
        </div>
        <h1>Panel de Administración</h1>
        <div class="admin-box">
            <p>Bienvenido al área restringida, <strong><?php echo $nombre; ?></strong>.</p>
            <p>Solo los usuarios con el rol <code>ROLE_ADMIN</code> pueden ver este contenido.</p>
        </div>
    </div>
</body>
</html>
