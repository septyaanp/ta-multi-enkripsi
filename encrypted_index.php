<?php
include 'db_connect.php';
include 'encryption.php';

$sql = "SELECT * FROM encrypted_data";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encrypted Database</title>
</head>
<body>
    <h1>Encrypted Database</h1>
    <a href="add.php">Tambah Data</a>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Plaintext</th>
            <th>Ciphertext</th>
            <th>Aksi</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['plaintext']; ?></td>
            <td><?php echo bin2hex($row['ciphertext']); ?></td>
            <td>
                <a href="edit.php?id=<?php echo $row['id']; ?>">Edit</a> |
                <a href="delete.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>