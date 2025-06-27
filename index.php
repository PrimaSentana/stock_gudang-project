<?php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/config/database.php';

// Hitung total produk
$stmt = $pdo->query("SELECT COUNT(*) FROM produk");
$total_produk = $stmt->fetchColumn();

// Hitung total lokasi
$stmt = $pdo->query("SELECT COUNT(*) FROM lokasi_gudang");
$total_lokasi = $stmt->fetchColumn();

// Hitung total stok masuk hari ini
$today = date('Y-m-d');
$stmt = $pdo->prepare("SELECT COUNT(*) FROM stok_masuk WHERE DATE(tanggal_masuk) = ?");
$stmt->execute([$today]);
$stok_masuk_hari_ini = $stmt->fetchColumn();

// Hitung total stok keluar hari ini
$stmt = $pdo->prepare("SELECT COUNT(*) FROM stok_keluar WHERE DATE(tanggal_keluar) = ?");
$stmt->execute([$today]);
$stok_keluar_hari_ini = $stmt->fetchColumn();

// Ambil produk dengan stok rendah
$stmt = $pdo->query("SELECT p.nama_produk, s.jumlah_stok, p.stok_minimal 
                     FROM stok_saat_ini s
                     JOIN produk p ON s.id_produk = p.id_produk
                     WHERE s.jumlah_stok <= p.stok_minimal AND p.stok_minimal > 0
                     ORDER BY s.jumlah_stok ASC
                     LIMIT 5");
$stok_rendah = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5 class="card-title">Total Produk</h5>
                <h2 class="card-text"><?= $total_produk ?></h2>
                <a href="modules/produk/index.php" class="text-white">Lihat Detail</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5 class="card-title">Total Lokasi</h5>
                <h2 class="card-text"><?= $total_lokasi ?></h2>
                <a href="modules/lokasi/index.php" class="text-white">Lihat Detail</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h5 class="card-title">Stok Masuk Hari Ini</h5>
                <h2 class="card-text"><?= $stok_masuk_hari_ini ?></h2>
                <a href="modules/stok/masuk.php" class="text-white">Lihat Detail</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-dark">
            <div class="card-body">
                <h5 class="card-title">Stok Keluar Hari Ini</h5>
                <h2 class="card-text"><?= $stok_keluar_hari_ini ?></h2>
                <a href="modules/stok/keluar.php" class="text-dark">Lihat Detail</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Stok Rendah</h5>
            </div>
            <div class="card-body">
                <?php if (empty($stok_rendah)): ?>
                    <div class="alert alert-success">Tidak ada produk dengan stok rendah</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Stok Saat Ini</th>
                                    <th>Stok Minimal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($stok_rendah as $item): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($item['nama_produk']) ?></td>
                                        <td>
                                            <span class="badge bg-danger"><?= $item['jumlah_stok'] ?></span>
                                        </td>
                                        <td><?= $item['stok_minimal'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <a href="modules/laporan/stok.php?filter=rendah" class="btn btn-sm btn-warning">Lihat Semua</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Aktivitas Terakhir</h5>
            </div>
            <div class="card-body">
                <?php
                // Ambil 5 aktivitas terakhir (gabungan stok masuk dan keluar)
                $query = "(SELECT 'Masuk' as jenis, tanggal_masuk as tanggal, id_produk, jumlah_masuk as jumlah, NULL as tipe, id_supplier as id_orang, 'supplier' as tipe_orang
                          FROM stok_masuk
                          ORDER BY tanggal_masuk DESC LIMIT 5)
                          
                          UNION ALL
                          
                          (SELECT 'Keluar' as jenis, tanggal_keluar as tanggal, id_produk, jumlah_keluar as jumlah, tipe_keluar as tipe, id_pelanggan as id_orang, 'pelanggan' as tipe_orang
                          FROM stok_keluar
                          ORDER BY tanggal_keluar DESC LIMIT 5)
                          
                          ORDER BY tanggal DESC LIMIT 5";
                
                $stmt = $pdo->query($query);
                $aktivitas = $stmt->fetchAll(PDO::FETCH_ASSOC);
                ?>
                
                <?php if (empty($aktivitas)): ?>
                    <div class="alert alert-info">Belum ada aktivitas</div>
                <?php else: ?>
                    <ul class="list-group">
                        <?php foreach ($aktivitas as $item): ?>
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <strong><?= $item['jenis'] ?></strong> - 
                                        <?= date('d/m/Y H:i', strtotime($item['tanggal'])) ?>
                                        <br>
                                        <?php
                                        // Ambil nama produk
                                        $stmt = $pdo->prepare("SELECT nama_produk FROM produk WHERE id_produk = ?");
                                        $stmt->execute([$item['id_produk']]);
                                        $produk = $stmt->fetch(PDO::FETCH_ASSOC);
                                        echo htmlspecialchars($produk['nama_produk']) . ' (' . $item['jumlah'] . ')';
                                        ?>
                                    </div>
                                    <div>
                                        <?php if ($item['jenis'] === 'Keluar'): ?>
                                            <span class="badge bg-secondary"><?= $item['tipe'] ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
