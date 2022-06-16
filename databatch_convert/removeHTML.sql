CREATE FUNCTION [dbo].[fn_clearHtml] 
(
    @descriptionHtml NVARCHAR(MAX)
)
RETURNS NVARCHAR(MAX) AS
BEGIN
    DECLARE @Start INT = (CHARINDEX('<',@descriptionHtml))
    DECLARE @End INT = (CHARINDEX('>',@descriptionHtml,CHARINDEX('<',@descriptionHtml)))
    DECLARE @Length INT = ((@End - @Start) + 1)
    WHILE @Start > 0 AND @End > 0 AND @Length > 0
    BEGIN
        SET @descriptionHtml = STUFF(@descriptionHtml,@Start,@Length,'')
        SET @Start = CHARINDEX('<',@descriptionHtml)
        SET @End = CHARINDEX('>',@descriptionHtml,CHARINDEX('<',@descriptionHtml))
        SET @Length = (@End - @Start) + 1
    END
    RETURN LTRIM(RTRIM(@descriptionHtml))
END
GO   

CREATE FUNCTION [dbo].[fn_clearStyle] 
(
    @descriptionHtml NVARCHAR(MAX)
)
RETURNS NVARCHAR(MAX) AS
BEGIN
    DECLARE @Start INT = (CHARINDEX('<style',@descriptionHtml))
    DECLARE @End INT = (CHARINDEX('/style>',@descriptionHtml))
    DECLARE @Length INT = ((@End - @Start) + 1)
    WHILE @Start > 0 AND @End > 0 AND @Length > 0
    BEGIN
        SET @descriptionHtml = STUFF(@descriptionHtml,@Start,@Length + 6,'')
        SET @Start = CHARINDEX('<style',@descriptionHtml)
        SET @End = CHARINDEX('/style>',@descriptionHtml)
        SET @Length = (@End - @Start) + 1
    END
    RETURN LTRIM(RTRIM(@descriptionHtml))
END
GO

CREATE FUNCTION [dbo].[fn_clearJavascript] 
(
    @descriptionHtml NVARCHAR(MAX)
)
RETURNS NVARCHAR(MAX) AS
BEGIN
    DECLARE @Start INT = (CHARINDEX('<script',@descriptionHtml))
    DECLARE @End INT = (CHARINDEX('/script>',@descriptionHtml))
    DECLARE @Length INT = ((@End - @Start)) 
    WHILE @Start > 0 AND @End > 0 AND @Length > 0
    BEGIN
        SET @descriptionHtml = STUFF(@descriptionHtml,@Start,@Length + 7,'')
        SET @Start = CHARINDEX('<script',@descriptionHtml)
        SET @End = CHARINDEX('/script>',@descriptionHtml)
        SET @Length = (@End - @Start) + 1
    END
    RETURN LTRIM(RTRIM(@descriptionHtml))
END
GO

CREATE FUNCTION [dbo].[fn_clearNoScript] 
(
    @descriptionHtml NVARCHAR(MAX)
)
RETURNS NVARCHAR(MAX) AS
BEGIN
    DECLARE @Start INT = (CHARINDEX('<noscript',@descriptionHtml))
    DECLARE @End INT = (CHARINDEX('/noscript>',@descriptionHtml))
    DECLARE @Length INT = ((@End - @Start)) 
    WHILE @Start > 0 AND @End > 0 AND @Length > 0
    BEGIN
        SET @descriptionHtml = STUFF(@descriptionHtml,@Start,@Length + 9,'')
        SET @Start = CHARINDEX('<noscript',@descriptionHtml)
        SET @End = CHARINDEX('/noscript>',@descriptionHtml)
        SET @Length = (@End - @Start) + 1
    END
    RETURN LTRIM(RTRIM(@descriptionHtml))
END
GO

CREATE FUNCTION [dbo].[FN_getItemID] 
(
	@currentBatch INT,
	@maxBatches INT, 
	@currentInstance INT, 
	@maxRows BIGINT
)

RETURNS BIGINT AS
BEGIN
	DECLARE @rowNumberStart BIGINT = (@currentBatch * @maxRows+1)
	DECLARE @rowNumberEnd BIGINT = ((@currentBatch+1)*@maxRows)
	DECLARE @rowNumber BIGINT = @rowNumberStart + @currentInstance
	SET @currentInstance = @currentInstance + 1
	IF @rowNumber <= @rowNumberEnd AND @rowNumber >= @rowNumberStart
	BEGIN
		DECLARE @itemId BIGINT = (SELECT voorwerpNummer
		FROM (SELECT ROW_NUMBER() OVER (ORDER BY voorwerpNummer ASC) AS rownumber, voorwerpNummer FROM voorwerp) item 
		WHERE item.rownumber = @rowNumber)
		RETURN @itemId
	END
	RETURN 0
END
GO

