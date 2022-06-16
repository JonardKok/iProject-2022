<?php
$input = $_POST["search"];

header('location: ../site/zoekresultaten.php?search=' . $input);
