<?php

class database
{

    private $host;
    private $username;
    private $password;
    private $database;
    private $charset;
    private $db;


    function __construct($host, $username, $password, $database, $charset)
    {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
        $this->charset = $charset;


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

    function insertAccount($email, $password)
    {

        // try -> catch DONE
        // begin transaction DONE
        // committen DONE


        try {
            // begin transaction
            $this->db->beginTransaction();

            echo "Testbericht <br>";
            $sql = "INSERT INTO account(id,email,password) VALUES (:id,:email,:password)";
            echo 'sql:' . $sql . "<br>";

            $statement = $this->db->prepare($sql);
            print_r($statement);

            // password hashen
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $statementExec = $statement->execute(['id' => NULL, 'email' => $email, 'password' => $hashed_password]);

            $lastID = $this->db->lastInsertId();
            echo "<BR>Laatste ingevoerde ID= ";
            print_r($lastID);

            $this->db->commit();

            //lastInsertId() -> meegeven aan je insert van je persoon


        } catch (Exception $e) {

            $this->db->rollBack();
            throw ($e);
            echo "Rollback uitgevoerd! Fout bij Account";
        }
    }

    function insertPersoon($id, $username, $voornaam, $tussenvoegsel, $achternaam, $lastID)
    {
        try {
            //begin transaction
            $this->db->beginTransaction();

            echo "Dit is om te kijken of persoon werkt. <BR>";
            $sql_persoon = "INSERT INTO persoon(id, account_id,username,voornaam,tussenvoegsel,achternaam) VALUES (:id, :account_id,:username,:voornaam,:tussenvoegsel,:achternaam)";
            echo "<br>sql voor persoon: " . $sql_persoon . "<br>";

            $stmtPersoon = $this->db->prepare($sql_persoon);
            print_r($sql_persoon);

            $stmtExec = $stmtPersoon->execute(['id' => NULL, 'account_id' => $lastID, 'username' => $username, 'voornaam' => $voornaam, 'tussenvoegsel' => $tussenvoegsel, 'achternaam' => $achternaam]);

            $this->db->commit();
        } catch (Exception $e) {
            $this->db->rollBack();
            throw ($e);
            echo "Rollback uitgevoerd! Fout bij Persoon";
        }
    }
}
