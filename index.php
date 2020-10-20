<?php

include "database.php";
session_start();



if (isset($_POST['login'])) {
    $inlogvelden = ['username', 'password'];

    $error = false;

    foreach ($inlogvelden as $inlogvelden) {
        if (!(isset($_POST['inlogvelden']) || empty($_POST['inlogvelden']))) {
            $error = true;
            echo "Error bij het inloggen! Een of meerdere velden zijn niet ingevuld.";
        }
    }

    if (!$error) {

        $username = $_POST['username'];
        $password = $_POST['password'];

        session_start();
    }
}

?>

<html>

<head>
    <title>INLOGFORM</title>
</head>

<body>
    <form action="index.php" method="POST" id="login">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <input type="submit" name="login" value="login"><br>
        <a href="lostpass.php">Wachtwoord vergeten?</a><br><br>
        <a href="signup.php">Klik hier om te registreren als u nog geen account heeft.</a>
    </form>
</body>

</html>