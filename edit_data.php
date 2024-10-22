<?php
session_start();
require_once 'inc/config.php';
require_once 'inc/functions.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    if (edit_data($id, $name, $email, $phone)) {
        header('Location: dashboard.php');
        exit;
    } else {
        echo "Gagal mengedit data.";
    }
}

// Ambil data pengguna untuk ditampilkan
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

// Dekripsi nomor telepon untuk ditampilkan
$decrypted_phone = blowfish_decrypt($user['phone'], $user['public_key']);
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