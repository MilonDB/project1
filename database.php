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

    // Functie om account te inserten
    function insertAccount($email, $password, $username)
    {
        try {
            // begin transactie naar database
            $this->db->beginTransaction();

            echo "Testbericht <br>";
            // $sql is nu niets meer dan een string, om deze uit te kunnen voeren, moeten wij hem preparen
            $sql = "INSERT INTO account(id,email,password,username) VALUES (:id,:email,:password,:username)";
            echo 'sql:' . $sql . "<br>";

            // Het preparen om $sql uit te voeren gebeurt hier
            $statement = $this->db->prepare($sql);
            print_r($statement);

            // password hashen
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Hier wordt de statement executed
            $statementExec = $statement->execute(['id' => NULL, 'email' => $email, 'password' => $hashed_password, 'username' => $username]);

            // Hier wordt de laatste id opgehaald om te gebruiken als foreign key naar Persoon
            $lastID = $this->db->lastInsertId();
            echo "<BR>Laatste ingevoerde ID= ";
            print_r($lastID);

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

    // Functie om persoon te inserten
    function insertPersoon($voornaam, $tussenvoegsel, $achternaam, $lastID)
    {
        try {
            //begin transaction
            $this->db->beginTransaction();

            echo "Dit is om te kijken of persoon werkt." . "<BR>";
            // $sql_persoon is nu niets meer dan een string, om deze uit te kunnen voeren, moeten wij hem preparen
            $sql_persoon = "INSERT INTO persoon(id, account_id,voornaam,tussenvoegsel,achternaam) VALUES (:id, :account_id ,:voornaam,:tussenvoegsel,:achternaam)";
            echo "<br>sql voor persoon: " . $sql_persoon . "<br>";

            // Het preparen om $sql_persoon uit te voeren gebeurt hier
            $stmtPersoon = $this->db->prepare($sql_persoon);
            print_r($sql_persoon);

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
}
