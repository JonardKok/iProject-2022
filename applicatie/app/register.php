<?php
require_once 'applicatiefuncties.php';
require_once '../data/datafuncties.php';
$_SESSION['username'] = standardDataSafety($_POST['username']);  //Onthoudt de ingevulde gegevens
$_SESSION['firstname'] = standardDataSafety($_POST['firstname']);
$_SESSION['lastname'] = standardDataSafety($_POST['lastname']);
$_SESSION['phone'] = standardDataSafety($_POST['phone']);
$_SESSION['adress'] = standardDataSafety($_POST['adress']);
$_SESSION['adress2'] = standardDataSafety($_POST['adress2']);
$_SESSION['zipcode'] = standardDataSafety(trim($_POST['zipcode']));
$_SESSION['city'] = standardDataSafety($_POST['city']);
$_SESSION['birthdate'] = standardDataSafety($_POST['birthdate']);

$allDataItems = ['username', 'firstname', 'lastname', 'adress', 'adress2', 'zipcode', 'city', 'country', 'birthdate', 'password', 'securityquestion', 'answer'];
$requiredDataItems = ['username', 'firstname', 'lastname', 'adress', 'zipcode', 'city', 'country', 'birthdate', 'password', 'securityquestion', 'answer'];
if (registerValidation($_POST, $allDataItems, $requiredDataItems)) {
    unset($_SESSION['username']);
    unset($_SESSION['firstname']);
    unset($_SESSION['lastname']);
    unset($_SESSION['adress']);
    unset($_SESSION['adress2']);
    unset($_SESSION['zipcode']);
    unset($_SESSION['city']);
    unset($_SESSION['birthdate']);
    registerUser(prepareData($_POST, $allDataItems)) || errorRedirect('register', 'onbekendeFout'); //Registreert de gebruiker
    signInUser($_POST);
    redirect('home');
} else {
    errorRedirect('register', 'onbekendeFout');
}
