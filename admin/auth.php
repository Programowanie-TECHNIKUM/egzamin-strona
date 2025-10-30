<?php
require_once '../connectdb.php';
session_start();
$c = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

$stmt = mysqli_prepare($c, "SELECT * FROM admins WHERE username = ? AND password = ?");
mysqli_stmt_bind_param($stmt, "ss", $_POST['username'], $_POST['password']);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($res) > 0) {
    $_SESSION['admin_username'] = $_POST['username'];
    header("Location: dashboard.php");
    exit;
} else {
    echo "Nieprawidłowa nazwa użytkownika lub hasło.";
    header("location: index.php?error=1");
}

mysqli_stmt_close($stmt);
mysqli_close($c);
?>