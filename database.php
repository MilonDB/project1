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

        // try -> catch
        // begin transaction
        // committen


    try{
            // begin transaction

            echo "zomaar";
            $sql = "INSERT INTO account(id,email,password) VALUES (?, :email, ?)";
            echo 'sql:' . $sql;

            $statement = $this->db->prepare($sql);
            print_r($statement);

            // password hashen

            $statementExec = $statement->execute([NULL, 'email' => $email, $password]);

            //lastInsertId() -> meegeven aan je insert van je persoon


    }catch(){

        // pdo rollback
    }
       

        //print_r($statementExec);
        
    }
}
