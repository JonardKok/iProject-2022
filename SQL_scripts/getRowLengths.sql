--Alleen op varchar en char
SELECT OBJECT_NAME(OBJECT_ID) AS Tabelnaam, name AS kolomnaam, TYPE_NAME(system_type_id) AS bestandstype, max_length AS maximumlength
FROM sys.columns
WHERE TYPE_NAME(system_type_id) IN ('CHAR','VARCHAR') AND OBJECT_NAME(OBJECT_ID) IN (SELECT TABLE_NAME FROM information_schema.tables)
--Voor nvarchar, nchar, char en varchar
SELECT OBJECT_NAME(OBJECT_ID) AS Tabelnaam, name AS kolomnaam, TYPE_NAME(system_type_id) AS bestandstype, 
CASE 
	WHEN TYPE_NAME(system_type_id) IN ('NCHAR','NVARCHAR') 
        THEN max_length / 2
	ELSE max_length
	END 
AS maximumlength
FROM sys.columns
WHERE TYPE_NAME(system_type_id) IN ('CHAR','NCHAR','VARCHAR','NVARCHAR') AND OBJECT_NAME(OBJECT_ID) IN (SELECT TABLE_NAME FROM information_schema.tables)