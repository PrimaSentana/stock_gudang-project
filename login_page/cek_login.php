<?php
session_start();
include '../config/database.php'; // Pastikan path benar sesuai folder kamu

$username = $_POST['username'];
$password = $_POST['password'];

$stmt = $pdo->prepare("SELECT * FROM admin WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['username'] = $username;
    $_SESSION['status'] = "login";
    header("Location: ../index.php"); // sesuaikan path jika perlu
} else {
    header("Location: login.php?pesan=gagal");
}
?>