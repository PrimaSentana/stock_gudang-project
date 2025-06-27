<?php
    require_once __DIR__ . '/../../includes/header.php';
    require_once __DIR__ . '/../../config/database.php';
    require_once __DIR__ . '/../../includes/functions.php';


    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $stmt = $pdo->prepare("SELECT kode_produk FROM produk ORDER BY kode_produk DESC LIMIT 1");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!is_null($data)) {
            $kproduk = $data['kode_produk'];
            $a = substr($kproduk, 4);
            $b = (int) $a;
            $c = $b + 1;
            $d = strlen($c);
            $e = substr($a, 0, strlen($a) - $d);
            $f = "PGB-" . $e . $c;
            $kode_produk = $f;
        } else {
            $kode_produk = 'PGB-0001';
        }

        $nama_produk = $_POST['nama_produk'];
        $deskripsi = $_POST['deskripsi'];
        $satuan = $_POST['satuan'];
        $harga_beli = $_POST['harga_beli'];
        $harga_jual = $_POST['harga_jual'];
        $stok_minimal = $_POST['stok_minimal'];

        try {
            $stmt = $pdo->prepare("INSERT INTO produk (kode_produk, nama_produk, deskripsi, satuan, harga_beli, harga_jual, stok_minimal) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$kode_produk, $nama_produk, $deskripsi, $satuan, $harga_beli, $harga_jual, $stok_minimal]);
            
            $_SESSION['message'] = "Produk berhasil ditambahkan";
            $_SESSION['message_type'] = "success";
            redirect('index.php');
        } catch (PDOException $e) {
            $error = "Gagal menambahkan produk: " . $e->getMessage();
        }
    }
    ?>

    <div class="card">
        <div class="card-header">
            <h4>Tambah Produk Baru</h4>
        </div>
        <div class="card-body">
            <?php if (isset($error)): ?>
                <?= alert($error, 'danger') ?>
            <?php endif; ?>
            
            <form method="POST">
                <div class="mb-3">
                    <label for="nama_produk" class="form-label">Nama Produk</label>
                    <input type="text" class="form-control" id="nama_produk" name="nama_produk" required>
                </div>
                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label for="satuan" class="form-label">Satuan</label>
                    <input type="text" class="form-control" id="satuan" name="satuan" required>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="harga_beli" class="form-label">Harga Beli</label>
                        <input type="number" step="0.01" class="form-control" id="harga_beli" name="harga_beli" required>
                    </div>
                    <div class="col-md-6">
                        <label for="harga_jual" class="form-label">Harga Jual</label>
                        <input type="number" step="0.01" class="form-control" id="harga_jual" name="harga_jual" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="stok_minimal" class="form-label">Stok Minimal</label>
                    <input type="number" class="form-control" id="stok_minimal" name="stok_minimal" value="0">
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="index.php" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>