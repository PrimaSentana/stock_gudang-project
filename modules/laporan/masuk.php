<?php
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../config/database.php';


$start_date = $_GET['start_date'] ?? date('Y-m-01');
$end_date = $_GET['end_date'] ?? date('Y-m-d');
$id_produk = $_GET['id_produk'] ?? '';
$id_supplier = $_GET['id_supplier'] ?? '';

$query = "SELECT sm.*, p.nama_produk, p.satuan, l.nama_lokasi, s.nama_supplier 
          FROM stok_masuk sm
          JOIN produk p ON sm.id_produk = p.id_produk
          JOIN lokasi_gudang l ON sm.id_lokasi = l.id_lokasi
          LEFT JOIN supplier s ON sm.id_supplier = s.id_supplier
          WHERE sm.tanggal_masuk BETWEEN :start_date AND :end_date";

$params = [
    ':start_date' => $start_date . ' 00:00:00',
    ':end_date' => $end_date . ' 23:59:59'
];

if ($id_produk) {
    $query .= " AND sm.id_produk = :id_produk";
    $params[':id_produk'] = $id_produk;
}

if ($id_supplier) {
    $query .= " AND sm.id_supplier = :id_supplier";
    $params[':id_supplier'] = $id_supplier;
}

$query .= " ORDER BY sm.tanggal_masuk DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$stok_masuk = $stmt->fetchAll(PDO::FETCH_ASSOC);

$produkList = getProdukList($pdo);
$supplierList = getSupplierList($pdo);
?>

<div class="d-flex justify-content-between mb-4">
    <h2>Laporan Stok Masuk</h2>
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
            <div class="col-md-3">
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
            <div class="col-md-3">
                <label for="id_supplier" class="form-label">Supplier</label>
                <select class="form-select" id="id_supplier" name="id_supplier">
                    <option value="">Semua Supplier</option>
                    <?php foreach ($supplierList as $supplier): ?>
                        <option value="<?= $supplier['id_supplier'] ?>" <?= $id_supplier == $supplier['id_supplier'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($supplier['nama_supplier']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="masuk.php" class="btn btn-secondary">Reset</a>
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
                        <th>Supplier</th>
                        <th>Referensi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($stok_masuk)): ?>
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data stok masuk</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($stok_masuk as $key => $item): ?>
                            <tr>
                                <td><?= $key + 1 ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($item['tanggal_masuk'])) ?></td>
                                <td><?= htmlspecialchars($item['nama_produk']) ?></td>
                                <td><?= htmlspecialchars($item['nama_lokasi']) ?></td>
                                <td><?= $item['jumlah_masuk'] ?> <?= htmlspecialchars($item['satuan']) ?></td>
                                <td><?= $item['nama_supplier'] ?? '-' ?></td>
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
