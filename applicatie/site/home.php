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
    <h2>Populair🔥</h2>
    <div class="row g-4">
      <?= homepageProducts('popular'); ?>
    </div>
  </div>
  <div class="b-example-divider"></div>
  <div class="banner">
    <div class="content px-4 text-center">
      <img class="d-block mx-auto mb-4" src="img/EA.png" alt="">
      <h1 class="display-5 fw-bold">EenmmaalAndermaal</h1>
      <div class="col-lg-6 mx-auto">
        <p class="lead fw-bold mb-4">Grootste veilingsite van Nederland</p>
        <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
          <button type="button" class="btn btn-outline-light btn-lg px-4">Over ons</button>
        </div>
      </div>
    </div>
  </div>
  <div class="b-example-divider"></div>
  <div class="container my-5">
    <h2>Bijna verlopen⏳</h2>
    <div class="row g-4">
      <?= homepageProducts('new') ?>
    </div>
  </div>
  <div class="b-example-divider"></div>
  <div class="banner">
    <!-- <img class="banner-thumb" src="pluis.jpeg" alt="banner"> -->
    <div class="content px-4 text-center">
      <h1 class="display-5 fw-bold">Hoe werkt bieden?</h1>
      <div class="col-lg-6 mx-auto">
        <div class="iets d-flex align-items-stretch">
          <div class="icons ">
            <i class="bi bi-diamond-fill" style="font-size: 5rem;"></i>
            <h2>Aanmelden</h2>
            <h6>Meld je gratis en gemakkelijk<br>
              aan binnen 1 minuut</h6>
          </div>
          <div class="icons">
            <i class="bi bi-diamond-fill" style="font-size: 5rem;"></i>
            <h2>Bieden</h2>
            <h6>Zoek een kavel en plaats<br>
              het hoogste bod</h6>
          </div>
          <div class="icons">
            <i class="bi bi-check" style="font-size: 5rem;"></i>
            <h2>Winnen</h2>
            <h6>Geniet van je aanwinst en<br>
              voel je een winnaar!</h6>
          </div>
        </div>
        <div class="d-grid gap-4  my-5 d-sm-flex justify-content-sm-center">
          <button type="button" onclick="window.location.href='spelregels.php'" class="btn btn-outline-light btn-lg px-4">Spelregels</button>
        </div>
      </div>
    </div>
  </div>
  <div class="b-example-divider"></div>
  <div class="container my-5">
    <h2>Nieuw🧼</h2>
    <div class="row g-4">
      <?php echo homepageProducts('old') ?>
    </div>
  </div>
  <div class="b-example-divider"></div>
  <div class="banner-newsletter">
    <div class="container col-xl-10 col-xxl-8 px-4 py-5">
      <div class="row align-items-center g-lg-5 py-5">
        <div class="col-lg-7 text-center text-lg-start">
          <h1 class="display-4 fw-bold lh-1 mb-3">Nieuwsbrief</h1>
          <p class="col-lg-10 fs-4">Wilt u graag op de hoogte blijven van nieuws van EenmaalAndermaal? Altijd op de hoogte zijn van eventuele veranderingen en acties? twijfel niet! Meld u dan aan voor de digitale nieuwsbrief. U krijgt hem wekelijks op de woensdag in uw mailbox.</p>
        </div>
        <div class="col-md-10 mx-auto col-lg-5">
          <form class="p-4 p-md-5 border rounded-3 bg-light">
            <div class="sign-up-box">
              <div class="form-floating mb-2">
                <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
                <label for="floatingInput">Email address*</label>
              </div>
              <div class="checkbox mb-3">
              </div>
              <button class="w-100 btn btn-lg btn-primary" type="submit">Abonneer</button>
              <hr class="my-4">
              <small class="text-muted">Door op abonneer te klikken, ga je akkoord met de gebruikersvoorwaarden.</small>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>


  <!-- Einde content van de page-->
  <?=
  footer();
  ?>

  <script src="js/countdown.js"></script>

</body>

</html>