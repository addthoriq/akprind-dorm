CREATE TABLE admin (
  kode_admin CHAR(5) PRIMARY KEY,
  nama_admin VARCHAR(50) NOT NULL,
  email_admin VARCHAR(30) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL
  );
  
 CREATE TABLE session_admin (
   id_session VARCHAR(20) PRIMARY KEY,
   kode_admin CHAR(5) NOT NULL
  );
  
  CREATE TABLE member (
    nim CHAR(9) PRIMARY KEY,
    nama_member VARCHAR(50) NOT NULL,
    email_member VARCHAR(30) UNIQUE,
    password VARCHAR(255) NOT NULL,
    nomor_wa CHAR(13) NOT NULL
   );
 
 ALTER TABLE member ADD CONSTRAINT check_nim CHECK (nim ~'^[0-9]{9}$');
   
  CREATE TABLE session_member (
    id_session VARCHAR(20) PRIMARY KEY,
    nim CHAR(9) NOT NULL
   );
   
   CREATE TABLE lantai (
     nomor_lantai CHAR(1) PRIMARY KEY CHECK (nomor_lantai ~'^[0-9]{1}$'),
     harga INTEGER NOT NULL CHECK (harga >= 0 AND harga <= 2147483647)
    );
    
  CREATE TABLE gambar_lantai (
    nomor_lantai CHAR(1) CHECK (nomor_lantai ~'^[0-9]{1}$'),
    url_gambar VARCHAR(255) NOT NULL,
    deskripsi TEXT,
    FOREIGN KEY (nomor_lantai) REFERENCES lantai(nomor_lantai)
    );
   
 CREATE TYPE enum_status_kamar AS ENUM('tersedia', 'telah ditempati', 'renovasi');
 
 CREATE TABLE kamar (
   kode_kamar CHAR(3) PRIMARY KEY CHECK (kode_kamar ~'^[0-9]{3}$'),
   nomor_kamar CHAR(2) UNIQUE CHECK (nomor_kamar ~'^[0-9]{2}$'),
   status_kamar enum_status_kamar NOT NULL DEFAULT 'tersedia'
   );
  
  ALTER TABLE kamar ADD COLUMN lantai_kamar CHAR(1) REFERENCES lantai(nomor_lantai);
  
  CREATE TABLE bank (
    nomor_rekening VARCHAR(16) PRIMARY KEY CHECK (nomor_rekening ~'^[0-9]{1}$'),
    nama_bank VARCHAR(50) NOT NULL,
    atas_nama VARCHAR(100) NOT NULL
   );
 
 CREATE TABLE reservasi (
   kode_reservasi CHAR(22) PRIMARY KEY,
   kode_kamar CHAR(3) NOT NULL,
   nim CHAR(9) NOT NULL,
   qty SMALLINT NOT NULL CHECK (qty >= 1 AND qty <= 120),
   batas_awal_bayar TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   batas_akhir_bayar TIMESTAMP DEFAULT CURRENT_TIMESTAMP + '30 days',
   FOREIGN KEY (kode_kamar) REFERENCES kamar(kode_kamar),
   FOREIGN KEY (nim) REFERENCES member(nim)
 );
 CREATE TYPE enum_status_reservasi AS ENUM ('menunggu pembayaran', 'dibatalkan', 'berhasil dibayar', 'kadaluwarsa');
 ALTER TABLE reservasi ADD COLUMN status_reservasi enum_status_reservasi NOT NULL DEFAULT 'menunggu pembayaran';
 
  CREATE TABLE bukti_pembayaran (
  kode_pembayaran CHAR(22) PRIMARY KEY,
  bank_pengirim VARCHAR(50) NOT NULL,
  nama_pengirim VARCHAR(100) NOT NULL,
  bank_tujuan VARCHAR(16) NOT NULL,
  nominal_yang_dikirim INTEGER NOT NULL CHECK (nominal_yang_dikirim >= 0 AND nominal_yang_dikirim <= 2147483647),
  dir_gambar_resi VARCHAR(255) NOT NULL,
  FOREIGN KEY (bank_tujuan) REFERENCES bank(nomor_rekening)
  );
 
 ALTER TABLE bukti_pembayaran ADD CONSTRAINT fk_bukti_reservasi FOREIGN KEY (kode_pembayaran) REFERENCES reservasi(kode_reservasi);
 ALTER TABLE bukti_pembayaran ADD COLUMN tgl_unggah TIMESTAMP DEFAULT CURRENT_TIMESTAMP;
 
 CREATE TABLE history_pembayaran (
   kode_pembayaran CHAR(22) PRIMARY KEY,
   nim CHAR(9) NOT NULL CHECK (nim ~'^[0-9]{9}$'),
   kode_kamar CHAR(3) NOT NULL CHECK (kode_kamar ~'^[0-9]{3}$'),
   tgl_pembayaran TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
   qty SMALLINT NOT NULL CHECK (qty >= 1 AND qty <= 120),
   FOREIGN KEY (kode_pembayaran) REFERENCES bukti_pembayaran(kode_pembayaran)
   );

CREATE TYPE enum_status_penghuni AS ENUM('aktif','keluar');
CREATE TABLE penghuni (
  kode_reservasi CHAR(22) PRIMARY KEY,
  tgl_mulai_sewa TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  tgl_akhir_sewa TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP + '30 days',
  status_penghuni enum_status_penghuni NOT NULL DEFAULT 'keluar',
  tgl_status_aktif TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  tgl_status_keluar TIMESTAMP,
  FOREIGN KEY (kode_reservasi) REFERENCES history_pembayaran(kode_pembayaran)
 );