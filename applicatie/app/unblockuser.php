<?php
require_once '../app/applicatiefuncties.php';
require_once '../data/datafuncties.php';
$userName = standardDataSafety($_GET['username']);

//Deblokkeert gebruiker
if(isUserBlocked($userName)){
	unBlockUser($userName);
	succesRedirect('usermanagement', 'heractiveerd&searchuser=' . $userName);
}else{
	errorRedirect('usermanagement', 'alGeblokkeerd&searchuser=' . $userName);
}