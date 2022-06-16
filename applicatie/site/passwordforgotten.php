<!DOCTYPE html>
<html lang="en">

<head>
<?php
    require_once '../app/applicatiefuncties.php';
    echo getHead();
 ?>
<link rel="stylesheet" href="css/logincss/login.css">
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
    <div class="loginbody">
        <?= getResetPasswordHtml() ?>
    </div>
    <?php
    echo footer();
    ?>
</body>
</html>