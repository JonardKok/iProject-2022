<?php

require_once 'applicatiefuncties.php';
require_once '../data/datafuncties.php';

//Maakt gebruiker een verkoper, is voor beheerder
$arrayItems = ['bank', 'bankaccount', 'option', 'cardnumber'];
$user = standardDataSafety($_GET['user']);
if (!empty($_POST['cardnumber'])) {
    if (checkCreditcard($_POST['cardnumber'])) {
        if (!empty($_POST['bankaccount'])) {
            if (checkIBAN($_POST['bankaccount'])) {
                insertVerkoper(prepareData($_POST, $arrayItems), $user);
                $_SESSION['sellerStatus'] = isUserSeller($user);
                succesRedirect('usermanagement', 'creditcardToegevoegd&searchuser=' . $user);
            } else {
                errorRedirect('usermanagement', 'bankrekeningnummerOnjuist&searchuser=' . $user);
            }
        } else {
            insertVerkoper(prepareData($_POST, $arrayItems), $user);
            $_SESSION['sellerStatus'] = isUserSeller($user);
            succesRedirect('usermanagement', 'creditcardToegevoegd&searchuser=' . $user);
        }
    }
}else{
    errorRedirect('usermanagement', 'onbekendeFout&searchuser=' . $user);
}
