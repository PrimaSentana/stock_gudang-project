<?php
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_supplier = $_POST['nama_supplier'];
    $alamat = $_POST['alamat'];
    $telepon = $_POST['telepon'];
    $email = $_POST['email'];
    $kontak_person = $_POST['kontak_person'];

    try {
        $stmt = $pdo->prepare("INSERT INTO supplier (nama_supplier, alamat, telepon, email, kontak_person) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nama_supplier, $alamat, $telepon, $email, $kontak_person]);
        
        $_SESSION['message'] = "Supplier berhasil ditambahkan";
        $_SESSION['message_type'] = "success";
        redirect('index.php');
    } catch (PDOException $e) {
        $error = "Gagal menambahkan supplier: " . $e->getMessage();
    }
}
?>

<div class="card">
    <div class="card-header">
        <h4>Tambah Supplier</h4>
    </div>
    <div class="card-body">
        <?php if (isset($error)): ?>
            <?= alert($error, 'danger') ?>
        <?php endif; ?>
        
        <form method="POST">
            <div class="mb-3">
                <label for="nama_supplier" class="form-label">Nama Supplier</label>
                <input type="text" class="form-control" id="nama_supplier" name="nama_supplier" required>
            </div>
            <div class="mb-3">
                <label for="kontak_person" class="form-label">Kontak Person</label>
                <input type="text" class="form-control" id="kontak_person" name="kontak_person">
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
