<?php

//Voor lokaal gebruik $databaseconnectionLocalTest.php;
//Voor gebruik op de server $databaseconnectionserver.php;
require_once 'databaseconnectionLocalTest.php';
// require_once 'databaseconnectionserver.php';


//Voor lokaal gebruik $databaseConnection;
//Voor gebruik op de han server $hanDatabase;
function dbConnection()
{
    global $databaseConnection;
    return $databaseConnection;
}


function registerDropDownInfo($sql)
{
    $queryeresults = dbConnection()->query($sql);
    $results = $queryeresults->fetchAll();
    return $results;
}

function getRubricInformationOnNumber($rubricsNumber)
{
    $query = "SELECT rubrieknummer, rubrieknaam, rubriekparent FROM rubriek WHERE rubrieknummer = ?";
    $queryPrepare = dbConnection()->prepare($query);
    $queryPrepare->execute([$rubricsNumber]);
    $results = $queryPrepare->fetch();
    return $results;
}

function getRubricInformation()
{
    $query = "SELECT * FROM rubriek";
    $queryeresults = dbConnection()->query($query);
    $results = $queryeresults->fetchAll();
    return $results;
}

function getProductInfo($product, $info)
{
    $dirtyQuery = "SELECT $info FROM voorwerp WHERE voorwerpnummer = ?";
    $query = dbConnection()->prepare($dirtyQuery);
    $query->execute([$product]);
    $queryResults = $query->fetchAll();
    return $queryResults;
}


//REGISTER/SIGNUP__________________________________
function registerUser(array $userAccount)
{
    try {
        $userAccount['password'] = password_hash($userAccount['password'], PASSWORD_DEFAULT);
        $key = getEnvFile('encryptionkey', 'app');
        $dataToEncrypt = ['firstname', 'lastname', 'zipcode', 'adress', 'adress2', 'city', 'phone', 'answer'];
        foreach ($dataToEncrypt as $data) {
            if (!empty($userAccount[$data])) {
                $userAccount[$data] = encrypt(standardDataSafety($userAccount[$data]), $key);
            }
        }
        $sql = "INSERT INTO gebruiker 
            VALUES (:user_name, :firstname, :lastname, :adress, :adress2, :zipcode, :city, :country_name, :birth_date, :customer_mail_adress, :password, :question, :answer, 0, 0, GETDATE(), 0, 0)";
        $query = dbConnection()->prepare($sql);
        $query->execute([
            standardDataSafety($userAccount['username']), standardDataSafety($userAccount['firstname']), standardDataSafety($userAccount['lastname']), standardDataSafety($userAccount['adress']),
            standardDataSafety($userAccount['adress2']), standardDataSafety($userAccount['zipcode']), standardDataSafety($userAccount['city']), standardDataSafety($userAccount['country']),
            standardDataSafety($userAccount['birthdate']), standardDataSafety($_SESSION['email']), $userAccount['password'], standardDataSafety($userAccount['securityquestion']),
            standardDataSafety($userAccount['answer'])
        ]);
    } catch (Exception $e) {
        return false;
    }
    try {
        $sql2 = "INSERT INTO gebruikerstelefoon VALUES (:user_name,:phone)";
        $query2 = dbConnection()->prepare($sql2);
        $query2->execute([
            standardDataSafety($userAccount['username']), standardDataSafety($userAccount['phone'])
        ]);
        return true;
    } catch (Exception $e) {
        return false;
    }
}

function getUserCredentials($userName, $mailbox)
{
    $sql = "SELECT gebruikersnaam, mailbox FROM gebruiker WHERE gebruikersnaam = ? OR mailbox = ?";
    $checkedQuery = dbConnection()->prepare($sql);
    $checkedQuery->execute([
        standardDataSafety($userName),
        standardDataSafety($mailbox)
    ]);
    $result = $checkedQuery->fetch();
    return $result;
}

//userdata is data from the user. usercriteria is what you want to filter on. usercategory is what you want to know
// TO DO REPLACE FUNCTIONS WITH THIS ONE
function getUserData($userData, $userCriteria, $userCategory)
{
    $sql = "SELECT $userCategory FROM gebruiker WHERE $userCriteria = ?";
    $checkedQuery = dbConnection()->prepare($sql);
    $checkedQuery->execute([
        standardDataSafety($userData)
    ]);
    $result = $checkedQuery->fetch();
    return $result;
}

