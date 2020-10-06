<?php

include "database.php";

//Na de button press wordt de array aangemaakt met de fieldnames
if (isset($_POST['submit'])) {
    $fieldnames = ['username', 'password', 'repassword', 'voornaam', 'achternaam', 'tussenvoegsel', 'email'];

    $error = False;
    // Loopt over de field heen om te kijken of velden ingevuld zijn. Zo nee, wordt $error true.
    foreach ($fieldnames as $fieldname) {
        if (!isset($_POST[$fieldname]) || empty($_POST[$fieldname])) {
            $error = true;
            echo "Error gevonden, velden zijn niet correct ingevuld!";
        }
    }

    // Met deze if statement wordt gecheckt of de velden ingevuld zijn. Zo ja wordt de database connectie gemaakt en de data ingevoerd.
    if (!$error) {

        $username = $_POST['username'];
        $password = $_POST['password'];
        $repassword = $_POST['repassword'];
        $email = $_POST['email'];
        $voornaam = $_POST['voornaam'];
        $tussenvoegsel = $_POST['tussenvoegsel'];
        $achternaam = $_POST['achternaam'];

        $db = new database('localhost', 'root', '', 'project1', 'utf8');
        $account_id = $db->insertAccount($email, $password);
        $db->insertPersoon($username, $voornaam, $tussenvoegsel, $achternaam, $account_id);
    }
}


?>


<html>

<head>
    <title>Register</title>
</head>

<body>

    <form method="post" action="signup.php" id="register">
        <input type="text" name="voornaam" id="voornaam" placeholder="voornaam" required><br>
        <input type="text" name="tussenvoegsel" id="tussenvoegsel" placeholder="tussenvoegsel (optioneel)"><br>
        <input type="text" name="achternaam" id="achternaam" placeholder="achternaam" required><br>
        <input type="email" name="email" id="email" placeholder="email" required><br>
        <input type="text" name="username" id="username" placeholder="username" required><br>
        <input type="password" name="password" id="password" placeholder="password" required><br>
        <input type="password" name="repassword" id="repassword" placeholder="repeat password" required><br>
        <input type="submit" name='submit' value="register"><br>
        <a href="index.php">Heb je al een account? Ga terug naar login.</a>
    </form>

</body>

</html>