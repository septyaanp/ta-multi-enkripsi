<?php
require_once 'inc/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    if (edit_data($id, $name, $email, $phone)) {
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Edit data gagal';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Data</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h2>Edit Data Pengguna</h2>
    <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>
    <form method="POST" action="">
        <input type="text" name="name" value="<?= $user['name']; ?>" required>
        <input type="email" name="email" value="<?= $user['email']; ?>" required>
        <input type="text" name="phone" value="<?= $user['phone']; ?>" required>
        <button type="submit">Simpan Perubahan</button>
    </form>
    <a href="dashboard.php">Kembali ke Dashboard</a>
</body>
</html>