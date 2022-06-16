<!DOCTYPE html>
<html lang="en">

<head>
<?php
// hier wordt de head aangemaakt dit is een vaste structuur voor elke pagina.
    require_once '../app/applicatiefuncties.php';
    echo getHead();
 ?>
<!-- Plaats hier eventuele extra stylesheets, scripts -->
<script src="js/carousel.js"></script>
<link rel="stylesheet" href="css/productpagecss/style.css">
</head>
<body onload="makeRubrics(), preview()">
<header>
    <?php
    require_once '../app/applicatiefuncties.php';
    checkNumeric($_GET['id']);
    debugMessages('off');
    echo navbar();
    echo productClosedMessage($_GET['id']);

    echo errorType($_GET['error']);

    if (isset($_GET['succes'])) {
        echo succesType($_GET['succes']);
    }
    ?>
    <script>
        <?php
        $php_array = getRubrics();
        $js_array = json_encode($php_array);
        echo "var rubricArray = " . $js_array . ";\n";

        $user = getProductInfo(standardDataSafety($_GET['id']), 'verkopernaam');
        $php_array = getImage(4, $_GET['id'], 'pics', $user[0]['verkopernaam']);
        $js_array = json_encode($php_array);
        echo "var imagesArray = " . $js_array . ";\n";
        ?>
    </script>
</header>
<!-- Begin content van de page -->

    <div class="container my-5">
        <h2> <?= getProductHtml($_GET['id'], 'titel'); ?></h2>
        <div class="row g-1">
            <div class="col-12 col-lg-12">
                <div class="wrapper-image">
                    <div class="product_head">
                        <div class="col-12 justify-content-center text-center mt-5 mb-3">
                            <div id="carouselExampleIndicators" class="carousel slide carousel-dark" data-bs-ride="carousel">

                                <div id="img-container" class="carousel-inner">

                                </div>

                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                                <br><br>
                                <div id="indicator" class="carousel-indicators">

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-1">
                <div class="col-lg-8 d-flex">
                    <div class="wrapper-tekst">
                        <div class="product-info">
                            <h6><strong>Product beschrijving:</strong></h6>
                            <p>
                                <?= getProductHtml($_GET['id'], 'beschrijving');
                                ?></p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 d-flex align-items-stretch">
                    <div class="wrapper">
                        <ul>
                            <li><strong>Startprijs:</strong></li>
                            <li>
                                <?= '€' . str_replace('.', ',',getProductHtml($_GET['id'], 'startprijs'));
                                ?></li>
                        </ul>
                        <ul>
                            <li><strong>Verzendinstructies:</strong></li>
                            <li><?= getProductHtml($_GET['id'], 'verzendinstructies')
                                ?></li>
                        </ul>

                        <ul>
                            <li><strong>Verzendkosten:</strong></li>
                            <li><?= '€' . str_replace('.', ',', getProductHtml($_GET['id'], 'verzendkosten'));
                                ?></li>
                        </ul>
                        <ul>
                            <li><strong>Land:</strong></li>
                            <li>
                                <?= getProductHtml($_GET['id'], 'land'); ?></li>
                        </ul>
                        <ul>
                            <li><strong>Stad:</strong></li>
                            <li>
                                <?= getProductHtml($_GET['id'], 'plaatsnaam'); ?></li>
                        </ul>
                    </div>
                </div>


                <div class="row g-1">
                    <div class="col-12 col-lg-12">
                        <div class="wrapper-bieden">
                            <h6><strong>Hoogste bod:
                                    <?= str_replace('.', ',', getHighestBidHTML($_GET['id'])) ?>
                                </strong></h6>

                            <div class='list-group'>
                                <?= getHighestBidsHTMLList($_GET['id'], 20) ?>
                            </div>


                            <div class='d-grid'>
                                <div class="input-group mb-3">
                                    <?=
                                    getBidFormHtml($_SESSION, $_GET['id'])
                                    ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- Einde content van de page-->
    <?=
    footer();
    ?>
    <script src="../site/js/countdown.js"></script>
</body>

</html>