function getUserTel($userName)
{
    $sql = "SELECT telefoon FROM Gebruikerstelefoon WHERE gebruikersnaam = ?";
    $query = dbConnection()->prepare($sql);
    $query->execute([
        standardDataSafety($userName)
    ]);
    $result = $query->fetch();
    return $result[0];
}

function getProductPerUser($userName)
{
    try {
        $sql = "SELECT v.voorwerpNummer, v.titel, v.beschrijving, v.looptijdEindeDag, v.looptijdeindeTijdstip, v.veilingGesloten, g.gebruikersnaam
        from Voorwerp v
        inner join Gebruiker g
        on v.verkopernaam = g.gebruikersnaam
        where g.gebruikersnaam = :username";

        $query = dbConnection()->prepare($sql);
        $query->execute([
            standardDataSafety($userName)
        ]);
        $result = $query->fetchAll();
        return $result;
    } catch (Exception $e) {
        return false;
    }
}

function blockUser($userName)
{
    try {
        $sql = "UPDATE Gebruiker 
                SET geblokkeerd = 1 
                WHERE gebruikersnaam = ?";
        $query = dbConnection()->prepare($sql);
        $query->execute([
            standardDataSafety($userName)
        ]);
        return true;
    } catch (Exception $e) {
        return false;
    }
}

function unBlockUser($userName)
{
    try {
        $sql = "UPDATE Gebruiker SET geblokkeerd = 0 WHERE gebruikersnaam = ?";
        $query = dbConnection()->prepare($sql);
        $query->execute([
            standardDataSafety($userName)
        ]);
        return true;
    } catch (Exception $e) {
        return false;
    }
}

function updateUser($userData, $user) //encrypted
{
    $key = getEnvFile('encryptionkey', 'app');
    $dataToEncrypt = ['firstname', 'lastname', 'zipcode', 'adress', 'adress2', 'city', 'tel', 'email'];
    foreach ($dataToEncrypt as $data) {
        if (!empty($userData[$data])) {
            $userData[$data] = encrypt($userData[$data], $key);
        }
    }
    $_SESSION['mail'] = $userData['email'];
    try {
        $sql1 = "UPDATE Gebruiker SET 
        voornaam = :firstname,
        achternaam = :lastname,
        geboortedag = :birthdate,
        postcode = :zipcode,
        adresregel_1 = :adress,
        adresregel_2 = :adress2,
        plaatsnaam = :city,
        mailbox = :email
        WHERE gebruikersnaam = :username";

        $query1 = dbConnection()->prepare($sql1);
        $query1->execute([
            standardDataSafety($userData['firstname']),
            standardDataSafety($userData['lastname']),
            standardDataSafety($userData['birthdate']),
            standardDataSafety($userData['zipcode']),
            standardDataSafety($userData['adress']),
            standardDataSafety($userData['adress2']),
            standardDataSafety($userData['city']),
            standardDataSafety($userData['email']),
            standardDataSafety($user)
        ]);

        $sql2 = "UPDATE Gebruikerstelefoon SET 
        telefoon = :tel
        WHERE gebruikersnaam = :username";

        $query2 = dbConnection()->prepare($sql2);
        $query2->execute([
            standardDataSafety($userData['tel']),
            $user
        ]);
        return true;
    } catch (Exception $e) {
        return false;
    }
}

function getUsers($usernameInput) //encryped
{
    try {
        $userName = '%' . trim($usernameInput) . '%';
        $sql = "SELECT gebruikersnaam, mailbox
        FROM Gebruiker
        WHERE gebruikersnaam = ? OR gebruikersnaam LIKE ?
        ORDER BY gebruikersnaam";

        $query = dbConnection()->prepare($sql);
        $query->execute([
            standardDataSafety($usernameInput), standardDataSafety($userName)
        ]);
        $result = $query->fetchAll();
        return $result;
    } catch (Exception $e) {
        return false;
    }
}

