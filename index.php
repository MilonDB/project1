<?php




?>

<html>

    <head>
        <title>INLOGFORM</title>
    </head>
    <body>
        <form action="index.php" method="POST" id="login">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <input type="submit" value="login"><br>
            <a href="lostpass.php">Wachtwoord vergeten?</a><br><br>
            <a href="signup.php">Klik hier om te registreren als u nog geen account heeft.</a>
        </form>
    </body>

</html>