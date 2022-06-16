<?php
require_once 'applicatiefuncties.php';
require_once '../data/datafuncties.php';
$arrayItems = ['username', 'password'];
if (loginValidation($_POST, $arrayItems)) {
    signInUser(prepareData($_POST, $arrayItems));
    redirect('home');
} else {
    errorRedirect('login', 'onbekendeFout');
}
