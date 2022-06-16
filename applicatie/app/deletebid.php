<?php
require_once '../app/applicatiefuncties.php';

//Verwijdert een bod
$productId = standarddatasafety($_GET['id']);
if (filter_var($productId, FILTER_VALIDATE_INT)) {
	$productinfo = getProductInfo($productId, '*');
	if (!$productinfo[0]['veilingGesloten']) {
		if (deleteBid($productId)) {
			succesRedirect('mijnbiedingen', 'bodingetrokken');
		} else {
			errorRedirect("../../site/mijnbiedingen", "onbekendeFout");
		}
	} else {
		errorRedirect("../../site/mijnbiedingen", "veilingalgesloten");
	}
} else {
	errorRedirect("../../site/mijnbiedingen", "onbekendeFout");
}