function insertSafetyCode($code, $userName, $type)
{
    try {
        $sql = 'INSERT INTO VerificatieCode (Verificatiecode, Aanmaakdatum, Gebruikersnaam, type) VALUES (:code, GETDATE(), :user, :type)';
        $query = dbConnection()->prepare($sql);
        $query->execute([
            $code, $userName, $type
        ]);
        return true;
    } catch (Exception $e) {
        return false;
    }
}


function getBidsPerUser($userName)
{
    try {
        $sql = "SELECT distinct(b.voorwerpnummer), max(b.Bodbedrag) as bodbedrag
        from Bod b
        inner join Gebruiker g
        on b.gebruikersnaam = g.gebruikersnaam
        where b.gebruikersnaam = :username
        group by b.voorwerpnummer";

        $query = dbConnection()->prepare($sql);
        $query->execute([
            standardDataSafety($userName)
        ]);
        $result = $query->fetchAll();
        return $result;
    } catch (Exception $e) {
        return false;
    }
}

function deleteBidFromDatabase($productNumber, $userName)
{
    try {
        $sql = "DELETE FROM Bod
                WHERE voorwerpnummer = ? AND Gebruikersnaam = ? AND BodTijdStip = (SELECT TOP (1) BodTijdStip
                FROM Bod
                WHERE voorwerpnummer = ? AND Gebruikersnaam = ? 
                ORDER BY BodTijdStip DESC
                )";
        $query = dbConnection()->prepare($sql);
        $query->execute([
            standardDataSafety($productNumber), standardDataSafety($userName), standardDataSafety($productNumber), standardDataSafety($userName)
        ]);
        return true;
    } catch (Exception $e) {
        return false;
    }
}

//geeft de vraag terug bij dat vraagnummer
function getVraag($vraagnummer) //aangepast 1-6-2022
{
    $sql = "SELECT tekstvraag FROM vraag WHERE vraagnummer = ?";
    $checkedQuery = dbConnection()->prepare($sql);
    $checkedQuery->execute([$vraagnummer]);
    $result = $checkedQuery->fetch();
    return $result[0];
}

//SIGN IN___________________________________________
function getExistingUser($userData)
{
    $sql = 'SELECT gebruikersnaam, wachtwoord, verwijderd FROM gebruiker WHERE gebruikersnaam = ?';
    $checkedQuery = dbConnection()->prepare($sql);
    $checkedQuery->execute([$userData['username']]);
    $result = $checkedQuery->fetch();
    return $result;
}

function getSellerStatus($userData)
{
    $sql = 'SELECT verkoperstatus FROM gebruiker WHERE gebruikersnaam = ?';
    $checkedQuery = dbConnection()->prepare($sql);
    $checkedQuery->execute([$userData]);
    $result = $checkedQuery->fetch();
    return $result;
}

function getAdminStatus($userData)
{
    $sql = 'SELECT beheerderstatus FROM Gebruiker WHERE gebruikersnaam = ?';
    $checkedQuery = dbConnection()->prepare($sql);
    $checkedQuery->execute([$userData]);
    $result = $checkedQuery->fetch();
    return $result;
}

function getBlockedStatus($userData)
{
    $sql = 'SELECT geblokkeerd FROM Gebruiker WHERE gebruikersnaam = ?';
    $checkedQuery = dbConnection()->prepare($sql);
    $checkedQuery->execute([$userData]);
    $result = $checkedQuery->fetch();
    return $result;
}



//PRODUCTS____________________________________________
// homepage
function getProducts($amount, $order)
{
    $sql = "SELECT TOP $amount voorwerpnummer, titel, beschrijving, looptijdbeginDag AS startDay, looptijdbeginTijdstip AS startTime, looptijdEindeDag AS endDay, looptijdeindeTijdstip AS endTime, verkopernaam AS seller
    FROM Voorwerp
    INNER JOIN gebruiker g
    ON voorwerp.verkopernaam = g.gebruikersnaam
    WHERE veilinggesloten = 0 AND g.geblokkeerd = 0 AND looptijd != 0 and looptijdeindeDag > GETDATE() AND verkopernaam NOT LIKE '%Johnny%'
    ORDER BY $order";
    $checkedQuery = dbConnection()->prepare($sql);
    $checkedQuery->execute();
    $result = $checkedQuery->fetchAll();
    return $result;
}

