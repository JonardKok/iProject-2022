--tables
drop table Voorwerp_In_Rubriek
drop table Bestand
drop table Bod
drop table Voorwerp
drop table verkoper
drop table Gebruikerstelefoon
drop table pre_deleted_users
drop table verificatiecode
drop table gebruiker
drop table landen
drop table rubriek
GO
--drop table test
drop table Vraag

--functies
drop function fnCheckVerkoper
drop function fnCheckBeheerder
drop function fnAfbeeldingsLimiet
drop function fnNieuwBod
drop function fnGetSellerName
drop function FN_getuserDeletionStatus
drop function [dbo].[fn_clearJavascript]
drop function [dbo].[fn_clearStyle]
drop function [dbo].[fn_setDeliveryFee]
drop function [dbo].[fn_clearHtml] 
drop function [dbo].[fn_clearNoScript] 
drop function [dbo].[FN_getItemID] 
drop function [dbo].[FN_getDeletedUser]
drop function [dbo].[FN_getuserDeletionStatus]
drop function [dbo].[fn_setDeliveryFee]
GO
--views
drop view VWleeftijd
drop view VWaantalrubrieken
drop view VWaanmeldingen
drop view VWaantalgeslaagdeveilingen
drop view VWvoorwerponverkochteitemspercategorie
drop view VWaantalverkocht
GO

--TRIGGER
drop trigger TR_minimaleBodhoogte
GO