<?php
include '../config/database.php';

$username = $_POST['username'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

try {
    // Cek apakah username sudah digunakan
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM admin WHERE username = ?");
    $stmt->execute([$username]);
    $userExists = $stmt->fetchColumn();

    if ($userExists > 0) {
        header("Location: signup.php?pesan=gagal");
    } else {
        // Insert user baru
        $stmt = $pdo->prepare("INSERT INTO admin (username, password) VALUES (?, ?)");
        $success = $stmt->execute([$username, $password]);

        if ($success) {
            header("Location: /stock_gudang/login_page/login.php?pesan=signup_berhasil");
            exit();
        } else {
            echo "Registrasi gagal.";
        }
    }
} catch (PDOException $e) {
    echo "Terjadi kesalahan: " . $e->getMessage();
}
?>