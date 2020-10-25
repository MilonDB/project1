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

    private function admin_check($username){
        $sql = "SELECT type_id FROM account WHERE username = :username";

        $statement = $this->db->prepare($sql);

        $statement->execute(['username' => $username]);

        $fetched = $statement->fetch();

        if ($fetched['type_id'] == self::ADMIN) {
            //gebruiker is admin
            return true;
        }
            // gebruiker is geen admin
            return false;
    }


    // Functie om account te inserten
    private function insert_update_Account($id, $email, $password, $username, $type_id)
    {
        $updated_at = date('Y-m-d H:i:s');
        if (is_null($id)) {


            // if (!$this->existing_username_check($username)) {
            //     return "Username already exists. Please pick another one, and try again.";
            // }

            echo "Testbericht <br>";
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

    public function register($username, $type_id, $voornaam, $tussenvoegsel, $achternaam, $email, $password)
    {
        try {
            $this->db->beginTransaction();

            //TODO: existing_user_check


            $account_id = $this->insert_update_Account(NULL, $email,$password,$username,$type_id);
            $this->insert_update_Persoon(NULL, $voornaam,$tussenvoegsel,$achternaam,$account_id);

            $this->db->commit();


            echo "u wordt nu geredirect naar login pagina";
            header('refresh:15;url=index.php');

            // check if there's a session (created in login, should only visit here in case of admin)
            // if (isset($_SESSION) && $_SESSION['usertype'] == self::ADMIN) {
            //     return "New user has been succesfully added to the database";
            // }
            // // user gets redirected to login if method is not called by admin. 
            // header('location: index.php');
            // // exit makes sure that further code isn't executed.
            // exit;
            



            exit;
        } catch (PDOException $e) {
            $this->db->rollback();
            echo "Signup mislukt, er klopt iets niet,: " . $e->getMessage();
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
            // $_SESSION['id'] = $res['id'];
            $_SESSION['username'] = $username;
            $_SESSION['loggedin'] = true;
            header('refresh:3;url=welcome_user.php');
        } else {
            echo "Invalid username and/or password, or user does not exist" . '<br>';
        }
    }
}
