<?php
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    redirect('index.php');
}

// Cek apakah produk digunakan di stok_saat_ini
$stmt = $pdo->prepare("SELECT COUNT(*) FROM stok_saat_ini WHERE id_produk = ?");
$stmt->execute([$id]);
$count = $stmt->fetchColumn();

if ($count > 0) {
    $_SESSION['message'] = "Produk tidak dapat dihapus karena masih digunakan dalam stok";
    $_SESSION['message_type'] = "danger";
    redirect('index.php');
}

try {
    $stmt = $pdo->prepare("DELETE FROM produk WHERE id_produk = ?");
    $stmt->execute([$id]);
    
    $_SESSION['message'] = "Produk berhasil dihapus";
    $_SESSION['message_type'] = "success";
} catch (PDOException $e) {
    $_SESSION['message'] = "Gagal menghapus produk: " . $e->getMessage();
    $_SESSION['message_type'] = "danger";
}

redirect('index.php');
?>