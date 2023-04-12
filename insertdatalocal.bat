@ECHO OFF
@REM %%D is folder
@REM %%F is filename
SET "dataBatchPath=%CD%\SQL\Batch-Verzamelen-Industrie"
sqlcmd /S IP_FROM_SERVER,1434 /d eenmaalandermaal -U sa -P abc123!@# -Q "SET NOCOUNT ON" -x
ECHO Adding eenmaalandermaal.
sqlcmd /S IP_FROM_SERVER,1434 /d eenmaalandermaal -U sa -P abc123!@# -i"%CD%\eenmaalandermaal\CREATE_EA.sql" -x
sqlcmd /S IP_FROM_SERVER,1434 /d eenmaalandermaal -U sa -P abc123!@# -i"%CD%\eenmaalandermaal\ADD_DBUSER_EA.sql" -x
sqlcmd /S IP_FROM_SERVER,1434 /d eenmaalandermaal -U sa -P abc123!@# -i"%CD%\eenmaalandermaal\INSERT_EA.sql" -x

ECHO Adding databatch tables.
START /min sqlcmd /S IP_FROM_SERVER,1434 /d datadump -U sa -P abc123!@# -i"%dataBatchPath%\GBALanden.sql" -x
sqlcmd /S IP_FROM_SERVER,1434 /d datadump -U sa -P abc123!@# -i"%dataBatchPath%\CREATE Tables.sql" -x
START /min sqlcmd /S IP_FROM_SERVER,1434 /d datadump -U sa -P abc123!@# -i"%CD%\databatch_convert\removeconstraints.sql" -x

ECHO Adding categories
sqlcmd /S IP_FROM_SERVER,1434 /d datadump -U sa -P abc123!@# -i"%dataBatchPath%\CREATE Categorieen.sql" -x


ECHO Adding databatch
for /d %%D in ("%dataBatchPath%\*") do (
    ECHO Directory: %%~nD
    for %%F in ("%%D\*.sql") do (
        ECHO Inserting %%~nF.sql
        START /min sqlcmd /S "IP_FROM_SERVER,1434" /d "datadump" -U "sa" -P "abc123!@#" -i"%%F" -x -f 65001
    )
)
ping -n 4 ::1 >nul 2>&1

:loop
CALL :checkRunningScripts
IF %runningScripts% == 1 (
    ECHO Converting databatch...
    :START
    sqlcmd /S IP_FROM_SERVER,1434 /d datadump -U sa -P abc123!@# -i"%CD%\databatch_convert\convertdatalocal.sql" -x 
    GOTO :samplepopulation
    GOTO :eof
)
ping -n 2 ::1 >nul 2>&1
GOTO loop
GOTO :eof

:checkRunningScripts
FOR /f "usebackq" %%t IN (`tasklist /fo csv /fi "imagename eq sqlcmd.exe"^|find /v /c ""`) DO SET runningScripts=%%t
GOTO :eof

:samplepopulation
ECHO Done converting databatch
GOTO :PROMPT
:PROMPT
SET /P SAMPLE=Do you want to insert a sample population Y/N?:
IF /I "%SAMPLE%" NEQ "Y" IF /I "%SAMPLE%" NEQ "N" GOTO PROMPT
SET /p Confirm=Are you sure? Y/N?: 
IF /I "%Confirm%" NEQ "Y" IF /I "%SAMPLE%" NEQ "N" GOTO PROMPT
IF /I "%Confirm%" EQU "N" IF /I "%SAMPLE%" EQU "Y" GOTO PROMPT
IF /I "%Confirm%" EQU "N" IF /I "%SAMPLE%" EQU "N" GOTO PROMPT
IF /I "%Confirm%" EQU "Y" IF /I "%SAMPLE%" EQU "Y" GOTO yes
IF /I "%Confirm%" EQU "Y" IF /I "%SAMPLE%" EQU "N" GOTO no
GOTO removeHTML

:no
ECHO Not inserting sample population
GOTO removeHTML

:yes
ECHO Inserting sample population... This may take a while!
sqlcmd /S IP_FROM_SERVER,1434 /d eenmaalandermaal -U sa -P abc123!@# -i"%CD%\eenmaalandermaal\INSERT_voorbeeldata.sql" -x
GOTO removeHTML


:removeHTML
@ECHO OFF
ECHO Adding convertfunctions
sqlcmd /S IP_FROM_SERVER,1434 /d eenmaalandermaal -U sa -P abc123!@# -i"%CD%\databatch_convert\removeHTML.sql" -x 
ECHO Removing HTML and javascript from descriptions, the site can be used while the script is running!
SET "instances=8"
FOR /L %%i IN (0, 1, %instances%) DO (
   START /min sqlcmd /S IP_FROM_SERVER,1434 /d eenmaalandermaal -U sa -P abc123!@# -Q "DECLARE @currentBatch INT = %%i DECLARE @instanceCount INT = %instances% DECLARE @rowsPerBatch BIGINT = (SELECT count(*)/@instanceCount FROM Voorwerp) DECLARE @currentInstance INT = 0 WHILE @currentInstance < @rowsPerBatch AND @currentBatch < @instanceCount BEGIN UPDATE [dbo].[voorwerp] SET beschrijving = [dbo].[fn_clearHtml]([dbo].[fn_clearStyle] ([dbo].[fn_clearJavascript]([dbo].[fn_clearNoScript] (Beschrijving)))) WHERE voorwerpNummer = [dbo].[FN_getItemID](@currentBatch, @instanceCount, @currentInstance, @rowsPerBatch) SET @currentInstance = @currentInstance + 1 END" -x 
)
ping -n 4 ::1 >nul 2>&1

:endCheck
CALL :checkRunningScripts
IF %runningScripts% == 1 (
    GOTO :END
)
ping -n 2 ::1 >nul 2>&1
GOTO endCheck
GOTO :EOF

:END
ECHO Finished executing the script