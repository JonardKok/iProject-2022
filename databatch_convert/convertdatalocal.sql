USE eenmaalandermaal
GO


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
PRINT N'Adding countries'; 
GO 
INSERT INTO [dbo].[Landen]
(
    landnaam, gba_code, begindatum, einddatum, eerlid
)
SELECT CAST(NAAM_LAND AS NVARCHAR (56)), CAST(GBA_CODE AS NCHAR (4)), CAST(BEGINDATUM AS DATE), CAST(EINDDATUM AS DATE), CAST(EER_Lid AS BIT)
FROM [datadump].[dbo].[tblIMAOLand]
ORDER BY NAAM_LAND ASC
GO


-- Insert rubrieken
PRINT N'Adding rubrics';  
GO
INSERT INTO [dbo].[Rubriek]
(
    rubrieknummer, rubrieknaam, rubriekparent, volgnr
)
SELECT CAST(ID AS INT), CAST(Name AS NVARCHAR (30)), CAST(Parent AS INT), CAST(ID AS INT)
FROM [datadump].[dbo].[Categorieen]
ORDER BY ID ASC, Parent ASC
GO

--Insert producten
PRINT N'Adding products';  
GO
SET IDENTITY_INSERT Voorwerp ON
INSERT INTO [dbo].[Voorwerp]
(
    voorwerpnummer, titel, beschrijving, startprijs, betalingswijze, plaatsnaam, land, looptijd, looptijdbeginDag, looptijdbeginTijdstip, verzendkosten, verzendinstructies, verkopernaam
)
SELECT ID, Titel, Beschrijving, Prijs, 'Bank', 'Nijmegen' , 'Nederland', 7, GETDATE(), CURRENT_TIMESTAMP, [dbo].[fn_setDeliveryFee](Prijs), 'Bij voorkeur ophalen, dan geen verzendkosten' ,'docentgebruikersnaam'
FROM [datadump].[dbo].[Items]
GO

--Insert producten
PRINT N'Adding product images';  
GO
INSERT INTO [dbo].[Bestand]
(
    filenaam,
    voorwerpNummer
)
SELECT IllustratieFile, ItemID
FROM [datadump].[dbo].[Illustraties]
WHERE ItemID IN (
SELECT ItemID
FROM [datadump].[dbo].[Illustraties]
GROUP BY ItemID
HAVING count(ItemID) <=4
)
GO

--Insert rubrieken
PRINT N'Adding product rubrics';  
GO
INSERT INTO [dbo].[Voorwerp_In_Rubriek]
(
    voorwerpNummer,
    rubrieknummer
)
SELECT ID, Categorie
FROM [datadump].[dbo].[Items]
GO