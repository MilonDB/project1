<?php

include "database.php";
// start session en zorgt dat loggedin true is.
session_start();

$db = new database('localhost', 'root', '', 'project1', 'utf8');


// Kijkt of je loggedin bent en anders direct terug naar login om te voorkomen dat data zichtbaar wordt
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !$db->admin_check($_SESSION['username'])) {
    echo "U heeft deze rechten niet!";
    header('refresh:3;index.php');
    exit;
}


$_SESSION['loggedin'] = true;
$username = $_SESSION['username'];

// Print de session_status. 0 = uit, 1 = none, en 2 = active
// print_r(session_status());


?>

<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

</head>




<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Navbar</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="welcome_admin.php">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="add_user.php">ADD USER</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="view_delete_export_user.php">VIEW/DELETE/EXPORT USER DATA</a>
                </li>
                <li class="nav-item">
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>



</body>


</html>