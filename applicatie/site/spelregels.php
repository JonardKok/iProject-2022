<!DOCTYPE html>
<html lang="en">
<head>
<?php
// hier wordt de head aangemaakt dit is een vaste structuur voor elke pagina.
    require_once '../app/applicatiefuncties.php';
    echo getHead();
 ?>
<!-- Plaats hier eventuele extra stylesheets, scripts -->
<link rel="stylesheet" href="css/privacyverklaring/avg.css">
<link rel="stylesheet" href="css/homepagecss/main.css">
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

    <div class="container my-5">
        <h1>Spelregels📃</h1>
        <div class="accordion" id="accordionExample">
            <div class="accordion-item">
              <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                 Maak een account aan
                </button>
              </h2>
              <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                  <ul>
                    <li>Zodra je een account hebt, kun je inloggen om mee te bieden. In je account vind je mijn biedingen waar je je kavels bij kunt houden waar je een bod open hebt staan.</li>
                    <li>Zodra je een account hebt, kun je ook een verkoper worden. Hier verifieer je je account door een betalingsmethode op te geven, na het accepteren kan je zelf ook een veiling starten.</li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="accordion-item">
              <h2 class="accordion-header" id="headingTwo">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                  Loop van een veiling
                </button>
              </h2>
              <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <ul>
                      <li>Veiling wordt geplaatst met een looptijd</li>
                      <li>Tijdens de looptijd biedt men tegen elkaar op</li>
                      <li>Het hoogste bod wordt geaccepteerd als de looptijd verloopt of als de verkoper het hoogste bod accepteert.</li>
                      <li>De winnaar en verkoper krijgen beide een mail met elkaars informatie en kunnen nu samen de verkoop realiseren.</li>
                      <li>Einde veiling</li>
                    </ul>
                    
                    
                </div>
              </div>
            </div>
            <div class="accordion-item">
              <h2 class="accordion-header" id="headingThree">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                    Kan ik mijn bod intrekken?
                </button>
              </h2>
              <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                  Dit kan <strong>niet</strong> let op dat je <strong>tijdens</strong> een veiling je bod niet kan intrekken, als de tijd voorbij is en de verkoper accepteerd zit u vast aan de aankoop uw enige optie dan zou zijn om
                  contact op te nemen met de verkoper en van de verkoop af te zien mits de verkoper accepteert.
              </div>
            </div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingFour">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                Ik kan niet bieden, wat nu?
              </button>
            </h2>
            <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionExample">
              <div class="accordion-body">
               <strong>Dit kan verschillende redenen hebben:</strong>
                 <ul>
                    <li>Je bent niet ingelogd. Ga naar de inlogpagina (LINK) om dit alsnog te doen.</li>
                    <li>Je registratie is niet volledig afgerond. Kijk op de registratiepagina (LINK) hoe je dat alsnog kunt doen.</li>
                    <li>Je account is geblokkeerd.</li>
                 </ul>
                <strong>Er zijn regels over de bedragen die geplaatst kunnen worden </strong>
                <ul>
                    <li>Bij veiling tussen de €1,00 en €50,00 moet je minimaal overbieden met €0.50 euro.</li>
                    <li>Bij veiling tussen de €50,00 en €500,00 moet je minimaal overbieden met €1.00 euro.</li>
                    <li>Bij veiling tussen de €500,00 en €1000,00 moet je minimaal overbieden met €5,00 euro.</li>
                    <li>Bij veiling tussen de €1000,00 en €5000,00 moet je minimaal overbieden met €10,00 euro.</li>
                    <li>Bij veiling boven de €5000,00 en meer moet je minimaal overbieden met €50,00 euro.</li>
                </ul>
            </div>
          </div>
        </div>


        <div class="accordion-item">
          <h2 class="accordion-header" id="headingFive">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
              Hoe weet ik of ik het hoogste bod heb?
            </button>
          </h2>
          <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#accordionExample">
            <div class="accordion-body">

              <ul>
                 <li>In je overzicht met biedingen als je veiling nog loopt</li>
                 <li>Je ontvangt een winnaarsmail. Heb je deze mail niet gehad? Kijk dan even in je map met ongewenste e-mail</li>
              </ul>          
          </div>
        </div>
      </div>

      <div class="accordion-item">
        <h2 class="accordion-header" id="headingSix">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
            Ik heb het hoogste bod, hoe nu verder?
          </button>
        </h2>
        <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix" data-bs-parent="#accordionExample">
          <div class="accordion-body">
            Ben je de hoogste bieder? Gefeliciteerd, je bent de winnaar van de veiling! In de winnaarsmail staat aangegeven welk arrangement of product je precies hebt gewonnen. U kunt nu verder contact opnemen met de verkoper om de verkoop af te ronden.
        </div>
      </div>
    </div>


  <div class="accordion-item">
    <h2 class="accordion-header" id="headingSeven">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
        wachtwoord vergeten?
      </button>
    </h2>
    <div id="collapseSeven" class="accordion-collapse collapse" aria-labelledby="headingSeven" data-bs-parent="#accordionExample">
      <div class="accordion-body">
        Wachtwoord vergeten, wijzig uw wachtwoord op de login pagina door "wachtwoord vergeten" te drukken.
    </div>
  </div>
</div>
</div>
</div>
<!-- Einde content van de page-->
    <?php
    echo footer();
    ?>
</body>
</html>