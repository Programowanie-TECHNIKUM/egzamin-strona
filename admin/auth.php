<?php
require_once '../connectdb.php';
session_start();
$c = mysqli_connect($host, $user, $password, $database);

// Pobieramy dane użytkownika z bazy danych
$stmt = mysqli_prepare($c, "SELECT * FROM admins WHERE username = ?");
mysqli_stmt_bind_param($stmt, "s", $_POST['username']);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($res) > 0) {
    $admin = mysqli_fetch_assoc($res);
    
    if (password_verify($_POST['password'], $admin['password'])) {
        $_SESSION['admin_username'] = $_POST['username'];
        header("Location: dashboard.php");
        exit;
    } else {
        echo "Nieprawidłowa nazwa użytkownika lub hasło.";
        header("location: index.php?error=1");
    }
} else {
    echo "Nieprawidłowa nazwa użytkownika lub hasło.";
    header("location: index.php?error=1");
}

mysqli_stmt_close($stmt);
mysqli_close($c);
?>