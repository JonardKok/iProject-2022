<?php
require_once 'applicatiefuncties.php';
require_once '../data/datafuncties.php';


//Plaatst een bod
$productId = standardDataSafety($_GET["id"]);
$id = $productId;
$bid = $_POST['bid'];
if (!isUserBlocked($_SESSION['user'])) {
    if (bidValidation($_POST['bid'], $productId)) {
        $productinfo = getProductInfo($productId, 'veilingGesloten');
        if (!$productinfo[0]['veilingGesloten']) {
            if (addBid(standardDataSafety($_POST['bid']), $_SESSION['user'], $productId)) {
                redirectBieden("../../site/productpage", $productId);
            } else {
                errorRedirect("../../site/productpage", "onbekendeFout&id=$productId");
            }
        } else {
            errorRedirect("../../site/productpage", "veilingalgesloten&id=$productId");
        }
    } else {
        errorRedirect("../../site/productpage", "bodtelaag&id=$productId");
    }
} else {
    errorRedirect("../../site/productpage", "geblokkeerdBieden&id=$productId");
}
