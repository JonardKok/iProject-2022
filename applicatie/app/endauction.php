<?php
require_once '../app/applicatiefuncties.php';
require_once '../data/datafuncties.php';

//Eindigt een veiling
$productId = standardDataSafety($_GET['id']);
$key = getEnvFile('encryptionkey', 'app');
if (filter_var($productId, FILTER_VALIDATE_INT)) {
    $bidData = getHighestBidData($productId, 1, '*');
    $productinfo = getProductInfo($productId, '*');
    if (!$productinfo[0]['veilingGesloten']) {
        if (!empty($bidData)) {
            $sellerName = $productinfo[0]['verkopernaam'];
            $sellerInfo = getUserData($sellerName, 'gebruikersnaam', 'gebruikersnaam, voornaam, achternaam, mailbox');
            if ($_SESSION['user'] === trim($sellerInfo['gebruikersnaam'])) {
                $buyerInfo = getUserData($bidData[0]['Gebruikersnaam'], 'gebruikersnaam', 'gebruikersnaam, voornaam, achternaam, mailbox'); 
                endAuction($productinfo, $bidData, $buyerInfo);
                stuurCommunicatieMailKoper($buyerInfo, $sellerInfo, $productinfo, $bidData, $key);
                stuurCommunicatieMailVerkoper($buyerInfo, $sellerInfo, $productinfo, $bidData, $key);
                errorRedirect("../../site/productpage", "bodgeaccepteerd&id=$productId");
            } else {
                errorRedirect("../../site/productpage", "onbekendeFout&id=$productId");
            }
        } else {
            errorRedirect("../../site/productpage", "noggeenbod&id=$productId");
        }
    } else {
        errorRedirect("../../site/productpage", "veilingalgesloten&id=$productId");
    }
} else {
    errorRedirect("../../site/productpage", "onbekendeFout&id=$productId");
}
