CREATE DATABASE stock_gudang;
USE stock_gudang;


CREATE TABLE produk (
    id_produk INT AUTO_INCREMENT PRIMARY KEY,
    kode_produk VARCHAR(50) UNIQUE NOT NULL,
    nama_produk VARCHAR(255) NOT NULL,
    deskripsi TEXT,
    satuan VARCHAR(50) NOT NULL,
    harga_beli DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    harga_jual DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    stok_minimal INT DEFAULT 0,
    tanggal_dibuat DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    tanggal_diperbarui DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


CREATE TABLE lokasi_gudang (
    id_lokasi INT AUTO_INCREMENT PRIMARY KEY,
    kode_lokasi VARCHAR(50) UNIQUE NOT NULL,
    nama_lokasi VARCHAR(100) NOT NULL,
    kapasitas INT DEFAULT 0,
    deskripsi TEXT
);


CREATE TABLE supplier (
    id_supplier INT AUTO_INCREMENT PRIMARY KEY,
    nama_supplier VARCHAR(255) NOT NULL,
    alamat VARCHAR(255),
    telepon VARCHAR(50),
    email VARCHAR(100),
    kontak_person VARCHAR(100)
);


CREATE TABLE pelanggan (
    id_pelanggan INT AUTO_INCREMENT PRIMARY KEY,
    nama_pelanggan VARCHAR(255) NOT NULL,
    alamat VARCHAR(255),
    telepon VARCHAR(50),
    email VARCHAR(100)
);


CREATE TABLE stok_saat_ini (
    id_stok INT AUTO_INCREMENT PRIMARY KEY,
    id_produk INT NOT NULL,
    id_lokasi INT NOT NULL,
    jumlah_stok INT NOT NULL DEFAULT 0,
    tanggal_terakhir_masuk DATETIME,
    tanggal_terakhir_keluar DATETIME,
    tanggal_diperbarui DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_produk) REFERENCES produk(id_produk),
    FOREIGN KEY (id_lokasi) REFERENCES lokasi_gudang(id_lokasi)
);


CREATE TABLE stok_masuk (
    id_stok_masuk INT AUTO_INCREMENT PRIMARY KEY,
    tanggal_masuk DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    id_produk INT NOT NULL,
    id_lokasi INT NOT NULL,
    jumlah_masuk INT NOT NULL,
    id_supplier INT,
    nomor_referensi VARCHAR(100),
    keterangan TEXT,
    FOREIGN KEY (id_produk) REFERENCES produk(id_produk),
    FOREIGN KEY (id_lokasi) REFERENCES lokasi_gudang(id_lokasi),
    FOREIGN KEY (id_supplier) REFERENCES supplier(id_supplier)
);


CREATE TABLE stok_keluar (
    id_stok_keluar INT AUTO_INCREMENT PRIMARY KEY,
    tanggal_keluar DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    id_produk INT NOT NULL,
    id_lokasi INT NOT NULL,
    jumlah_keluar INT NOT NULL,
    id_pelanggan INT,
    tipe_keluar ENUM('Penjualan', 'Transfer', 'Rusak', 'Lain-lain') NOT NULL DEFAULT 'Penjualan',
    nomor_referensi VARCHAR(100),
    keterangan TEXT,
    FOREIGN KEY (id_produk) REFERENCES produk(id_produk),
    FOREIGN KEY (id_lokasi) REFERENCES lokasi_gudang(id_lokasi),
    FOREIGN KEY (id_pelanggan) REFERENCES pelanggan(id_pelanggan)
);