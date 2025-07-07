<?php
include 'config/database.php';

$username = $_POST['username'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

$cek = mysqli_query($koneksi, "SELECT * FROM admin WHERE username='$username'");
if(mysqli_num_rows($cek) > 0){
    echo "Username sudah digunakan! <a href='signup.php'>Kembali</a>";
} else {
    $query = mysqli_query($koneksi, "INSERT INTO admin (username, password) VALUES ('$username', '$password')");
    if($query){
        header("Location: login.php?pesan=signup_berhasil");
    } else {
        echo "Registrasi gagal.";
    }
}
?>