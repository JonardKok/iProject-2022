<?php
require_once 'applicatiefuncties.php';
require_once '../data/datafuncties.php';
$key = getEnvFile('encryptionkey', 'app');
if (isset($_POST['email']) && confirmEmail($_POST) && !isEmailUsed($_POST, NULL, $key)) { //Checkt of de verificatiecode juist is
    $_SESSION['verificationCode'] = generateRandomString(100);
    insertCode($_SESSION['verificationCode']);
    $_SESSION['email'] = encrypt($_POST['email'], $key);
    echo decrypt($_SESSION['email'], $key);
    stuurVerificatieMail($_SESSION['verificationCode'], $_POST['email'], 'register');
    succesredirect('preregister','codesent');
} else if(isset($_POST['email'])) {
    errorredirect('preregister', 'emailAlGebruikt');
}
