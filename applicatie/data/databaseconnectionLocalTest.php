<?php
// defined in 'variables.env'
$db_host = 'database_server';   // Database Server Name
$db_name = 'eenmaalandermaal';  // Database Name

// defined in sql-script 'movies.sql'
$db_user    = 'applicatie';         // Database username
$db_password = '5_GXnMV&jN4J%QZY';  // Database user password


try {
  $databaseConnection = new PDO('sqlsrv:Server=' . $db_host . ';Database=' . $db_name . ';ConnectionPooling=0;', $db_user, $db_password);
  $databaseConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die('Database error: ' . $e->getMessage());
}
// Removes the password
unset($db_password);