function getPopularProducts($amount)
{
    $sql = "SELECT DISTINCT TOP $amount count(Bodbedrag), v.voorwerpnummer, v.titel, v.beschrijving, v.looptijdbeginDag AS startDay, v.looptijdbeginTijdstip AS startTime, v.looptijdEindeDag AS endDay, v.looptijdeindeTijdstip AS endTime, v.verkopernaam AS seller
    from Voorwerp v
    left outer join Bod b
    ON v.voorwerpNummer = b.voorwerpNummer
    INNER JOIN Gebruiker g
    ON v.verkopernaam = g.gebruikersnaam
    WHERE veilinggesloten = 0 AND g.geblokkeerd = 0 AND looptijd != 0 and looptijdeindeDag > GETDATE()
	GROUP BY  v.voorwerpNummer, v.titel, v.beschrijving, v.looptijdbeginDag, v.looptijdbeginTijdstip, v.looptijdEindeDag, v.looptijdeindeTijdstip, v.verkopernaam
    ORDER BY count(Bodbedrag) desc";
    $checkedQuery = dbConnection()->prepare($sql);
    $checkedQuery->execute();
    $result = $checkedQuery->fetchAll();
    return $result;
}

function getPopulairCategoryNavbar()
{
    $sql = "SELECT TOP 5 count(VIR.rubrieknummer) as rubriekcount, R.rubrieknummer, r.rubrieknaam
    from Voorwerp_In_Rubriek VIR
    INNER JOIN Rubriek R ON VIR.rubrieknummer = R.rubrieknummer
    GROUP BY R.rubrieknummer, r.rubrieknaam
    ORDER BY count(VIR.rubrieknummer) DESC";

    $query = dbConnection()->prepare($sql);
    $query->execute();
    $result = $query->fetchAll();
    return $result;
}


//SEARCH PRODUCTS
function getSearchProducts($searchInput)
{
    try {
        $titel = '%' . trim($searchInput) . '%';
        $sql = "SELECT voorwerpnummer, titel, beschrijving, looptijdbeginDag AS startDay, looptijdbeginTijdstip AS startTime, looptijdEindeDag AS endDay, looptijdeindeTijdstip AS endTime, verkopernaam as seller
                FROM Voorwerp
                INNER JOIN Gebruiker g
                ON voorwerp.verkopernaam = g.gebruikersnaam
                WHERE titel LIKE ? AND veilinggesloten = 0 AND g.geblokkeerd = 0 AND looptijd != 0 and looptijdeindeDag > GETDATE()";
        $checkedQuery = dbConnection()->prepare($sql);
        $checkedQuery->execute([$titel]);
        $result = $checkedQuery->fetchAll();
        return $result;
    } catch (Exception $e) {
        return false;
    }
}


//ADD PRODUCTS_________________________________________
function addProduct($product, $time)
{
    try {
        $sql = "INSERT INTO Voorwerp(titel, beschrijving, startprijs, looptijd, verzendkosten, verzendinstructies, betalingswijze, land, plaatsnaam, verkopernaam, looptijdbegindag, looptijdbeginTijdstip, veilinggesloten, looptijdeindetijdstip)
            VALUES(:title, :description, :startingprice, :timelisted, :shippingcosts, :shippinginstructions, :betalingswijze, :land, :plaatsnaam, :verkopernaam, GETDATE(), :time, 0, CURRENT_TIMESTAMP)";
        $query = dbConnection()->prepare($sql);
        $query->execute([
            standardDataSafety($product['title']), standardDataSafety($product['description']), standardDataSafety($product['startingprice']),
            standardDataSafety($product['timelisted']), standardDataSafety($product['shippingcosts']), standardDataSafety($product['shippinginstructions']),
            standardDataSafety($product['paymentmethod']), standardDataSafety($product['country']), standardDataSafety($product['city']), $_SESSION['user'],
            $time
        ]);
        return true;
    } catch (Exception $e) {
        die($e);
        return false;
    }
}

function addProductRubic($productNumber, $productCategory)
{
    try {
        $sql = "INSERT INTO Voorwerp_In_Rubriek(voorwerpNummer, rubrieknummer) VALUES(?, ?)";
        $query = dbConnection()->prepare($sql);
        $query->execute([standardDataSafety($productNumber), standardDataSafety($productCategory)]);
        return true;
    } catch (Exception $e) {
        die($productNumber);
        return false;
    }
}

