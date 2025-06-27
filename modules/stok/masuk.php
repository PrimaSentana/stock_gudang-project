<?php
    require_once __DIR__ . '/../../includes/header.php';
    require_once __DIR__ . '/../../includes/functions.php';
    require_once __DIR__ . '/../../config/database.php';


    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id_produk = $_POST['id_produk'];
        $id_lokasi = $_POST['id_lokasi'];
        $jumlah_masuk = $_POST['jumlah_masuk'];
        $id_supplier = $_POST['id_supplier'] ?: null;
        $nomor_referensi = $_POST['nomor_referensi'];
        $keterangan = $_POST['keterangan'];

        try {
            $pdo->beginTransaction();
            
            // Insert ke tabel stok_masuk
            $stmt = $pdo->prepare("INSERT INTO stok_masuk (id_produk, id_lokasi, jumlah_masuk, id_supplier, nomor_referensi, keterangan) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$id_produk, $id_lokasi, $jumlah_masuk, $id_supplier, $nomor_referensi, $keterangan]);
            
            // Update stok_saat_ini
            updateStok($pdo, $id_produk, $id_lokasi, $jumlah_masuk, true);
            
            $pdo->commit();
            
            $_SESSION['message'] = "Stok masuk berhasil dicatat";
            $_SESSION['message_type'] = "success";
            redirect('masuk.php');
        } catch (PDOException $e) {
            $pdo->rollBack();
            $error = "Gagal mencatat stok masuk: " . $e->getMessage();
        }
    }

    $produkList = getProdukList($pdo);
    $lokasiList = getLokasiList($pdo);
    $supplierList = getSupplierList($pdo);
    ?>

    <div class="card">
        <div class="card-header">
            <h4>Stok Masuk</h4>
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
                        <label for="jumlah_masuk" class="form-label">Jumlah Masuk</label>
                        <input type="number" class="form-control" id="jumlah_masuk" name="jumlah_masuk" min="1" required>
                    </div>
                    <div class="col-md-4">
                        <label for="id_supplier" class="form-label">Supplier (Opsional)</label>
                        <select class="form-select" id="id_supplier" name="id_supplier">
                            <option value="">Pilih Supplier</option>
                            <?php foreach ($supplierList as $supplier): ?>
                                <option value="<?= $supplier['id_supplier'] ?>"><?= htmlspecialchars($supplier['nama_supplier']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="nomor_referensi" class="form-label">Nomor Referensi</label>
                        <input type="text" class="form-control" id="nomor_referensi" name="nomor_referensi">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="keterangan" class="form-label">Keterangan</label>
                    <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
