<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  // hier wordt de head aangemaakt dit is een vaste structuur voor elke pagina.
  require_once '../app/applicatiefuncties.php';
  echo getHead();
  ?>
  <!-- Plaats hier eventuele extra stylesheets, scripts -->
  <link rel="stylesheet" href="css/homepagecss/main.css">
</head>

<body onload="makeRubrics()">
  <header>
    <?php
    require_once '../app/applicatiefuncties.php';
    debugMessages('off');
    echo navbar();
    echo errorType($_GET['error']);
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
    <?= categoryFilter($_GET['categorie'], $_GET['page']) ?>
  </div>


  <!-- Einde content van de page-->
  <?=
  footer();
  ?>

  <script src="../site/js/countdown.js"></script>

</body>

</html>