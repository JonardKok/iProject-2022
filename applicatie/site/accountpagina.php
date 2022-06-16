<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  require_once '../app/applicatiefuncties.php';
  echo getHead();
  debugMessages('off');
  generateSafetyCode($_GET['username'], 'account');
  ?>
</head>

<body onload="makeRubrics()">
  <header>
    <?php
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
  <div class="container">
    <?php
    if (isset($_GET['username']) && isUserAdmin($_SESSION)) {
      echo myAccount(standardDataSafety($_GET['username']));
    } else {
      echo myAccount($_SESSION['user']);
    }
    ?>
  </div>
  <?=
  footer();
  ?>

</body>

</html>