ALTER TABLE [datadump].[dbo].[illustraties]
DROP CONSTRAINT ItemsVoorPlaatje
GO

ALTER TABLE [datadump].[dbo].[Items]
DROP CONSTRAINT FK_Items_In_Categorie
GO

ALTER TABLE [dbo].[illustraties]
DROP CONSTRAINT ItemsVoorPlaatje
GO

ALTER TABLE [dbo].[Items]
DROP CONSTRAINT FK_Items_In_Categorie
GO