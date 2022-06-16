create VIEW VWaantalverkocht as
    select R.rubrieknaam, count(R.rubrieknaam) as 'Hoeveelheid verkochte items',YEAR(looptijdbeginDag) as jaar,month( looptijdbeginDag) as 'maand'
    from Voorwerp V
	inner join voorwerp_In_Rubriek VIR
on V.voorwerpNummer = VIR.voorwerpNummer
inner join Rubriek R
on R.rubrieknummer = VIR.rubrieknummer
where verkoopprijs is not null
group by YEAR(looptijdbeginDag),datename(month, looptijdbeginDag), R.rubrieknaam, looptijdbeginDag 