<?php
require_once '../app/applicatiefuncties.php';
session_start();
session_destroy();
redirect('home');
