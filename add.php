<?php
include 'db_connect.php';
include 'encryption.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $plaintext = $_POST['plaintext'];
    $ciphertext = blowfish_encrypt($plaintext, $key);

    $sql = "INSERT INTO encrypted_data (plaintext, ciphertext) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $plaintext, $ciphertext);
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data</title>
</head>
<body>
    <h1