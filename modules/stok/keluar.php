<?php
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../config/database.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_produk = $_POST['id_produk'];
    $id_lokasi = $_POST['id_lokasi'];
    $jumlah_keluar = $_POST['jumlah_keluar'];
    $id_pelanggan = $_POST['id_pelanggan'] ?: null;
    $tipe_keluar = $_POST['tipe_keluar'];
    $nomor_referensi = $_POST['nomor_referensi'];
    $keterangan = $_POST['keterangan'];

    try {
        $pdo->beginTransaction();
        
        // Cek stok tersedia
        $stmt = $pdo->prepare("SELECT jumlah_stok FROM stok_saat_ini WHERE id_produk = ? AND id_lokasi = ?");
        $stmt->execute([$id_produk, $id_lokasi]);
        $stok = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$stok || $stok['jumlah_stok'] < $jumlah_keluar) {
            throw new Exception("Stok tidak mencukupi untuk transaksi keluar");
        }
        
        // Insert ke tabel stok_keluar
        $stmt = $pdo->prepare("INSERT INTO stok_keluar (id_produk, id_lokasi, jumlah_keluar, id_pelanggan, tipe_keluar, nomor_referensi, keterangan) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$id_produk, $id_lokasi, $jumlah_keluar, $id_pelanggan, $tipe_keluar, $nomor_referensi, $keterangan]);
        
        // Update stok_saat_ini
        updateStok($pdo, $id_produk, $id_lokasi, $jumlah_keluar, false);
        
        $pdo->commit();
        
        $_SESSION['message'] = "Stok keluar berhasil dicatat";
        $_SESSION['message_type'] = "success";
        redirect('keluar.php');
    } catch (Exception $e) {
        $pdo->rollBack();
        $error = "Gagal mencatat stok keluar: " . $e->getMessage();
    }
}

$produkList = getProdukList($pdo);
$lokasiList = getLokasiList($pdo);
$pelangganList = getPelangganList($pdo);
?>

<div class="card">
    <div class="card-header">
        <h4>Stok Keluar</h4>
    </div>
    <div class="card-body">
        <?php if (isset($error)): ?>
            <?= alert($error, 'danger') ?>
        <?php endif; ?>
        
        <form method="POST">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="id_produk" class="form-label">Produk</label>
                    <select class="form-select" id="id_produk" name="id_produk" required>
                        <option value="">Pilih Produk</option>
                        <?php foreach ($produkList as $produk): ?>
                            <option value="<?= $produk['id_produk'] ?>"><?= htmlspecialchars($produk['nama_produk']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="id_lokasi" class="form-label">Lokasi Gudang</label>
                    <select class="form-select" id="id_lokasi" name="id_lokasi" required>
                        <option value="">Pilih Lokasi</option>
                        <?php foreach ($lokasiList as $lokasi): ?>
                            <option value="<?= $lokasi['id_lokasi'] ?>"><?= htmlspecialchars($lokasi['nama_lokasi']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="jumlah_keluar" class="form-label">Jumlah Keluar</label>
                    <input type="number" class="form-control" id="jumlah_keluar" name="jumlah_keluar" min="1" required>
                </div>
                <div class="col-md-4">
                    <label for="tipe_keluar" class="form-label">Tipe Keluar</label>
                    <select class="form-select" id="tipe_keluar" name="tipe_keluar" required>
                        <option value="Penjualan">Penjualan</option>
                        <option value="Transfer">Transfer</option>
                        <option value="Rusak">Rusak</option>
                        <option value="Lain-lain">Lain-lain</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="id_pelanggan" class="form-label">Pelanggan (Opsional)</label>
                    <select class="form-select" id="id_pelanggan" name="id_pelanggan">
                        <option value="">Pilih Pelanggan</option>
                        <?php foreach ($pelangganList as $pelanggan): ?>
                            <option value="<?= $pelanggan['id_pelanggan'] ?>"><?= htmlspecialchars($pelanggan['nama_pelanggan']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label for="nomor_referensi" class="form-label">Nomor Referensi</label>
                <input type="text" class="form-control" id="nomor_referensi" name="nomor_referensi">
            </div>
            <div class="mb-3">
                <label for="keterangan" class="form-label">Keterangan</label>
                <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>