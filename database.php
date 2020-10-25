<?php

// Class aangemaakt
class database
{

    private $host;
    private $username;
    private $password;
    private $database;
    private $charset;
    private $db;


    // Hier maak ik de constants aan.
    const ADMIN = 1;
    const USER = 2;

    // Constructor zodat met deze data gewerkt kan worden.
    function __construct($host, $username, $password, $database, $charset)
    {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
        $this->charset = $charset;

        // Try and catch voor poging om database te verbinden
        try {
            // dsn = data source name
            $conn = "mysql:host=$this->host;dbname=$this->database;charset=$this->charset";
            $this->db = new PDO($conn, $this->username, $this->password);
            echo "Database connectie gemaakt.";
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit("Error");
        }
    }


    private function existing_username_check($username)
    {

        $stmt = $this->db->prepare('SELECT * FROM account WHERE username=:username');
        $stmt->execute(['username' => $username]);
        $result = $stmt->fetch();

        if (is_array($result) && count($result) > 0) {
            return false;
        }

        return true;
    }


    // Functie om account te inserten
    function insert_update_Account($email, $password, $username)
    {
        try {
            // begin transactie naar database. 
            $this->db->beginTransaction();

            // if (!$this->existing_username_check($username)) {
            //     return "Username already exists. Please pick another one, and try again.";
            // }

            echo "Testbericht <br>";
            // $sql is nu niets meer dan een string, om deze uit te kunnen voeren, moeten wij hem preparen, klaarzetten om te executen.
            $sql = "INSERT INTO account(id,email,password,username) VALUES (:id,:email,:password,:username)";
            // echo 'sql:' . $sql . "<br>";

            // Het preparen om $sql uit te voeren gebeurt hier
            $statement = $this->db->prepare($sql);
            // print_r($statement);

            // password hashen met ingebouwde php functie password_hash
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Hier wordt de statement executed
            $statementExec = $statement->execute(['id' => NULL, 'email' => $email, 'password' => $hashed_password, 'username' => $username]);

            // Hier wordt de laatste id opgehaald om te gebruiken als foreign key naar Persoon
            $lastID = $this->db->lastInsertId();
            echo "<BR>Laatste ingevoerde ID= ";
            // print_r($lastID);

            // Hier wordt de statement gecommit naar de database
            $this->db->commit();
            return $lastID;
        } catch (Exception $e) {
            // Bij een error wordt er een rollback uitgevoerd.
            $this->db->rollBack();
            throw ($e);
            echo "Rollback uitgevoerd! Fout bij Account";
        }
    }

    // Functie om persoon te inserten in de database.
    function insert_update_Persoon($voornaam, $tussenvoegsel, $achternaam, $lastID)
    {
        try {
            //begin transaction
            $this->db->beginTransaction();

            echo "Dit is om te kijken of persoon werkt." . "<BR>";
            // $sql_persoon is nu niets meer dan een string, om deze uit te kunnen voeren, moeten wij hem preparen
            $sql_persoon = "INSERT INTO persoon(id, account_id,voornaam,tussenvoegsel,achternaam) VALUES (:id, :account_id ,:voornaam,:tussenvoegsel,:achternaam)";
            // echo "<br>sql voor persoon: " . $sql_persoon . "<br>";

            // Het preparen om $sql_persoon uit te voeren gebeurt hier
            $stmtPersoon = $this->db->prepare($sql_persoon);
            // print_r($sql_persoon);

            // Hier wordt de statement executed
            $stmtExec = $stmtPersoon->execute(['id' => NULL, 'account_id' => $lastID, 'voornaam' => $voornaam, 'tussenvoegsel' => $tussenvoegsel, 'achternaam' => $achternaam]);
            // Hier wordt de statement gecommit naar de database
            $this->db->commit();
        } catch (Exception $e) {
            // Bij een error wordt er een rollback uitgevoerd.
            $this->db->rollBack();
            throw ($e);
            echo "Rollback uitgevoerd! Fout bij Persoon";
        }
    }


    // Functie om te checken of een user bestaat, en om het ingevoerde form te confirmen met de juiste username/password combo
    function user_confirmation($username, $password)
    {
        // user_check is nu niets meer dan een string, wij preparen hem om uitgevoerd te worden. De echo is een pure check.
        $user_check = "SELECT password FROM Account WHERE username = :username";
        echo $user_check . "<br>" . "<br>";


        // user_check wordt geprepared om uitgevoerd te worden, en is nu een query.
        $stmt = $this->db->prepare($user_check);


        // voer user_check uit met de ingevoerde username value
        $stmt->execute(['username' => $username]);

        // haal uit de database 
        $res = $stmt->fetch();


        $hpwd = $res['password'];
        $user_exists = false;


        // als de username en wachtwoordcombinatie overeenkomen met data uit de database, en de user dus bestaat, wordt deze code uitgevoerd
        if ($username && password_verify($password, $hpwd)) {
            $user_exists = true;
            echo "welkom: " . $username;
            session_start();
            $_SESSION['id'] = $res['id'];
            $_SESSION['username'] = $username;
            $_SESSION['loggedin'] = true;
            header('refresh:3;url=welcome_user.php');
        } else {
            echo "Invalid username and/or password, or user does not exist" . '<br>';
        }
    }
}
