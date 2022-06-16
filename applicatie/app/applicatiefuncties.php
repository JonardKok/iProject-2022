<?php
require_once '../data/datafuncties.php';
require_once 'getEnv.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once 'PHPMailer/src/Exception.php';
require_once 'PHPMailer/src/PHPMailer.php';
require_once 'PHPMailer/src/SMTP.php';
session_start();
//NAVBAR_______________
//Deze functie maakt de complete navbar.
function navbar()
{
    $html = <<<EOD
	<nav class="navbar-expand-md navbar-light">
        <div class="container-fluid ">
            <nav class="navbar py-3">
                <div class="container-fluid">
                    <div class="d-flex justify-content-center align-items-center">
                        <a class="navbar-brand d-flex justify-content-center align-items-center mx-auto" href="../site/home.php">
                            <img src="../site/img/logo.png" class="navbar-logo" alt="logo">
                            <span class="navbar-title d-none d-sm-block">
                                EENMAAL ANDERMAAL
                            </span>
                        </a>
                    </div>
                    <button data-bs-toggle="collapse" class="navbar-toggler" data-bs-target=".navbar-collapse">
                        <span class="visually-hidden">
                            Toggle navigation
                        </span>
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse navbar-buttons align-items-center">
                        <form class="ms-auto w-75 d-flex" action="../app/searchbar.php" method="post">
                            <input class="search-input w-100" type="text" name="search" placeholder="Search..." >
                            <button class="btn btn-light search-btn" type="submit"> 
                                <i class="fa fa-search"></i>
                            </button>
                        </form>
EOD;
    $html .= accountOrSignIn();
    $html .= '</div>';
    $html .= '</div>';
    $html .= columnTree();
    $html .= <<<EOD
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</nav>
<div class="b-example-divider"></div>
EOD;
    return $html;
}

//Deze functie checkt aan de hand van de gebruikersstatus welke navigatiebalk opgestuurd moet worden.
function accountOrSignIn()
{
    if (loggedIn()) {
        if (isUserAdmin($_SESSION)) {
            return '<div class="dropdown">
                        <a class="btn btn-primary dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Admin&nbsp;&nbsp;
                        </a>
                        <ul class="dropdown-menu w-100" aria-labelledby="navbarDropdownMenuLink">
                            <li><a class="dropdown-item" href="../site/accountpagina.php">Profiel</a></li>
                            <li><a class="dropdown-item" href="../site/mijnveiling.php">Veilingen</a></li>
                            <li><a class="dropdown-item" href="../site/usermanagement.php">Gebruikers</a></li>
                            <li><a class="dropdown-item" href="../app/logout.php">Log uit</a></li>
                        </ul>
                    </div>';
        } elseif (!$_SESSION['sellerStatus'][0]) {
            return '<a href="../site/verkoper-worden.php">
                        <div class="btn ms-auto sign-in">            
                            Verkopen?
                        </div>
                    </a>
                    <div class="dropdown">
                        <a class="btn btn-primary dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Account
                        </a>
                        <ul class="dropdown-menu w-100" aria-labelledby="navbarDropdownMenuLink">
                            <li><a class="dropdown-item" href="../site/accountpagina.php">Profiel</a></li>
                            <li><a class="dropdown-item" href="../site/mijnbiedingen.php">Biedingen</a></li>
                            <li><a class="dropdown-item" href="../app/logout.php">Logout</a></li>
                        </ul>
                    </div>';
        } elseif ($_SESSION['sellerStatus'][0]) {
            return '<a href="../site/addproduct.php">
                        <div class="btn ms-auto sign-in">   
                        <i class="fas fa-plus"></i> Item</div>
                    </a>'
                .
                '<div class="dropdown">
                        <a class="btn btn-primary dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Account
                        </a>
                        <ul class="dropdown-menu w-100" aria-labelledby="navbarDropdownMenuLink">
                            <li><a class="dropdown-item" href="../site/accountpagina.php">Profiel</a></li>
                            <li><a class="dropdown-item" href="../site/mijnveiling.php">Veilingen</a></li>
                            <li><a class="dropdown-item" href="../site/mijnbiedingen.php">Biedingen</a></li>
                            <li><a class="dropdown-item" href="../app/logout.php">Logout</a></li>
                        </ul>
                    </div>';
        }
    } else {
        return '<a class="btn ms-auto sign-in" href="../site/login.php">Sign in</a>' . '<a class="btn sign-up" href="../site/preregister.php">Sign up</a>';
    }
}

//Deze functie kijkt of de gebruiker ingelogd is.
function loggedIn()
{
    return isset($_SESSION['user']);
}

//Deze functie laadt te rubrieken in.
function columnTree()
{
    $html = <<<EOD
				<div class="container-fluid navbar-top">
                    <div class="collapse navbar-collapse d-lg-flex flex-row justify-content-center justify-content-lg-center category-navbar">
                        <ul class="navbar-nav flex-grow-1">
                            <li class="nav-item dropdown flex-grow-1 category">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Alle categorieën </a>
                                <ul class="dropdown-menu w-100" id="rubricsTree" aria-labelledby="navbarDropdownMenuLink">

                                </ul>
                            </li>   
EOD;
    $populairCategory = getPopulairCategoryNavbar();
    foreach ($populairCategory as $category) {
        $categoryId = $category['rubrieknummer'];
        $categoryName = $category['rubrieknaam'];
        $html .= '
        <li class="nav-item flex-grow-1 category">
            <a class="nav-link active" href="categoriepagina.php?categorie=' . $categoryId .'">' . $categoryName . '</a>
        </li>';
    }

    return $html;
}

//Haalt alle rubrieken op
function getRubrics()
{
    return getRubricInformation();
}

//Returnt de header van elke pagina
function getHead()
{
    $html = <<<EOD
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous"> <!-- <link rel="stylesheet" href="css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css" integrity="sha256-2XFplPlrFClt0bIdPgpz8H7ojnk10H69xRqd9+uTShA=" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/colors.css">
    <link rel="stylesheet" href="css/navbarcss/Navbar-Centered-Brand.css">
    <link rel="stylesheet" href="css/navbarcss/Search-Field-With-Icon.css">
    <link rel="stylesheet" href="css/navbarcss/Search-Input-Responsive-with-Icon.css">
    <link rel="stylesheet" href="css/navbarcss/styles.css">
    <link rel="stylesheet" href="css/footercss/footer.css">
    <link rel="stylesheet" href="css/main.css">
    <script src="js/navbar.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <title>EenmmalAndermaal</title>
    <link rel="icon" type="image/x-icon" href="img/favicon.ico">
    EOD;
    return $html;
}



//Deze functie stuurt de footer op.
function footer()
{
    $html = <<<EOD
<footer class="deneb_footer">
    <div class="b-example-divider"></div>
    <div class="widget_wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12 justify-content-center text-center mt-5 mb-5">
                    <div class="widget widegt_about">
                        <div class="social">
                            <img src="../site/img/logo.png" class="navbar-logo" alt="logo">
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-12">
                    <div class="widget widget_link">
                        <div class="widget_title">
                            <h4>EenmaalAndermaal</h4>
                        </div>
                        <ul>
                            <li><a href="spelregels.php">Spelregels</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-12">
                    <div class="widget widget_link">
                        <div class="widget_title">
                            <h4>Legal</h4>
                        </div>
                        <ul>
                            <li><a href="../site/privacyverklaring.php">Privacyverklaring</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-12">
                    <div class="widget widget_contact">
                        <div class="widget_title">
                            <h4>Contact Us</h4>
                        </div>
                        <div class="contact_info">
                            <div class="single_info">
                                <div class="icon">
                                    <i class="fas fa-phone-alt"></i>
                                </div>
                                <div class="info">
                                    <p><a href="tel:+3161234567890">Telefoonnummer</a></p>
                                </div>
                            </div>
                            <div class="single_info">
                                <div class="icon">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="info">
                                    <p><a href="mailto:Iproject36@han.nl">Mailadress</a></p>
                                </div>
                            </div>
                            <div class="single_info">
                                <div class="icon">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div class="info">
                                    <p>Professor Molkenboerstraat 3, 6524 RN Nijmegen</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="col-12 justify-content-center text-center">
                    <div class="widget widegt_about">
                        <ul class="social">
                            <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                            <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                            <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="copyright_area">
        <div class="container">
            <div class="row">
                <div class="col-12 justify-content-center text-center">
                    <div class="copyright_text">
                        <p>Copyright &copy; 2022 EenmaalAndermaal. All rights reserved.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="seo_text">
        <div class="container">
            
        </div>
    </div>
    </footer>
EOD;
    return $html;
}

//Deze functie maakt de de landendropdown of de vragendropdown aan de hand van het meegegeven type.
function registerDropdown($type)
{
    if ($type === 'country') {
        $sql = 'SELECT landnaam as value, landnaam as text FROM Landen ORDER BY landnaam ASC';
        $information = registerDropDownInfo($sql);
    } elseif ($type === 'question') {
        $sql = 'SELECT vraagnummer as value, tekstvraag as text FROM Vraag';
        $information = registerDropDownInfo($sql);
    }
    return registerDropdownGenerator($information);
}

//Deze functie maakt de dropdown voor de registratiepagina.
function registerDropdownGenerator($loopArray)
{
    $html = '';
    foreach ($loopArray as $site) {
        $html .= '<option value="' . $site['value'] . '">' . $site['text'] . '</option>';
    }
    return $html;
}

//Encryption & DataSafety_______________
//Deze functie zorgt met behulp van de meegegeven data dat alle gegevens gecheckt worden op fouten.
function encrypt($plainText, $key)
{
    $encryptionType = 'AES-256-GCM';
    $iv_len = openssl_cipher_iv_length($encryptionType);
    $options = OPENSSL_RAW_DATA;
    $tag_length = 16;
    $iv = openssl_random_pseudo_bytes($iv_len);
    $tag = '';
    $aditionalAuthData = '';
    $encryption = openssl_encrypt(
        $plainText,
        $encryptionType,
        $key,
        $options,
        $iv,
        $tag,
        $aditionalAuthData,
        $tag_length
    );
    return base64_encode($iv . $encryption . $tag);
}

//Decrypt gegevens
function decrypt($encryptedText, $key)
{
    $encryptionType = 'AES-256-GCM';
    $encodedText = base64_decode($encryptedText);
    $options = OPENSSL_RAW_DATA;
    $tag_length = 16;
    $iv_len = openssl_cipher_iv_length($encryptionType);
    $iv = substr($encodedText, 0, $iv_len);
    $ciphertext = substr($encodedText, $iv_len, -$tag_length);
    $tag = substr($encodedText, -$tag_length);
    $decryption = openssl_decrypt(
        $ciphertext,
        $encryptionType,
        $key,
        $options,
        $iv,
        $tag
    );
    return $decryption;
}
//Encrypt gegevens
function encryptStandardData($email, $key)
{
    $userData = getUserData($email, 'mailbox', '*');
    $sellerData = getSellerData($email);
    $userDataToEncrypt = ['voornaam', 'achternaam', 'postcode', 'adresregel_1', 'adresregel_2', 'plaatsnaam', 'mailbox', 'antwoordtekst'];
    $sellerDataToEncrypt = ['banknaam', 'rekeningnummer', 'creditcardnummer'];
    $phoneNumber = getUserTel($userData['gebruikersnaam']);
    $userData['phone'] = encrypt(standardDataSafety($phoneNumber), $key);
    foreach ($userDataToEncrypt as $encryptionItem) {
        if (!empty($userData[$encryptionItem])) {
            $userData[$encryptionItem] = encrypt(standardDataSafety($userData[$encryptionItem]), $key);
        }
    }
    foreach ($sellerDataToEncrypt as $sellerDataItem) {
        if (!empty($sellerData[$sellerDataItem])) {
            $sellerData[$sellerDataItem] = encrypt(standardDataSafety($sellerData[$sellerDataItem]), $key);
        }
    }
    return sendEncryptedUserData($userData, $sellerData) ? $userData['mailbox'] : errorRedirect('home', 'onbekendeFout');
}

