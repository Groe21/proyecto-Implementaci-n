<?php
include_once("../model/validacion.php");
include_once('../model/conexion.php');

// Aquí puedes añadir la lógica para validar el inicio de sesión.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $contrasena = $_POST['contrasena'] ?? '';

    // Validación básica de campos no vacíos
    if (!empty($usuario) && !empty($contrasena)) {
        $conexion = new Connection(); // Asegúrate de que este sea el nombre correcto en tu archivo de conexión

        // Lógica de validación con base de datos
        $query = $conexion->prepare("SELECT * FROM usuarios WHERE usuario = :usuario AND contrasena = :contrasena");
        $query->bindParam(':usuario', $usuario);
        $query->bindParam(':contrasena', $contrasena);
        $query->execute();

        if ($query->rowCount() > 0) {
            // Inicio de sesión exitoso
            header("Location: http://localhost/proyecto-vinculacion/web2/administrativo.php");
            exit;
        } else {
            echo "<div class='alert alert-danger text-center'>Usuario o contraseña incorrectos.</div>";
        }
    } else {
        echo "<div class='alert alert-warning text-center'>Por favor, completa todos los campos.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="icon" href="../img/logo23.ico" type="image/x-icon">

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>

<body class="d-flex justify-content-center align-items-center vh-100" style="background-color: #f0f2f5;">
    <div class="card p-5 shadow-lg" style="border-radius: 10px; width: 100%; max-width: 400px; background: #fff;">
        <div class="text-center mb-4">
            <img src="../img/logo23.png" alt="login-icon" class="mb-3" style="height: 60px;" />
            <h2 style="color: #1877f2; font-weight: bold;">Iniciar Sesión</h2>
        </div>
        <form action="" method="post">
            <div class="mb-3">
                <label for="usuario" class="form-label">Usuario</label>
                <input id="usuario" class="form-control" type="text" placeholder="Introduce tu usuario" name="usuario" required>
            </div>
            <div class="mb-3">
                <label for="contrasena" class="form-label">Contraseña</label>
                <div class="input-group">
                    <input id="contrasena" class="form-control" type="password" placeholder="Introduce tu contraseña" name="contrasena" required>
                    <button class="btn btn-outline-secondary" type="button" onclick="showPassword()">
                        <i class="fas fa-eye-slash" id="togglePasswordIcon"></i>
                    </button>
                </div>
            </div>
            <button class="btn btn-primary w-100 fw-bold" type="submit" name="btningresar">Ingresar</button>
        </form>
        <div class="text-center mt-3">
            <a href="#" class="text-decoration-none text-muted">¿Olvidaste tu contraseña?</a>
        </div>
    </div>
    <script src="../js/contra.js"></script>
    <script>
        function showPassword() {
            const passwordField = document.getElementById('contrasena');
            const toggleIcon = document.getElementById('togglePasswordIcon');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            }
        }
    </script>
</body>

</html>