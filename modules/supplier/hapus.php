<?php
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';


$id = $_GET['id'] ?? null;
if (!$id) {
    redirect('index.php');
}

// Cek apakah supplier digunakan di stok_masuk
$stmt = $pdo->prepare("SELECT COUNT(*) FROM stok_masuk WHERE id_supplier = ?");
$stmt->execute([$id]);
$count = $stmt->fetchColumn();

if ($count > 0) {
    $_SESSION['message'] = "Supplier tidak dapat dihapus karena masih digunakan dalam transaksi stok masuk";
    $_SESSION['message_type'] = "danger";
    redirect('index.php');
}

try {
    $stmt = $pdo->prepare("DELETE FROM supplier WHERE id_supplier = ?");
    $stmt->execute([$id]);
    
    $_SESSION['message'] = "Supplier berhasil dihapus";
    $_SESSION['message_type'] = "success";
} catch (PDOException $e) {
    $_SESSION['message'] = "Gagal menghapus supplier: " . $e->getMessage();
    $_SESSION['message_type'] = "danger";
}

redirect('index.php');
?>