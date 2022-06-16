CREATE FUNCTION [dbo].[fn_setDeliveryFee]
(
    @prijs NUMERIC (10,2)  
)
RETURNS NUMERIC (6,2) AS
BEGIN 
    DECLARE @duurProduct TINYINT = (100)
    IF @prijs > @duurProduct
    BEGIN 
        RETURN 5.00
    END
    IF @prijs < @duurProduct
    BEGIN 
        RETURN 2.00
    END
RETURN 5
END
GO

--Insert landen
INSERT INTO [dbo].[Landen]
(
    landnaam, gba_code, begindatum, einddatum, eerlid
)
SELECT CAST(NAAM_LAND AS NVARCHAR (56)), CAST(GBA_CODE AS NCHAR (4)), CAST(BEGINDATUM AS DATE), CAST(EINDDATUM AS DATE), CAST(EER_Lid AS BIT)
FROM [dbo].[tblIMAOLand]
ORDER BY NAAM_LAND ASC
GO


-- Insert rubrieken
INSERT INTO [dbo].[Rubriek]
(
    rubrieknummer, rubrieknaam, rubriekparent, volgnr
)
SELECT CAST(ID AS INT), CAST(Name AS NVARCHAR (30)), CAST(Parent AS INT), CAST(ID AS INT)
FROM [dbo].[Categorieen]
ORDER BY ID ASC, Parent ASC
GO

--Insert producten
SET IDENTITY_INSERT Voorwerp ON
INSERT INTO [dbo].[Voorwerp]
(
    voorwerpNummer, titel, beschrijving, startprijs, betalingswijze, plaatsnaam, land, looptijd, looptijdbeginDag, looptijdbeginTijdstip, verzendkosten, verkopernaam
)
SELECT ID, Titel, Beschrijving, Prijs, 'creditcard', 'Nijmegen' , Locatie, 10, GETDATE(), CURRENT_TIMESTAMP, [dbo].[fn_setDeliveryFee](Prijs), 'docentgebruikersnaam'
FROM [dbo].[Items]
GO

--Insert producten
INSERT INTO [dbo].[Bestand]
(
    filenaam,
    voorwerpNummer
)
SELECT IllustratieFile, ItemID
FROM [dbo].[Illustraties]
WHERE ItemID IN (
SELECT ItemID
FROM [dbo].[Illustraties]
GROUP BY ItemID
HAVING count(ItemID) <=4
)
GO

--Insert rubrieken
INSERT INTO [dbo].[Voorwerp_In_Rubriek]
(
    voorwerpNummer,
    rubrieknummer
)
SELECT ID, Categorie
FROM [dbo].[Items]
GO

drop table Illustraties
drop table Items
drop table tblIMAOLand
drop table Users
drop table Categorieen
GO
