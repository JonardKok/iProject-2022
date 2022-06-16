<!DOCTYPE html>
<html lang="en">

<head>
<?php
// hier wordt de head aangemaakt dit is een vaste structuur voor elke pagina.
    require_once '../app/applicatiefuncties.php';
    echo getHead();
 ?>
<!-- Plaats hier eventuele extra stylesheets, scripts -->
 <link rel="stylesheet" href="css/mijnbiedingencss/mijnbiedingencss.css">
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

  <div class="container my-5">

    <nav class="navbar">
      <h1>Mijn Biedingen💸</h1>
    </nav>

    <div class="row g-4">
      <?= myBiddings() ?>
    </div>
    
  </div>

<!-- Einde content van de page-->

  <script src="js/countdown.js"></script>
  <?php
  echo footer();
  ?>
</body>

</html>