function prepareData($data, $arrayItems)
{
    foreach ($arrayItems as $listitem) {
        $data[$listitem] = standardDataSafety($data[$listitem]);
    }
    if (!empty($arrayItems['adress'])) {
        password_hash($data['password'], PASSWORD_DEFAULT);
    }
    return $data;
}

//Deze functie zet de data neer zoals die in de database en op de site moet komen.
function standardDataSafety($data)
{
    return trim(htmlspecialchars($data));
}

//Register validation_____________
//Deze functie kijkt of de ingevoerde gegevens voldoen aan de criteria.
function registerValidation($data, $allData, $requiredData)
{
    return emptyDataCheck($data, $requiredData) && comparePasswordRegister($data['password'], $data['passwordconfirm'], $allData) && accountExistence($data) && checkForHTML($data, $allData) && checkTypes($data);
}


//Checkt of de types van de ingevoerde gegevens correct zijn
function checkTypes($data)
{
    return confirmPhoneNumber($data['phone']) && confirmZipCode($data['zipcode']) && confirmBirthdate($data['birthdate']) && confirmPassword($data['password'], 'register');
}

//Deze functie kijkt of het wachtwoord voldoet aan de eisen.
function confirmPassword($password, $redirect)
{
    $length = strlen($password);
    $number = preg_match('@[0-9]@', $password);
    return ($number && $length >= 7) ?: errorRedirect($redirect, 'wachtwoordTeKort');
}

//Deze functie kijkt of de geboortedatum voldoet aan de eisen.
function confirmBirthdate($birthdate)
{
    $birthdate = date('Y-m-d', strtotime($birthdate));
    $minBirthdate = date('Y-m-d', strtotime('01/01/1900'));
    $maxBirthdate = date('Y-m-d');
    $maxBirthdate = date('Y-m-d', strtotime($maxBirthdate));
    if (($birthdate >= $minBirthdate) && ($birthdate <= $maxBirthdate)) {
        return true;
    }
    errorRedirect('register', 'geboortedatumOnjuist');
}

function confirmBirthdateAccount($birthdate)
{
    $birthdate = date('Y-m-d', strtotime($birthdate));
    $minBirthdate = date('Y-m-d', strtotime('01/01/1900'));
    $maxBirthdate = date('Y-m-d');
    $maxBirthdate = date('Y-m-d', strtotime($maxBirthdate));
    if (($birthdate >= $minBirthdate) && ($birthdate <= $maxBirthdate)) {
        return true;
    }
}

//Deze functie kijkt of het telefoonnummer voldoet aan de eisen.
function confirmPhoneNumber($phonenumber) 
{
    return preg_match("/^[0-9]{10,11}+$/", $phonenumber) ?: errorRedirect('register', 'telefoonnummerOnjuist');
}

function confirmPhoneNumberAccount($phonenumber)
{
    return preg_match("/^[0-9]{10,11}+$/", $phonenumber);
}

//Deze functie kijkt of de postcode voldoet aan de eisen.
function confirmZipCode($zipcode)
{
    return preg_match("/^[0-9]{4}[a-zA-Z]{2}+$/", $zipcode) ?: errorRedirect('register', 'postcodeOnjuist');
}

function confirmZipCodeAccount($zipcode)
{
    return preg_match("/^[0-9]{4}[a-zA-Z]{2}+$/", $zipcode);
}

//Deze functie vergelijkt of het ingevoerde wachtwoord overeenkomt met het 2e wachtwoord.
function comparePasswordRegister($password1, $password2)
{
    return compareVariables($password1, $password2) ?: errorRedirect('register', 'wachtwoordRegistrerenOnjuist');
}

// deze functie vergelijkt of het ingevoerde wachtwoord oveernkomt met het wachtwoord in de database
function comparePasswordLogin($password1, $password2)
{
    return password_verify($password1, $password2) ?: errorRedirect('login', 'wachtwoordOnjuist');
}

//Deze functie kijkt of er HTML in de ingevoerde gegevens zit.
function checkForHTML($data, $allArrayData)
{
    foreach ($allArrayData as $listitem) {
        if (htmlspecialchars($data[$listitem]) !== $data[$listitem]) {
            return false;
        }
    }
    return true;
}

//Checkt of de ingevoerde gegevens niet leeg zijn
function emptyDataCheck($data, $requiredArrayData)
{
    foreach ($requiredArrayData as $listitem) {
        if (empty($data[$listitem])) {
            return false;
        }
    }
    return true;
}

//Deze functie kijkt of het ingevoerde emailadres wel een emailadres is.
function confirmEmail($checkableEmail)
{
    $email = filter_var($checkableEmail['email'], FILTER_SANITIZE_EMAIL);
    return filter_var($email, FILTER_VALIDATE_EMAIL) === $checkableEmail['email'];
}

//Kijkt of het account bestaat.
function accountExistence($data)
{
    $suspectedUser = getUserData(htmlspecialchars($data['username']), 'gebruikersnaam', 'gebruikersnaam');
    if (empty($suspectedUser)) {
        return true;
    } elseif (compareVariables(trim($suspectedUser['gebruikersnaam']), trim($data['username']))) {
        errorRedirect('register', 'accountnaamAlGebruikt');
    } else {
        return true;
    }
}

function emailExistence($mail, $username, $key)
{
    $suspectedUser = getUserData(htmlspecialchars($username), 'gebruikersnaam', 'mailbox');
    $mailbox = !decrypt($suspectedUser['mailbox'], $key) ? $suspectedUser['mailbox'] : decrypt($suspectedUser['mailbox'], $key);
    return compareVariables(trim($mailbox), trim($mail));
}


function compareAllMails($mail, $key)
{
    $suspectedMail = getAllUserMail();
    foreach ($suspectedMail as $mailAdress) {
        $mailing['mailbox'] = $mailAdress['mailbox'];
        $decrypted['mailbox'] = decrypt($mailing['mailbox'], $key);
        if (compareVariables($decrypted['mailbox'], $mail['email'])) {
            $mailing['check'] = 'encrypted';
            return $mailing;
        }
        if (compareVariables($mailing['mailbox'], $mail['email'])) {
            $mailing['check'] = 'normaal';
            return $mailing;
        }
    }
}

//Checkt of het email al bestaat
function isEmailUsed($mail, $check, $key)
{
    $suspectedUser = compareAllMails($mail, $key);
    if (empty($suspectedUser)) {
        return false;
    } elseif (compareVariables(trim(decrypt($suspectedUser['mailbox'], $key)), trim($mail['email'])) && confirmEmail($mail) && $check === 'check' && $suspectedUser['check'] === 'encrypted') {
        return $suspectedUser['mailbox'];
    } elseif (compareVariables(trim($suspectedUser['mailbox']), trim($mail['email'])) && confirmEmail($mail) && $check === 'check' && $suspectedUser['check'] === 'normaal') {
        return 'normaal';
    } elseif (compareVariables(trim($suspectedUser['mailbox']), trim($mail['email']))) {
        return true;
    } elseif (compareVariables(decrypt(trim($suspectedUser['mailbox']), $key), trim($mail['email']))) {
        return true;
    } else {
        return false;
    }
}

//Kijkt of de gebruiker ouder dan 18 is
function checkAccountAge()
{
    $username = $_SESSION['user'];
    $userBirthDate = getUserData($username, 'gebruikersnaam', 'geboortedag')[0];
    $today = date("Y-m-d");
    $diff = date_diff(date_create($userBirthDate), date_create($today));
    if ($diff->format('%y') <= '18') {
        errorRedirect('home', 'teJong');
    }
}

function redirectBlocked()
{
    if (isUserBlocked($_SESSION['user'])) {
        errorRedirect('home', 'geblokkeerd');
    }
}


//Verkoperworden validation_________

function checkIBAN($iban)
{

    $iban = strtolower($iban);
    $countries = array(
        'al' => 28, 'ad' => 24, 'at' => 20, 'az' => 28, 'bh' => 22, 'be' => 16, 'ba' => 20, 'br' => 29, 'bg' => 22, 'cr' => 21, 'hr' => 21, 'cy' => 28, 'cz' => 24,
        'dk' => 18, 'do' => 28, 'ee' => 20, 'fo' => 18, 'fi' => 18, 'fr' => 27, 'ge' => 22, 'de' => 22, 'gi' => 23, 'gr' => 27, 'gl' => 18, 'gt' => 28, 'hu' => 28,
        'is' => 26, 'ie' => 22, 'il' => 23, 'it' => 27, 'jo' => 30, 'kz' => 20, 'kw' => 30, 'lv' => 21, 'lb' => 28, 'li' => 21, 'lt' => 20, 'lu' => 20, 'mk' => 19,
        'mt' => 31, 'mr' => 27, 'mu' => 30, 'mc' => 27, 'md' => 24, 'me' => 22, 'nl' => 18, 'no' => 15, 'pk' => 24, 'ps' => 29, 'pl' => 28, 'pt' => 25, 'qa' => 29,
        'ro' => 24, 'sm' => 27, 'sa' => 24, 'rs' => 22, 'sk' => 24, 'si' => 19, 'es' => 24, 'se' => 24, 'ch' => 21, 'tn' => 24, 'tr' => 26, 'ae' => 23, 'gb' => 22, 'vg' => 24
    );
    $chars = array(
        'a' => 10, 'b' => 11, 'c' => 12, 'd' => 13, 'e' => 14, 'f' => 15, 'g' => 16, 'h' => 17, 'i' => 18, 'j' => 19, 'k' => 20, 'l' => 21, 'm' => 22,
        'n' => 23, 'o' => 24, 'p' => 25, 'q' => 26, 'r' => 27, 's' => 28, 't' => 29, 'u' => 30, 'v' => 31, 'w' => 32, 'x' => 33, 'y' => 34, 'z' => 35
    );

    if (strlen($iban) !== $countries[substr($iban, 0, 2)]) {
        return false;
    }

    $movedChar = substr($iban, 4) . substr($iban, 0, 4);
    $movedCharArray = str_split($movedChar);
    $newString = "";

    foreach ($movedCharArray as $char => $v) {
        if (!is_numeric($movedCharArray[$char])) {
            $movedCharArray[$char] = $chars[$movedCharArray[$char]];
        }
        $newString .= $movedCharArray[$char];
    }

    $x = $newString;
    $y = "97";
    $take = 5;
    $mod = "";

    do {
        $a = (int)$mod . substr($x, 0, $take);
        $x = substr($x, $take);
        $mod = $a % $y;
    } while (strlen($x));

    return (int)$mod == 1;
}

