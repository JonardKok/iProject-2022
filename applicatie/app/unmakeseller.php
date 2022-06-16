<?php
require_once '../app/applicatiefuncties.php';
require_once '../data/datafuncties.php';

$user = standardDataSafety($_GET['user']);

//Maakt gebruiker geen verkoper meer
if (isUserSeller($user)) {
	unmakeSeller($user);
	succesRedirect('usermanagement', 'verkoperGedeactiveerd&searchuser=' . $user);
}else{
	errorRedirect('usermanagement', 'geenVerkoper&searchuser=' . $user);
}
