<?php
require_once '../app/applicatiefuncties.php';

//Controleert verificatiecode
deleteExpiredCodes();
$key = getEnvFile('encryptionkey', 'app');
$codeType = standardDataSafety($_GET['destination']);
$code = standardDataSafety($_GET['code']);
$username = standardDataSafety($_GET['username']);
$email = encrypt(standardDataSafety($_GET['email']), $key);
if (doesCodeExist($code)[0]) {
    deleteCode($code);
    if ($codeType === 'register') {
        $_SESSION['verified'] = true;
        $_SESSION['email'] = $email;
        redirect('register');
    } else if ($codeType === 'reset') {
        $_SESSION['verifiedPassword'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;
        updateMail($userName, $email) || errorRedirect('home', 'onbekendefout');
        redirect('passwordforgotten');
    } else {
        errorRedirect('home', 'onbekendefout');
    }
} else {
    redirect('home');
}
