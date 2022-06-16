--Add security questions.
INSERT INTO vraag (tekstvraag)
VALUES 
(N'Wat is de naam van je eerste huisdier'), 
(N'Wat is de koosnaam van je moeder'), 
(N'Wat is de naam van je vader'), 
(N'Wat is de naam van je oma'), 
(N'Wat is de naam van je opa') 
GO

--Add Main-Administrator account
DECLARE @mainAdmin CHAR (40) = N'iProject36_administrator'
INSERT INTO Gebruiker VALUES (@mainAdmin, N'Hans', N'Hansmakker', N'Professor Molkeboerstraat 3', NULL, N'6524RN', N'Nijmegen', 
N'Nederland', N'01/01/1970', N'iproject36@eenmaalandermaal.com', N'Reset password!', 1, N'Dopper', 0, 1, GETDATE(), 0, 0);
UPDATE Gebruiker SET verkoperstatus = 1 WHERE beheerderstatus = 1;
INSERT INTO Verkoper VALUES(@mainAdmin, N'Rabobank', N'NL98RABO0123456789', N'creditcard', 100000);
INSERT INTO Gebruikerstelefoon (gebruikersnaam, telefoon) VALUES (@mainAdmin, N'06123456789');
GO

--Add product owner account
DECLARE @poAccount CHAR (40) = N'docentgebruikersnaam'
INSERT INTO Gebruiker VALUES (@poAccount, N'Jorg', N'Visch', N'Professor Molkeboerstraat 3', NULL, N'6524RN', N'Nijmegen', N'Nederland', N'01/01/1970', 
N'Admin@veilingsite.nl', N'Reset password!', 1, N'guppie', 0, 1, GETDATE(), 0, 0);
UPDATE Gebruiker SET verkoperstatus = 1 WHERE gebruikersnaam = @poAccount;
INSERT INTO Verkoper VALUES(@poAccount, N'Rabobank', 1, N'post', NULL);
INSERT INTO Gebruikerstelefoon (gebruikersnaam, telefoon) VALUES (@poAccount, N'06123456789');
GO

--Add account for replaced
DECLARE @deletedUsername CHAR (40) = N'DELETED_USER_USERNAME'
INSERT INTO Gebruiker VALUES (@deletedUsername, N'DELETED_USER_FIRSTNAME', N'DELETED_USER_LASTNAME', N'DELETED_USER_ADDRESS1', 
N'DELETED_USER_ADDRESS2', N'DELETED_USER_ZIPCODE', N'DELETED_USER_CITY', N'DELETED USER COUNTRY', N'01/01/1970', N'DELETED_USER_MAIL', 
N'DELETED_USER_PASSWORD', 1, N'DELETED_USER_SECURITY_ANSWER', 0, 1, GETDATE(), 0, 0);
UPDATE Gebruiker SET verkoperstatus = 1 WHERE gebruikersnaam = @deletedUsername;
INSERT INTO Verkoper VALUES(@deletedUsername, N'DELETED_USER_', 1, N'DELETED_USER_CHECK', NULL);
INSERT INTO Gebruikerstelefoon (gebruikersnaam, telefoon) VALUES (@deletedUsername, N'DELETED_USER_PHONE_NUMBER');
GO

CREATE FUNCTION [dbo].[FN_getDeletedUser]()
RETURNS BIT
AS
BEGIN
	DECLARE @deletedUserSellerStatus BIT = (SELECT verkoperstatus FROM gebruiker WHERE gebruikersnaam = 'DELETED_USER_USERNAME');
	RETURN @deletedUserSellerStatus;
END;
GO	
--Deleted user check
ALTER TABLE [dbo].[gebruiker] WITH CHECK ADD CONSTRAINT [CHK_DEL] 
CHECK([dbo].[FN_getDeletedUser]() = 1)
GO