function checkCreditcard($cc)
{
    $total = 0;
    $i = 1;
    $last4 = substr($cc, -4, 4);
    $cc = str_split($cc);
    $cc = array_reverse($cc);
    foreach ($cc as $digit) {
        if ($i % 2 == 0) {
            $digit *= 2;

            if ($digit > 9) {
                $digit -= 9;
            }
        }
        $total += $digit;
        $i++;
    }

    if ($total % 10 == 0) {
        return true;
    } else {
        return false;
    }
}

//Deze functie kijkt of 2 variabeles gelijk aan elkaar zijn
function compareVariables($inputOne, $inputTwo)
{
    return $inputOne === $inputTwo;
}

//Deze functie stuurt je door naar een andere pagina in de presentatielaag
function redirect($location)
{
    header('location: ../site/' . $location . '.php');
    exit();
}

//Deze functie stuurt je door naar een andere pagina in de presentatielaag en geef voorwerp id mee
function redirectBieden($location, $id)
{
    header('location: ../site/' . $location . '.php' . '?id=' . $id);
    exit();
}


//Deze functie stuurt je door naar een andere pagina in de presentatielaag met een error
function errorRedirect($location, $error)
{
    header('location: ../site/' . $location . '.php?error=' . $error);
    exit();
}

//Deze functie stuurt je door naar een andere pagina met een melding
function succesRedirect($location, $succes)
{
    header('location: ../site/' . $location . '.php?succes=' . $succes);
    exit();
}

function succesType($dirtySuccesCode)
{
    if (empty($dirtySuccesCode)) {
        return NULL;
    } else {
        $cleanSuccesCode = htmlspecialchars($dirtySuccesCode);
        $html = '<div class="alert alert-success alert-dismissible fade show" role="alert">';
        $prefix = 'Behandeling is succesvol uitgevoerd: ';
        switch ($cleanSuccesCode) {
            case 'bodingetrokken':
                $html .= $prefix . 'het bod is ingetrokken!';
                break;
            case 'bodgeaccepteerd':
                $html .= 'Het bod is geaccepteerd!';
                break;
            case 'veilingannuleren':
                $html .= 'De veiling is geannuleerd!';
                break;
            case 'producttoevoegen':
                $html .= 'Het product is toegevoegd!';
                break;
            case 'accountgeupdate':
                $html .= 'De profielgegevens zijn succesvol bijgewerkt!';
                break;
            case 'geblokkeerd':
                $html .= 'Het account is geblokkeerd!';
                break;
            case 'heractiveerd':
                $html .= 'Het account is geheractiveerd!';
                break;
            case 'verkoperAanvraagGestuurd':
                $html .= 'De aanvraag is verstuurd. Je krijgt Z.S.M. van EenmaalAndermaal te horen.';
                break;
            case 'verkoperGedeactiveerd':
                $html .= 'De verkoper is succesvol gedeactiveerd!';
                break;
            case 'creditcardToegevoegd':
                $html .= 'De gegevens zijn toegevoegd!';
                break;
            case 'voorlopverwijderd':
                $html .= 'Het account is verwijderd, u heeft 10 dagen de tijd om te beslissen of u uw eenmaalandermaal account verwijderd wil hebben. Kijk in uw mailbox voor meer informatie.';
                break;
            case 'codesent':
                $html .= 'De code is verstuurd!';
                break;
            case 'accountterug':
                $html .= 'Uw account is teruggezet.';
                break;
            case 'postgestuurd':
                $html .= 'Er wordt een brief naar uw adres gestuurd, deze zal binnen 5 dagen aankomen.';
                break;
            case 'accverwijderd':
                $html .= 'Het account heeft de verwijderdstatus gekregen, er is een mail gestuurd naar de gebruiker.';
                break;
            case 'accountterugAdmin':
                $html .= 'Het account is teruggezet.';
                break;
        }
        $html .= '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div> ';
        return $html;
    }
}

//Deze functie maakt de HTML voor de verschillende errors op de site.
function errorType($dirtyErrorCode)
{
    if (empty($dirtyErrorCode)) {
        return NULL;
    } else {
        $cleanErrorCode = htmlspecialchars($dirtyErrorCode);
        $html = '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
        $prefix = 'Er is een fout opgetreden met het ';
        switch ($cleanErrorCode) {
            case 'onbekendeFout':
                $html .= 'Er is een onbekende fout opgetreden, probeert u het later opnieuw.';
                break;
            case 'accountnaamAlGebruikt':
                $html .= $prefix . 'aanmaken van een account, de accountnaam wordt al gebruikt.';
                break;
            case 'emailAlGebruikt':
                $html .= $prefix . 'aanmaken van een account, het email-adres wordt al gebruikt.';
                break;
            case 'postcodeOnjuist':
                $html .= $prefix . 'aanmaken van het account, er is een geen geldige postcode ingevuld.';
                break;
            case 'wachtwoordTeKort':
                $html .= $prefix . 'aanmaken van het account, het wachtwoord moet minstens zeven tekens en minimaal één cijfer bevatten.';
                break;
            case 'wachtwoordOnjuist':
                $html .= $prefix . 'inloggen, het wachtwoord is onjuist.';
                break;
            case 'gebruikersnaamOnjuist':
                $html .= $prefix . 'inloggen, de gebruikersnaam is verkeerd ingevoerd of niet bij ons bekend.';
                break;
            case 'wachtwoordRegistrerenOnjuist':
                $html .= $prefix . 'aanmaken van het account, de wachtwoorden komen niet overeen.';
                break;
            case 'geboortedatumOnjuist':
                $html .= $prefix . 'aanmaken van het account, de geboortedatum is onjuist.';
                break;
            case 'plaatjeFormaatOnjuist':
                $html .= $prefix . 'toevoegen van het product, er is geen geldig plaatje geüpload.';
                break;
            case 'plaatjeTeGroot':
                $html .= $prefix . 'toevoegen van het product, het plaatje is te groot.';
                break;
            case 'uploadenMislukt':
                $html .= $prefix . 'toevoegen van het product, het uploaden is mislukt.';
                break;
            case 'aantalPlaatjes':
                $html .= $prefix . 'toevoegen van het product, er mogen maximaal 4 plaatjes worden toegevoegd.';
                break;
            case 'bankrekeningnummerOnjuist':
                $html .= $prefix . 'invullen van de gegevens, het bankrekeningnummer is onjuist.';
                break;
            case 'credditcardNummerOnjuist':
                $html .= $prefix . 'invullen van de gegevens, het credditcardnummer is onjuist.';
                break;
            case 'bodtelaag':
                $html .= $prefix . 'het toevoegen van uw bod, het bod is te laag.';
                break;
            case 'onjuisteverificatiecode':
                $html .= $prefix . 'het controleren van uw code, uw verificatiecode klopt niet. Probeer een nieuwe verificatiecode aan te vragen.';
                break;
            case 'emailAlGebruiktPreRegister':
                $html .= $prefix . 'versturen van de verificatiecode, het email-adres wordt al gebruikt.';
                break;
            case 'emailBestaatNiet':
                $html .= $prefix . 'versturen van de verificatiecode, het email-adres hoort niet bij deze gebruikersnaam.';
                break;
            case 'wachtWoordenOngelijk':
                $html .= $prefix . 'veranderen van uw wachtwoord, de wachtwoorden komen niet overeen.';
                break;
            case 'onjuistAntwoord':
                $html .= $prefix . 'veranderen van uw wachtwoord, het antwoord op uw controlevraag klopte niet.';
                break;
            case 'noggeenbod':
                $html .= $prefix . 'accepteren van het bod. Er is nog geen bod beschikbaar om te accepteren.';
                break;
            case 'veilingalgesloten':
                $html .= $prefix . 'het accepteren van het bod. Er is al een bod op deze veiling geaccepteerd.';
                break;
            case 'teJong':
                $html .= 'Om een verkoper te worden moet je 18 jaar of ouder zijn.';
                break;
            case 'alGeblokkeerd':
                $html .= $prefix . 'het blokkeren van het account. Het account is al geblokkeerd.';
                break;
            case 'geblokkeerdBieden':
                $html .= $prefix . 'het plaatsen van een bod. Het account is geblokkeerd.';
                break;
            case 'geblokkeerd':
                $html .= 'Uw account is geblokkeerd.';
                break;
            case 'geenVerkoper':
                $html .= $prefix . 'het deactiveren van deze verkoper. De gebruiker is geen verkoper.';
                break;
            case 'verwijderfout':
                $html .= $prefix . 'het verwijderen van het account. Neem zo snel mogelijk contact op!';
                break;
            case 'foutgegevens':
                $html .= $prefix . 'het updaten van de gegevens, er zijn geen geldige gegevens ingevuld.';
                break;
        }
        $html .= '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div> ';
        return $html;
    }
}

/*LOGIN_________________________________________*/
//Deze functie kijkt of de gebruiker ingelogd kan worden.
function loginValidation($data, $itemsToCheck)
{
    $userInformation = getExistingUser($data);
    return comparePasswordLogin($data['password'], $userInformation['wachtwoord']) && checkForHTML($data, $itemsToCheck) && !$userInformation['verwijderd'];
}

//Deze functie zorgt ervoor dat de gebruiker ingelogd wordt.
function signInUser($userData)
{
    $_SESSION['user'] = $userData['username'];
    $_SESSION['email'] = getUserCredentials(standardDataSafety($userData['username']), 'null')['mailbox'];
    $_SESSION['sellerStatus'] = getSellerStatus($userData['username']);
    $_SESSION['adminStatus'] = getAdminStatus($userData['username'])['beheerderstatus'];
}


//DEBUG__________________________________________
//Deze methode zet errorberichten uit.
function debugMessages($type)
{
    switch ($type) {
        case 'off':
            error_reporting(E_ALL);
            ini_set('display_errors', 0);
            break;
        case 'on':
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
            break;
    }
}

///Deze functie zet producten neer.
function homepageProducts($data)
{
    if ($data === 'popular') {
        $products = getPopularProducts(8);
    } elseif ($data === 'old') {
        $products = getProducts(12, 'looptijdeindeDag DESC');
    } elseif ($data === 'new') {
        $products = getProducts(20, 'looptijdeindeDag ASC');
    }
    $html = '';

    foreach ($products as $product) {
        $productTitle = $product['titel'];
        $productDescription = $product['beschrijving'];
        $productNumber = $product['voorwerpnummer'];
        $image = getImage(1, $productNumber, 'thumbnails', $product['seller'])[0];
        $productTime = convertToHiddenTime($product['endDay'], $product['endTime']);
        $html .= <<<EOD
        <div class="col-sm-12 col-md-6 col-lg-3 align-items-stretch">
            <div class="Product">
                <div class="card">
                    <div class="thumb" style="background-image: url($image);"></div>
                    <article>
                        <h1> $productTitle </h1>
                        <div class="tekst">
                            <p> $productDescription </p>
                        </div>
                        <span class="countdownclock">
                            <i class="bi-clock countdown" data-time="$productTime"></i>
                            <button onclick="window.location.href='productpage.php?id=$productNumber'" class="btn btn-primary me-md-2 auctionButton" type="button"> Bied mee!</button>
                        </span>
                    </article>
                </div>
            </div>
        </div>
EOD;
    }
    return $html;
}


