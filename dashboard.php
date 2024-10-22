<?php
session_start();
require_once 'inc/functions.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$data = get_data();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h2>Dashboard</h2>
    <a href="logout.php">Logout</a>
    <h3>Data Pengguna</h3>
    <table border="1">
    <tr>
        <th>ID</th>
        <th>Nama</th>
        <th>Email</th>
        <th>Telepon</th>
        <th>Aksi</th>
    </tr>
    <?php while($row = mysqli_fetch_assoc($data)): ?>
    <tr>
        <td><?= $row['id']; ?></td>
        <td><?= $row['name']; ?></td>
        <td><?= $row['email']; ?></td>
        <td><?= blowfish_decrypt($row['phone'], $row['blowfish_key']); ?></td>
        <td>
            <a href="edit_data.php?id=<?= $row['id']; ?>">Edit</a>
            <a href="delete_data.php?id=<?= $row['id']; ?>" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
</body>
</html>
           