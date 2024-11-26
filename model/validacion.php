<?php
include_once("../model/validacion.php");
include_once('../model/conexion.php');

function mostrarAlerta($title, $text, $icon)
{
    echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
    echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                icon: "' . $icon . '",
                title: "' . $title . '",
                text: "' . $text . '",
            });
        });
    </script>';
}

// Aquí puedes añadir la lógica para validar el inicio de sesión.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $contrasena = $_POST['contrasena'] ?? '';

    // Validación básica de campos no vacíos
    if (!empty($usuario) && !empty($contrasena)) {
        // Lógica de validación con base de datos
        $query = $conexion->prepare("SELECT * FROM usuarios WHERE usuario = :usuario AND contrasena = :contrasena");
        $query->bindParam(':usuario', $usuario);
        $query->bindParam(':contrasena', $contrasena);
        $query->execute();

        if ($query->rowCount() > 0) {
            // Inicio de sesión exitoso
            header("Location: ../web2/administrativo.php");
            exit;
        } else {
            mostrarAlerta('Error', 'Usuario o contraseña incorrectos.', 'error');
        }
    } else {
        mostrarAlerta('Advertencia', 'Por favor, completa todos los campos.', 'warning');
    }
}
?>
