@echo OFF
SET /p HANPASSWD=<%CD%\applicatie\data\hanWachtwoord.env
echo Removing database
sqlcmd -S sql.ip.aimsites.nl -U iproject36 -P "%HANPASSWD%" -i "%CD%\SQL_scripts\DROP_eenmaalandermaal.sql" -x
echo Done removing database