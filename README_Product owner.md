In deze map vindt u de EenmaalAndermaal site van iProject36.

In het bestand Admins.txt kunt u de adminsgegevens vinden die nodig zijn om het wachtwoord te resetten.
Op de site https://iproject36mail.ip.aimsites.nl/
Log daar in met de gebruikersnaam: iproject36
En met het wachtwoord wat zich in applicatie\data\hanWachtwoord.env bevindt.

Op die site staat dan een mailtje met een link die u kunt gebruiken om het wachtwoord te resetten.

Om het databatch insertscript te testen moet eerst het resetdatabase.bat script uitgevoerd worden. Deze maakt de database leeg, wat er voor zorgt dat het insertdataserver.bat bestand 
zonder problemen uitgevoerd kan worden.

Wilt u lokaal op uw eigen machine testen?
Dan moet docker geinstalleerd zijn.
Is docker niet geinstalleerd moet u de volgende linkjes gebruiken om docker te installeren:
Ten eerste moet WSL geinstallerd en aangezet zijn:
<a href="https://docs.microsoft.com/nl-nl/windows/wsl/install-manual">WSL</a>
Zonder WSL werkt docker niet, volg de handleiding aandachtig.
Met de volgende link kan docker geinstalleerd worden.
<a href="https://docs.docker.com/desktop/windows/install/">Docker</a>

Na het installeren van docker moet u naar de iproject36 map navigeren in een terminal en docker compose up uitvoeren.
Dan begint docker automatisch met het downloaden en instellen van de php webserver en SQL-server.
Nadat die uitgevoerd zijn moet er in een nieuwe terminal genavigeerd worden naar de iproject36 map en het bestand insertdatalocal.bat uitgevoerd worden.
Dit wordt gedaan door .\insertdatalocal.bat in te typen en uit te voeren door op enter te drukken.

Tijdens het uitvoeren van (of erna / ervoor) het .\insertdatalocal.bat moet in het datafuncties.php bestand wat zich bevindt in 'applicatie\data\datafuncties.php' de databasehost aangepast worden. 
Dit kan door de databaseconnectionLocalTest.php in de require_once te zetten en in de dbConnection() functie de variabele om te zetten naar $databaseConnection.

Als dit gedaan is kan er lokaal getest worden.