function getImage($number, $productId, $type, $seller)
{
    $fileName = 0;
    if (filter_var($productId, FILTER_VALIDATE_INT) && filter_var($number, FILTER_VALIDATE_INT)) {
        $images = getImageName(standardDataSafety($number), standardDataSafety($productId));
        $fileNames = [];
        if (empty($images)) {
            $images[0] = 'img/placeholder.png';
            return $images;
        }
        if (trim($seller) === 'docentgebruikersnaam') {
            for ($index = 0; $index < $number; $index++) {
                array_push($fileNames, 'https://iproject36.ip.aimsites.nl/' . 'pics' .  '/' . $images[$index][$fileName]);
            }
        } else {
            for ($index = 0; $index < $number; $index++) {
                array_push($fileNames, $type .  '/' . $images[$index][$fileName]);
            }
        }
        return $fileNames;
    }
}

//Returnt de aflopende tijd
function countDown($productId)
{
    $endDay = getProductInfo($productId, 'looptijdEindeDag')[0]['looptijdEindeDag'];
    $endTime = getProductInfo($productId, 'looptijdEindetijdstip')[0]['looptijdEindetijdstip'];
    return '<span class="countdownclock"><i class="bi-clock countdown" data-time=' . convertToHiddenTime($endDay, $endTime) . '></i></span';
}

function convertToHiddenTime($endDay, $endTime)
{
    $currentTime = strtotime(date('Y-m-d H:i:s'));
    return strtotime($endDay . $endTime) - $currentTime;
}
//Deze functie haalt productinformatie op
function getProductHtml($product, $info)
{
    if (filter_var($product, FILTER_VALIDATE_INT)) {
        return getProductInfo($product, $info)[0]["$info"];
    }
}

//Checkt of het een int is
function checkNumeric($var)
{
    if (!filter_var($var, FILTER_VALIDATE_INT)) {
        redirect('home');
    }
}

//Checkt of een product kan worden geupload
function productUploadAllowed($input, $arrayItems, $session, $requiredArrayData, $amountOfImages, $maxImages)
{
    return checkForHTML($input, $arrayItems) && emptyDataCheck($input, $requiredArrayData) && isUserSeller($session['user']) && checkPaymentOption($input['paymentmethod']) && checkDuration($input['timelisted']) && checkImage($amountOfImages, $maxImages);
}

//checkt of er minder dan 4 plaatjes geupload zijn
function checkImage($nrOfImages, $maxImages) 
{
    return $nrOfImages <= $maxImages ?: errorRedirect('addproduct', 'aantalPlaatjes');
}

//Controleert betaal opties
function checkPaymentOption($option)
{
    return $option === 'IDEAL' || $option === 'Creditcard' || $option === 'Cash';
}

//Controleert of de tijd geldig is
function checkDuration($duration)
{
    $options = array(1, 3, 5, 7, 10);
    if (filter_var($duration, FILTER_VALIDATE_INT)) {
        foreach ($options as $option) {
            if ($duration == $option) {
                return true;
            }
        }
    } else {
        return false;
    }
    return false;
}

//Checkt of het een geldige land is
function checkCountry($countryInput)
{
    $sql = 'SELECT landnaam FROM Landen';
    $information = registerDropDownInfo($sql);

    foreach ($information as $country) {
        if ($country == $countryInput) {
            return true;
        }
    }
    return false;
}

//checkt of de categorie geldig is
function checkCategory($category)
{
    $rubrics = getRubricInformation();

    foreach ($rubrics as $rubric) {
        if ($rubric[1] == $category) {
            return true;
        }
    }
    return false;
}


//Checkt of de gebruiker een verkoper is
function isUserSeller($user)
{
    return getSellerStatus($user)[0];
}

//Checkt of de gebruiker een admin is
function isUserAdmin($session)
{
    return getAdminStatus($session['user'])[0];
}


//Checkt of de gebruiker geblokkeerd is
function isUserBlocked($user)
{
    return getBlockedStatus($user)[0];
}


//Deze functie laat gebruikers zien voor de beheerder 
function getUsersHTML($userInput)
{
    $key = getEnvFile('encryptionkey', 'app');
    $userInput = standardDataSafety($userInput);
    $users = getUsers($userInput);
    $html = '';
    if ($userInput != '') {
        $userInput = standardDataSafety($userInput);
        $users = getUsers($userInput);
        $iteration = 0;
        foreach ($users as $user) {
            $iteration++;
            if (isUserBlocked($user['gebruikersnaam'])) {
                $status = "Geblokkeerd";
            } elseif (isUserDeleted($user['gebruikersnaam'])) {
                $status = 'Verwijderd';
            } else {
                $status = "Actief";
            }
            if (isUserSeller($user['gebruikersnaam'])) {
                $seller = "Ja";
            } else {
                $seller = "Nee";
            }
            $html .= "<tr> <td><a class='link-primary' href='../site/accountpagina.php?username=" . $user['gebruikersnaam'] . "'>" . $user['gebruikersnaam'] .  "</a></td>
                    <td>" . getUserMail($user['mailbox'], $key) . "</td>
                    <td>" . $seller . "</td>
                    <td>" . $status . "</td>
                    <td>
                        <div class='dropdown'>
                            <button class='btn btn-primary dropdown-toggle' type='button' id='dropdownActies" . $iteration . "' data-bs-toggle='dropdown' aria-expanded='false'>
                                Acties
                            </button>
                            <div class='dropdown-menu'>" .
                getSellerButton($user, $iteration) .
                getBlockButton($user, $iteration) .
                getDeleteButton($user, $iteration) . "
                                </div> " .
                getSellerModal($user, $iteration) .
                getBlockModal($user, $iteration) .
                getDeleteModal($user, $_SESSION['adminStatus'], $iteration) .
                "
                        </div>
                    </td>
                </tr>";
        }
    }
    return $html;
}

function getUserMail($email, $key)
{
    $mail = decrypt($email, $key);
    return $mail ? $mail : $email;
}

//Returnt knoppen voor verkoper maken
function getSellerButton($user, $iteration)
{
    if (isUserSeller($user['gebruikersnaam'])) {
        $button = " <div class='d-grid'>
                            <button type='button' class='btn btn-secondary' data-bs-toggle='modal' data-bs-target='#unSetSeller" . $iteration . "'>Geen verkoper</button>
                        </div>";
    } else {
        $button = " <div class='d-grid'>
                            <button type='button' class='btn btn-secondary' data-bs-toggle='modal' data-bs-target='#setSeller" . $iteration . "'>Verkoper</button>
                        </div>";
    }
    return $button;
}

//Returnt pop-up voor verkoper maken
function getSellerModal($user, $iteration)
{
    if (isUserSeller($user['gebruikersnaam'])) {
        $modal = " 
                    <form class='w-100' method='post' action='../app/unmakeseller.php?user=" . $user['gebruikersnaam'] . "'>
                        <div class='modal fade' id='unSetSeller" . $iteration . "' tabindex='-1'>
                            <div class='modal-dialog modal-dialog-centered'>
                                <div class='modal-content'>
                                    <div class='modal-header'>
                                        <h5 class='modal-title' id='unSetSellerLabel" . $iteration . "'>Weet u het zeker?</h5>
                                        <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                                    </div>
                                    <div class='modal-body'>
                                        De gebruiker zal geen verkoper meer zijn als u op 'ja' klikt.
                                    </div>
                                    <div class='modal-footer'>
                                        <button type='button' class='btn btn-secondary w-25' data-bs-dismiss='modal'>Nee</button>
                                        <button type='submit' class='btn btn-primary w-25'>Ja</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>";
    } else {
        $modal = "
                    <form class='w-100' method='post' action='../app/makeseller.php?user=" . $user['gebruikersnaam'] . "'>
                        <div class='modal fade' id='setSeller" . $iteration . "' tabindex='-1'>
                            <div class='modal-dialog modal-dialog-centered'>
                                <div class='modal-content'>
                                    <div class='modal-header'>
                                        <h5 class='modal-title' id='setSellerLabel" . $iteration . "'>Vul de gegevens in</h5>
                                        <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                                    </div>
                                    <div class='modal-body'>
                                        <div class='row justify-content-center text-center'>
                                            <div class='form-group col-sm-12 flex-column d-flex'>
                                                <label class='form-control-label px-3'>
                                                    Controle-optie*
                                                </label>
                                                <select class='form-select form-select-lg sm-1' aria-label='.form-select-lg example' id='option' name='option'>
                                                    <option value='creditcard'>Creditcard</option>
                                                    <option value='bank'>Bank</option>
                                                </select>
                                            </div>
                                        </div>
            
                                        <div class='row justify-content-center text-center' id='creditcardform'>
                                            <div class='form-group col-sm-12 flex-column d-flex'>
                                                <label class='form-control-label px-3'>
                                                    Creditcardnummer
                                                </label>
                                                <input class='form-control-lg' type='number' placeholder='5555555555554444' name='cardnumber'>
                                            </div>
                                        </div>
            
                                        <div class='row justify-content-center text-center'>
                                            <div class='form-group col-sm-6 flex-column d-flex'>
                                                <label class='form-control-label px-3'>
                                                    Bank
                                                </label>
                                                <select class='form-select form-select-lg sm-1' aria-label='.form-select-lg example' id='bank' name='bank'>
                                                    <option value='rabobank'>Rabobank</option>
                                                    <option value='ING'>ING</option>
                                                    <option value='ABN AMRO'>ABN AMRO</option>
                                                    <option value='ASN bank'>ASN Bank</option>
                                                    <option value='bunq'>Bunq</option>
                                                    <option value='SNS bank'>SNS Bank</option>
                                                    <option value='knab'>Knab</option>
                                                </select>
                                            </div>
                                            <div class='form-group col-sm-6 flex-column d-flex'>
                                                <label class='form-control-label px-3'>
                                                    Rekeningnummer
                                                </label>
                                                <input class='form-control-lg' type='text' placeholder='NL44RABO0123456789' name='bankaccount'>
                                            </div>
                                        </div>
                                    </div>
                                    <div class='modal-footer'>
                                        <button type='submit' class='btn btn-primary w-25'>Sla op</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>";
    }
    return $modal;
}

//Returnt knoppen voor blokkeren
function getBlockButton($user, $iteration)
{
    if (isUserBlocked($user['gebruikersnaam'])) {
        $button = " <div class='d-grid'>
                            <button type='button' class='btn btn-secondary' data-bs-toggle='modal' data-bs-target='#unBlockUser" . $iteration . "'>Reactiveer</button>
                        </div>";
    } else {
        $button = " <div class='d-grid'>
                            <button type='button' class='btn btn-secondary' data-bs-toggle='modal' data-bs-target='#blockUser" . $iteration . "'>Blokkeer</button>
                        </div>";
    }
    return $button;
}

