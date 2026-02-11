<?php
session_start();

// Pastikan parameter id produk dikirim
if (!isset($_GET['id'])) {
    die("Error: ID produk tidak diberikan.");
}

$id = $_GET['id'];

// Hapus dari session keranjang
if (isset($_SESSION['keranjang'][$id])) {
    unset($_SESSION['keranjang'][$id]);
}

// Redirect kembali ke halaman keranjang
header("Location: keranjang.php");
exit;
?>