function removeProductInfo($productInfo)
{
    try {
        $sql = 'DELETE FROM voorwerp WHERE titel = ? AND beschrijving = ?';
        $cleanQuery = dbConnection()->prepare($sql);
        $cleanQuery->execute([
            standardDataSafety($productInfo['title']), standardDataSafety($productInfo['description'])
        ]);
        return true;
    } catch (Exception $e) {
        return false;
    }
}

function addImage($fileName, $product, $time)
{
    try {
        $productNumber = getProductNumber($product, $time);
        $dirtyImageQuery = 'INSERT INTO Bestand(filenaam, voorwerpNummer) VALUES(?, ?)';
        $cleanImageQuery = dbConnection()->prepare($dirtyImageQuery);
        $cleanImageQuery->execute([
            standardDataSafety($fileName), standardDataSafety($productNumber)
        ]);
        return true;
    } catch (Exception $e) {
        return false;
    }
}

function getProductNumber($product, $time)
{

    $dirtyItemNumberQuery = 'SELECT voorwerpNummer as itemnr FROM Voorwerp WHERE titel = ? AND beschrijving = ? AND looptijdbeginTijdstip = ?';
    $cleanItemNumberQuery = dbConnection()->prepare($dirtyItemNumberQuery);
    $cleanItemNumberQuery->execute([
        standardDataSafety($product['title']),
        standardDataSafety($product['description']),
        $time
    ]);
    $productNumber = $cleanItemNumberQuery->fetch();
    return $productNumber[0];
}



//BECOME SELLER________________________
function insertVerkoper($sellerData, $user)
{
    $key = getEnvFile('encryptionkey', 'app');
    $dataToEncrypt = ['banknaam', 'rekeningnummer', 'creditcardnummer'];
    foreach ($dataToEncrypt as $data) {
        if (!empty($sellerData[$data])) {
            $sellerData[$data] = encrypt($sellerData[$data], $key);
        }
    }
    try {
        $sql1 = "UPDATE Gebruiker SET verkoperstatus = 1 WHERE gebruikersnaam = ?";
        $query1 = dbConnection()->prepare($sql1);
        $query1->execute([standardDataSafety($user)]);
    } catch (Exception $e) {
        return false;
    }
    try {
        $sql2 = "INSERT INTO Verkoper(gebruikersnaam, banknaam, rekeningnummer, controleoptie, creditcardnummer)
                 VALUES(:gebruikersnaam, :banknaam, :rekeningnummer, :controleoptie, :creditcardnummer)";
        $query2 = dbConnection()->prepare($sql2);
        $query2->execute([
            standardDataSafety($user),
            standardDataSafety($sellerData['bank']),
            standardDataSafety($sellerData['bankaccount']),
            standardDataSafety($sellerData['option']),
            standardDataSafety($sellerData['cardnumber'])
        ]);
    } catch (Exception $e) {
        try {
            $sql3 = "UPDATE Gebruiker SET verkoperstatus = 0 WHERE gebruikersnaam = ?";
            $query3 = dbConnection()->prepare($sql3);
            $query3->execute([
                standardDataSafety($user)
            ]);
        } catch (Exception $e) {
            return false;
        }
        return false;
    }
}

function unmakeSeller($user)
{
    try {
        $sql1 = "UPDATE Gebruiker SET verkoperstatus = 0 WHERE gebruikersnaam = ?";
        $query1 = dbConnection()->prepare($sql1);
        $query1->execute([standardDataSafety($user)]);
    } catch (Exception $e) {
        return false;
    }

    try {
        $sql2 = "DELETE FROM Verkoper WHERE gebruikersnaam = ?";
        $query2 = dbConnection()->prepare($sql2);
        $query2->execute([standardDataSafety($user)]);
        return true;
    } catch (Exception $e) {
        return false;
    }
}

function getImageName($number, $productId)
{
    $sql = "SELECT TOP $number filenaam from bestand WHERE voorwerpNummer = ?";
    $query = dbConnection()->prepare($sql);
    $query->execute([$productId]);
    $queryResults = $query->fetchAll();
    return $queryResults;
}

