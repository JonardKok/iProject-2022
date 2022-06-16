DECLARE @currentBatch INT = 7
DECLARE @instanceCount INT = 8
DECLARE @rowsPerBatch BIGINT = (SELECT count(*)/@instanceCount FROM Voorwerp)
DECLARE @currentInstance INT = 0
WHILE @currentInstance < @rowsPerBatch AND @currentBatch < @instanceCount
BEGIN
	UPDATE [dbo].[voorwerp] SET beschrijving = [dbo].[fn_clearHtml]([dbo].[fn_clearStyle] ([dbo].[fn_clearJavascript]([dbo].[fn_clearNoScript] (Beschrijving)))) WHERE voorwerpNummer = [dbo].[FN_getItemID](@currentBatch, @instanceCount, @currentInstance, @rowsPerBatch)
	SET @currentInstance = @currentInstance + 1
END
