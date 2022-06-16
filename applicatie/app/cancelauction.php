<?php
require_once '../app/applicatiefuncties.php';
require_once '../data/datafuncties.php';
$productId = standardDataSafety($_GET['id']);


//Sluit een veiling vroegtijdig
if (filter_var($productId, FILTER_VALIDATE_INT)) {
    $productinfo = getProductInfo($productId, '*');
    if (!$productinfo[0]['veilingGesloten']) {
        cancelBid($productId);
        if(!isUserAdmin($_SESSION)){
            succesRedirect('mijnveiling', 'veilingannuleren');
        }else{
            succesRedirect('productpage', "veilingannuleren&id=$productId");
        }
    } else {
        if(!isUserAdmin($_SESSION)){
            errorRedirect('mijnveiling', 'veilingalgesloten');
        }else{
            errorRedirect("productpage", "veilingalgesloten&id=$productId");
        }
    }
} else {
    errorRedirect("productpage", "onbekendeFout&id=$productId");
}
