<?php
session_start();

// Control de acceso: si no hay sesión, redirigir a login
if (!isset($_SESSION['nombre'])) {
    $_SESSION['aviso'] = 'Debe iniciar sesión para continuar';
    header('Location: login.php');
    exit;
}

$nombre = htmlspecialchars($_SESSION['nombre']);
$rol = htmlspecialchars($_SESSION['rol']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Página Principal</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 2rem; background-color: #f4f4f4; }
        .container { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .nav { margin-bottom: 2rem; }
        .nav a { margin-right: 1rem; text-decoration: none; color: #007bff; }
        .logout { color: red !important; }
    </style>
</head>
<body>
    <div class="container">
        <div class="nav">
            <a href="principal.php">Inicio</a>
            <?php if ($rol === 'ROLE_ADMIN'): ?>
                <a href="admin.php">Administración</a>
            <?php endif; ?>
            <a href="logout.php" class="logout">Cerrar Sesión</a>
        </div>
        <h1>Bienvenido, <?php echo $nombre; ?></h1>
        <p>Tu rol actual es: <strong><?php echo $rol; ?></strong></p>
        <p>Esta es la página principal de la aplicación segura.</p>
    </div>
</body>
</html>
