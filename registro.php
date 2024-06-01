<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include("conexion.php");
    $errores = array();
    $success = false;
    $errorsModal = false;
    
    $username = (isset($_POST['username'])) ? $_POST['username'] : null;
    $email = (isset($_POST['email'])) ? $_POST['email']  : null;
    $password = (isset($_POST['password'])) ? $_POST['password'] : null;
    $cPassword = (isset($_POST['cPassword'])) ? $_POST['cPassword']  : null;

    if (empty($username)) {
        $errores['username'] = "El campo de usuario es obligatorio";
    }

    if (empty($email)) {
        $errores['email'] = "El campo de email es obligatorio";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores['email'] = "El email no es válido";
    }

    if (empty($password)) {
        $errores['password'] = "El campo de contraseña es obligatoria";
    } else if (strlen($password) < 6) {
        $errores['password'] = "La contraseña debe tener al menos 6 caracteres";
    } else if (strlen($password) > 16) {
        $errores['password'] = "La contraseña no debe tener más de 16 caracteres";
    }

    if (empty($cPassword)) {
        $errores['cPassword'] = "El campo de confirmar contraseña es obligatorio";
    } else if ($password != $cPassword) {
        $errores['cPassword'] = "Las contraseñas no coinciden";
    }




    try {
        $pdo = new PDO('mysql:host=' . $dbDireccion . ';dbname=' . $database, $dbUsername, $dbPassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        // $sql1 = "SELECT email FROM users";
        // $result = $pdo->query($sql1);

        // if ($result->rowCount() > 0) {
            // while ($row = $result->fetch()) {
            //     if ($row['email'] == $email) {
            //         $errores['email'] = "El email ya está en uso";
            //     }
            // }
            //     $errorsModal = true;
        // } else {
            if (empty($errores)) {
                $nuevoPassword = password_hash($password, PASSWORD_DEFAULT);

                $sql = "INSERT INTO `users` (`id`, `username`, `email`, `password`) VALUES (NULL, :username, :email, :password);";

                $resultado = $pdo->prepare($sql);
                $resultado->execute(array(
                    ':username' => $username,
                    ':email' => $email,
                    ':password' => $nuevoPassword
                ));
                $success = true;
            } else {
                $success = false;
                $errorsModal = true;
                // header('Location: ' . $_SERVER['PHP_SELF']);
            }
        // }
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Registro</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
</head>

<body>
    <header>
        <!-- place navbar here -->
    </header>
    <main>
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-6">

                    <?php if ($success === true) { ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

                            <strong>Te has registrado con exito!</strong> Para acceder al programa debes <a href="login.html" class="btn btn-success">Iniciar Sesion</a>
                        </div>
                    <?php } ?>

                    <?php if ($errorsModal === true) { ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

                            <?php
                            foreach ($errores as $error) {
                                echo "<p style='color:red'>" . $error . "</p>";
                            ?>
                            <?php
                            }

                            ?>
                        </div>
                    <?php } ?>


                    <div class="card">
                        <div class="card-header">Registro</div>
                        <div class="card-body">

                            <!-- novalidate -->
                            <form action="registro.php" method="post" id="formularioderegistro">
                                <div class="mb-3">
                                    <label for="" class="form-label">Nombre de Usuario</label>
                                    <input type="text" class="form-control" name="username" id="username" aria-describedby="helpId" placeholder="" required />
                                    <div class="invalid-feedback">
                                        <?php echo isset($errores['username']) ? $errores['username'] : '' ?>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="" class="form-label">Correo electrónico</label>
                                    <input type="email" class="form-control" name="email" id="email" aria-describedby="helpId" placeholder="" required />
                                    <div class="invalid-feedback">
                                        <?php echo isset($errores['email']) ? $errores['email'] : '' ?>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="" class="form-label">Contraseña</label>
                                    <input type="password" class="form-control" name="password" id="password" placeholder="" required />
                                    <div class="invalid-feedback">
                                        <?php echo isset($errores['password']) ? $errores['password'] : '' ?>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="" class="form-label">Confirmar contraseña</label>
                                    <input type="password" class="form-control" name="cPassword" id="cPassword" placeholder="" required />
                                    <div class="invalid-feedback">Las contraseñas no coinciden</div>
                                    <div class="invalid-feedback">
                                        <?php echo isset($errores['cPassword']) ? $errores['cPassword'] : '' ?>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <input type="submit" class="btn btn-primary" value="Registrarme" />
                                    <a href="login.php" class="btn btn-secondary">Iniciar Sesion</a>
                                </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </main>
    <footer>
        <!-- place footer here -->
    </footer>
    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>

    <script src="validacion.js"></script>
</body>

</html>