function getTime($id)
{
    $sql = 'SELECT looptijdeindeTijdstip AS time FROM voorwerp WHERE voorwerpNummer = ?';
    $checkQuery = dbConnection()->prepare($sql);
    $checkQuery->execute([$id]);
    $result = $checkQuery->fetch();
    return $result['time'];
}

// BOD FUNCTIES

//voegt bod toe
function addBid($bid, $userName, $productNumber)
{
    try {
        $sql = "INSERT INTO Bod(voorwerpnummer, Bodbedrag, Gebruikersnaam, BodDag, BodTijdStip)
        VALUES(:productnumber, :bid, :user, GETDATE(), CURRENT_TIMESTAMP)";
        $query = dbConnection()->prepare($sql);
        $query->execute([$productNumber, $bid, $userName]);
        return true;
    } catch (Exception $e) {
        return false;
    }
}

//returned startbod
function getStartingPrice($id)
{
    $sql = 'SELECT startprijs
    FROM voorwerp
    WHERE voorwerpnummer = ?';
    $query = dbConnection()->prepare($sql);
    $query->execute([$id]);
    $result = $query->fetch();
    return $result['startprijs'];
}

// returned hoogste aantal boden dat je invuld met $amount
function getHighestBidData($id, $amount, $columns)
{
    $columns = 'b.' . $columns;
    $sql = "SELECT TOP $amount $columns
    FROM Bod b
    INNER JOIN gebruiker g
    ON g.gebruikersnaam = b.Gebruikersnaam
    WHERE voorwerpnummer = ? AND g.geblokkeerd = 0  
    ORDER BY Bodbedrag DESC";
    $query = dbConnection()->prepare($sql);
    $query->execute([$id]);
    $result = $query->fetchAll();
    return $result;
}

function getAmountOfBids($id)
{
    $sql = 'SELECT COUNT(*) AS amount FROM bod WHERE voorwerpnummer = ?';
    $query = dbConnection()->prepare($sql);
    $query->execute([$id]);
    $result = $query->fetch();
    return $result['amount'];
}

function endAuction($productinfo, $bidData, $buyerInfo)
{
    $buyerName = $buyerInfo['gebruikersnaam'];
    $soldPrice = $bidData[0]['Bodbedrag'];
    $productId = $productinfo[0]['voorwerpNummer'];
    try {
        $sql = 'UPDATE Voorwerp
        SET looptijd = 0, veilingGesloten = 1, kopernaam = :buyername, verkoopprijs = :price
        WHERE voorwerpNummer = :voorwerpnummer';
        $query = dbConnection()->prepare($sql);
        $query->execute([
            standardDataSafety($buyerName), standardDataSafety($soldPrice),
            standardDataSafety($productId)
        ]);
        return true;
    } catch (Exception $e) {
        return false;
    }
}

function cancelBid($productId)
{
    try {
        $sql = 'UPDATE Voorwerp
        SET looptijd = 0, veilingGesloten = 1, kopernaam = NULL, verkoopprijs = NULL
        WHERE voorwerpNummer = :voorwerpnummer';
        $query = dbConnection()->prepare($sql);
        $query->execute([
            standardDataSafety($productId)
        ]);
        return true;
    } catch (Exception $e) {
        return false;
    }
}


//CATECORYFILTER________________________________
function getRubriekNumber($category)
{
    $sql = 'SELECT rubrieknummer FROM Rubriek WHERE rubrieknaam = :categoryname';
    $query = dbConnection()->prepare($sql);
    $query->execute([
        standardDataSafety($category)
    ]);
    $result = $query->fetch();
    return $result['rubrieknummer'];
}

function getProductPerCategory($category)
{
    $sql = 'SELECT v.voorwerpnummer, v.titel, v.beschrijving, v.looptijdeindeDag, v.looptijdeindeTijdstip, v.verkopernaam as seller
        FROM Voorwerp v
        INNER JOIN Voorwerp_In_Rubriek vir
        ON v.voorwerpNummer = vir.voorwerpnummer
        WHERE rubrieknummer = :category
        AND veilinggesloten = 0 AND looptijdeindeDag > GETDATE()';
    $query = dbConnection()->prepare($sql);
    $query->execute([
        standardDataSafety($category)
    ]);
    $result = $query->fetchAll();
    return $result;
}

//HIER GEBLEVEN MET ENCRYPTIE

