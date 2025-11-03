<?php
    require_once '../connectdb.php';

    $c = mysqli_connect($host, $user, $password, $database);
    $q = mysqli_query($c, "SELECT * FROM admins");
    if(mysqli_num_rows($q) == 0){
        header("Location: setup.php");
        exit;
    }

?>


<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administracyjny - Logowanie</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
</head>

<body class="bg-primary">
    <div class="container-fluid vh-100 d-flex align-items-center justify-content-center">
        <div class="row w-100 justify-content-center">
            <div class="col-lg-4 col-md-6 col-sm-8">
                <div class="card shadow">
                    <div class="card-header bg-dark text-white text-center py-4">
                        <h2 class="mb-0">Panel Administracyjny</h2>
                        <p class="mb-0">System Zarządzania Quizami</p>
                    </div>
                    <div class="card-body p-4">
                        <form action="auth.php" method="post">
                            <div class="mb-3">
                                <label for="username" class="form-label fw-bold">Nazwa użytkownika</label>
                                <input type="text" class="form-control form-control-lg" id="username" name="username"
                                    placeholder="Wprowadź nazwę użytkownika" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label fw-bold">Hasło</label>
                                <input type="password" class="form-control form-control-lg" id="password"
                                    name="password" placeholder="Wprowadź hasło" required>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-success btn-lg">
                                    Zaloguj się
                                </button>
                            </div>

                            <div class="d-grid">
                                <?php
                                    if(isset($_GET['error']) && $_GET['error'] == 1) {
                                        echo '<div class="alert alert-danger mt-3" role="alert">';
                                        echo 'Nieprawidłowa nazwa użytkownika lub hasło.';
                                        echo '</div>';
                                    }
                                ?>
                            </div>
                        </form>

                        <div class="text-center mt-3">
                            <a href="../index.php" class="text-muted text-decoration-none">
                                Powrót do strony głównej
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>