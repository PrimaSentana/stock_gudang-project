<?php
    session_start();
    if (!isset($_SESSION['username'])) {
        header("Location: /stock_gudang/login_page/login.php"); // sesuaikan path jika perlu
        exit();
    }
    require_once __DIR__ . '/../../includes/header.php';
    require_once __DIR__ . '/../../includes/functions.php';
    require_once __DIR__ . '/../../config/database.php';


    $search = $_GET['search'] ?? '';
    $query = "SELECT * FROM produk";
    if ($search) {
        $query .= " WHERE nama_produk LIKE :search OR kode_produk LIKE :search";
    }

    $stmt = $pdo->prepare($query);
    if ($search) {
        $searchTerm = "%$search%";
        $stmt->bindParam(':search', $searchTerm);
    }
    $stmt->execute();
    $produk = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <div class="d-flex justify-content-between mb-4">
        <h2>Daftar Produk</h2>
        <div>
            <a href="tambah.php" class="btn btn-primary">Tambah Produk</a>
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
                    <input type="text" name="search" class="form-control" placeholder="Cari produk..." value="<?= htmlspecialchars($search) ?>">
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
                            <th>Kode Produk</th>
                            <th>Nama Produk</th>
                            <th>Satuan</th>
                            <th>Harga Beli</th>
                            <th>Harga Jual</th>
                            <th>Stok Minimal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($produk)): ?>
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada data produk</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($produk as $key => $item): ?>
                                <tr>
                                    <td><?= $key + 1 ?></td>
                                    <td><?= htmlspecialchars($item['kode_produk']) ?></td>
                                    <td><?= htmlspecialchars($item['nama_produk']) ?></td>
                                    <td><?= htmlspecialchars($item['satuan']) ?></td>
                                    <td><?= number_format($item['harga_beli'], 2) ?></td>
                                    <td><?= number_format($item['harga_jual'], 2) ?></td>
                                    <td><?= $item['stok_minimal'] ?></td>
                                    <td>
                                        <a href="edit.php?id=<?= $item['id_produk'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                        <a href="hapus.php?id=<?= $item['id_produk'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus?')">Hapus</a>
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
