<?php
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_pelanggan = $_POST['nama_pelanggan'];
    $alamat = $_POST['alamat'];
    $telepon = $_POST['telepon'];
    $email = $_POST['email'];

    try {
        $stmt = $pdo->prepare("INSERT INTO pelanggan (nama_pelanggan, alamat, telepon, email) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nama_pelanggan, $alamat, $telepon, $email]);
        
        $_SESSION['message'] = "Pelanggan berhasil ditambahkan";
        $_SESSION['message_type'] = "success";
        redirect('index.php');
    } catch (PDOException $e) {
        $error = "Gagal menambahkan pelanggan: " . $e->getMessage();
    }
}
?>

<div class="card">
    <div class="card-header">
        <h4>Tambah Pelanggan</h4>
    </div>
    <div class="card-body">
        <?php if (isset($error)): ?>
            <?= alert($error, 'danger') ?>
        <?php endif; ?>
        
        <form method="POST">
            <div class="mb-3">
                <label for="nama_pelanggan" class="form-label">Nama Pelanggan</label>
                <input type="text" class="form-control" id="nama_pelanggan" name="nama_pelanggan" required>
            </div>
            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea class="form-control" id="alamat" name="alamat" rows="2"></textarea>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="telepon" class="form-label">Telepon</label>
                    <input type="text" class="form-control" id="telepon" name="telepon">
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>