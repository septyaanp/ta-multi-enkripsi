<?php
require_once 'inc/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    if (register($username, $password, $name , $email, $phone)) {
        header('Location: login.php');
        exit;
    } else {
        $error = 'Registrasi gagal';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h2>Register</h2>
    <form method="POST" action="">
    <input type="text" name="name" placeholder="Nama" required>
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="text" name="phone" placeholder="Telepon" required>
    <button type="submit">Daftar</button>
</form>
    <p>Sudah punya akun? <a href="index.php">Login</a></p>
</body>
</html>