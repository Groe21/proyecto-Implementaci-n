<?php

if (!isset($_SESSION['id']) || empty($_SESSION['nombre']) || empty($_SESSION['rol'])) {
    header("Location: login.php");
    exit();
}

?>

<head>
    <div class="right">
        <div class="top">
            <button id="menu-btn">
                <span class="material-icons-sharp">menu</span>
            </button>
            <div class="theme-toggler">
                <span class="material-icons-sharp active">light_mode</span>
                <span class="material-icons-sharp">dark_mode</span>
            </div>
            <div class="profile">
                <div class="info">
                    <p>Hola,<b>
                            <?php echo $_SESSION["nombre"]; ?>
                        </b></p>
                    <small class="text-muted">
                        <?php echo $_SESSION["rol"]; ?>
                    </small>
                </div>
                <div class="profile-photo">
                    <img src="../img/usuario.png" alt="">
                </div>
            </div>
        </div>

    </div>
</head>