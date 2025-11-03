<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administracyjny - Konfiguracja</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
</head>

<body>
    <h1>Konfiguracja Systemu Zarządzania Quizami</h1>
    <form method="post">
        <div class="mb-3">
            <label for="admin_username" class="form-label">Nazwa użytkownika administratora</label>
            <input type="text" class="form-control" id="admin_username" name="admin_username" required>
            <hr />
            <label for="admin_password" class="form-label">Hasło administratora</label>
            <input type="password" class="form-control" id="admin_password" name="admin_password" required>
        </div>
        <button type="submit" class="btn btn-primary">Stworz konto administratora</button>

</body>

<?php
if (isset($_POST['admin_username']) && isset($_POST['admin_password'])) {
    require_once '../connectdb.php';
    $c = mysqli_connect($host, $user, $password, $database);

    $stmt = mysqli_prepare($c, "INSERT INTO admins (username, password) VALUES (?, ?)");
    $hashed = password_hash($_POST['admin_password'], PASSWORD_BCRYPT);
    mysqli_stmt_bind_param($stmt, "ss", $_POST['admin_username'], $hashed);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);
    mysqli_close($c);

    header("Location: index.php");
    exit;


}
?>

</html>