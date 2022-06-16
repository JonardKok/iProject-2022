/****** Object:  Table [dbo].[Bestand] ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[Bestand](
    [filenaam]          [NVARCHAR]      (30)    NOT NULL,   
	[voorwerpNummer]    [BIGINT]                NOT NULL,       
    CONSTRAINT          [PK_Filename]           PRIMARY KEY 
(
	[filenaam] ASC
)
) ON [PRIMARY]
GO


/****** Object:  Table [dbo].[Bod] ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[Bod] (
[voorwerpnummer]    BIGINT             NOT NULL,
[Bodbedrag]         NUMERIC    (10,2)  NOT NULL,
[Gebruikersnaam]    NCHAR      (40)    NOT NULL,
[BodDag]            DATE               NOT NULL,
[BodTijdStip]       TIME       (0)     NOT NULL,
CONSTRAINT [PK_bod]                    PRIMARY KEY 
(
    [voorwerpnummer] ASC,
    [Bodbedrag] ASC,
    [Gebruikersnaam] ASC
)
) ON [PRIMARY]
GO


/****** Object:  Table [dbo].[Gebruiker] ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[Gebruiker] (
    [gebruikersnaam]    [NCHAR]             (40)            NOT NULL, 
    [voornaam]          [NVARCHAR]          (120)           NOT NULL, --Van 60 naar 120
    [achternaam]        [NVARCHAR]          (120)           NOT NULL, --Van 60 naar 120
    [adresregel_1]      [NVARCHAR]          (84)            NOT NULL, --Van 35 naar 84
    [adresregel_2]      [NVARCHAR]          (84)            NULL,     --Van 35 naar 84
    [postcode]          [NVARCHAR]          (52)            NOT NULL, --Van 10 naar 52
    [plaatsnaam]        [NVARCHAR]          (80)            NOT NULL, --Van 30 naar 80
    [land]              [NVARCHAR]          (56)            NOT NULL, 
    [geboortedag]       [DATE]                              NOT NULL, 
    [mailbox]           [NVARCHAR]          (140)           NOT NULL, --Van 75 naar 140
    [wachtwoord]        [NVARCHAR]          (255)           NOT NULL, 
    [vraagnummer]       [TINYINT]                           NOT NULL, 
    [antwoordtekst]     [NVARCHAR]          (172)           NOT NULL, --Van 100 naar 172
    [verkoperstatus]    [BIT]       DEFAULT (0)             NOT NULL, 
    [beheerderstatus]   [BIT]       DEFAULT (0)             NOT NULL,
    [aanmaakdatum]      [DATE]      DEFAULT (getdate())     NOT NULL,
    [geblokkeerd]       [BIT]       DEFAULT (0)             NOT NULL,
    [verwijderd]        [BIT]       DEFAULT (0)             NOT NULL,
    CONSTRAINT          [PK_gebruikersnaam] PRIMARY KEY 
    (
	    [gebruikersnaam] ASC
    )
) ON [PRIMARY]
GO


/****** Object:  Table [dbo].[Gebruikerstelefoon] ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[Gebruikerstelefoon] (
    [volgnr]            INT IDENTITY        (1, 1)  NOT NULL, 
    [gebruikersnaam]    NCHAR				(40)    NOT NULL, 
    [telefoon]          NVARCHAR            (56)    NOT NULL, -- Van 14 naar 56
    CONSTRAINT          [PK_volgnummernaam]         PRIMARY KEY 
    (
        [volgnr] ASC,
        [gebruikersnaam] ASC
    )
) ON [PRIMARY]
GO

/****** Object:  Table [dbo].[Rubriek] ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[Rubriek] (
    [rubrieknummer]     [INT]                           NOT NULL, 
    [rubrieknaam]       [NVARCHAR]           (30)       NOT NULL, 
    [rubriekparent]     [INT]                           NULL,     
    [volgnr]            [INT]                           NULL,  
    CONSTRAINT          [PK_rubrieknummer]              PRIMARY KEY
    (
        [rubrieknummer] ASC
    )
) ON [PRIMARY]
GO

/****** Object:  Table [dbo].[Verkoper] ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[Verkoper] (
    [gebruikersnaam]    [NCHAR]          (40)               NOT NULL,  
    [banknaam]          [NVARCHAR]       (92)               NULL, --Van 40 naar 92
    [rekeningnummer]    [NVARCHAR]       (84)               NULL, --Van 35 naar 84
    [controleoptie]     [NVARCHAR]       (20)               NOT NULL, 
    [creditcardnummer]  [NVARCHAR]       (64)               NULL, --Van 19 naar 64
    CONSTRAINT          [PK_verkoper]                       PRIMARY KEY 
    (
        [gebruikersnaam] ASC
    )
) ON [PRIMARY]
GO


/****** Object:  Table [dbo].[Voorwerp] ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[Voorwerp] (
    [voorwerpNummer]        BIGINT              IDENTITY		(1, 1)                                          NOT NULL, 
    [titel]                 [NVARCHAR]                          (150)                                           NOT NULL, 
    [beschrijving]          [NVARCHAR]                          (MAX)                                           NOT NULL, 
    [startprijs]            [NUMERIC]                           (10,2)                                          NOT NULL, 
    [betalingswijze]        [NVARCHAR]                          (10)                                            NOT NULL, 
    [betalingsinstructie]   [NVARCHAR]                          (100)                                           NULL,     
    [plaatsnaam]            [NVARCHAR]                          (30)                                            NOT NULL,
    [land]                  [NVARCHAR]                          (56)                                            NOT NULL, 
    [looptijd]              TINYINT             DEFAULT         (7)                                             NOT NULL, 
    [looptijdbeginDag]      [DATE]                                                                              NOT NULL, 
    [looptijdbeginTijdstip] [TIME]                              (0)                                             NOT NULL, 
    [verzendkosten]         [NUMERIC]                           (6,2)                                           NULL, 
    [verzendinstructies]    [NVARCHAR]                          (100)                                           NULL,  
    [verkopernaam]          [NCHAR]			                    (40)                                            NOT NULL,
    [kopernaam]             [NCHAR]                             (40)                                            NULL,
    [looptijdeindeDag]      AS                                  (dateadd(day, [looptijd], [looptijdbeginDag])),
    [looptijdeindeTijdstip] [TIME]                              (0)     DEFAULT (CONVERT(time, getdate()))      NOT NULL,
    [veilingGesloten]       [BIT]               DEFAULT         (0)                                             NOT NULL,
    [verkoopprijs]          [NUMERIC]                           (10,2)                                          NULL, 
    CONSTRAINT              [PK_voorwerpnummer]                                                                 PRIMARY KEY 
    (
        [voorwerpNummer] ASC
    )
) ON [PRIMARY]
GO


/****** Object:  Table [dbo].[Voorwerp_In_Rubriek] ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[Voorwerp_In_Rubriek] (
	[voorwerpNummer]        BIGINT                      NOT NULL, 
    [rubrieknummer]         INT                         NOT NULL, 
    CONSTRAINT [PK_RubriekVoorwerp]                     PRIMARY KEY
    (
        [voorwerpNummer] ASC, 
        [rubrieknummer] 
    )
) ON [PRIMARY]
GO

/****** Object:  Table [dbo].[Pre_deleted_users] ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[Pre_deleted_users] (
    [gebruikersnaam]                [NCHAR]         (40)            NOT NULL,
    [aanvraag_datum]                [DATE]                          NOT NULL,
    [minimale_uitvoerdatum]         AS                              (dateadd(day, 10, [aanvraag_datum])),
    CONSTRAINT [PK_deleteduser]                                     PRIMARY KEY 
    (
        [gebruikersnaam] ASC
    )
) ON [PRIMARY]
GO


/****** Object:  Table [dbo].[VerificatieCode] ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[VerificatieCode] (
	[Verificatiecode]               NCHAR        (100)      NOT NULL, 
    [Aanmaakdatum]                  DATETIME                NOT NULL, 
    [Gebruikersnaam]                NCHAR        (40)       NULL,
    [type]                          NVARCHAR     (10)       NULL,
    CONSTRAINT [PK_verificatiecode]                         PRIMARY KEY
    (
        [VerificatieCode] ASC, 
        [Aanmaakdatum] ASC
    )
) ON [PRIMARY]
GO


/****** Object:  Table [dbo].[Landen] ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[Landen] (
[landnaam]      NVARCHAR        (56)    NOT NULL,
[gba_code]      NCHAR           (4)     NOT NULL,
[begindatum]    DATE                    NULL,
[einddatum]     DATE                    NULL,
[eerlid]        BIT DEFAULT (0)         NOT NULL,
CONSTRAINT [PK_LAND]                    PRIMARY KEY 
(
    [landnaam] ASC
)
) ON [PRIMARY]
GO


/****** Object:  Table [dbo].[Vraag] ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[Vraag] (
    [vraagnummer]   TINYINT IDENTITY        (1, 1)      NOT NULL,
    [tekstvraag]    [NVARCHAR]              (100)       NOT NULL,
    CONSTRAINT      [PK_vraagnummer]                    PRIMARY KEY  
	(
		[vraagnummer] ASC
	)
)

--Foreign keys.
--Bestand
GO
ALTER TABLE [dbo].[Bestand]  WITH CHECK ADD CONSTRAINT [FK_bestand_voorwerpnummer] FOREIGN KEY([voorwerpNummer])
REFERENCES [dbo].[Voorwerp] ([voorwerpNummer])
ON UPDATE CASCADE
ON DELETE CASCADE
GO


--BOD
GO
ALTER TABLE [dbo].[bod]  WITH CHECK ADD CONSTRAINT [FK_bod_voorwerpnummer] FOREIGN KEY([voorwerpNummer])
REFERENCES [dbo].[Voorwerp] ([voorwerpNummer])
ON UPDATE CASCADE
ON DELETE NO ACTION
GO

GO
ALTER TABLE [dbo].[bod]  WITH CHECK ADD CONSTRAINT [FK_bod_gebruikersnaam] FOREIGN KEY([gebruikersnaam])
REFERENCES [dbo].[Gebruiker] ([gebruikersnaam])
ON UPDATE CASCADE
ON DELETE NO ACTION
GO

--GEBRUIKER
GO
ALTER TABLE [dbo].[Gebruiker]  WITH CHECK ADD CONSTRAINT [FK_Gebruiker_Vraagnummer] FOREIGN KEY([vraagnummer])
REFERENCES [dbo].[Vraag] ([vraagnummer])
ON UPDATE CASCADE
ON DELETE CASCADE
GO

--GEBRUIKERSTELEFOON
GO
ALTER TABLE [dbo].[Gebruikerstelefoon]  WITH CHECK ADD CONSTRAINT [FK_Gebruikerstelefoon_Gebruikersnaam] FOREIGN KEY([gebruikersnaam])
REFERENCES [dbo].[Gebruiker] ([gebruikersnaam])
ON UPDATE CASCADE
ON DELETE CASCADE
GO

--RUBRIEK
GO
ALTER TABLE [dbo].[Rubriek]  WITH CHECK ADD CONSTRAINT [FK_Rubriek_Rubrieknummer] FOREIGN KEY([rubriekparent])
REFERENCES [dbo].[Rubriek] ([rubrieknummer])
ON UPDATE NO ACTION
ON DELETE NO ACTION
GO

--VERKOPER
GO
ALTER TABLE [dbo].[Verkoper]  WITH CHECK ADD CONSTRAINT [FK_Verkoper_gebruikersnaam] FOREIGN KEY([gebruikersnaam])
REFERENCES [dbo].[Gebruiker] ([gebruikersnaam])
ON UPDATE CASCADE
ON DELETE CASCADE
GO


--VOORWERP
GO
ALTER TABLE [dbo].[Voorwerp]  WITH CHECK ADD CONSTRAINT [FK_Voorwerpverkoper] FOREIGN KEY([verkopernaam])
REFERENCES [dbo].[Verkoper] ([gebruikersnaam])
ON UPDATE NO ACTION
ON DELETE NO ACTION
GO


GO
ALTER TABLE [dbo].[Voorwerp]  WITH CHECK ADD CONSTRAINT [FK_gebruikersnaam_koper] FOREIGN KEY([verkopernaam])
REFERENCES [dbo].[Gebruiker] ([gebruikersnaam])
ON UPDATE NO ACTION
ON DELETE NO ACTION
GO

--VOORWERP IN RUBRIEK
GO 
ALTER TABLE [dbo].[Voorwerp_In_Rubriek] WITH CHECK ADD CONSTRAINT [FK_voorwerp_in_rubriek_voorwerp] FOREIGN KEY([voorwerpnummer])
REFERENCES [dbo].[Voorwerp] ([voorwerpnummer])
ON UPDATE NO ACTION
ON DELETE NO ACTION
GO

GO 
ALTER TABLE [dbo].[Voorwerp_In_Rubriek] WITH CHECK ADD CONSTRAINT [FK_voorwerp_in_rubriek_rubrieknummer] FOREIGN KEY([rubrieknummer])
REFERENCES [dbo].[Rubriek] ([rubrieknummer])
ON UPDATE NO ACTION
ON DELETE NO ACTION
GO

--Pre_deleted_users
GO
ALTER TABLE [dbo].[Pre_deleted_users] WITH CHECK ADD CONSTRAINT [FK_deleted_user_name] FOREIGN KEY ([gebruikersnaam])
REFERENCES [dbo].[gebruiker] ([gebruikersnaam])
ON UPDATE NO ACTION
ON DELETE NO ACTION
GO

--Functions
CREATE FUNCTION [dbo].[fnCheckVerkoper]
(
    @gebruikersnaam char(40)    
) 
RETURNS BIT 
AS
BEGIN
    DECLARE @verkoperStatus BIT = (SELECT verkoperstatus FROM gebruiker WHERE gebruikersnaam = @gebruikersnaam);
    RETURN @verkoperStatus;
END;
GO

CREATE FUNCTION [dbo].[fnCheckBeheerder]
(
    @gebruikersnaam char(40)
)
RETURNS BIT
AS
BEGIN
    DECLARE @beheerderStatus BIT = (SELECT beheerderstatus FROM gebruiker WHERE gebruikersnaam = @gebruikersnaam)
    RETURN @beheerderStatus;
END;
GO


CREATE FUNCTION [dbo].[fnAfbeeldingsLimiet]
(
    @voorwerpNummer BIGINT
)
RETURNS TINYINT
AS
BEGIN
    DECLARE @aantalAfbeeldingen TINYINT = (SELECT count(filenaam) FROM Bestand WHERE voorwerpnummer = @voorwerpNummer);
    RETURN @aantalAfbeeldingen;
END;
GO

CREATE FUNCTION [dbo].[fnNieuwBod] 
(
    @voorwerp BIGINT
)
RETURNS NUMERIC (10,2)
AS 
BEGIN 
    DECLARE @bod NUMERIC (10,2) = (SELECT TOP 1 Bodbedrag from Bod where voorwerpnummer = @voorwerp ORDER BY Bodbedrag DESC);
    RETURN @bod;
END;
GO

CREATE FUNCTION [dbo].[fnGetSellerName]
(
    @voorwerpNummer BIGINT
)
RETURNS NCHAR(40)
AS 
BEGIN 
    DECLARE @verkoper NCHAR(40) = (SELECT verkopernaam FROM voorwerp where voorwerpnummer = @voorwerpnummer)
    RETURN @verkoper;
END;
GO

CREATE FUNCTION [dbo].[FN_getuserDeletionStatus]
(
    @gebruikersnaam NCHAR (40)
)
RETURNS BIT
AS
BEGIN
    DECLARE @status BIT = (SELECT verwijderd FROM gebruiker WHERE gebruikersnaam = @gebruikersnaam)
    RETURN @status 
END;
GO



--Checks-----------
ALTER TABLE [dbo].[Landen] ADD CONSTRAINT [UQ_GBACODE]
UNIQUE ([gba_code])
GO

ALTER TABLE [dbo].[Landen] WITH CHECK ADD CONSTRAINT [CHK_gbaCodeLengte] 
CHECK ( LEN(gba_code) = 4 )
GO
ALTER TABLE [dbo].[Landen] WITH CHECK ADD CONSTRAINT [CHK_beginVoorEindDatum]
CHECK ( begindatum < einddatum )
GO
--B1
ALTER TABLE [dbo].[Verkoper] WITH CHECK ADD CONSTRAINT [CHK_isVerkoper] 
CHECK([dbo].[fnCheckVerkoper](gebruikersnaam) = 1)
GO

--B2
ALTER TABLE [dbo].[Verkoper] WITH CHECK ADD CONSTRAINT [CHK_Creditcard] 
CHECK(controleoptie = 'creditcard' AND creditcardnummer IS NOT NULL OR controleoptie != 'creditcard' AND creditcardnummer IS NULL OR controleoptie != 'creditcard' AND creditcardnummer IS NOT NULL)
GO

--B3
ALTER TABLE [dbo].[Verkoper] WITH CHECK ADD CONSTRAINT [CHK_Betaalgegevens] 
CHECK(rekeningnummer IS NOT NULL OR creditcardnummer IS NOT NULL)
GO

--B4
ALTER TABLE [dbo].[Bestand] WITH CHECK ADD CONSTRAINT [CHK_afbeeldinglimiet] 
CHECK([dbo].[fnAfbeeldingsLimiet](voorwerpNummer) <=4 )
GO

--B6
ALTER TABLE [dbo].[Bod] WITH CHECK ADD CONSTRAINT [CHK_Bod] 
CHECK([dbo].[fnGetSellerName](voorwerpNummer) <> gebruikersnaam)
GO

--Pre_deleted_users
ALTER TABLE [dbo].[Pre_deleted_users] WITH CHECK ADD CONSTRAINT [CHK_deleteStatus]
CHECK ([dbo].[FN_getuserDeletionStatus](gebruikersnaam) = 1)
GO

--Triggers------------
--B5
CREATE TRIGGER [dbo].[TR_minimaleBodhoogte] ON [dbo].[Bod]
FOR UPDATE, INSERT
AS
BEGIN
    DECLARE @productNummer BIGINT = (SELECT voorwerpnummer FROM INSERTED);
    DECLARE @nieuwBodBedrag NUMERIC (10,2) = (SELECT Bodbedrag FROM INSERTED WHERE voorwerpnummer = @productNummer);
    DECLARE @oudBodBedrag NUMERIC (10,2) = (SELECT TOP 1 Bodbedrag FROM Bod B INNER JOIN Gebruiker G on B.Gebruikersnaam = G.gebruikersnaam WHERE G.geblokkeerd = 0 AND G.verwijderd = 0 AND Bodbedrag != @nieuwBodBedrag AND voorwerpnummer = @productNummer ORDER BY Bodbedrag DESC);
    IF @oudBodBedrag >= 1.0 OR @oudBodBedrag >= 1
    BEGIN
        IF @oudBodBedrag >= @nieuwBodBedrag
        BEGIN
            ROLLBACK;
        END;
        --Tussen de 1,00 en 49,99
        IF @nieuwBodBedrag > 1.00 AND @nieuwBodBedrag < 50.00 AND @nieuwBodBedrag - @oudBodBedrag < 0.50
        BEGIN
            ROLLBACK;
        END;
        --Tussen de 50,00 en 500,00
        IF @nieuwBodBedrag > 49.99 AND @nieuwBodBedrag < 500 AND @nieuwBodBedrag - @oudBodBedrag < 1.00
        BEGIN
            ROLLBACK;
        END;
        --Tussen de 500,00 en 1000,00
        IF  @nieuwBodBedrag > 500.00 AND @nieuwBodBedrag < 1000 AND @nieuwBodBedrag - @oudBodBedrag < 5.00
        BEGIN
            ROLLBACK;
        END;
        --Tussen de 1000,00 en 5000,00
        IF @nieuwBodBedrag > 1000.00 AND @nieuwBodBedrag < 5000 AND @nieuwBodBedrag - @oudBodBedrag < 10.00
        BEGIN
            ROLLBACK;
        END;
        --5000,00 of meer
        IF @nieuwBodBedrag > 5000.00 AND @nieuwBodBedrag - @oudBodBedrag < 50.00
        BEGIN           
            ROLLBACK;
        END;
    END;
END;
GO