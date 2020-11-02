<?php


// INDEX LOGIN PAGE
include "database.php";

// Kijkt of session active is
if (session_status() == PHP_SESSION_ACTIVE) {
    echo 'Session is active';
} else {
    echo "session is niet active" . "<br>";
}

//Als login button wordt ingedrukt, wordt array $loginvelden aangemaakt, en de $error variabele op false gezet.
if (isset($_POST['login'])) {
    $loginvelden = ['username', 'password'];

    $error = false;

    // loopt door array $loginvelden heen, als er eentje leeg is, dan moet je het opnieuw invullen.
    foreach ($loginvelden as $loginveld) {
        if (!isset($_POST[$loginveld]) || empty($_POST[$loginveld])) {
            $error = true;
            echo "Error gevonden, velden zijn niet correct ingevuld!" . "<BR>" . "<BR>";
            echo '<a href="index.php">Probeer nog een keer</a>';
            return;
        }
    }


    $username = $_POST['username'];
    $password = $_POST['password'];


    $db = new database('localhost', 'root', '', 'project1', 'utf8');

    //roept login funcite aan wanneer alles gechekt is
    $db->user_confirmation($username, $password);
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