<?php
require_once '../app/applicatiefuncties.php';
require_once '../data/datafuncties.php';

//Verwijdert gebruiker
deleteExpiredUserInfo();
!$_SESSION['adminStatus'] ? NULL : generateSafetyCode(standardDataSafety(standardDataSafety($_POST['user'])), 'account');
$userName = $_SESSION['adminStatus'] ? standardDataSafety($_POST['user']) : standardDataSafety($_SESSION['user']);
if (userCheck($_SESSION, $userName)) {
    tempRemoveUser($userName) ? deleteCode($_SESSION['verificationCodeAccount']) . generateSafetyCode(standardDataSafety($userName), 'deletion') . deleteUserInteractions($_SESSION['adminStatus'], $userName, $_SESSION['verificationCodeAccount']) : session_destroy() . errorRedirect('home', 'verwijderfout');
}
$_SESSION['adminStatus'] ? succesRedirect('usermanagement', 'accverwijderd') : succesRedirect('home', 'voorlopverwijderd');