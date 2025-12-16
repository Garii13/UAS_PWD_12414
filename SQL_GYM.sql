-- 1. Buat database
CREATE DATABASE IF NOT EXISTS gym_center_yogyakarta
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE gym_center_yogyakarta;

-- 2. Tabel users
CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama_lengkap VARCHAR(100) NOT NULL,
    email VARCHAR(191) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    foto VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 3. Tambah kolom identitas & kesehatan di users
ALTER TABLE users
    ADD COLUMN tempat_lahir VARCHAR(100) NULL AFTER nama_lengkap,
    ADD COLUMN tanggal_lahir DATE NULL AFTER tempat_lahir,
    ADD COLUMN jenis_kelamin ENUM('Male','Female') NULL AFTER tanggal_lahir,
    ADD COLUMN alamat_domisili TEXT NULL AFTER jenis_kelamin,
    ADD COLUMN berat_badan DECIMAL(5,2) NULL AFTER alamat_domisili,
    ADD COLUMN tinggi_badan DECIMAL(5,2) NULL AFTER berat_badan,
    ADD COLUMN riwayat_penyakit TEXT NULL AFTER tinggi_badan;

-- 4. Tabel membership
CREATE TABLE IF NOT EXISTS membership (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    tanggal_mulai DATE NOT NULL,
    periode_bulan INT NOT NULL,
    total_bayar INT UNSIGNED NOT NULL,
    status ENUM('Aktif','Expired') NOT NULL DEFAULT 'Aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_membership_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);
