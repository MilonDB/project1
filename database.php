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
            echo "Rollback uitgevoerd!";
        }
    }

    function insertPersoon(){
        try {
            //begin transaction
            $this->db->beginTransaction();

            echo "Dit is om te kijken of persoon werkt. <BR>";
            $sql_persoon = "INSERT INTO persoon(id, account_id,username,voornaam,tussenvoegsel,achternaam)";
        } catch (Exception $e) {
            //throw $th;
        }
}