//Verander wachtwoord $wachtwoord is het nieuwe wachtwoord
function resetPassword($password, $userName)
{

    try {
        $sql = 'UPDATE Gebruiker
        SET wachtwoord = :password
        WHERE gebruikersnaam = :userName';
        $query = dbConnection()->prepare($sql);
        $query->execute([
            $password,
            $userName
        ]);
        return true;
    } catch (Exception $e) {
        return false;
    }
}

function getBankName($itemNumber)
{
    $sql = 'SELECT ve.banknaam as name
    FROM Voorwerp vo
    INNER JOIN Verkoper ve ON vo.verkopernaam = ve.gebruikersnaam 
    WHERE vo.voorwerpNummer = ?';
    $query = dbConnection()->prepare($sql);
    $query->execute([$itemNumber]);
    $result = $query->fetch();
    return $result['name'];
}

function getColumnLength()
{
    $sql = "SELECT OBJECT_NAME(OBJECT_ID) AS tablename, name AS columnname, TYPE_NAME(system_type_id) AS bestandstype, 
    CASE 
        WHEN TYPE_NAME(system_type_id) IN ('NCHAR','NVARCHAR') 
            THEN max_length / 2
        ELSE max_length
        END 
    AS maximumlength
    FROM sys.columns
    WHERE TYPE_NAME(system_type_id) IN ('CHAR','NCHAR','VARCHAR','NVARCHAR') AND OBJECT_NAME(OBJECT_ID) IN (SELECT TABLE_NAME FROM information_schema.tables) 
    AND (name) IN ('voornaam', 'achternaam', 'adresregel_1', 'adresregel_2', 'Postcode', 'plaatsnaam', 'Mailbox', 'Antwoordtekst', 
        'banknaam', 'rekeningnummer', 'creditcardnummer', 'telefoon')";
    $query = dbConnection()->prepare($sql);
    $query->execute();
    $result = $query->fetchAll();
    return $result;
}

function getSellerData($userMail)
{
    $sql = 'SELECT TOP 1 G.gebruikersnaam, banknaam, rekeningnummer, creditcardnummer FROM Verkoper V 
INNER JOIN gebruiker G ON V.gebruikersnaam = G.gebruikersnaam
WHERE G.mailbox = ?';
    $query = dbConnection()->prepare($sql);
    $query->execute([$userMail]);
    $result = $query->fetch();
    return $result;
}

function isUserCode($userName, $verificationCode)
{
    $sql = 'SELECT COUNT(*) FROM verificatiecode WHERE verificatiecode = :verificationCode AND gebruikersnaam = :userName';
    $query = dbConnection()->prepare($sql);
    $query->execute([
        $userName, $verificationCode
    ]);
    $result = $query->fetch();
    return $result;
}

function sendEncryptedUserData($userData, $sellerData)
{
    try {
        //Update user
        $sqlUser = 'UPDATE Gebruiker 
        SET voornaam = ?, achternaam = ?, adresregel_1 = ?, adresregel_2 = ?, postcode = ?, plaatsnaam = ?, mailbox = ?, antwoordtekst = ? 
        WHERE gebruikersnaam = ?';
        $query = dbConnection()->prepare($sqlUser);
        $query->execute([
            $userData['voornaam'], $userData['achternaam'], $userData['adresregel_1'], $userData['adresregel_2'],
            $userData['postcode'], $userData['plaatsnaam'], $userData['mailbox'], $userData['antwoordtekst'],
            $userData['gebruikersnaam']
        ]);
    } catch (Exception $e) {
        return false;
    }
    try {
        //Update seller
        $sqlSeller = 'UPDATE Verkoper 
    SET banknaam = ?, rekeningnummer = ?, creditcardnummer = ?
    WHERE gebruikersnaam = ?';
        $query = dbConnection()->prepare($sqlSeller);
        $query->execute([
            $sellerData['banknaam'], $sellerData['rekeningnummer'], $sellerData['creditcardnummer'],
            $sellerData['gebruikersnaam']
        ]);
    } catch (Exception $e) {
        return false;
    }
    try {
        //Update user phone
        $sqlUserPhone = 'UPDATE Gebruikerstelefoon 
    SET telefoon = ?
    WHERE gebruikersnaam = ?';
        $query = dbConnection()->prepare($sqlUserPhone);
        $query->execute([
            $userData['phone'],
            $sellerData['gebruikersnaam']
        ]);
    } catch (Exception $e) {
        return false;
    }
    return true;
}

