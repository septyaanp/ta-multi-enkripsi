<?php
session_start();
require_once 'includes/functions.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$id = $_GET['id'];

if (delete_data($id)) {
    header('Location: dashboard.php');
    exit;
} else {
    echo "Gagal menghapus data.";
}
?>