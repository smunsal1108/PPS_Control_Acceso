<?php
require_once 'const.php';

// Iniciar sesión solo si ya existe una cookie para no generar cookies innecesarias a visitantes
// o bien, forzamos iniciarla para poder leer el aviso de la redirección
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Si ya está logueado, redirigir a principal.php
if (isset($_SESSION['nombre'])) {
    header('Location: principal.php');
    exit;
}

$error_msg = "";

// Verificar si hay avisos en la sesión (y borrarlos para que se muestren solo una vez)
if (isset($_SESSION['aviso'])) {
    $error_msg = htmlspecialchars($_SESSION['aviso']);
    unset($_SESSION['aviso']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!empty($nombre) && !empty($password)) {
        try {
            $dsn = "mysql:host=" . HOST . ";dbname=" . DB . ";port=" . PORT;
            $pdo = new PDO($dsn, USER, PASSWORD);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE nombre = :nombre");
            $stmt->execute(['nombre' => $nombre]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                $login_success = false;
                $update_hash = false;

                // 1. Comprobar con password_verify (Bcrypt)
                if (password_verify($password, $user['password'])) {
                    $login_success = true;
                } 
                // 2. Si falla, comprobar con MD5
                else if (md5($password) === $user['password']) {
                    $login_success = true;
                    $update_hash = true;
                }

                if ($login_success) {
                    // Si era MD5, actualizar a Bcrypt (coste 12)
                    if ($update_hash) {
                        $new_hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
                        $update_stmt = $pdo->prepare("UPDATE usuarios SET password = :password WHERE id = :id");
                        $update_stmt->execute(['password' => $new_hash, 'id' => $user['id']]);
                    }

                    // Iniciar sesión si no está iniciada
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }
                    session_regenerate_id(true); // Previene fijación de sesión
                    
                    $_SESSION['nombre'] = $user['nombre'];
                    $_SESSION['rol'] = $user['rol'];

                    header('Location: principal.php');
                    exit;
                } else {
                    $error_msg = "Usuario o contraseña incorrectos";
                }
            } else {
                $error_msg = "Usuario o contraseña incorrectos";
            }
        } catch (PDOException $e) {
            $error_msg = "Error de conexión: " . $e->getMessage();
        }
    } else {
        $error_msg = "Por favor, rellene todos los campos";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        body { font-family: Arial, sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; background-color: #f4f4f4; }
        .login-container { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); width: 300px; }
        .error { color: red; margin-bottom: 1rem; }
        input { width: 100%; padding: 0.5rem; margin-bottom: 1rem; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 0.5rem; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #0056b3; }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Iniciar Sesión</h2>
        <?php if ($error_msg): ?>
            <div class="error"><?php echo $error_msg; ?></div>
        <?php endif; ?>
        <form method="POST">
            <input type="text" name="nombre" placeholder="Nombre de usuario" maxlength="64">
            <input type="password" name="password" placeholder="Contraseña" maxlength="64">
            <button type="submit">Entrar</button>
        </form>
    </div>
</body>
</html>
