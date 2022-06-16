create view VWleeftijd as
SELECT count(*) as 'hoeveel gebruikers', * FROM 
(
select 
  case
   when year(GETDATE())- year(geboortedag) > 65 then '65+'
   when year(GETDATE())- year(geboortedag) between 55 and 64 then '55-64'
   when year(GETDATE())- year(geboortedag) between 45 and 54 then '45-54'
   when year(GETDATE())- year(geboortedag) between 35 and 44 then '35-44'
   when year(GETDATE())- year(geboortedag) between 25 and 34 then '25-34'
   when year(GETDATE())- year(geboortedag) between 18 and 24 then '18-24'
   when  year(GETDATE())- year(geboortedag)<18 then '0-18'
 END as age_range 
 from Gebruiker
) t
group by age_range
