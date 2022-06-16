<?php
$input = $_POST["searchuser"];

header('location: ../site/usermanagement.php?searchuser=' . $input);
