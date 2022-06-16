<?php
//Wachtwoord wordt opgehaald uit een.env file zodat we geen wachtwoorden in de code pushen. 
require_once '../app/getEnv.php';
$hanwachtwoord = getEnvFile('hanWachtwoord', 'data');

$dsn = "sqlsrv:Server=sql.ip.aimsites.nl;Database=iproject36;ConnectionPooling=0";
try {
    $hanDatabase = new PDO($dsn, "iproject36", $hanwachtwoord);
    $hanDatabase->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );
} catch (PDOException $e) {
    echo $e->getMessage();
}
unset($hanwachtwoord);
