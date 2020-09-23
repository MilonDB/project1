<?php

include "database.php";

$db = new database('localhost', 'root', '', 'project1', 'utf8');


$username = $_POST['username'];
$password = $_POST['password'];
$email = $_POST['email'];
$voornaam = $_POST['voornaam'];
$tussenvoegsel = $_POST['tussenvoegsel'];
$achternaam = $_POST['achternaam'];

echo $username;

$sql_account = "INSERT INTO account('email','password') VALUES ('$email','$password')";
$sql_persoon = "INSERT INTO persoon('username','voornaam','tussenvoegsel','achternaam') VALUES ('$username','$voornaam','$tussenvoegsel','$achternaam')";

if (isset($_POST['submit'])) {
    exec($sql_account, $sql_persoon);
}else {
    echo "Dit werkt niet zo.";
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
        <input type="submit" value="register"><br>
        <a href="index.php">Heb je al een account? Ga terug naar login.</a>
    </form>

</body>

</html>