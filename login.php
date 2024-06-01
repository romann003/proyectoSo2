<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include("conexion.php");
    $errores = array();
    $errorsModal = false;
    $email = (isset($_POST['email'])) ? htmlspecialchars($_POST['email']) : null;
    $password = (isset($_POST['password'])) ? $_POST['password'] : null;

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


    if (empty($errores)) {
        try {
            $pdo = new PDO('mysql:host=' . $dbDireccion . ';dbname=' . $database, $dbUsername, $dbPassword);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT * FROM users WHERE email=:email";
            $sentencia = $pdo->prepare($sql);
            $sentencia->execute(['email' => $email]);

            $usuarios = $sentencia->fetchAll(PDO::FETCH_ASSOC);

            $login = false;
            foreach ($usuarios as $usuario) {
                if (password_verify($password, $usuario['password'])) {
                    $_SESSION['usuario_id']=$usuario['id'];
                    $_SESSION['usuario_username']=$usuario['username'];
                    $login = true;
                    // session_start();
                    // $_SESSION['email'] = $email;
                    // $_SESSION['rol'] = $usuario['rol'];
                    // header('Location: dashboard.php');
                    // exit;
                }
            }
            if (!$login) {
                $errores['password'] = "Usuario y/o contraseña incorrectas";
                $errorsModal=true;
            } else {
                header("Location:index.php");
            }
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    } else {
        $errorsModal = true;
    }
}

?>



<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Login</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />

    <!-- Bootstrap CSS v5.2.1 -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
      crossorigin="anonymous"
    />
  </head>

  <body>
    <header>
      <!-- place navbar here -->
    </header>
    <main>
      <div class="container mt-5">
        <div class="row justify-content-center">
          <div class="col-md-6">
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
              <div class="card-header">Login</div>
              <div class="card-body">
                <form action="login.php" method="post">
                  <div class="mb-3">
                    <label for="" class="form-label">Correo electrónico</label>
                    <input
                      type="email"
                      class="form-control"
                      name="email"
                      id="email"
                      aria-describedby="helpId"
                      placeholder=""
                      required
                    />
                  </div>

                  <div class="mb-3">
                    <label for="" class="form-label">Contraseña</label>
                    <input
                      type="password"
                      class="form-control"
                      name="password"
                      id="password"
                      placeholder=""
                      required
                    />
                  </div>

                  <input
                    type="submit"
                    class="btn btn-primary"
                    value="Iniciar Sesion"
                  />
                  <a href="registro.php" class="btn btn-secondary">Registrarme</a>
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
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
      crossorigin="anonymous"
    ></script>

    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
      integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
      crossorigin="anonymous"
    ></script>
  </body>
</html>
