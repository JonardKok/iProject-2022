<?php
require_once 'applicatiefuncties.php';

$username = $_SESSION['user'];
$imageFile = $_FILES["image"]["tmp_name"];
$imageExtension = $_FILES["image"]["type"];
$imageExtension = str_replace("image/", "", $imageExtension);
$imgEncoded = base64_encode(file_get_contents($imageFile));
$maxSize = 2000000; //2MB
$key = getEnvFile('encryptionkey', 'app');

if(!empty($_POST['option'])){ //Checkt de geselecteerde optie
    $_POST['option'] === 'post' ? succesRedirect('home', 'postgestuurd') : NULL ;

    if (!empty($_POST['cardnumber']) && $_POST['option'] === 'creditcard') {
        if (checkCreditcard(standardDataSafety($_POST['cardnumber']))) {
            if(!empty($_POST['bank'])){
                if (!empty($_POST['bankaccount'])) {
                    if (checkIBAN(standardDataSafety($_POST['bankaccount']))) {
                        if(checkAllowedImageTypesId($imageExtension) && checkFileSizeId($_FILES["image"], $maxSize)){ //Checkt het bestand
                            // Stuurt mail met de gegevens naar de beheerder
                            $userData = getUserData($username, 'gebruikersnaam', '*');
                            stuurMailVerkoperWorden($userData, $_POST['cardnumber'], $_POST['bankaccount'], $imgEncoded, $key);
                            succesRedirect('home', 'verkoperAanvraagGestuurd');
                        }
                    } else {
                        errorRedirect('verkoper-worden', 'bankrekeningnummerOnjuist');
                    }
                } else {
                    errorRedirect('verkoper-worden', 'onbekendeFout');
                }
            } else {
                if(checkAllowedImageTypesId($imageExtension) && checkFileSizeId($_FILES["image"], $maxSize)){
                    // Stuurt mail met de gegevens naar de beheerder
                    $userData = getUserData($username, 'gebruikersnaam', '*');
                    stuurMailVerkoperWorden($userData, $_POST['cardnumber'], 'Niet ingevoerd...', $imgEncoded, $key);
                    succesRedirect('home', 'verkoperAanvraagGestuurd');
                }
            }
        } else {
            errorRedirect('verkoper-worden', 'credditcardNummerOnjuist');
        } 
    }
} else {
    errorRedirect('verkoper-worden', 'onbekendeFout');
}