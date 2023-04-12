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
        <h1>Privacy🤐 </h1>
        <p class="lead">
            IConcepts, gevestigd aan Professor Molkenboerstraat 3, 6524 RN Nijmegen, is verantwoordelijk voor de verwerking van persoonsgegevens zoals weergegeven in deze privacyverklaring.<br>
        <p>Contactgegevens:<br>
            https://DOMEIN<br>
            Professor Molkenboerstraat 3, 6524 RN Nijmegen<br>
            (024) 353 05 00<br>
            Hans is de Functionaris Gegevensbescherming van IConcepts. Hij is te bereiken via <a href="mailto:Iproject36@han.nl" target="_blank">Iproject36@han.nl</a>
        </p>


        <h2>Persoonsgegevens die wij verwerken</h2>
        <p class="lead">IConcepts verwerkt uw persoonsgegevens doordat u gebruik maakt van onze diensten en/of omdat u deze zelf aan ons verstrekt. Hieronder vindt u een overzicht van de persoonsgegevens die wij verwerken:<br> </p>
        <p class="lead">
        <ul>
            <li>Voor- en achternaam</li>
            <li>Geboortedatum</li>
            <li>Adresgegevens</li>
            <li>Telefoonnummer</li>
            <li>E-mailadres</li>
            <li>IP-adres</li>
            <li>Internetbrowser en apparaat type</li>
            <li>Bankrekeningnummer</li>
        </ul>


        <h2>Bijzondere en/of gevoelige persoonsgegevens die wij verwerken</h2>
        <p class="lead">Onze website en/of dienst heeft niet de intentie gegevens te verzamelen over websitebezoekers die jonger zijn dan 16 jaar. Tenzij ze toestemming hebben van ouders of voogd. We kunnen echter niet controleren of een bezoeker ouder dan 16 is. Wij raden ouders dan ook aan betrokken te zijn bij de online activiteiten van hun kinderen, om zo te voorkomen dat er gegevens over kinderen verzameld worden zonder ouderlijke toestemming. Als u er van overtuigd bent dat wij zonder die toestemming persoonlijke gegevens hebben verzameld over een minderjarige, neem dan contact met ons op via Iproject36@han.nl, dan verwijderen wij deze informatie.</p>


        <h2>Met welk doel en op basis van welke grondslag wij persoonsgegevens verwerken</h2>
        <p class="lead">IConcepts verwerkt uw persoonsgegevens voor de volgende doelen:</p>
        <p class="lead">
        <ul>
            <li>Het afhandelen van uw betaling</li>
            <li>Verzenden van onze nieuwsbrief en/of reclamefolder</li>
            <li>U te kunnen bellen of e-mailen indien dit nodig is om onze dienstverlening uit te kunnen voeren</li>
            <li>U te informeren over wijzigingen van onze diensten en producten</li>
            <li>U de mogelijkheid te bieden een account aan te maken</li>
            <li>Om goederen en diensten bij u af te leveren</li>
        </ul>



        <h2>Geautomatiseerde besluitvorming</h2>
        <p class="lead">IConcepts neemt #responsibility op basis van geautomatiseerde verwerkingen besluiten over zaken die (aanzienlijke) gevolgen kunnen hebben voor personen. Het gaat hier om besluiten die worden genomen door computerprogramma's of systemen, zonder dat daar een mens (bijvoorbeeld een medewerker van IConcepts) tussen zit. IConcepts gebruikt de volgende computerprogramma's of systemen </p>


        <h2>Hoe lang we persoonsgegevens bewaren</h2>
        <p class="lead">IConcepts bewaart uw persoonsgegevens niet langer dan strikt nodig is om de doelen te realiseren waarvoor uw gegevens worden verzameld. Wij hanteren de volgende bewaartermijnen voor de volgende (categorieën) van persoonsgegevens: #retention_period</p>

        <h2>Delen van persoonsgegevens met derden</h2>
        <p class="lead">IConcepts verkoopt uw gegevens niet aan derden en verstrekt deze uitsluitend indien dit nodig is voor de uitvoering van onze overeenkomst met u of om te voldoen aan een wettelijke verplichting. Met bedrijven die uw gegevens verwerken in onze opdracht, sluiten wij een bewerkersovereenkomst om te zorgen voor eenzelfde niveau van beveiliging en vertrouwelijkheid van uw gegevens. IConcepts blijft verantwoordelijk voor deze verwerkingen.</p>


        <h2>Cookies, of vergelijkbare technieken, die wij gebruiken</h2>
        <p class="lead">IConcepts gebruikt alleen technische en functionele cookies. En analytische cookies die geen inbreuk maken op uw privacy. Een cookie is een klein tekstbestand dat bij het eerste bezoek aan deze website wordt opgeslagen op uw computer, tablet of smartphone. De cookies die wij gebruiken zijn noodzakelijk voor de technische werking van de website en uw gebruiksgemak. Ze zorgen ervoor dat de website naar behoren werkt en onthouden bijvoorbeeld uw voorkeursinstellingen. Ook kunnen wij hiermee onze website optimaliseren. U kunt zich afmelden voor cookies door uw internetbrowser zo in te stellen dat deze geen cookies meer opslaat. Daarnaast kunt u ook alle informatie die eerder is opgeslagen via de instellingen van uw browser verwijderen.</p>


        <h2>Gegevens inzien, aanpassen of verwijderen</h2>
        <p class="lead">U heeft het recht om uw persoonsgegevens in te zien, te corrigeren of te verwijderen. Dit kunt u zelf doen via de persoonlijke instellingen van uw account. Daarnaast heeft u het recht om uw eventuele toestemming voor de gegevensverwerking in te trekken of bezwaar te maken tegen de verwerking van uw persoonsgegevens door ons bedrijf en heeft u het recht op gegevensoverdraagbaarheid. Dat betekent dat u bij ons een verzoek kunt indienen om de persoonsgegevens die wij van u beschikken in een computerbestand naar u of een ander, door u genoemde organisatie, te sturen. Wilt u gebruik maken van uw recht op bezwaar en/of recht op gegevensoverdraagbaarheid of heeft u andere vragen/opmerkingen over de gegevensverwerking, stuur dan een gespecificeerd verzoek naar <a href="mailto:Iproject36@han.nl" target="_blank">Iproject36@han.nl</a>. Om er zeker van te zijn dat het verzoek tot inzage door u is gedaan, vragen wij u een kopie van uw identiteitsbewijs bij het verzoek mee te sturen. Maak in deze kopie uw pasfoto, MRZ (machine readable zone, de strook met nummers onderaan het paspoort), paspoortnummer en Burgerservicenummer (BSN) zwart. Dit ter bescherming van uw privacy. IConcepts zal zo snel mogelijk, maar in ieder geval binnen vier weken, op uw verzoek reageren. IConcepts wil u er tevens op wijzen dat u de mogelijkheid hebt om een klacht in te dienen bij de nationale toezichthouder, de Autoriteit Persoonsgegevens. Dat kan via de volgende link: <a href="https://autoriteitpersoonsgegevens.nl/nl/contact-met-de-autoriteit-persoonsgegevens/tip-ons" target="_blank">https://autoriteitpersoonsgegevens.nl/nl/contact-met-de-autoriteit-persoonsgegevens/tip-ons</a> </p>

        <h2>Hoe wij persoonsgegevens beveiligen</h2>
        <p class="lead">Concepts neemt de bescherming van uw gegevens serieus en neemt passende maatregelen om misbruik, verlies, onbevoegde toegang, ongewenste openbaarmaking en ongeoorloofde wijziging tegen te gaan. Als u de indruk heeft dat uw gegevens niet goed beveiligd zijn of er aanwijzingen zijn van misbruik, neem dan contact op met onze klantenservice of via <a href="mailto:Iproject36@han.nl" target="_blank">Iproject36@han.nl</a></p>
        

    </div>
    <!-- Einde content van de page-->

    <?php
    echo footer();
    ?>
</body>

</html>