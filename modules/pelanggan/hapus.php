<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: /stock_gudang/login_page/login.php"); // sesuaikan path jika perlu
    exit();
}

require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    redirect('index.php');
}

// Cek apakah pelanggan digunakan di stok_keluar
$stmt = $pdo->prepare("SELECT COUNT(*) FROM stok_keluar WHERE id_pelanggan = ?");
$stmt->execute([$id]);
$count = $stmt->fetchColumn();

if ($count > 0) {
    $_SESSION['message'] = "Pelanggan tidak dapat dihapus karena masih digunakan dalam transaksi stok keluar";
    $_SESSION['message_type'] = "danger";
    redirect('index.php');
}

try {
    $stmt = $pdo->prepare("DELETE FROM pelanggan WHERE id_pelanggan = ?");
    $stmt->execute([$id]);
    
    $_SESSION['message'] = "Pelanggan berhasil dihapus";
    $_SESSION['message_type'] = "success";
} catch (PDOException $e) {
    $_SESSION['message'] = "Gagal menghapus pelanggan: " . $e->getMessage();
    $_SESSION['message_type'] = "danger";
}

redirect('index.php');
?>