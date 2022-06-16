<?php
require_once '../app/applicatiefuncties.php';
$arrayItems = ['title', 'description', 'startingprice', 'timelisted', 'shippingcosts', 'shippinginstructions', 'paymentmethod', 'country', 'city'];
$requiredArrayItems = ['title', 'description', 'startingprice', 'timelisted', 'country', 'city'];
$categories = ['category5', 'category4', 'category3', 'category2', 'category1'];

$_SESSION['title'] = $_POST['title'];
$_SESSION['description'] = $_POST['description'];
$_SESSION['shippinginstructions'] = $_POST['shippinginstructions'];
$_SESSION['startingprice'] = $_POST['startingprice'];
$_SESSION['shippingcosts'] = $_POST['shippingcosts'];
$_SESSION['city'] = $_POST['city'];


// session variabele maken van post voor onthouden form *optie dus miscchien niet nodig* 

$rubriek = getLastRubric($categories);
if (productUploadAllowed($_POST, $arrayItems, $_SESSION, $requiredArrayItems, count($_FILES['image']['name']), 4)) {
    $time = date("H:i:s");
    $targetImageDirectory = "../site/pics/";
    $targetThumbnailDirectory = "../site/thumbnails/";
    $maxSize = 2000000; //2MB
    $prudoctAdded = false;
    $productTime = getdate();
    $nrOfImages = count($_FILES['image']['name']);
    addProductInfo($_POST, $rubriek, $time);
    //Kijkt of de plaatjes toegestaan zijn.
    for ($index = 0; $index < $nrOfImages; $index++) {
        $image = $_FILES['image'];
        $imageExtension = strtolower(pathinfo($image['name'][$index], PATHINFO_EXTENSION));
        if (checkAllowedImageTypes($imageExtension) && checkFileSize($image, $maxSize, $index)) {
            $newFileName = convertFileName($_POST['title'], $imageExtension, 30, $index);
            $imageLocation = $targetImageDirectory . $newFileName;
            $thumbnailLocation = $targetThumbnailDirectory . $newFileName;
            if (move_uploaded_file($image['tmp_name'][$index], $imageLocation)) {
                if (copy($imageLocation, $thumbnailLocation)) {
                    unset($_SESSION['title']);
                    unset($_SESSION['description']);
                    unset($_SESSION['shippinginstructions']);
                    unset($_SESSION['startingprice']);
                    unset($_SESSION['shippingcosts']);
                    unset($_SESSION['city']);
                    addImage($newFileName, $_POST, $time) || removeProduct($_POST) . removeImage($newFileName, 'pics/') . removeImage($newFileName, 'thumbnails/') . errorRedirect('addproduct', 'uploadenMislukt');
                } else {
                    removeProduct($_POST);
                    errorRedirect('addproduct', 'uploadenMislukt');
                }
            } else {
                errorRedirect('addproduct', 'uploadenMislukt');
            }
        } else {
            errorRedirect('addproduct', 'uploadenMislukt');
        }
    }
    //Bij geen errorRedirect wordt het product geüpload.
    succesRedirect('home', 'producttoevoegen');
} else {
    errorRedirect('addproduct', 'onbekendeFout');
}
