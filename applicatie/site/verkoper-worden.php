<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    // hier wordt de head aangemaakt dit is een vaste structuur voor elke pagina.
    require_once '../app/applicatiefuncties.php';
    echo getHead();
    ?>
    <!-- Plaats hier eventuele extra stylesheets, scripts -->
    <link href="css/logincss/login.css" rel="stylesheet">
</head>

<!-- Begin content van de page -->

<body onload="makeRubrics()">
    <header>
        <?php
        require_once '../app/applicatiefuncties.php';
        checkAccountAge();

        debugMessages('off');
        echo navbar();
        if (isset($_GET['error'])) {
            echo errorType($_GET['error']);
        }
        ?>
        <script>
            <?php
            $php_array = getRubrics();
            $js_array = json_encode($php_array);
            echo "var rubricArray = " . $js_array . ";\n";
            ?>
        </script>
    </header>
    <div class="loginbody">
        <div class="container-fluid px-1 py-5 mx-auto">
            <div class="row d-flex justify-content-center">
                <div class="col-xl-7 col-lg-8 col-md-9 col-11 text-center">
                    <div class="card">
                        <!-- NIEUWE FORM -->
                        <form class="form-card" action="../app/verkoper.php" method="post" enctype="multipart/form-data">
                            <h5 class="text-center mb-4">Word een verkoper!</h5>
                            <p class="text-center">Om verkoper te worden op EenmaalAndermaal moeten er een paar gegevens van je gecontroleerd worden.</p>
                            <p class="text-center">Je moet ten eerste een controleoptie kiezen, dit gaat per creditcard, of per post.</p>
                            <p class="text-center">In beide gevallen worden de hier onder opgestuurde gegevens ook gecontroleerd door medewerkes van EenmaalAndermaal, om zeker te weten of je geschikt bent om een verkoper te worden.</p>

                            <div class="row justify-content-center text-center">
                                <div class="form-group col-sm-12 flex-column d-flex">
                                    <label class="form-control-label px-3">
                                        Controle-optie*
                                    </label>
                                    <select class="form-select form-select-lg sm-1" aria-label=".form-select-lg example" id="option" name="option" required>
                                        <option value="" disabled selected hidden>Selecteer optie</option>
                                        <option value="creditcard">Creditcard</option>
                                        <option value="post">Post</option>
                                    </select>
                                </div>
                            </div>

                            <p class="text-center mt-5">Je moet ook een betalings/incasseringsoptie kiezen die je koppelt aan je account</p>
                            <p class="text-center">Je kan een van de onderstaande betalingsopties kiezen. Ook is het mogelijk om deze opties beide in te vullen.</p>
                            <div class="row justify-content-center text-center" id="creditcardform">
                                <div class="form-group col-sm-12 flex-column d-flex">
                                    <label class="form-control-label px-3">
                                        Creditcardnummer
                                    </label>
                                    <input type="number" placeholder="5555555555554444" name='cardnumber'>
                                </div>
                            </div>

                            <div class="row justify-content-center text-center">
                                <div class="form-group col-sm-6 flex-column d-flex">
                                    <label class="form-control-label px-3">
                                        Bank
                                    </label>
                                    <select class="form-select form-select-lg sm-1" aria-label=".form-select-lg example" id="bank" name="bank">
                                        <option value="rabobank" disabled selected>Kies een bank...</option>
                                        <option value="rabobank">Rabobank</option>
                                        <option value="ING">ING</option>
                                        <option value="ABN AMRO">ABN AMRO</option>
                                        <option value="ASN bank">ASN Bank</option>
                                        <option value="bunq">Bunq</option>
                                        <option value="SNS bank">SNS Bank</option>
                                        <option value="knab">Knab</option>
                                    </select>
                                </div>
                                <div class="form-group col-sm-6 flex-column d-flex">
                                    <label class="form-control-label px-3">
                                        Rekeningnummer
                                    </label>
                                    <input type="text" placeholder="NL44RABO0123456789" name='bankaccount'>
                                </div>
                            </div>
                            <div class="row justify-content-center mt-5 text-left">
                                <div class="form-group col-sm-6 flex-column d-flex">
                                    <label class="form-control-label px-3" data-toggle="tooltip" title="Tot maximaal 4 plaatjes">
                                        Upload plaatje*
                                    </label>
                                    <input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
                                    <input class="bg-secondary" type="file" name="image" id="formFile" multiple="multiple" required />
                                </div>
                            </div>
                            <div class="row justify-content-between">
                                <div class="form-group col-sm-16">
                                    <button type="submit" class="btn-block btn-primary" name="submit" value="Submit">
                                        Vraag aan
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Einde content van de page-->
    <?php
    require_once '../app/applicatiefuncties.php';
    echo footer();
    ?>
</body>

</html>