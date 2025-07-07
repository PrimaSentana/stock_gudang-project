<?php
session_start();
include 'config/database.php';

$username = $_POST['username'];
$password = $_POST['password'];

$data = mysqli_query($koneksi, "SELECT * FROM admin WHERE username='$username'");
$user = mysqli_fetch_assoc($data);

if($user && password_verify($password, $user['password'])){
    $_SESSION['username'] = $username;
    $_SESSION['status'] = "login";
    header("location: index.php");
} else {
    header("location: login.php?pesan=gagal");
}
?>