//Returnt pop-up voor blokkeren
function getBlockModal($user, $iteration)
{
    if (isUserBlocked($user['gebruikersnaam'])) {
        $modal = "
                        <form class='w-100' method='post' action='../app/unblockuser.php?username=" . $user['gebruikersnaam'] .  "'>
                            <div class='modal fade' id='unBlockUser" . $iteration . "' tabindex='-1'>
                                <div class='modal-dialog modal-dialog-centered'>
                                    <div class='modal-content'>
                                        <div class='modal-header'>
                                            <h5 class='modal-title' id='unBlockUserLabel" . $iteration . "'>Weet u het zeker?</h5>
                                            <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                                        </div>
                                        <div class='modal-body'>
                                            De gebruiker zal worden gereactiveerd als u op 'ja' klikt.
                                        </div>
                                        <div class='modal-footer'>
                                            <button type='button' class='btn btn-secondary w-25' data-bs-dismiss='modal'>Nee</button>
                                            <button type='submit' class='btn btn-primary w-25'>Ja</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>";
    } else {
        $modal = "
                <form class='w-100' method='post' action='../app/blockuser.php?username=" . $user['gebruikersnaam'] .  "'>
                    <div class='modal fade' id='blockUser" . $iteration . "' tabindex='-1'>
                        <div class='modal-dialog modal-dialog-centered'>
                            <div class='modal-content'>
                                <div class='modal-header'>
                                    <h5 class='modal-title' id='blockUserLabel" . $iteration . "'>Weet u het zeker?</h5>
                                    <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                                </div>
                                <div class='modal-body'>
                                    De gebruiker zal worden geblokkeerd als u op 'ja' klikt.
                                </div>
                                <div class='modal-footer'>
                                    <button type='button' class='btn btn-secondary w-25' data-bs-dismiss='modal'>Nee</button>
                                    <button type='submit' class='btn btn-primary w-25'>Ja</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>";
    }
    return $modal;
}

//Returnt knoppen voor gebruiker verwijderen
function getDeleteButton($user, $iteration)
{
    if (isUserDeleted($user['gebruikersnaam'])) {
        return '<div class="d-grid"> 
                <button class="btn btn-secondary" data-bs-toggle="modal" type="button" data-bs-target="#reinstateUser' . $iteration . '">Account herstellen</button>
        </div>';
    } else {
        return '<div class="d-grid"> 
                <button class="btn btn-secondary" data-bs-toggle="modal" type="button" data-bs-target="#deleteUser' . $iteration . '">Account verwijderen</button>
        </div>';
    }
}

//Returnt pop-up voor gebruiker verwijderen
function getDeleteModal($postUsername, $adminStatus, $iteration)
{
    $userStatus = isUserDeleted($postUsername['gebruikersnaam']);
    $viewerType1 = $adminStatus ? 'het' : 'uw';
    $viewerType2 = $adminStatus ? 'Het' : 'Uw';
    $viewerType3 = $adminStatus ? 'U of de desbetreffende gebruiker' : 'U';
    $idType = $userStatus ? 'reinstateUser' : 'deleteUser';
    $viewerType4 = $userStatus ? 'herstellen' : 'verwijderen';
    $viewerType5 = $userStatus ?  "$viewerType2 account zal worden hersteld als u op 'Ja' klikt.\n" : "$viewerType2 account zal worden verwijderd als u op 'Ja' klikt.\n $viewerType3 heeft dan 10 dagen de tijd om het account weer actief te maken, anders zal het account verwijderd worden.";
    $viewerType6 = $userStatus ? 'reinstateuser.php?name=' . trim($postUsername['gebruikersnaam']) . ' &token=' . $_SESSION['verificationCodeAccount'] : 'tempremoveuser.php';
    $modal = "
        <form class='w-100' method='post' action='../app/$viewerType6'>
            <div class='modal fade' id='$idType" .  $iteration . "' tabindex='-1'>";
    $modal .= <<<EOD
                <div class='modal-dialog modal-dialog-centered'>
                    <input name="user" type='hidden' value="{$postUsername['gebruikersnaam']}">
                    <div class='modal-dialog modal-dialog-centered'>
                        <div class='modal-content'>
                            <div class='modal-header'>
                                <h5 class='modal-title'>Weet u zeker dat u $viewerType1 account wil $viewerType4?</h5>
                                <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                            </div>
                            <div class='modal-body'>
                                $viewerType5
                            </div>
                            <div class='modal-footer'>
                                <button type='button' class='btn btn-secondary w-25' data-bs-dismiss='modal'>Nee</button>
                                <button type='submit' class='btn btn-primary w-25'>Ja</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
EOD;
    return $modal;
}


//Deze functie beslist of een bestand te groot is of niet.
function checkFileSize($file, $maxSize, $index)
{
    return $file['size'][$index] <= $maxSize ?: errorRedirect('addproduct', 'plaatjeFormaatOnjuist');
}

function checkFileSizeId($file, $maxSize)
{
    return $file['size'] <= $maxSize ?: errorRedirect('verkoper-worden', 'plaatjeFormaatOnjuist');
}

//Deze fucntie controleert of de plaatjes de juiste bestanden zijn
function checkAllowedImageTypes($imageExtension)
{
    return $imageExtension === 'jpg' || $imageExtension === 'png' || $imageExtension === 'jpeg' ?: errorRedirect('addproduct', 'plaatjeFormaatOnjuist');
}

function checkAllowedImageTypesId($imageExtension)
{
    return $imageExtension === 'jpg' || $imageExtension === 'png' || $imageExtension === 'jpeg' ?: errorRedirect('verkoper-worden', 'plaatjeFormaatOnjuist');
}

//Verwijdert de spaties in een string
function removeSpaces($string)
{
    return str_replace(' ', '', $string);
}

//Deze functie returnt een unieke string voor de plaatjes
function convertFileName($title, $filetype, $maxLength, $index)
{
    $title = removeSpaces(standarddatasafety($title)) . strval($index);
    $time = getdate();
    $addonInfo = $time['seconds'] . $time['minutes'] . $time['hours'] . '.' . $filetype;
    if (strlen($title) + strlen($addonInfo) < $maxLength) {
        return $title . $addonInfo;
    } else {
        $difference = intval(strlen($title) + strlen($addonInfo) - $maxLength);
        $title = substr($title, 0,  -$difference - 1) . strval($index);
        return $title . $addonInfo;
    }
}

//Deze functie voegt het product toe aan de database
function addProductInfo($input, $category, $time)
{
    addProduct($input, $time) || errorRedirect('addproduct', 'uploadenMislukt');
    $productNumber = getProductNumber($input, $time);
    $rubricsNumber = getRubriekNumber($category);
    addProductRubic($productNumber, $rubricsNumber) || errorRedirect('addproduct', 'uploadenMislukt');
}

//Deze functie verwijdert een plaatje
function removeImage($fileName, $directory)
{
    $file = '../site/' . $directory . $fileName;
    if (file_exists($file)) {
        return unlink($file);
    } else {
        return true;
    }
}

//Deze functie verwijdert een veiling
function removeProduct($postInfo)
{
    removeProductInfo($postInfo);
}


//Deze functie laat de aflooptijd zien
function displayTime($productId)
{
    $time = getTime(standarddatasafety($productId));
    $timeFiltered = strtotime($time);
    return date("h:i:s", $timeFiltered);
}

// BOD FUNCTIES
//checkt of bod een nummer is 
function bidValidation($bid, $id)
{
    return (is_numeric($bid) && bidIsHigher($bid, $id) && checkBidHigherThanZero($bid)) && checkBidDifference($bid, $id);
}


//checkt of bod hoger is
function bidIsHigher($bid, $id)
{
    $highestBidData = getHighestBid($id);
    return empty($highestBidData) && $bid >= getStartingPrice($id) ?: $bid > $highestBidData;
}

//checkt of bod hoger is dan 0
function checkBidHigherThanZero($bid)
{
    return $bid > 0;
}

//checkt of het verschil hoog genoeg is
function checkBidDifference($newBid, $id)
{
    return $newBid - getHighestBid($id) >= minBidRaise($newBid);
}


//Return hoogste bod, uitgezonderd geblokkeerde gebruikers
function getHighestBid($id)
{
    return getHighestBidData($id, 1, 'Bodbedrag')[0]['Bodbedrag'];
}

//krijgt String voor hoogste bod
function getHighestBidHTML($id)
{
    $html = '€';
    if (!empty(getHighestBidData($id, 1, 'Bodbedrag')[0]['Bodbedrag'])) {
        if (getHighestBid($id) != 0) {
            $html .= getHighestBid($id);
        } else {
            $html .= '0.00';
        }
    } else {
        $html .= '0.00';
    }
    return $html;
}

//krijgt lijstje html voor hoogste aantal biedingen met bedrag en gebruikersnaam in een lijst
function getHighestBidsHTMLList($id, $amount)
{
    $html = '';
    for ($iteration = 0; $iteration < $amount; $iteration++) {
        if (getHighestBid($id) !== 0) {
            $user = getHighestBidData($id, $iteration + 1, 'gebruikersnaam')[$iteration]['gebruikersnaam'];
            $bod = getHighestBidData($id, $iteration + 1, 'Bodbedrag')[$iteration]['Bodbedrag'];
            if ($bod !== NULL) {
                $bod = str_replace('.', ',', $bod);
                $time = getHighestBidData($id, $iteration + 1, 'BodTijdStip')[$iteration]['BodTijdStip'];
                $html .= "<a href='#' class='list-group-item list-group-item-action d-flex gap-3 py-3' aria-current='true'>
                                    <img src='img/Template.png' alt='twbs' width='32' height='32' class='rounded-circle flex-shrink-0'>
                                    <div class='d-flex gap-2 w-100 justify-content-between'>
                                        <div>
                                            <h6 class='mb-0'>$user</h6>
                                            <p class='mb-0 opacity-75'>€$bod</p>
                                        </div>
                                        <small class='opacity-50 text-nowrap'>$time</small>
                                    </div>
                                </a>";
            }
        }
    }
    return $html;
}


//Returned html voor de biedingen
function getBidFormHtml($userdata, $id)
{
    $html = '';
    if ($userdata['user'] === trim(getProductInfo($id, 'verkopernaam')[0]['verkopernaam'])) {
        $html = <<<EOD
        <form method='post' action='../app/endauction.php?id=$id'
          <div class="input-group mb-3">
          </div>
          <div class="d-grid">
            <button class="btn btn-primary" type="submit">Accepteer bod</button>
          </div>
          </form>
EOD;
    } else if (!empty($userdata['user'])) {

        $minimumBid = 1;
        if (!empty(getHighestBidData($id, 1, 'Bodbedrag')[0]['Bodbedrag'])) {
            $currentHighestBid = getHighestBid($id);
            if (getHighestBid($id)) {
                $minimumBid = getProductInfo($id, 'startprijs')[0]['startprijs'];
            } else {
                $bidStep = minBidRaise($currentHighestBid);
                $minimumBid = $currentHighestBid + $bidStep;
            }
        } else {
            $minimumBid = getProductInfo($id, 'startprijs')[0]['startprijs'];
        }

        if (isUserAdmin($_SESSION)) {
            $html = getAdminClosingHTML($id);
        } else {
            $html = "
            <form class='w-100' method='post' action='../app/bid.php?id=$id'>
                <div class='input-group mb-3'>
                    <input type='number' class='form-control' name='bid' step='0.01' min='$minimumBid' max='99999999.99' placeholder='€$minimumBid' value='$minimumBid' aria-label='Bod'>
                </div>
                <div class='d-grid'>
                    <button class='btn btn-primary' type='submit'>Bied</button>
                </div>
            </form>";
        }
    } else {
        $minimumBid = getProductInfo($id, 'startprijs')[0]['startprijs'];
        if (isUserAdmin($_SESSION)) {
            $html = "
                <form class='w-100' method='post' action='../app/cancelauction.php?id=$id'>
                    <div class='d-grid'>
                        <button disabled class='btn btn-primary' type='submit'>Stop veiling</button>
                    </div>
                </form>";
        } else {
            $html = "
            <form class='w-100' method='post'>
                <div class='input-group mb-3'>
                    <input type='number' class='form-control' name='bid' step='0.01' min='$minimumBid' max='99999999.99' placeholder='€$minimumBid' value='$minimumBid'  aria-label='Bod'>
                </div>
                <div class='d-grid'>
                    <button disabled class='btn btn-primary' type='submit'>Bied</button>
                </div>
            </form>";
        }
    }

    return $html;
}

