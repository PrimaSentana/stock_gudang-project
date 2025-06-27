<?php
require_once __DIR__ . '/../config/database.php';


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function alert($message, $type = 'success') {
    return '<div class="alert alert-' . $type . ' alert-dismissible fade show" role="alert">
        ' . $message . '
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}

function getProdukList($pdo) {
    $stmt = $pdo->query("SELECT id_produk, kode_produk, nama_produk FROM produk ORDER BY nama_produk");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getLokasiList($pdo) {
    $stmt = $pdo->query("SELECT id_lokasi, kode_lokasi, nama_lokasi FROM lokasi_gudang ORDER BY nama_lokasi");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getSupplierList($pdo) {
    $stmt = $pdo->query("SELECT id_supplier, nama_supplier FROM supplier ORDER BY nama_supplier");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getPelangganList($pdo) {
    $stmt = $pdo->query("SELECT id_pelanggan, nama_pelanggan FROM pelanggan ORDER BY nama_pelanggan");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function updateStok($pdo, $id_produk, $id_lokasi, $jumlah, $is_masuk = true) {
    // Cek apakah stok sudah ada
    $stmt = $pdo->prepare("SELECT * FROM stok_saat_ini WHERE id_produk = ? AND id_lokasi = ?");
    $stmt->execute([$id_produk, $id_lokasi]);
    $stok = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($stok) {
        // Update stok yang ada
        $new_jumlah = $is_masuk ? $stok['jumlah_stok'] + $jumlah : $stok['jumlah_stok'] - $jumlah;
        $field = $is_masuk ? 'tanggal_terakhir_masuk' : 'tanggal_terakhir_keluar';
        
        $stmt = $pdo->prepare("UPDATE stok_saat_ini SET jumlah_stok = ?, $field = NOW() WHERE id_stok = ?");
        $stmt->execute([$new_jumlah, $stok['id_stok']]);
    } else {
        // Buat record stok baru (hanya untuk stok masuk)
        if ($is_masuk) {
            $stmt = $pdo->prepare("INSERT INTO stok_saat_ini (id_produk, id_lokasi, jumlah_stok, tanggal_terakhir_masuk) VALUES (?, ?, ?, NOW())");
            $stmt->execute([$id_produk, $id_lokasi, $jumlah]);
        }
    }
}

// Fungsi untuk memformat angka dengan separator ribuan
function formatNumber($number) {
    return number_format($number, 0, ',', '.');
}
?>