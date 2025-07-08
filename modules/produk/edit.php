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

// Ambil data produk yang akan diedit
$stmt = $pdo->prepare("SELECT * FROM produk WHERE id_produk = ?");
$stmt->execute([$id]);
$produk = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produk) {
    $_SESSION['message'] = "Produk tidak ditemukan";
    $_SESSION['message_type'] = "danger";
    redirect('index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_produk = $_POST['nama_produk'];
    $deskripsi = $_POST['deskripsi'];
    $satuan = $_POST['satuan'];
    $harga_beli = $_POST['harga_beli'];
    $harga_jual = $_POST['harga_jual'];
    $stok_minimal = $_POST['stok_minimal'];

    try {
        $stmt = $pdo->prepare("UPDATE produk SET nama_produk = ?, deskripsi = ?, satuan = ?, harga_beli = ?, harga_jual = ?, stok_minimal = ? WHERE id_produk = ?");
        $stmt->execute([$nama_produk, $deskripsi, $satuan, $harga_beli, $harga_jual, $stok_minimal, $id]);
        
        $_SESSION['message'] = "Produk berhasil diperbarui";
        $_SESSION['message_type'] = "success";
        redirect('index.php');
    } catch (PDOException $e) {
        $error = "Gagal memperbarui produk: " . $e->getMessage();
    }
}
?>

<div class="card">
    <div class="card-header">
        <h4>Edit Produk</h4>
    </div>
    <div class="card-body">
        <?php if (isset($error)): ?>
            <?= alert($error, 'danger') ?>
        <?php endif; ?>
        
        <form method="POST">
            <div class="mb-3">
                <label for="nama_produk" class="form-label">Nama Produk</label>
                <input type="text" class="form-control" id="nama_produk" name="nama_produk" value="<?= htmlspecialchars($produk['nama_produk']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"><?= htmlspecialchars($produk['deskripsi']) ?></textarea>
            </div>
            <div class="mb-3">
                <label for="satuan" class="form-label">Satuan</label>
                <input type="text" class="form-control" id="satuan" name="satuan" value="<?= htmlspecialchars($produk['satuan']) ?>" required>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="harga_beli" class="form-label">Harga Beli</label>
                    <input type="number" step="0.01" class="form-control" id="harga_beli" name="harga_beli" value="<?= $produk['harga_beli'] ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="harga_jual" class="form-label">Harga Jual</label>
                    <input type="number" step="0.01" class="form-control" id="harga_jual" name="harga_jual" value="<?= $produk['harga_jual'] ?>" required>
                </div>
            </div>
            <div class="mb-3">
                <label for="stok_minimal" class="form-label">Stok Minimal</label>
                <input type="number" class="form-control" id="stok_minimal" name="stok_minimal" value="<?= $produk['stok_minimal'] ?>">
            </div>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
