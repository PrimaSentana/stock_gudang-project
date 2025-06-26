<?php
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../config/database.php';


$search = $_GET['search'] ?? '';
$query = "SELECT * FROM supplier";
if ($search) {
    $query .= " WHERE nama_supplier LIKE :search OR kontak_person LIKE :search";
}

$stmt = $pdo->prepare($query);
if ($search) {
    $searchTerm = "%$search%";
    $stmt->bindParam(':search', $searchTerm);
}
$stmt->execute();
$supplier = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="d-flex justify-content-between mb-4">
    <h2>Daftar Supplier</h2>
    <div>
        <a href="tambah.php" class="btn btn-primary">Tambah Supplier</a>
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
                <input type="text" name="search" class="form-control" placeholder="Cari supplier..." value="<?= htmlspecialchars($search) ?>">
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
                        <th>Nama Supplier</th>
                        <th>Kontak Person</th>
                        <th>Telepon</th>
                        <th>Email</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($supplier)): ?>
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data supplier</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($supplier as $key => $item): ?>
                            <tr>
                                <td><?= $key + 1 ?></td>
                                <td><?= htmlspecialchars($item['nama_supplier']) ?></td>
                                <td><?= htmlspecialchars($item['kontak_person']) ?></td>
                                <td><?= htmlspecialchars($item['telepon']) ?></td>
                                <td><?= htmlspecialchars($item['email']) ?></td>
                                <td>
                                    <a href="edit.php?id=<?= $item['id_supplier'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <a href="hapus.php?id=<?= $item['id_supplier'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus?')">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>