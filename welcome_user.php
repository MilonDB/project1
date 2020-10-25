<?php
// start session en zorgt dat loggedin true is.
session_start();
$_SESSION['loggedin'] = true;
$username = $_SESSION['username'];

// Print de session_status. 0 = uit, 1 = none, en 2 = active
print_r(session_status());




?>

<html>

<body>
    <p>Welkom <?= $username ?></p>

    <!-- Redirect naar logout.php om daar de sessie te eindigen en te unsetten. -->
    <a href="logout.php">Logout</a>
</body>

</html>