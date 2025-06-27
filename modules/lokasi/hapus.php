<?php
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    redirect('index.php');
}

// Cek apakah lokasi digunakan di stok_saat_ini
$stmt = $pdo->prepare("SELECT COUNT(*) FROM stok_saat_ini WHERE id_lokasi = ?");
$stmt->execute([$id]);
$count = $stmt->fetchColumn();

if ($count > 0) {
    $_SESSION['message'] = "Lokasi tidak dapat dihapus karena masih digunakan dalam stok";
    $_SESSION['message_type'] = "danger";
    redirect('index.php');
}

try {
    $stmt = $pdo->prepare("DELETE FROM lokasi_gudang WHERE id_lokasi = ?");
    $stmt->execute([$id]);
    
    $_SESSION['message'] = "Lokasi gudang berhasil dihapus";
    $_SESSION['message_type'] = "success";
} catch (PDOException $e) {
    $_SESSION['message'] = "Gagal menghapus lokasi gudang: " . $e->getMessage();
    $_SESSION['message_type'] = "danger";
}

redirect('index.php');
?>