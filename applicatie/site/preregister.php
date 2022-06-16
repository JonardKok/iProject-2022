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

<body onload="makeRubrics()">
    <header>
        <?php
        require_once '../app/applicatiefuncties.php';
        debugMessages('off');
        echo navbar();
        if (isset($_GET['error'])) {
            echo errorType($_GET['error']);
        }
        if (isset($_GET['succes'])) {
            echo succesType($_GET['succes']);
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
    <!-- Begin content van de page -->

    <div class="loginbody">
        <div class="container-fluid px-1 py-5 mx-auto">
            <div class="row d-flex justify-content-center">
                <div class="col-xl-7 col-lg-8 col-md-9 col-11 text-center">
                    <div class="card">
                        <h5 class="text-center mb-4">Verificatiemail</h5>
                        <form class="form-card" action="../app/verificationcode.php" method="post">
                            <div class="row justify-content-center text-left">
                                <div class="form-group col-sm-6 flex-column d-flex">
                                    <label class="form-control-label px-3">
                                        E-mailadres
                                    </label>
                                    <input type="email" placeholder="E-mailadres" name='email' required>
                                </div>
                            </div>
                            <div class="form-group col-sm-16">
                                <button type="submit" class="btn-block btn-primary" value="Submit">
                                    Stuur Verificatiemail
                                </button>
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