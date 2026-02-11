<?php
session_start();
// Jika belum login, redirect ke login.php
if (!isset($_SESSION['user_id'])) {
header("Location: login.php");
exit();
}
?>