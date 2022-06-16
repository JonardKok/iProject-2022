DECLARE @k INT = 0
DECLARE @amountpassed INT = 0
DECLARE @userIteration INT = 1
DECLARE @userName CHAR (40);
WHILE @k < 6
BEGIN
    DECLARE @j INT = 1
    WHILE @j < 13
    BEGIN
        DECLARE @RANDOM INT = FLOOR(RAND()*(100-5+1)+5);
        DECLARE @i INT = 0
        WHILE @i < @random
        BEGIN
            SET @i = @i + 1
            SET @userIteration = @userIteration + 1
            SET @userName = TRIM('Johnny' + TRIM(CAST(@userIteration AS CHAR)))
	        INSERT INTO [dbo].[Gebruiker]
            (
                gebruikersnaam, voornaam, achternaam, adresregel_1, adresregel_2, postcode, plaatsnaam,
                land, geboortedag, mailbox, wachtwoord, vraagnummer, antwoordtekst, verkoperstatus, aanmaakdatum
            )
            VALUES
                (
                    @userName, 'John', 'Doe', 'eenmaalandermaalweg 1', 'B', '1234ab' , 'eenmaalandermaaldorp',
                    'Nederland', concat('01/01/',1910 + FLOOR(RAND()*(100-5+1)+5)), ('woi' + TRIM(CAST(@userIteration AS CHAR)) + '@eenmaalandermaal.nl'), 'EncryptedPassword', 1, 'hond', 1, concat(concat(concat(concat( '',@j),'/7' ), '/202'), @k)
                )
            INSERT INTO [dbo].[Gebruikerstelefoon]
            (
                gebruikersnaam, telefoon
            ) 
            VALUES
            (
                @userName, N'06123456789'
            )
            INSERT INTO [dbo].[Verkoper] 
            (
                gebruikersnaam, banknaam, rekeningnummer, controleoptie
            )
            VALUES
                (
                    @userName, N'Rabobank', 1, N'post'
                )
	        INSERT INTO [dbo].[Voorwerp] 
            (
                titel, beschrijving, startprijs, betalingswijze, plaatsnaam, land, looptijd, looptijdbeginDag,
                looptijdbeginTijdstip, verkopernaam, verkoopprijs
            )
            VALUES
                (
                    'voorbeelddata', 's', 12, 'contant', 'Barneveld','Nederland',10,concat(concat(concat(concat( '',@j),'/7' ), '/202'),@k),'10:10', @userName, 1
                )

            INSERT INTO [dbo].[Voorwerp_In_Rubriek]
            (
                voorwerpNummer, rubrieknummer
            ) 
            VALUES
                (
                    (400809934427 + @amountpassed), 20253
                )
            SET @amountpassed = @amountpassed + 1
	        INSERT INTO [dbo].[Voorwerp] 
            (
                titel, beschrijving, startprijs, betalingswijze, plaatsnaam, land, looptijd, looptijdbeginDag,
                looptijdbeginTijdstip, verkopernaam
            ) 
            VALUES
                (
                    'voorbeelddata', 's', 12, 'contant', 'Barneveld', 'Nederland', 10, concat(concat(concat(concat( '' , @j), '/7' ), '/202'), @k), '10:10', @userName
                )
            INSERT INTO [dbo].[Voorwerp_In_Rubriek] 
            (
                voorwerpNummer, rubrieknummer
            ) 
            VALUES
                (
                    (400809934427 + @amountpassed),20253
                )
            SET @amountpassed = @amountpassed + 1
        END
        SET @j = @j + 1
    END
    SET @k = @k + 1
END
