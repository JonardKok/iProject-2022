<?php
require_once '../app/applicatiefuncties.php';
require_once '../data/datafuncties.php';
$userName = $_GET['username'];


//Blokkeert een gebruiker
if(!isUserBlocked($userName)){
	blockUser($userName);
	succesRedirect('usermanagement', 'geblokkeerd&searchuser=' . $userName);
}else{
	errorRedirect('usermanagement', 'alGeblokkeerd&searchuser=' . $userName);
}