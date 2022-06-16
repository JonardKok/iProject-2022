
<!-- Page template EenmaalAndermaal @2022 -->

<!DOCTYPE html>
<html lang="en">
<head>
<?php
// hier wordt de head aangemaakt dit is een vaste structuur voor elke pagina.
    require_once '../app/applicatiefuncties.php';
    echo getHead();
    debugMessages('off');
 ?>
<!-- Plaats hier eventuele extra stylesheets, scripts -->
</head>

<body onload="makeRubrics()">
<header>
    <?php
    // hier wordt de navbar aangemaakt
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



     <div class="container">
         <!-- Bootstrap container waar de content van de pagina in geplaatst kan worden, kan evt verwijderd 
        bij geen gebruik!-->
     </div>



<!-- Einde content van de page-->
<footer>
    <?=footer()?>
    <!-- Hier wordt de footer aangemaakt -->
</footer>
    
</body>
</html>