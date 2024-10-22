<?php
session_start();
require_once 'inc/functions.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    if (add_data($name, $email, $phone)) {
        header('Location: dashboard.php');
        exit;
    } else {
        echo "Gagal menambah data.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tambah Data</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h2>Tambah Data Pengguna</h2>
    <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>
    <form method="POST" action="">
        <input type="text" name="name" placeholder="Nama" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="phone" placeholder="Telepon" required>
        <button type="submit">Tambah</button>
    </form>
    <a href="dashboard.php">Kembali ke Dashboard</a>
</body>
</html>