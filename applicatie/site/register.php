<!DOCTYPE html>
<html lang="en">

<?php
require_once '../app/applicatiefuncties.php';
verificatieCodeCheck(); ?>

<head>
<?php
// hier wordt de head aangemaakt dit is een vaste structuur voor elke pagina.
    require_once '../app/applicatiefuncties.php';
    echo getHead();
 ?>
<!-- Plaats hier eventuele extra stylesheets, scripts -->
<link rel="stylesheet" href="css/homepagecss/main.css">
<link href="css/registratiecss/registratiecss.css" rel="stylesheet">
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
    <div class="registerbody">
        <div class="container-fluid px-1 py-5 mx-auto">
            <div class="row d-flex justify-content-center">
                <div class="col-xl-7 col-lg-8 col-md-9 col-11 text-center">
                    <div class="card">
                        <h5 class="text-center mb-4">Registreren</h5>
                        <form class="form-card" action="../app/register.php" method="post">
                            <div class="row justify-content-between text-left">
                                <div class="form-group col-sm-6 flex-column d-flex">
                                    <label class="form-control-label px-3">
                                        Voornaam*
                                    </label>
                                    <input type="text" placeholder="Voornaam" value="<?php echo isset($_SESSION['firstname']) ? $_SESSION['firstname'] : '' ?>" name='firstname' required>
                                </div>
                                <div class="form-group col-sm-6 flex-column d-flex">
                                    <label class="form-control-label px-3">
                                        Achternaam*
                                    </label>
                                    <input type="text" placeholder="Achternaam" value="<?php echo isset($_SESSION['lastname']) ? $_SESSION['lastname'] : '' ?>" name='lastname' required>
                                </div>
                            </div>
                            <div class="row justify-content-between text-left">
                                <div class="form-group col-sm-6 flex-column d-flex">
                                    <label class="form-control-label px-3">
                                        Geboortedatum*
                                    </label>
                                    <input type="date" id="birthdate" value="<?php echo isset($_SESSION['birthdate']) ? $_SESSION['birthdate'] : '' ?>" name="birthdate" onblur="validate(3)" required>
                                </div>
                                <div class="form-group col-sm-6 flex-column d-flex">
                                    <label class="form-control-label px-3">
                                        Telefoon*
                                    </label>
                                    <input type="tel" pattern="[0-9\s]{1,14}" maxlength="11" placeholder="31612345678" value="<?php echo isset($_SESSION['phone']) ? $_SESSION['phone'] : '' ?>" name='phone' required>
                                </div>
                            </div>
                            <div class="row justify-content-between text-left">
                                <div class="form-group col-sm-6 flex-column d-flex">
                                    <label class="form-control-label px-3">
                                        Adres*
                                    </label>
                                    <input type="text" id="adress" value="<?php echo isset($_SESSION['adress']) ? $_SESSION['adress'] : '' ?>" name="adress" placeholder="Voorbeeldstraat 10" onblur="validate(5)" required>
                                </div>
                                <div class="form-group col-sm-6 flex-column d-flex">
                                    <label class="form-control-label px-3">
                                        Extra Adres Info
                                    </label>
                                    <input type="text" id="adress2" value="<?php echo isset($_SESSION['adress2']) ? $_SESSION['adress2'] : '' ?>" name="adress2" placeholder="A/Verdieping3" onblur="validate(5)">
                                </div>
                            </div>
                            <div class="row justify-content-between text-left">
                                <div class="form-group col-sm-6 flex-column d-flex">
                                    <label class="form-control-label px-3">
                                        Postcode*
                                    </label>
                                    <input type="text" id="zipcode" value="<?php echo isset($_SESSION['zipcode']) ? $_SESSION['zipcode'] : '' ?>" name="zipcode" placeholder="1234AB" onblur="validate(5)" required>
                                </div>
                                <div class="form-group col-sm-6 flex-column d-flex">
                                    <label class="form-group col-sm-12 flex-column d-flex">Land*
                                        <select class="form-select form-select-lg sm-1" aria-label=".form-select-lg example" id="country" name="country">
                                            <?php
                                            echo registerDropdown('country');
                                            ?>
                                        </select>
                                    </label>
                                </div>
                            </div>
                            <div class="row justify-content-between text-left">
                                <div class="form-group col-sm-6 flex-column d-flex ">
                                    <label class="form-control-label px-3">
                                        Plaatsnaam*
                                    </label>
                                    <input type="text" id="city" value="<?php echo isset($_SESSION['city']) ? $_SESSION['city'] : '' ?>" name="city" placeholder="Amsterdam" onblur="validate(5)" required>
                                </div>

                                <div class="form-group col-sm-6 flex-column d-flex">
                                    <label class="form-control-label px-3">
                                        Gebruikersnaam*
                                    </label>
                                    <input type="text" id="username" value="<?php echo isset($_SESSION['username']) ? $_SESSION['username'] : '' ?>" name="username" placeholder="Gebruikersnaam" onblur="validate(6)" required>
                                </div>
                            </div>
                            <div class="row justify-content-between text-left">
                                <div class="form-group col-sm-6 flex-column d-flex">
                                    <label class="form-control-label px-3">
                                        Wachtwoord*
                                    </label>
                                    <input type="password" id="password" name="password" onblur="validate(5)" required>
                                </div>
                                <div class="form-group col-sm-6 flex-column d-flex">
                                    <label class="form-control-label px-3">
                                        Wachtwoord bevestigen*
                                    </label>
                                    <input type="password" id="passwordconfirm" name="passwordconfirm" onblur="validate(5)" required>
                                </div>
                            </div>
                            <div class="form-group col-12 flex-column d-flex">
                                <label class="form-group col-sm-12 flex-column d-flex">
                                    Security question
                                    <select class="form-select form-select-lg sm-1" aria-label=".form-select-lg example" id="securitquestion" name="securityquestion">
                                        <?php
                                        echo registerDropdown('question');
                                        ?>
                                    </select>
                                </label>
                            </div>
                            <div class="row justify-content-between text-left">
                                <div class="form-group col-12 flex-column d-flex">
                                    <label class="form-control-label px-3">
                                        Antwoord*
                                    </label>
                                    <input type="text" id="answer" name="answer" placeholder="Antwoord op vraag" onblur="validate(6)" required>
                                </div>
                            </div>
                            <div class="row justify-content-between">
                                <div class="form-group col-sm-16">
                                    <button type="submit" class="btn-block btn-primary registerbutton" value="Submit">
                                        Registreren
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