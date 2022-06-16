 create VIEW VWaanmeldingen as
select  YEAR(aanmaakdatum) as jaar, month( aanmaakdatum) as 'maand', count(*) as 'Aantal aanmeldingen' from Gebruiker
group by YEAR(aanmaakdatum),  datename(month, aanmaakdatum),aanmaakdatum