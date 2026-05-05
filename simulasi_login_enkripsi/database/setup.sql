-- Simulasi login dengan PASSWORD_HASH (bcrypt) — folder simulasi_login_enkripsi
-- Bandingkan dengan ../simulasi_login/database/setup.sql (teks polos).
--
-- Impor: mysql -u root < simulasi_login_enkripsi/database/setup.sql

SET NAMES utf8mb4;

CREATE DATABASE IF NOT EXISTS simulasi_login_enkripsi
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE simulasi_login_enkripsi;

CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  username VARCHAR(50) NOT NULL,
  password_hash VARCHAR(255) NOT NULL COMMENT 'bcrypt; bukan teks sandi asli',
  nama VARCHAR(100) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_users_username (username)
) ENGINE=InnoDB;

TRUNCATE TABLE users;

-- Hash dari password_hash(..., PASSWORD_BCRYPT). Sandi yang sama dengan demo folder non-enkripsi:
-- admin / admin123 — mahasiswa / rahasia
INSERT INTO users (username, password_hash, nama) VALUES
  ('admin', '$2y$10$3eCktC3eFdJA0PoIGQaQPOhGLEiPVR8oI4G1nME1/ojHig8Q.qgeC', 'Administrator Demo'),
  ('mahasiswa', '$2y$10$t.EoSSTc59qXxrUPQagMdemGOxHvOHGK.ow.YlP/43NJmN5FBlkUm', 'Budi Contoh');
