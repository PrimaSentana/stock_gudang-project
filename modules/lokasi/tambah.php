<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: /stock_gudang/login_page/login.php"); // sesuaikan path jika perlu
    exit();
}

require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode_lokasi = $_POST['kode_lokasi'];
    $nama_lokasi = $_POST['nama_lokasi'];
    $kapasitas = $_POST['kapasitas'] ?: 0;
    $deskripsi = $_POST['deskripsi'];

    try {
        $stmt = $pdo->prepare("INSERT INTO lokasi_gudang (kode_lokasi, nama_lokasi, kapasitas, deskripsi) VALUES (?, ?, ?, ?)");
        $stmt->execute([$kode_lokasi, $nama_lokasi, $kapasitas, $deskripsi]);
        
        $_SESSION['message'] = "Lokasi gudang berhasil ditambahkan";
        $_SESSION['message_type'] = "success";
        redirect('index.php');
    } catch (PDOException $e) {
        $error = "Gagal menambahkan lokasi gudang: " . $e->getMessage();
    }
}
?>

<div class="card">
    <div class="card-header">
        <h4>Tambah Lokasi Gudang</h4>
    </div>
    <div class="card-body">
        <?php if (isset($error)): ?>
            <?= alert($error, 'danger') ?>
        <?php endif; ?>
        
        <form method="POST">
            <div class="mb-3">
                <label for="kode_lokasi" class="form-label">Kode Lokasi</label>
                <input type="text" class="form-control" id="kode_lokasi" name="kode_lokasi" required>
            </div>
            <div class="mb-3">
                <label for="nama_lokasi" class="form-label">Nama Lokasi</label>
                <input type="text" class="form-control" id="nama_lokasi" name="nama_lokasi" required>
            </div>
            <div class="mb-3">
                <label for="kapasitas" class="form-label">Kapasitas</label>
                <input type="number" class="form-control" id="kapasitas" name="kapasitas" value="0">
            </div>
            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
