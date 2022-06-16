
create VIEW VWvoorwerponverkochteitemspercategorie as
WITH  nodes AS (
         SELECT R.rubrieknummer as id, COALESCE(rubriekparent, 0) AS idParentCategory
              , sum(case when verkoopprijs is null and looptijdeindeDag < GETDATE() then 1 else null end) as sumSubtotal, month( looptijdbeginDag) as maand, YEAR(looptijdbeginDag) as jaar
           FROM Rubriek R
		   left outer join Voorwerp V
		   left outer join Voorwerp_In_Rubriek VIR
		   on V. voorwerpNummer = VIR.voorwerpnummer
		   on VIR.rubrieknummer = R.rubrieknummer
		   group by YEAR(looptijdbeginDag),datename(month, looptijdbeginDag), R.rubrieknummer, rubriekparent, verkoopprijs, looptijdbeginDag
          UNION
         SELECT -1, null, 0, 1, '2010'
     )
   , cte AS (
        SELECT t.*, t.id AS root
             , idParentCategory AS idParentCategory0
             , sumSubtotal      AS sumSubtotal0
             , maand      AS maand0
             , jaar      AS jaar0
          FROM nodes AS t
         UNION ALL
        SELECT t.* , t0.root
             , t0.idParentCategory0
             , t0.sumSubtotal0
             , t0.maand0
			 , t0.jaar0
          FROM cte AS t0
          JOIN nodes AS t
            ON t.idParentCategory = t0.id
     )
SELECT root
     , MIN(NULLIF(idParentCategory0,0))   AS idParentCategory
     , MIN(sumSubtotal0)        AS sumSubtotal
     , SUM(t1.sumSubtotal)      AS total,
	 jaar as jaar,
	 maand as maand,
	 rubrieknaam
  FROM cte AS t1
  inner join Rubriek R
  on  R.rubrieknummer = t1.root
 where idParentCategory != 0 and jaar is not null
 GROUP BY jaar,maand,root, rubrieknaam
