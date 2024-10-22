<?php
require_once 'inc/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];

    if (delete_data($id)) {
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Hapus data gagal';
    }
}
?>