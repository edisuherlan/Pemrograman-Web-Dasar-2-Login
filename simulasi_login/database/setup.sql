-- Simulasi login — PASSWORD TEKS POLOS (folder simulasi_login saja).
-- Versi bcrypt ada di folder ../simulasi_login_enkripsi/database/setup.sql
--
-- Impor: mysql -u root < simulasi_login/database/setup.sql

SET NAMES utf8mb4;

CREATE DATABASE IF NOT EXISTS simulasi_login
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE simulasi_login;

CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  username VARCHAR(50) NOT NULL,
  password VARCHAR(120) NOT NULL COMMENT 'PERINGATAN praktikum: teks polos, bukan produksi',
  nama VARCHAR(100) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_users_username (username)
) ENGINE=InnoDB;

TRUNCATE TABLE users;

INSERT INTO users (username, password, nama) VALUES
  ('admin', 'admin123', 'Administrator Demo'),
  ('mahasiswa', 'rahasia', 'Budi Contoh');