function getAllUserMail()
{
    $sql = 'SELECT mailbox FROM gebruiker';
    $query = dbConnection()->prepare($sql);
    $query->execute();
    return $query->fetchAll();
}

function doesCodeExist($code)
{
    $sql = 'SELECT COUNT(DISTINCT Verificatiecode) FROM VerificatieCode WHERE Verificatiecode IN (:code)';
    $query = dbConnection()->prepare($sql);
    $query->execute([
        standardDataSafety($code)
    ]);
    return $query->fetch();
}

function insertCode($code)
{
    try {
        $sql = 'INSERT INTO VerificatieCode (verificatiecode, aanmaakdatum) VALUES(:code , GETDATE())';
        $query = dbConnection()->prepare($sql);
        $query->execute([
            standardDataSafety($code)
        ]);
        return true;
    } catch (Exception $e) {
        return false;
    }
}


function deleteCode($code)
{
    try {
        $sql = 'DELETE FROM VerificatieCode WHERE Verificatiecode = :Code';
        $query = dbConnection()->prepare($sql);
        $query->execute([
            standardDataSafety($code)
        ]);
    } catch (Exception $e) {
        return false;
    }
}


function deleteExpiredCodes()
{
    try {
        $sql = 'DELETE FROM VerificatieCode WHERE DATEDIFF(mi, Aanmaakdatum, GETDATE()) >= 10';
        $query = dbConnection()->prepare($sql);
        $query->execute([]);
    } catch (Exception $e) {
        return false;
    }
}

function removeUserInfo($userName)
{
    try {
        $sql = "DECLARE @deletedUser CHAR (40) = 'DELETED_USER_USERNAME'
        DECLARE @removedUsername CHAR (40) = :username
        DELETE FROM Gebruikerstelefoon WHERE gebruikersnaam = @removedUsername
        UPDATE Voorwerp SET verkopernaam = @deletedUser WHERE verkopernaam = @removedUsername
        UPDATE Voorwerp SET kopernaam = @deletedUser WHERE kopernaam = @removedUsername
        DELETE FROM Verkoper WHERE gebruikersnaam = @removedUsername
        DELETE FROM Gebruiker WHERE gebruikersnaam = @removedUsername";
        $query = dbConnection()->prepare($sql);
        $query->execute([
            standardDataSafety($userName)
        ]);
        return true;
    } catch (Exception $e) {
        return false;
    }
}

function getUserCode($userName, $type)
{
    $sql = 'SELECT DISTINCT Verificatiecode FROM VerificatieCode WHERE Gebruikersnaam = :username AND TYPE = :type';
    $query = dbConnection()->prepare($sql);
    $query->execute([
        $userName, $type
    ]);
    $results = $query->fetch();
    return $results;
}

function setUserStatus($userName, $status)
{
    try {
        $sql = 'UPDATE gebruiker SET verwijderd = :status WHERE gebruikersnaam = :username';
        $query = dbConnection()->prepare($sql);
        $query->execute([
            $status, $userName
        ]);
        return true;
    } catch (Exception $e) {
        return false;
    }
}

function isUserDeleted($userName)
{
    $sql = 'SELECT verwijderd FROM gebruiker WHERE Gebruikersnaam = :username';
    $query = dbConnection()->prepare($sql);
    $query->execute([
        $userName
    ]);
    $results = $query->fetch();
    return $results['verwijderd'];
}

function updateMail($userName, $mailbox)
{
    try {
        $sql = 'UPDATE gebruiker SET mailbox = :mailbox WHERE Gebruikersnaam = :username';
        $query = dbConnection()->prepare($sql);
        $query->execute([
            $mailbox, $userName
        ]);
        return true;
    } catch (Exception $e) {
        return false;
    }
}

function getExpiredUserInfo()
{
    $sql = ' SELECT gebruikersnaam FROM Pre_deleted_users WHERE GETDATE() > minimale_uitvoerdatum';
    $query = dbConnection()->prepare($sql);
    $query->execute();
    $results = $query->fetchAll();
    return $results;
}
