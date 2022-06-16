<?php
require_once 'applicatiefuncties.php';
require_once '../data/datafuncties.php';
$key = getEnvFile('encryptionkey', 'app');
//code sturen
if (isset($_POST['email']) && confirmEmail($_POST) && emailExistence($_POST['email'], $_POST['username'], $key)) {
    $_SESSION['verificationCodePassword'] = generateRandomString(100);
    insertCode($_SESSION['verificationCodePassword']);
    stuurResetMail($_SESSION['verificationCodePassword'], $_POST['email'], 'reset', $_POST['username']);
    encryptStandardData($_POST['email'], $key);
    succesRedirect('passwordforgotten','codesent');
} else if (isset($_POST['email'])) {
    errorRedirect('passwordforgotten', 'emailBestaatNiet');
}

//code verifieren
if (isset($_POST['answer']) && $_POST['answer'] === decrypt(getUserData($_SESSION['username'], 'gebruikersnaam', 'antwoordtekst')[0], $key)) {
    if (compareVariables($_POST['password'], $_POST['passwordconfirm']) && confirmPassword($_POST['password'], 'passwordforgotten')) {
        resetPassword(standardDataSafety(password_hash($_POST['password'], PASSWORD_DEFAULT)), standardDataSafety($_SESSION['username']));
        session_destroy();
        redirect('login');
    } else {
        errorRedirect('passwordforgotten', 'wachtWoordenOngelijk');
    }
} else {
    errorRedirect('passwordforgotten', 'onjuistAntwoord');
}
