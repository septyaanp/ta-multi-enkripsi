<?php
$host = 'localhost';
$user = 'username';
$pass = 'password';
$db   = 'database_name';

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>