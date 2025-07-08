<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: /stock_gudang/login_page/login.php"); // sesuaikan path jika perlu
    exit();
}

require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../config/database.php';


$filter = $_GET['filter'] ?? '';
$query = "SELECT s.*, p.nama_produk, p.satuan, p.stok_minimal, l.nama_lokasi 
          FROM stok_saat_ini s
          JOIN produk p ON s.id_produk = p.id_produk
          JOIN lokasi_gudang l ON s.id_lokasi = l.id_lokasi";

if ($filter === 'rendah') {
    $query .= " WHERE s.jumlah_stok <= p.stok_minimal AND p.stok_minimal > 0";
} elseif ($filter === 'kosong') {
    $query .= " WHERE s.jumlah_stok = 0";
}

$query .= " ORDER BY p.nama_produk, l.nama_lokasi";

$stmt = $pdo->query($query);
$stok = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="d-flex justify-content-between mb-4">
    <h2>Laporan Stok</h2>
    <div>
        <a href="stok.php" class="btn btn-outline-primary <?= !$filter ? 'active' : '' ?>">Semua</a>
        <a href="stok.php?filter=rendah" class="btn btn-outline-warning <?= $filter === 'rendah' ? 'active' : '' ?>">Stok Rendah</a>
        <a href="stok.php?filter=kosong" class="btn btn-outline-danger <?= $filter === 'kosong' ? 'active' : '' ?>">Stok Kosong</a>
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
                        <th>Stok Saat Ini</th>
                        <th>Stok Minimal</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($stok)): ?>
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data stok</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($stok as $key => $item): ?>
                            <tr>
                                <td><?= $key + 1 ?></td>
                                <td><?= htmlspecialchars($item['nama_produk']) ?></td>
                                <td><?= htmlspecialchars($item['nama_lokasi']) ?></td>
                                <td><?= $item['jumlah_stok'] ?> <?= htmlspecialchars($item['satuan']) ?></td>
                                <td><?= $item['stok_minimal'] ?> <?= htmlspecialchars($item['satuan']) ?></td>
                                <td>
                                    <?php if ($item['jumlah_stok'] == 0): ?>
                                        <span class="badge bg-danger">Kosong</span>
                                    <?php elseif ($item['stok_minimal'] > 0 && $item['jumlah_stok'] <= $item['stok_minimal']): ?>
                                        <span class="badge bg-warning text-dark">Rendah</span>
                                    <?php else: ?>
                                        <span class="badge bg-success">Aman</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