//Returnt knop voor het sluiten van een veiling voor de beheerder
function getAdminClosingHTML($id)
{
    if (!isProductClosed($id)) {
        $html = "
            <form class='w-100' method='post' action='../app/cancelauction.php?id=$id'>
                <div class='d-grid'>
                    <button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#stopAuction'>Stop veiling</button>
                </div>

                <div class='modal fade' id='stopAuction' tabindex='-1' aria-labelledby='stopAuctionLabel' aria-hidden='true'>
                    <div class='modal-dialog modal-dialog-centered'>
                        <div class='modal-content'>
                            <div class='modal-header'>
                                <h5 class='modal-title' id='stopAuctionLabel'>Weet u het zeker?</h5>
                                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                            </div>
                            <div class='modal-body'>
                                De veiling zal worden gestopt als u op 'ja' klikt.
                            </div>
                            <div class='modal-footer'>
                                <button type='button' class='btn btn-secondary w-25' data-bs-dismiss='modal'>Nee</button>
                                <button type='submit' class='btn btn-primary w-25'>Ja</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>";
    } else {
        $html = "
                <form class='w-100' method='post' action='../app/cancelauction.php?id=$id'>
                    <div class='d-grid'>
                        <button disabled type='button' class='btn btn-primary'>Stop veiling</button>
                    </div>
                </form>";
    }
    return $html;
}

//Checkt of de veiling gesloten is
function isProductClosed($id)
{
    $isClosed = trim(getProductInfo($id, 'veilingGesloten')[0]['veilingGesloten']);
    return $isClosed;
}


//Returnt een melding als de veiling gesloten is
function productClosedMessage($id)
{

    $html = "";
    if (isProductClosed($id)) {
        $html = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Let op!</strong> Deze veiling is gesloten!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
    }
    return $html;
}


//Return de minimale verhoging van een bod
function minBidRaise($bid)
{
    if ($bid > 1.00 && $bid < 50.00) {
        return 0.50;
    } elseif ($bid > 50.00 && $bid < 500.00) {
        return 1.00;
    } elseif ($bid > 500.00 && $bid < 1000.00) {
        return 5.00;
    } elseif ($bid > 1000 && $bid < 5000.00) {
        return 10.00;
    } elseif ($bid > 5000.00) {
        return 50.00;
    }
    return 0.50;
}


//CATECORYFILTER________________________________
function categoryFilter($category, $page)
{
    $limitOfProducts = 100;
    $category = standardDataSafety($category);
    $page = standardDataSafety($page);

    if ($page == NULL) {
        $limit = 0;
    } else {
        $limit = $limitOfProducts * ($page - 1);
    }

    $html = '<div class="container my-5">
                <div class="row g-4">';
    $html .= categoryText();

    if (filter_var($category, FILTER_VALIDATE_INT)) {


        $categoryList = getChildrenFromParent($category);

        $products = [];
        foreach ($categoryList as $categoryNumber) {
            $products = array_merge($products, getProductPerCategory($categoryNumber));
        }
        $nrOfProducts = sizeof($products);


        for ($i = $limit; $i < $limit + $limitOfProducts; $i++) {
            if ($i < $nrOfProducts) {

                $product = $products[$i];
                $productTitle = $product['titel'];
                $productDescription = $product['beschrijving'];
                $productNumber = $product['voorwerpnummer'];
                $image = getImage(1, $productNumber, 'thumbnails', $product['seller'])[0];
                $productTime = convertToHiddenTime($product['looptijdeindeDag'], $product['looptijdeindeTijdstip']);
                $html .= <<<EOD
    <div class="col-sm-12 col-md-6 col-lg-3 align-items-stretch">
        <div class="Product">
            <div class="card">
                <div class="thumb" style="background-image: url($image);"></div>
                <article>
                    <h1> $productTitle </h1>
                    <div class="tekst">
                        <p> $productDescription
                         </p>
                    </div>
                    <span class="countdownclock">
                        <i class="bi-clock countdown" data-time="$productTime"></i>
                        <button onclick="window.location.href='productpage.php?id=$productNumber';" class="btn btn-primary me-md-2 auctionButton" type="button" >Bied mee!</button>
                    </span>
                </article>
            </div>
        </div>
    </div>
EOD;
            }
        }
    }

    if ($nrOfProducts === 0) {
        $html .= '<p> Er zijn geen resultaten gevonden voor</p></div></div> ';
    } else {
        $html .= '</div></div>';
        $nrOfPages = ceil($nrOfProducts / $limitOfProducts);
        $link = '../site/categoriepagina.php?categorie=' . $category . '&page=';
        $html .= getPaginationButtons($page, $nrOfPages, $link);
    }
    return $html;
}


//Returnt alle kinderen van een parent
function getChildrenFromParent($category)
{
    $rubrics = getRubricInformation();
    $children = [$category];
    $rubricFound = true;
    while ($rubricFound) {
        $rubricFound = false;
        foreach ($rubrics as $rubric) {
            if (in_array($rubric[2], $children) && !in_array($rubric[0], $children)) {
                array_push($children, $rubric[0]);
                $rubricFound = true;
            }
        }
    }
    return $children;
}

//Maakt de text bovenaan de categoriepagina
function categoryText()
{
    $rubricsNumber = standardDataSafety($_GET['categorie']);
    $html = '';
    while (!empty($rubricsNumber)) {
        $rubricsArray = getRubricInformationOnNumber($rubricsNumber);
        $currentNumber = $rubricsNumber;
        $rubricsNumber = $rubricsArray["rubriekparent"];
        $rubricsName = $rubricsArray["rubrieknaam"];
        if ($rubricsName != "Root") {
            $html = '<a class="link-dark fw-bold text-decoration-none" href="categoriepagina.php?categorie=' . $currentNumber . '">' . $rubricsName . '</a> > ' . $html;
        }
    }
    $html = substr_replace($html, "", -3);
    $html = '<h3>' . $html . '</h3>';
    return $html;
}

//DELETE BID
function deleteBid($productNumber)
{
    $username = $_SESSION['user'];

    $highestBidder = standardDataSafety(getHighestBidData($productNumber, 1, 'Gebruikersnaam')[0][0]);
    if ($highestBidder === $username) {
        if (deleteBidFromDatabase($productNumber, $username)) {
            return true;
        }
    }
    return false;
}


//SEARCHRESULTS_________________________________
function searchInput($searchInput, $page)
{
    $limitOfProducts = 100;
    $input = standardDataSafety($searchInput);
    $page = standardDataSafety($page);

    if ($page == NULL) {
        $limit = 0;
    } else {
        $limit = $limitOfProducts * ($page - 1);
    }

    $ignoredWords = ['en', 'de', 'het', 'een'];

    $html = '<div class="container my-5">';

    if ($input == "") {
        $html .= '<h1>🔎 Er zijn geen resultaten gevonden voor: ' . $input . '</h1><div class="row g-4">';
    } else {
        $html .= '<h1>🔎 Resultaten voor: ' . $input . '</h1><div class="row g-4">';

        $inputArray = explode(" ", $input); //Maakt een array van de input door te filteren op spaties.

        foreach ($ignoredWords as $word) { //Haalt alle onnodige woorden uit de array.
            foreach (array_keys($inputArray, $word) as $key) {
                unset($ignoredWords[$key]);
            }
        }

        $products = [];
        foreach ($inputArray as $keyword) { //Voor elk woord in de array worden de zoekresultaten opgehaald en in een array gestopt.
            $products = array_merge($products, getSearchProducts($keyword));
        }
        $nrOfProducts = sizeof($products);

        for ($i = $limit; $i < $limit + $limitOfProducts; $i++) {
            if ($i < $nrOfProducts) {

                $product = $products[$i];
                $productTitle = $product['titel'];
                $productDescription = $product['beschrijving'];
                $productNumber = $product['voorwerpnummer'];
                $image = getImage(1, $productNumber, 'thumbnails', $product['seller'])[0];
                $productTime = convertToHiddenTime($product['endDay'], $product['endTime']);
                $html .= <<<EOD
    <div class="col-sm-12 col-md-6 col-lg-3 align-items-stretch">
        <div class="Product">
            <div class="card">
                <div class="thumb" style="background-image: url($image);"></div>
                <article>
                    <h1> $productTitle </h1>
                    <div class="tekst">
                        <p> $productDescription
                         </p>
                    </div>
                    <span class="countdownclock">
                        <i class="bi-clock countdown"  data-time="$productTime"></i>
                        <button onclick="window.location.href='productpage.php?id=$productNumber';" class="btn btn-primary me-md-2 auctionButton" type="button" >Bied mee!</button>
                    </span>
                </article>
            </div>
        </div>
    </div>
EOD;
            }
        }
    }

    if ($nrOfProducts === 0) {
        $html .= '<p> Er zijn geen resultaten gevonden voor: ' . $input . '</p></div></div> ';
    } else {
        $html .= '</div></div>';
        $nrOfPages = ceil($nrOfProducts / $limitOfProducts);
        $link = '../site/zoekresultaten.php?search=' . $input . '&page=';
        $html .= getPaginationButtons($page, $nrOfPages, $link);
    }
    return $html;
}

