<?php
require_once '../app/applicatiefuncties.php';

$requiredDataItems = [
    'firstname', 'lastname', 'tel', 'birthdate', 'zipcode', 'adress',
    'city', 'email'
];

if (isset($_GET['username']) && isUserAdmin($_SESSION)) {
    $userName = standardDataSafety($_GET['username']);
    $redirect = "&username=$userName";
} else {
    $userName = $_SESSION['user'];
    $redirect = '';
}

if (emptyDataCheck($_POST, $requiredDataItems)) {
    // if (confirmPhoneNumber($_POST['tel']) && confirmZipCode($_POST['zipcode']) && confirmBirthdate($_POST['birthdate'])) {
    //     updateUser($_POST, $userName);
    //     succesRedirect('accountpagina', 'accountgeupdate' . $redirect);
    // }
    if (confirmPhoneNumberAccount($_POST['tel']) && confirmZipCodeAccount($_POST['zipcode']) && confirmBirthdateAccount($_POST['birthdate'])) {
        updateUser($_POST, $userName);
        succesRedirect('accountpagina', 'accountgeupdate' . $redirect);
    } else {
        errorRedirect('accountpagina', 'foutgegevens' . $redirect);
    }
} else {
    errorRedirect('accountpagina', 'onbekendeFout' . $redirect);
}
