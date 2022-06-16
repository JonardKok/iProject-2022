@ECHO OFF
SET "dataBatchPath=%CD%\SQL\Batch-Verzamelen-Industrie"
SET /p HANPASSWD=<%CD%\applicatie\data\hanWachtwoord.env

@REM De eenmaalandermaal CREATE en INSERT scriptjes worden op de server uitgevoerd.
ECHO Adding eenmaalandermaal.
sqlcmd -S sql.ip.aimsites.nl -U iproject36 -P "%HANPASSWD%" -i "%CD%\eenmaalandermaal\CREATE_EA.sql" -x
sqlcmd -S sql.ip.aimsites.nl -U iproject36 -P "%HANPASSWD%" -i "%CD%\eenmaalandermaal\INSERT_EA.sql" -x

@REM De databatch tabellen worden op de server gezet.
ECHO Creating databatch tables
sqlcmd -S sql.ip.aimsites.nl -U iproject36 -P "%HANPASSWD%" -i "%dataBatchPath%\CREATE Tables.sql" -x -f 65001
sqlcmd -S sql.ip.aimsites.nl -U iproject36 -P "%HANPASSWD%" -i "%dataBatchPath%\CREATE Categorieen.sql" -x -f 65001
sqlcmd -S sql.ip.aimsites.nl -U iproject36 -P "%HANPASSWD%" -i "%dataBatchPath%\GBALanden.sql" -x
sqlcmd -S sql.ip.aimsites.nl -U iproject36 -P "%HANPASSWD%" -i "%CD%\databatch_convert\removeconstraints.sql" -x

@REM De verschillende POWER BI views worden aan de databse toegevoegd
@REM %%F is een bestandsnaam
ECHO Adding views
for %%F in ("%CD%\eenmaalandermaal\views\*.sql") do (
    ECHO Adding view: %%~nF.sql
    sqlcmd -S sql.ip.aimsites.nl -U iproject36 -P "%HANPASSWD%" -i "%%F" -x -f 65001
)

@REM Dubbele for loop die in elke submap van de databatch alle .sql bestanden uitvoert en op de server zet.
@REM %%D is een mappenstructuur
@REM %%F is bestandsnaam
ECHO Adding databatch
for /d %%D in ("%dataBatchPath%\*") do (
    for %%F in ("%%D\*.sql") do (
        ECHO Inserting %%~nF.sql
        START /min sqlcmd -S sql.ip.aimsites.nl -U iproject36 -P "%HANPASSWD%" -i "%%F" -x -f 65001
    )
)

@REM checkt of alle .sql bestanden van de dubbele for loop in de server staan, zo ja voert die het convert script uit.
:loop
CALL :checkRunningScripts
IF %runningScripts% == 1 (
    ECHO Converting databatch...
    :START
    sqlcmd -S sql.ip.aimsites.nl -U iproject36 -P "%HANPASSWD%" -i"%CD%\databatch_convert\convertdataserver.sql" -x 
    GOTO :PROMPT
    GOTO :eof
)
ping -n 2 ::1 >nul 2>&1
GOTO loop
GOTO :eof

@REM Telt de hoeveelheid draaiende sqlcmd scriptjes.
:checkRunningScripts
FOR /f "usebackq" %%t IN (`tasklist /fo csv /fi "imagename eq sqlcmd.exe"^|find /v /c ""`) DO SET runningScripts=%%t
GOTO :eof


@REM Vraagt of de gebruiker een voorbeeldpopulatie in de database wil hebben.
:PROMPT
ECHO Done converting databatch
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
sqlcmd -S sql.ip.aimsites.nl -U iproject36 -P "%HANPASSWD%" -i"%CD%\eenmaalandermaal\INSERT_voorbeeldata.sql" -x
GOTO removeHTML


@REM script dat alle HTML uit de beschrijvingen filtert. 
:removeHTML
@ECHO OFF
ECHO Adding convertfunctions
sqlcmd -S sql.ip.aimsites.nl -U iproject36 -P "%HANPASSWD%" -i"%CD%\databatch_convert\removeHTML.sql" -x 
ECHO Removing HTML and javascript from descriptions, the site can be used while the script is running!
SET "instances=8"
FOR /L %%i IN (0, 1, %instances%) DO (
  START /min sqlcmd -S sql.ip.aimsites.nl -U iproject36 -P "%HANPASSWD%" -Q "DECLARE @currentBatch INT = %%i DECLARE @instanceCount INT = %instances% DECLARE @rowsPerBatch BIGINT = (SELECT count(*)/@instanceCount FROM Voorwerp) DECLARE @currentInstance INT = 0 WHILE @currentInstance < @rowsPerBatch AND @currentBatch < @instanceCount BEGIN UPDATE [dbo].[voorwerp] SET beschrijving = [dbo].[fn_clearHtml]([dbo].[fn_clearStyle] ([dbo].[fn_clearJavascript]([dbo].[fn_clearNoScript] (Beschrijving)))) WHERE voorwerpNummer = [dbo].[FN_getItemID](@currentBatch, @instanceCount, @currentInstance, @rowsPerBatch) SET @currentInstance = @currentInstance + 1 END" -x 
)
ping -n 4 ::1 >nul 2>&1

@REM Checkt of alle filterscriptjes klaar zijn met uitvoeren, als dat het geval is wordt het script afgesloten.
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