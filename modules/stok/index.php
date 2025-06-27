<?php
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../config/database.php';


$search = $_GET['search'] ?? '';
$query = "SELECT s.*, p.nama_produk, p.satuan, l.nama_lokasi 
          FROM stok_saat_ini s
          JOIN produk p ON s.id_produk = p.id_produk
          JOIN lokasi_gudang l ON s.id_lokasi = l.id_lokasi";

if ($search) {
    $query .= " WHERE p.nama_produk LIKE :search OR l.nama_lokasi LIKE :search";
}

$stmt = $pdo->prepare($query);
if ($search) {
    $searchTerm = "%$search%";
    $stmt->bindParam(':search', $searchTerm);
}
$stmt->execute();
$stok = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="d-flex justify-content-between mb-4">
    <h2>Stok Saat Ini</h2>
    <div>
        <a href="masuk.php" class="btn btn-success">Stok Masuk</a>
        <a href="keluar.php" class="btn btn-danger">Stok Keluar</a>
    </div>
</div>

<?php if (isset($_SESSION['message'])): ?>
    <?= alert($_SESSION['message'], $_SESSION['message_type']) ?>
    <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
<?php endif; ?>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-8">
                <input type="text" name="search" class="form-control" placeholder="Cari produk atau lokasi..." value="<?= htmlspecialchars($search) ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Cari</button>
                <?php if ($search): ?>
                    <a href="index.php" class="btn btn-secondary">Reset</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Produk</th>
                        <th>Lokasi</th>
                        <th>Jumlah Stok</th>
                        <th>Satuan</th>
                        <th>Terakhir Masuk</th>
                        <th>Terakhir Keluar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($stok)): ?>
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data stok</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($stok as $key => $item): ?>
                            <tr>
                                <td><?= $key + 1 ?></td>
                                <td><?= htmlspecialchars($item['nama_produk']) ?></td>
                                <td><?= htmlspecialchars($item['nama_lokasi']) ?></td>
                                <td><?= $item['jumlah_stok'] ?></td>
                                <td><?= htmlspecialchars($item['satuan']) ?></td>
                                <td><?= $item['tanggal_terakhir_masuk'] ? date('d/m/Y H:i', strtotime($item['tanggal_terakhir_masuk'])) : '-' ?></td>
                                <td><?= $item['tanggal_terakhir_keluar'] ? date('d/m/Y H:i', strtotime($item['tanggal_terakhir_keluar'])) : '-' ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
