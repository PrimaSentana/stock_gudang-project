<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Stock Control Gudang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/stock_gudang/index.php">Stock Control</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="masterDropdown" role="button" data-bs-toggle="dropdown">Master Data</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/stock_gudang/modules/produk/index.php">Produk</a></li>
                            <li><a class="dropdown-item" href="/stock_gudang/modules/lokasi/index.php">Lokasi Gudang</a></li>
                            <li><a class="dropdown-item" href="/stock_gudang/modules/supplier/index.php">Supplier</a></li>
                            <li><a class="dropdown-item" href="/stock_gudang/modules/pelanggan/index.php">Pelanggan</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="transaksiDropdown" role="button" data-bs-toggle="dropdown">Transaksi</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/stock_gudang/modules/stok/masuk.php">Stok Masuk</a></li>
                            <li><a class="dropdown-item" href="/stock_gudang/modules/stok/keluar.php">Stok Keluar</a></li>
                            <li><a class="dropdown-item" href="/stock_gudang/modules/stok/index.php">Stok Saat Ini</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="laporanDropdown" role="button" data-bs-toggle="dropdown">Laporan</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/stock_gudang/modules/laporan/stok.php">Laporan Stok</a></li>
                            <li><a class="dropdown-item" href="/stock_gudang/modules/laporan/masuk.php">Laporan Masuk</a></li>
                            <li><a class="dropdown-item" href="/stock_gudang/modules/laporan/keluar.php">Laporan Keluar</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
<div class="container mt-4"></div>