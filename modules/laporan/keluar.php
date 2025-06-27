<?php
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../config/database.php';


$start_date = $_GET['start_date'] ?? date('Y-m-01');
$end_date = $_GET['end_date'] ?? date('Y-m-d');
$id_produk = $_GET['id_produk'] ?? '';
$id_pelanggan = $_GET['id_pelanggan'] ?? '';
$tipe_keluar = $_GET['tipe_keluar'] ?? '';

$query = "SELECT sk.*, p.nama_produk, p.satuan, l.nama_lokasi, pl.nama_pelanggan 
          FROM stok_keluar sk
          JOIN produk p ON sk.id_produk = p.id_produk
          JOIN lokasi_gudang l ON sk.id_lokasi = l.id_lokasi
          LEFT JOIN pelanggan pl ON sk.id_pelanggan = pl.id_pelanggan
          WHERE sk.tanggal_keluar BETWEEN :start_date AND :end_date";

$params = [
    ':start_date' => $start_date . ' 00:00:00',
    ':end_date' => $end_date . ' 23:59:59'
];

if ($id_produk) {
    $query .= " AND sk.id_produk = :id_produk";
    $params[':id_produk'] = $id_produk;
}

if ($id_pelanggan) {
    $query .= " AND sk.id_pelanggan = :id_pelanggan";
    $params[':id_pelanggan'] = $id_pelanggan;
}

if ($tipe_keluar) {
    $query .= " AND sk.tipe_keluar = :tipe_keluar";
    $params[':tipe_keluar'] = $tipe_keluar;
}

$query .= " ORDER BY sk.tanggal_keluar DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$stok_keluar = $stmt->fetchAll(PDO::FETCH_ASSOC);

$produkList = getProdukList($pdo);
$pelangganList = getPelangganList($pdo);
?>

<div class="d-flex justify-content-between mb-4">
    <h2>Laporan Stok Keluar</h2>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label for="start_date" class="form-label">Dari Tanggal</label>
                <input type="date" class="form-control" id="start_date" name="start_date" value="<?= $start_date ?>">
            </div>
            <div class="col-md-3">
                <label for="end_date" class="form-label">Sampai Tanggal</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="<?= $end_date ?>">
            </div>
            <div class="col-md-2">
                <label for="id_produk" class="form-label">Produk</label>
                <select class="form-select" id="id_produk" name="id_produk">
                    <option value="">Semua Produk</option>
                    <?php foreach ($produkList as $produk): ?>
                        <option value="<?= $produk['id_produk'] ?>" <?= $id_produk == $produk['id_produk'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($produk['nama_produk']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label for="id_pelanggan" class="form-label">Pelanggan</label>
                <select class="form-select" id="id_pelanggan" name="id_pelanggan">
                    <option value="">Semua Pelanggan</option>
                    <?php foreach ($pelangganList as $pelanggan): ?>
                        <option value="<?= $pelanggan['id_pelanggan'] ?>" <?= $id_pelanggan == $pelanggan['id_pelanggan'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($pelanggan['nama_pelanggan']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label for="tipe_keluar" class="form-label">Tipe Keluar</label>
                <select class="form-select" id="tipe_keluar" name="tipe_keluar">
                    <option value="">Semua Tipe</option>
                    <option value="Penjualan" <?= $tipe_keluar === 'Penjualan' ? 'selected' : '' ?>>Penjualan</option>
                    <option value="Transfer" <?= $tipe_keluar === 'Transfer' ? 'selected' : '' ?>>Transfer</option>
                    <option value="Rusak" <?= $tipe_keluar === 'Rusak' ? 'selected' : '' ?>>Rusak</option>
                    <option value="Lain-lain" <?= $tipe_keluar === 'Lain-lain' ? 'selected' : '' ?>>Lain-lain</option>
                </select>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="keluar.php" class="btn btn-secondary">Reset</a>
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
                        <th>Tanggal</th>
                        <th>Produk</th>
                        <th>Lokasi</th>
                        <th>Jumlah</th>
                        <th>Tipe</th>
                        <th>Pelanggan</th>
                        <th>Referensi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($stok_keluar)): ?>
                        <tr>
                            <td colspan="8" class="text-center">Tidak ada data stok keluar</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($stok_keluar as $key => $item): ?>
                            <tr>
                                <td><?= $key + 1 ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($item['tanggal_keluar'])) ?></td>
                                <td><?= htmlspecialchars($item['nama_produk']) ?></td>
                                <td><?= htmlspecialchars($item['nama_lokasi']) ?></td>
                                <td><?= $item['jumlah_keluar'] ?> <?= htmlspecialchars($item['satuan']) ?></td>
                                <td>
                                    <?php 
                                    $badge_class = [
                                        'Penjualan' => 'bg-success',
                                        'Transfer' => 'bg-primary',
                                        'Rusak' => 'bg-danger',
                                        'Lain-lain' => 'bg-secondary'
                                    ];
                                    ?>
                                    <span class="badge <?= $badge_class[$item['tipe_keluar']] ?>">
                                        <?= $item['tipe_keluar'] ?>
                                    </span>
                                </td>
                                <td><?= $item['nama_pelanggan'] ?? '-' ?></td>
                                <td><?= $item['nomor_referensi'] ?? '-' ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
