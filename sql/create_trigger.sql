CREATE OR REPLACE FUNCTION cek_batas_pembayaran()
RETURNS TRIGGER AS $$
BEGIN
	IF EXISTS (
    SELECT 1 FROM reservasi WHERE kode_reservasi = NEW.kode_pembayaran
    AND CURRENT_TIMESTAMP >= batas_awal_bayar
    AND CURRENT_TIMESTAMP <= batas_akhir_bayar
  ) THEN
  	UPDATE reservasi SET status = 'berhasil dibayar' WHERE kode_reservasi = NEW.kode_pembayaran;
  END IF;
  RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trigger_update_status_reservasi
BEFORE INSERT ON bukti_pembayaran
FOR EACH ROW
EXECUTE FUNCTION cek_batas_pembayaran();

CREATE OR REPLACE FUNCTION generate_kode_reservasi()
RETURNS TRIGGER AS $$
DECLARE
  kode_generate VARCHAR(22);
BEGIN 
  kode_generate := 'INV' || to_char(DATE(CURRENT_TIMESTAMP),'ddmmyyyy') || to_char(CURRENT_TIMESTAMP, 'HH24MI') || RIGHT(SELECT 1 FROM reservasi WHERE kode_reservasi = NEW.kode_pembayaran) || SELECT kode_kamar FROM reservasi WHERE kode_reservasi = NEW.kode_pembayaran;
  INSERT INTO reservasi VALUES (kode_generate, NEW.kode_kamar, NEW.nim, NEW.qty);
  RETURNS NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trigger_generate_insert_reservasi
AFTER INSERT ON reservasi
FOR EACH ROW
EXECUTE FUNCTION generate_kode_reservasi();

SELECT to_char(DATE(CURRENT_TIMESTAMP), 'ddmmyyyy');

SELECT TO_CHAR(CURRENT_TIMESTAMP, 'hh24:MI');

SELECT RIGHT('201055001', 4);

SELECT 1 FROM nim WHERE nim = '201055001';