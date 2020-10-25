<?php

include "fieldval.php";
include "database.php";

//Na de button press wordt de array aangemaakt met de fieldnames
if (isset($_POST['submit'])) {
    $fields = ['username', 'password', 'repassword', 'voornaam', 'achternaam', 'email'];

    

    $validate = new fieldVal();
    $check =  $validate->filled_fields($fields);



    //Met deze if statement wordt gecheckt of de velden ingevuld zijn. Zo ja wordt de database connectie gemaakt en de data ingevoerd.
    if ($check) {

        $username = trim(strtolower($_POST['username']));
        $password = trim(strtolower($_POST['password']));
        $repassword = trim(strtolower($_POST['repassword']));
        $email = trim(strtolower($_POST['email']));
        $voornaam = trim(strtolower($_POST['voornaam']));
        $tussenvoegsel = trim(strtolower($_POST['tussenvoegsel']));
        $achternaam = trim(strtolower($_POST['achternaam']));

        $error = False;

        // als bevestigd is dat de velden ingevuld zijn, wordt nog een check gedaan om te kijken of password en repassword overeenkomen.
        if ($_POST['password'] === $_POST['repassword']) {
            echo "Wachtwoorden komen overeen";
            echo "U wordt nu doorverwezen naar de login.";
            header("refresh:3;url=index.php"); 

            // Maak database connectie aan en voer data in de tabellen.
            $db = new database('localhost', 'root', '', 'project1', 'utf8');
            $aanmaken = $db->register($username, $db::USER, $voornaam, $tussenvoegsel, $achternaam, $email, $password);
        
        } else {
            $error = true;
            echo 'wachtwoorden komen niet overeen! Probeer opnieuw!';
        }

    }
}



?>


<html>

<head>
    <title>Register</title>
</head>

<body>

    <form method="post" action="signup.php" id="register">
        <input type="text" name="voornaam" id="voornaam" value="<?php echo isset($_POST['voornaam']) ?>" placeholder="voornaam"><br>
        <input type="text" name="tussenvoegsel" id="tussenvoegsel" value="<?php echo isset($_POST['tussenvoegsel']) ? htmlentities($_POST['tussenvoegsel']) : ''; ?>" placeholder="tussenvoegsel (optioneel)"><br>
        <input type="text" name="achternaam" id="achternaam" placeholder="achternaam" value="<?php echo isset($_POST['achternaam']) ? htmlentities($_POST['achternaam']) : ''; ?>"><br>
        <input type="email" name="email" id="email" placeholder="email" value="<?php echo isset($_POST['email']) ? htmlentities($_POST['email']) : ''; ?>" required><br>
        <input type="text" name="username" id="username" placeholder="username" value="<?php echo isset($_POST['username']) ? htmlentities($_POST['username']) : ''; ?>" required><br>
        <input type="password" name="password" id="password" placeholder="password" value="<?php echo isset($_POST['password']) ? htmlentities($_POST['password']) : ''; ?>" required><br>
        <input type="password" name="repassword" id="repassword" placeholder="repeat password" required><br>
        <input type="submit" name='submit' value="register"><br>
        <a href="index.php">Heb je al een account? Ga terug naar login.</a>
    </form>

</body>

</html>