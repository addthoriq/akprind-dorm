SELECT
  to_char(current_time, 'ddmmyyyy');
DROP FUNCTION IF EXISTS tgl_trim;
CREATE FUNCTION tgl_trim () RETURNS VARCHAR(30) LANGUAGE plpgsql AS $ $ DECLARE tgl_trim VARCHAR(20);
tgl_raw VARCHAR(20);
BEGIN tgl_raw: = to_char(current_date, 'ddmmyyyy');
-- 	SELECT current_date INTO tgl_raw;
  --   SELECT BTRIM(tgl_raw, '-') INTO tgl_trim;
  tgl_trim: = TRIM(tgl_raw, '-');
return tgl_trim;
END;
$ $
SELECT
  tgl_trim();
SELECT
  'INV' || to_char(current_date, 'ddmmyyyy');
SELECT
  TRIM(current_date, '-');
SELECT
  current_date;