//MAILFUNCTIES
//$adres is emailadress waar het heen moet, $body is de inhoud van de mail en $subject is het onderwerp
function sendMail($adres, $body, $subject)
{
    try {
        $mail = new PHPMailer;
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'mail.ip.aimsites.nl'; //kan ook mail.ip.aimsites.nl zijn 
        $mail->SMTPAuth = false;
        $mail->Port = 0; //Zet hier de poort van je groepje neer
        $mail->setFrom('info@eenmaalandermaal.com', 'Eenmaal Andermaal');
        $mail->addAddress($adres);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->AltBody = $body;
        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

//stuurt de mail met de verificatiecode $code is de verificatiecode en $adres is het mailadres
function stuurVerificatieMail($code, $adress, $direction)
{
    $body = '<p> Beste meneer/mevrouw,</p>
    <br>
    <p>Dank u wel dat u een account wilt aanmaken.</p>
    <p>Klik op deze link om te registreren:</p>
    <p><a href =https://iproject36.ip.aimsites.nl/app/checkverificationcode.php?code=' . $code . '&destination=' . $direction . '&email=' . $adress . '>' . 'Registeren.<a></p>
    <br>
    <p>Met vriendelijke groet, </p>
    <p>Het Eenmaal Andermaal Team.</p>';
    $subject = 'Verificatiemail eenmaalandermaal';
    sendMail($adress, $body, $subject);
}


//stuurt de mail met de verificatiecode $code is de verificatiecode en $adres is het mailadres
function stuurResetMail($code, $adress, $direction, $username)
{
    $body = '<p> Beste meneer/mevrouw,</p>
    <br>
    <p>Klik op deze link om uw wachtwoord te resetten:</p>
    <p><a href =https://iproject36.ip.aimsites.nl/app/checkverificationcode.php?code=' . $code . '&destination=' . $direction . '&email=' . $adress . '&username=' . $username . '>' . 'Reset wachtwoord.<a></p>
    <br>
    <p>Met vriendelijke groet, </p>
    <p>Het Eenmaal Andermaal Team.</p>';
    $subject = 'Verificatiemail eenmaalandermaal';
    sendMail($adress, $body, $subject);
}


//stuurt de mail met de communicatie gegevens, $adres is het adres waar het heen moet, $email is de email van de gebruiker,
// $telefoon is het telefoonnummer van de gebruiker, $id is het voorwerpid
function stuurCommunicatieMailKoper($buyerInfo, $sellerInfo, $productinfo, $bidData, $key)
{
    $adress = decrypt($buyerInfo['mailbox'], $key);
    $body = '<p> Beste meneer/mevrouw (Gebruikersnaam op EenmaalAndermaal: ' . $buyerInfo['gebruikersnaam'] . '),</p>
    <br>
    <p>Gefeliciteerd! U heeft het bod gewonnen op het volgende product:</p>
    <p>Titel van het product: ' . $productinfo[0]['titel'] . '</p>
    <p>Beschrijving van het product: ' . $productinfo[0]['beschrijving'] . '</p>
    <p>Uw winnend bod: ' . $bidData[0]['Bodbedrag'] . '</p>
    <br>
    <p>U kunt nu contact opnemen met de veiler van dit product, zodat er een overeenkomst gemaakt 
    kan worden over de verzending en eventuele andere informatie en/of zaken</p>
    <p>Dit zijn de informatiegegevens van de veiler van het product waar u op heeft geboden:</p>
    <p>Naam van de veiler: ' . decrypt($sellerInfo['voornaam'], $key) . ' ' . decrypt($sellerInfo['achternaam'], $key) . '</p>
    <p>Het emailadress van de veiler: ' . decrypt($sellerInfo['mailbox'], $key) . '</p>
    <p>Telefoonnummer van de veiler: ' . decrypt(getUserTel($sellerInfo['gebruikersnaam']), $key) . '</p>
    <br>
    <p>Bedankt voor het kopen op EenmaalAndermaal!</p>';

    $subject = 'Veiling gewonnen op EenmaalAndermaal';
    sendMail($adress, $body, $subject);
}


function stuurCommunicatieMailVerkoper($buyerInfo, $sellerInfo, $productinfo, $bidData, $key)
{
    $adress = decrypt($sellerInfo['mailbox'], $key);
    $body = '<p> Beste meneer/mevrouw (Gebruikersnaam op EenmaalAndermaal: ' . $sellerInfo['gebruikersnaam'] . '),</p>
    <br>
    <p>U heeft een bod geaccepteerd op een van uw veilingen.</p>
    <p>U heeft het volgende product verkocht:</p>
    <p>Titel van het product: ' . $productinfo[0]['titel'] . '</p>
    <p>Beschrijving van het product: ' . $productinfo[0]['beschrijving'] . '</p>
    <p>Het winnend bod: ' . $bidData[0]['Bodbedrag'] . '</p>
    <br>
    <p>De koper van het product heeft uw contactgegevens binnengekregen en zal z.s.m contact met u opnemen voor de verdere afhandeling van de verkoop.</p>
    <p>Ook krijgt u de contactgegevens van de koper als deze niet binnen de gewneste tijd contact opneemt.</p>
    <p>Naam van de koper: ' . decrypt($buyerInfo['voornaam'], $key) . ' ' . decrypt($buyerInfo['achternaam'], $key) . '</p>
    <p>Het emailadress van de koper: ' . decrypt($buyerInfo['mailbox'], $key) . '</p>
    <p>Telefoonnummer van de koper: ' . decrypt(getUserTel($buyerInfo['gebruikersnaam']), $key) . '</p>
    <br>
    <p>Bedankt voor het verkopen op EenmaalAndermaal!</p>';

    $subject = 'Veiling geaccepteerd op EenmaalAndermaal';
    sendMail($adress, $body, $subject);
}

function stuurMailVerkoperWorden($userData, $cc, $banknummer, $img, $key)
{
    $adress = 'info@eenmaalandermaal.com';

    $body = '<p>Beste medewerker van EenmaalAndermaal.</p><br>
        <p>De volgende gebruiker heeft een verzoek ingediend om gebruiker te worden:</p>
        <p>Gebruikersnaam van de gebruiker: ' . $userData['gebruikersnaam'] . '</p>
        <p>Naam van de gebruiker: ' . decrypt($userData['voornaam'], $key) . ' ' . decrypt($userData['achternaam'], $key) . '</p>
        <p>Creditcardnummer dat is ingevoerd bij het aanvragen: ' . $cc . '</p>
        <p>Bankrekeningnummer dat is ingevoerd bij het aanvragen(optioneel): ' . $banknummer . '</p>
        <p>Voor de extra controle is er ook een foto van een ID meegestuurd. Deze is voor de extra controle.</p>
        <img style="height:40%; width:40%;" alt="Embedded Image" src="data:image/png;base64,' . $img . '"/>
        
    ';

    $subject = 'Verkoper worden accepteren';
    sendMail($adress, $body, $subject);
}


// genereerd een random string
function generateRandomString($stringLength = 10)
{
    $options = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($options);
    $code = '';
    for ($i = 0; $i < $stringLength; $i++) {
        $code .= $options[rand(0, $charactersLength - 1)];
    }
    return $code;
}

//Check voor verificatiecode
function verificatieCodeCheck()
{
    if (!isset($_SESSION['verified'])) {
        redirect('preregister');
    }
}

//geeft resetpassword html op basis van of je verificatiecode correct was of niet
function getResetPasswordHtml()
{
    $html = '<div class="container-fluid px-1 py-5 mx-auto">
    <div class="row d-flex justify-content-center">
        <div class="col-xl-7 col-lg-8 col-md-9 col-11 text-center">
            <div class="card">
                <h5 class="text-center mb-4">Verificatiemail</h5>
                <form class="form-card" action="../app/resetpassword.php" method="post">
                    <div class="row justify-content-center text-left">
                        <div class="form-group col-sm-6 flex-column d-flex">
                            <label class="form-control-label px-3">
                                Gebruikersnaam*
                            </label>
                            <input type="text" placeholder="Gebruikersnaam" name="username" required>
                        </div>
                    </div>
                    <div class="row justify-content-center text-left">
                        <div class="form-group col-sm-6 flex-column d-flex">
                            <label class="form-control-label px-3">
                               E-mailadres*
                            </label>
                            <input type="email" placeholder="E-mailadres" name="email" required>
                        </div>
                    </div>
                    <div class="form-group col-sm-16">
                        <button type="submit" class="btn-block btn-primary" value="Submit">
                            Stuur Verificatiemail
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>';
    if ($_SESSION['verifiedPassword']) {
        $vraagnummer = getUserData($_SESSION['username'], 'gebruikersnaam', 'vraagnummer')[0];
        $vraag = getVraag($vraagnummer);
        $html = "<div class='container-fluid px-1 py-5 mx-auto'>
    <div class='row d-flex justify-content-center'>
        <div class='col-xl-7 col-lg-8 col-md-9 col-11 text-center'>
            <div class='card'>
                <h5 class='text-center mb-4'>Wachtwoord resetten</h5>
                <form class='form-card' action='../app/resetpassword.php' method='post'>
                    <div class='row justify-content-center text-left'>
                        <div class='form-group col-sm-12 flex-column d-flex'>
                            <label class='form-control-label px-3'>
                                $vraag
                            </label>
                            <input type='text' placeholder='konijn' name='answer' required>
                        </div>
                    </div>
                    <div class='row justify-content-between text-left'>
                                <div class='form-group col-sm-6 flex-column d-flex'>
                                    <label class='form-control-label px-3'>
                                        Nieuw wachtwoord*
                                    </label>
                                    <input type='password' id='password' name='password' onblur='validate(5)' required>
                                </div>
                                <div class='form-group col-sm-6 flex-column d-flex'>
                                    <label class='form-control-label px-3'>
                                        Wachtwoord bevestigen*
                                    </label>
                                    <input type='password' id='passwordconfirm' name='passwordconfirm' onblur='validate(5)' required>
                                </div>
                            </div>
                        <div class='form-group col-sm-16'>
                            <button type='submit' class='btn-block btn-primary' value='Submit'>
                                Reset password
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>";
    }

    return $html;
}


//Haalt alle veilingen van een gebruiker op
function myAuctions()
{
    $html = '';
    $productInfo = getProductPerUser($_SESSION['user']);

    foreach ($productInfo as $product) {
        $highestBid = getHighestBid($product['voorwerpNummer']);
        if ($highestBid === 0) {
            $highestBid[0]['Bodbedrag'] = 'Nog geen bod';
        }
        $productTime = convertToHiddenTime($product['looptijdEindeDag'], $product['looptijdeindeTijdstip']);
        $image = getImage(1, $product['voorwerpNummer'], 'thumbnails', $_SESSION['user'])[0];

        $html .= <<<EOD
        <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
            <div class="Product">
                <div class="card">
                    <div class="thumb" style="background-image: url($image);"></div>
                    <article>
                    <h1>
EOD;
        $html .= $product['titel'];
        $html .= <<<EOD
                    </h1>
                    <div class="tekst">
                        <ul>
                            <li>Huidig bod: €
EOD;
        $html .= $highestBid[0]['Bodbedrag'];
        $html .= <<<EOD
                            </li>
                            <li class="countdownclock"><i class="bi-clock countdown" data-time="$productTime"></i></li>
                        </ul>
                        
                    </div>
                    
                    <span class="countdownclock">
                        <button onclick="window.location.href='../app/endauction.php?id=
EOD;
        $html .= $product['voorwerpNummer'];
        $html .= <<<EOD
                        ';" class="btn btn-success me-2 auctionButton" type="button"
EOD;
        if ($product['veilingGesloten']) {
            $html .= 'disabled';
        }
        $html .= <<<EOD
                        >Bod Accepteren</button>
                        

                        <button onclick="window.location.href='../app/cancelauction.php?id=
EOD;
        $html .= $product['voorwerpNummer'];
        $html .= <<<EOD
                        ';" class="btn btn-danger me-2 auctionButton" type="button"
EOD;
        if ($product['veilingGesloten']) {
            $html .= 'disabled';
        }
        $html .= <<<EOD
                        >Veiling annuleren</button>
                    </span>
                    </article>
                </div>
            </div>
        </div>    
EOD;
    }
    return $html;
}


//Haalt alle biedingen van een gebruiker op
function myBiddings()
{
    $html = '';
    $userBiddings = getBidsPerUser($_SESSION['user']);

    foreach ($userBiddings as $bids) {
        $highestBid = getHighestBid($bids['voorwerpnummer']);
        if (empty($highestBid)) {
            $highestBid[0]['Bodbedrag'] = 'Nog geen bod';
        }
        $productInfo = getProductInfo($bids['voorwerpnummer'], 'titel, looptijdEindeDag, looptijdeindeTijdstip, verkopernaam as seller');
        $productTime = convertToHiddenTime($productInfo[0]['looptijdEindeDag'], $productInfo[0]['looptijdeindeTijdstip']);
        $image = getImage(1, $bids['voorwerpnummer'], 'thumbnails', $productInfo[0]['seller'])[0];
        $html .= <<<EOD
        <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
        <div class="Product">
          <div class="card">
            <div class="thumb" style="background-image: url($image);"></div>
            <article>
              <h1>
EOD;
        $html .= $productInfo[0]['titel'];
        $html .= <<<EOD
                </h1>
              <div class="tekst">
                <ul>
                    <li>Huidig bod: €
EOD;
        $html .= $highestBid[0]['Bodbedrag'];
        $html .= <<<EOD
                    </li>
                  <li>Jouw bod: €
EOD;
        $html .= $bids['bodbedrag'];
        $html .= <<<EOD
                  </li>
                  <li class="countdownclock"><i class="bi-clock countdown" data-time="$productTime"></i></li>
                </ul>
              </div>
              <span class="countdownclock">
                <button onclick="window.location.href='../site/productpage.php?id=
EOD;
        $html .= $bids['voorwerpnummer'];
        $html .= <<<EOD
                ';" class="btn btn-success me-2 auctionButton" type="button">Bekijken</button>
                <button onclick="window.location.href='../app/deletebid.php?id=
EOD;
        $html .= $bids['voorwerpnummer'];
        $html .= <<<EOD
                ';" class="btn btn-danger me-2 auctionButton" type="button">Bod intrekken</button>
              </span>
            </article>
          </div>
        </div>
      </div>
EOD;
    }
    return $html;
}

//Laat alle gegevens van een gebruiker zien op zijn pagina
function myAccount($username)
{
    $key = getEnvFile('encryptionkey', 'app');
    $userdata = getUserData($username, 'gebruikersnaam', 'gebruikersnaam, voornaam, achternaam, geboortedag, adresregel_1, adresregel_2, postcode, plaatsnaam, mailbox');
    $decryptItems = ['voornaam', 'achternaam', 'adresregel_1', 'adresregel_2', 'postcode', 'plaatsnaam', 'mailbox'];
    foreach ($decryptItems as $item) {
        $userdata[$item] = !decrypt($userdata[$item], $key) ? $userdata[$item] : decrypt($userdata[$item], $key);
    }
    $dbPhone = getUserTel($username);
    $telefoon = !decrypt($dbPhone, $key) ? $dbPhone : decrypt($dbPhone, $key);
    $html = <<<EOD
    <div class="row mt-5 mb-5 justify-content-center">
        <div class="col-md-4">
            <div class="d-flex flex-column align-items-center text-center p-3 py-5">
                <img class="rounded-circle mt-5 rounded" width="150" src="../site/img/placeholder.png" alt="profile">
                <span class="font-weight-bold">{$userdata['gebruikersnaam']}</span>
            </div>
        </div>
        <div class="col-md-8 border-right">
            <div class="p-3 py-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="text-right">Profiel</h4>
                </div>
                <form action="../app/accountpage.php?username={$userdata['gebruikersnaam']}" method="post">
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <label class="labels">Voornaam</label>
                            <input name="firstname" type="text" class="form-control" value="{$userdata['voornaam']}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="labels">Achternaam</label>
                            <input name="lastname" type="text" class="form-control" value="{$userdata['achternaam']}" required>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label class="labels">Telefoonnummer</label>
                            <input name="tel" type="tel" class="form-control" pattern="[0-9\s]{1,14}" maxlength="11" value="$telefoon" required>
                        </div>
                        <div class="col-md-12">
                            <label class="labels">Geboortedatum</label>
                            <input name="birthdate" type="date" class="form-control" value="{$userdata['geboortedag']}" required>
                        </div>
                        <div class="col-md-12">
                            <label class="labels">Postcode</label>
                            <input name="zipcode" type="text" class="form-control" value="{$userdata['postcode']}" required>
                        </div>
                        <div class="col-md-12">
                            <label class="labels">Adres</label>
                            <input name="adress" type="text" class="form-control" value="{$userdata['adresregel_1']}" required>
                        </div>
                        <div class="col-md-12">
                            <label class="labels">Extra Adres regels</label>
                            <input name="adress2" type="text" class="form-control" placeholder="A/Verdieping 3" value="{$userdata['adresregel_2']}">
                        </div>
                        <div class="col-md-12">
                            <label class="labels">Plaatsnaam</label>
                            <input name="city" type="text" class="form-control" value="{$userdata['plaatsnaam']}" required>
                        </div>
                        <div class="col-md-12">
                            <label class="labels">E-mail</label>
                            <input name="email" type="email" class="form-control" value="{$userdata['mailbox']}" required>
                        </div>
                    </div>
                    <div class="mt-5">
                        <button type="submit" class="btn btn-primary">Opslaan</button>
                        <button class="btn btn-dark float-end" data-bs-toggle='modal' type="button" data-bs-target="#deleteUser1">Account verwijderen</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
EOD;
    $html .= getDeleteModal($userdata, $_SESSION['adminStatus'], '1');
    return $html;
}

//Returnt de pagination knoppen onder aan de pagina
function getPaginationButtons($currentpage, $pages, $link)
{
    $html = '<ul class="pagination flex-wrap justify-content-center">';
    if ($pages > 5) {
        if ($currentpage > 3) {
            $html .= '<li class="page-item">
                        <a class="page-link"  href="' . $link . ($currentpage - 1) . '" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                        </a>
                      </li>';
        }
        $limit = 5;
    } else {
        $limit = $pages;
    }

    if ($limit === 5) {
        for ($i = 0; $i < $limit; $i++) {
            $pagenumber = $currentpage - 2 + $i;
            if ($pagenumber <= 0) {
                $limit++;
            }
            if ($pagenumber > 0 && $pagenumber <= $pages) {
                if ($pagenumber == $currentpage) {
                    $html .= '<li class="page-item active"><a class="page-link link-secondary" href="' . $link . $pagenumber . '">' . $pagenumber . '</a></li>';
                } else {
                    $html .= '<li class="page-item"><a class="page-link link-secondary" href="' . $link . $pagenumber . '">' . $pagenumber . '</a></li>';
                }
            }
        }
    } else {
        for ($i = 1; $i <= $limit; $i++) {
            if ($i == $currentpage) {
                $html .= '<li class="page-item active"><a class="page-link link-secondary" href="' . $link . $i . '">' . $i . '</a></li>';
            } else {
                $html .= '<li class="page-item"><a class="page-link link-secondary" href="' . $link . $i . '">' . $i . '</a></li>';
            }
        }
    }

    if ($currentpage + 1 < $pages && $pages > 5) {
        $html .= '<li class="page-item">
                    <a class="page-link"  href="' . $link . ($currentpage + 1) . '" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                    </a>
                    </li>';
    }
    $html .= '</ul>';
    return $html;
}


function getLastRubric($categories)   //Kijkt wat de laatste rubriek is die ingevuld is.
{
    $categoryFound = false;
    foreach ($categories as $category) {
        if (!empty($_POST[$category]) && !$categoryFound) {
            $finalCategory = $_POST[$category];
            $categoryFound = true;
        }
    }

    $categoryArray = getRubrics();
    foreach ($categoryArray as $category) {
        if ($category['rubrieknummer'] == $finalCategory) {
            $returnCategory = $category['rubrieknaam'];
        }
    }

    return $returnCategory;
}

//checkt de verificatiecode
function userCheck($sessionInfo, $userName)
{
    return compareVariables($sessionInfo['verificationCodeAccount'], getUserCode($userName, 'account')['Verificatiecode']);
}

//Maakt verificatiecode
function generateSafetyCode($userName, $type)
{
    !doesCodeExist($_SESSION['verificationCodeAccount'])[0][''] ?: deleteCode($_SESSION['verificationCodeAccount']);
    $user = $_SESSION['adminStatus'] ? (standardDataSafety($userName)) : ($_SESSION['user']);
    $_SESSION['verificationCodeAccount'] = generateRandomString(100);
    insertSafetyCode($_SESSION['verificationCodeAccount'], $user, $type);
}

//Verwijdert tijdelijk een gebruiker
function tempRemoveUser($userName)
{
    return setUserStatus($userName, 1);
}

//Herstelt een gebruiker
function reinstateUser($userName)
{
    return setUserStatus($userName, 0);
}

//Verwijdert een gebruiker
function removeUser($userName)
{
    return removeUserInfo($userName);
}

//Stuurt mail naar gebruiker
function deleteUserInteractions($deletionType, $userName, $code)
{
    $key = getEnvFile('encryptionkey', 'app');
    $mailingAddress = $deletionType ? (!decrypt(getUserCredentials($userName, 'empty')['mailbox'], $key) ? getUserCredentials($userName, 'empty')['mailbox'] : decrypt(getUserCredentials($userName, 'empty')['mailbox'], $key)) : (!decrypt($_SESSION['email'], $key) ? session_destroy() . errorRedirect('home', 'verwijderfout') : decrypt($_SESSION['email'], $key));
    $body = '<p> Beste meneer/mevrouw,</p>
    <br>
    <p> ' . ($deletionType ? 'Een beheerder heeft uw account de verwijderstatus gegeven.' : 'U heeft een aanvraag ingediend om uw account te verwijderen')  . ' </p>
    <p> Dit betekent dat uw eenmaalandermaal account verwijderd zal worden. ' . ($deletionType ? 'U heeft 10 dagen de tijd om contact op te nemen' : 'U heeft 10 dagen te tijd om het account terug te zetten')  .  ', daarna zullen uw gegevens verwijderd worden. </p>
    <p> Heeft u vragen? Stuur dan gerust een email naar: <a href="mailto:Iproject36@han.nl">Iproject36@han.nl</a>. Of bel ons op: <a href="tel:+3161234567890">+31 6 1234567890</a> </p>
    ' . ($deletionType ? '' : '<p>' . 'U kunt op de knop klikken om de deactivatie ongedaan te maken:') . '
    ' . ($deletionType ? '' : ' <a type="button" href="https://iproject36.ip.aimsites.nl/app/reinstateuser.php?name=' . $userName . '&token=' . $code . '" style="
    text-decoration: none;
    background-color: #EEEEEE;
    color: #333333;
    padding: 2px 6px 2px 6px;
    border-top: 1px solid #CCCCCC;
    border-right: 1px solid #333333;
    border-bottom: 1px solid #333333;
    border-left: 1px solid #CCCCCC;">Account verwijderen ongedaan maken</a>') .
        '<p>Met vriendelijke groet, </p>
    <p>Het Eenmaal Andermaal Team.</p>';
    $subject = 'Account verwijderd';
    echo $mailingAddress;
    echo '<br>';
    echo getUserCredentials($userName, 'empty')['mailbox'];
    sendMail($mailingAddress, $body, $subject) || errorRedirect('home', 'verwijderfout2');
    // $deletionType ?: session_destroy();
    // succesRedirect('home', 'voorlopverwijderd');
}


function deleteExpiredUserInfo()
{
    $expiredUsers = getExpiredUserInfo();
    foreach ($expiredUsers as $user) {
        removeUserInfo($user['gebruikersnaam']);
    }
}
