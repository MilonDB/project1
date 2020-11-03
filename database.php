<?php

// Class aangemaakt
class database
{


    //instantieer de database
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
            // echo "Database connectie gemaakt.";
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit("Error");
        }
    }

    // Functie om te checken of account al bestaat.
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


    public function alterUser($account_details, $persoon_details)
    {

        if (is_array($account_details) && is_array($persoon_details)) {
            echo  'meh';
            try {

                // start transactie
                $this->db->beginTransaction();
                // set variables voor inhoud $account_details array
                $id = $account_details['account_id'];
                $username = $account_details['username'];
                $email = $account_details['email'];
                $type = $account_details['type'];

                $account_id = $this->insert_update_Account($id, $username, $email, $type, NULL);
                // set variables
                $id = $persoon_details['persoon_id'];
                $voornaam = $persoon_details['voornaam'];
                $tussenvoegsel = $persoon_details['tussenvoegsel'];
                $achternaam = $persoon_details['achternaam'];
                $this->insert_update_Persoon($id, $account_id, $voornaam, $tussenvoegsel, $achternaam);

                // commit database change
                $this->db->commit();
                echo
                    header("refresh:3;url=edit_user.php");
                return 'User data succesfully updated';
            } catch (Exception $e) {
                $this->db->rollback();
                echo 'Error occurred: ' . $e->getMessage();
            }
        } else {

            // return string error msg
            return 'account en persoon informatie zijn geen array.';
        }
    }

    // Functie om te checken of inlogger een admin is of niet.
    public function admin_check($username)
    {
        $sql = "SELECT type_id FROM account WHERE username = :username";

        $statement = $this->db->prepare($sql);

        $statement->execute(['username' => $username]);

        // fetch haalt data op uit database en plaatst in array
        $fetched = $statement->fetch();

        // Als de type_id van de gefetchde data overeenkomt met admin, dan ga je naar welkom_admin.php, anders ga je naar welcome_user.php
        if ($fetched['type_id'] == self::ADMIN) {
            //gebruiker is admin
            return true;
            header('refresh:4;url=welcome_admin.php');
        }
        // gebruiker is geen admin
        return false;
    }


    // Functie om account te inserten
    private function insert_update_Account($id, $email, $password, $username, $type_id)
    {
        $updated_at = date('Y-m-d H:i:s');
        if (is_null($id)) {

            // $sql is nu niets meer dan een string, om deze uit te kunnen voeren, moeten wij hem preparen, klaarzetten om te executen.
            $sql = "INSERT INTO account VALUES (NULL,:email,:password,:username, :created, :updated, :type_id)";
            // echo 'sql:' . $sql . "<br>";

            // Het preparen om $sql uit te voeren gebeurt hier
            $statement = $this->db->prepare($sql);
            // print_r($statement);

            // password hashen met ingebouwde php functie password_hash
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Hier wordt de statement executed
            $statementExec = $statement->execute(['email' => $email, 'password' => $hashed_password, 'username' => $username, 'created' => date('Y-m-d H:i:s'), 'updated' => $updated_at, 'type_id' => $type_id]);

            // Hier wordt de laatste id opgehaald om te gebruiken als foreign key naar Persoon
            $account_id = $this->db->lastInsertId();
            return $account_id;
        } else {
            // Anders gebeurt er niets.
            $sql = "UPDATE account SET email= :email, username = :username,updated_at = :updated, type_id = :type_id' WHERE id = :id";

            $statement = $this->db->prepare($sql);

            $statement->execute(["'email'= $email, 'username' = $username,'updated_at' = $updated_at, 'type_id' = $type_id"]);


            $account_id = $this->db->lastInsertId();
            return $account_id;
        }
    }

    // Functie om persoon te inserten in de database.
    function insert_update_Persoon($id, $voornaam, $tussenvoegsel, $achternaam, $account_id)
    {

        $updated_at = date('Y-m-d H:i:s');

        if (is_null($id)) {


            // $sql_persoon is nu niets meer dan een string, om deze uit te kunnen voeren, moeten wij hem preparen
            $sql_persoon = "INSERT INTO persoon VALUES (NULL, :account_id ,:voornaam,:tussenvoegsel,:achternaam, :created, :updated)";


            // Het preparen om $sql_persoon uit te voeren gebeurt hier
            $stmtPersoon = $this->db->prepare($sql_persoon);


            // Hier wordt de statement executed
            $stmtPersoon->execute(['account_id' => $account_id, 'voornaam' => $voornaam, 'tussenvoegsel' => $tussenvoegsel, 'achternaam' => $achternaam, 'created' => date('Y-m-d H:i:s'), 'updated' => $updated_at]);

            $person_id = $this->db->lastInsertId();
            return $person_id;
        } else {
            $sql = "UPDATE persoon SET voornaam = :voornaam, tussenvoegsel = :tussenvoegsel, achternaam = :achternaam, updated_at = :updated WHERE id = :id";

            $statement = $this->db->prepare($sql);

            $statement->execute(['id' => $id, 'voornaam' => $voornaam, 'tussenvoegsel' => $tussenvoegsel, 'achternaam' => $achternaam, 'updated' => $updated_at]);

            $person_id = $this->dbh->lastInsertId();
            return $person_id;
        }
    }


    // Registratiefunctie
    public function register($username, $type_id, $voornaam, $tussenvoegsel, $achternaam, $email, $password)
    {
        try {
            //Initiates a transaction to the database
            $this->db->beginTransaction();

            // Als username al bestaat dan moet er een andere gekozen worden.
            if (!$this->existing_username_check($username)) {
                echo "username bestaat al! probeer opnieuw";
                header('refresh:5;url=signup.php');
                exit;
            }

            // Account_id wordt aangemaakt.
            $account_id = $this->insert_update_Account(NULL, $email, $password, $username, $type_id);
            $this->insert_update_Persoon(NULL, $voornaam, $tussenvoegsel, $achternaam, $account_id);

            //Commits a transaction to the database
            $this->db->commit();
        } catch (PDOException $e) {
            $this->db->rollback();
            echo "Signup mislukt, er klopt iets niet,: " . $e->getMessage();
        }
    }

    public function get_account_details($id)
    {

        $statement = $this->db->prepare("SELECT * FROM account WHERE id=:id");
        $statement->execute(['id' => $id]);
        $account = $statement->fetch(PDO::FETCH_ASSOC);

        return $account;
    }


    //UNUSED FUNCTION. USE FOR EDIT
    public function get_persoon_details($id)
    {

        $statement = $this->db->prepare("SELECT * FROM persoon WHERE id=:id");
        $statement->execute(['id' => $id]);
        $persoon = $statement->fetch(PDO::FETCH_ASSOC);
        return $persoon;
    }

    // Data ophalen uit database om te tonen in frontend functie
    public function fetch_user_data_from_database($username)
    {
        $sql = "
        
            SELECT 
                a.id, 
                p.id as persoon_id, 
                a.email, 
                a.username, 
                u.type, 
                p.voornaam, 
                p.tussenvoegsel, 
                p.achternaam
            FROM persoon as p 
            LEFT JOIN account as a
            ON p.account_id = a.id
            LEFT JOIN usertype as u
            ON a.type_id = u.id       
         ";

        // Als meegegeven username niet gelijk is aan NULL wordt WHERE username = :username toegevoegd.
        if ($username !== NULL) {
            // Zodat de user alleen zijn eigen data ziet integendeel tot admin.
            $sql .= 'WHERE a.username = :username';


            $stmt = $this->db->prepare($sql);
            $stmt->execute(['username' => $username]);
            // fetch associative array 
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $results;
            print_r($results);
        } else {



            $stmt = $this->db->prepare($sql);

            $stmt->execute();
            // fetch associative array 
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $results;
            print_r($results);
        }
    }

    // Delete user from database functie.
    public function delete_user_from_database($persoon_id, $account_id)
    {
        // echo $account_id, $persoon_id;

        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("DELETE FROM persoon WHERE id=:id");
            $stmt->execute(['id' => $persoon_id]);
            print_r($stmt);

            $stmt = $this->db->prepare("DELETE FROM account WHERE id=:id");
            $stmt->execute(['id' => $account_id]);
            print_r($stmt);


            $this->db->commit();
        } catch (PDOException $e) {
            $this->db->rollback();
            echo 'Error: ' . $e->getMessage();
        }
    }






    // Functie om te checken of een user bestaat, en om het ingevoerde form te confirmen met de juiste username/password combo OOK WEL LOGIN
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

        if ($this->admin_check($username) && $username && password_verify($password, $hpwd)) {
            $user_exists = true;
            echo "welkom: " . $username;
            session_start();
            // $_SESSION['id'] = $res['id'];
            $_SESSION['username'] = $username;
            $_SESSION['loggedin'] = true;
            header('refresh:3;url=welcome_admin.php');
        } else {
            // als de username en wachtwoordcombinatie overeenkomen met data uit de database, en de user dus bestaat, wordt deze code uitgevoerd
            if ($username && password_verify($password, $hpwd)) {
                $user_exists = true;
                echo "welkom: " . $username;
                session_start();
                // $_SESSION['id'] = $res['id'];
                $_SESSION['username'] = $username;
                $_SESSION['loggedin'] = true;
                header('refresh:3;url=welcome_user.php');
            } else {
                echo "Invalid username and/or password, or user does not exist" . '<br>';
            }
        }
    }
}
