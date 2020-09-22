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
            $conn = "mysql:host=$this->host;dbname=$this->database;charset=$this->charset";
            $this->db = new PDO($conn, $this->username, $this->password);
            echo "Database connectie succesvol";
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit("Error");
        }
    }
}
