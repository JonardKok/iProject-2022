<?php
//Deze functie haalt de inhoud van een .env file op zodat er geen wachtwoorden in de code hoeven te komen.
function getEnvFile($file, $directory)
{
    ob_start();
    require_once '../' . $directory . '/' . $file . '.env';
    return ob_get_clean();
}
