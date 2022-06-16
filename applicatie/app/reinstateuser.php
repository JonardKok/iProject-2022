<?php
require_once '../app/applicatiefuncties.php';

//Zet gebruiker weer terug als het verwijderd was
if (isset($_GET['name']) && isset($_GET['token'])) {
    if (doesCodeExist($_GET['token'])[0]['']) {
        if (isUserCode(standardDataSafety($_GET['name']), standardDataSafety($_GET['token']))) {
            deleteCode($_GET['token']);
            reinstateuser(standardDataSafety($_GET['name']));
            $_SESSION['adminStatus'] ? succesRedirect('usermanagement', 'accountterugAdmin') :succesRedirect('home', 'accountterug');
        } else {
            redirect('home');
        }
    } else {
        redirect('home');
    }
} else {
    redirect('home');
}
