<?php

//  FILE MET TABEL ZODAT USER ALLEEN ZIJN EIGEN DATA KAN ZIEN, EN NIET VERWIJDEREN / EXPORTEN



include 'database.php';



session_start();

// zorgt ervoor dat loggedin true is
if (isset($_SESSION['username']) && $_SESSION['username'] == true) {
    $_SESSION['loggedin'] = true;
} else {
    header("Location: index.php");
    exit;
}

$db = new database('localhost', 'root', '', 'project1', 'utf8');

$username = $_SESSION['username'];
echo $username;

// returned een associative array
$resultset = $db->fetch_user_data_from_database($username)[0];

// resultaat splitten in key en value
$columns = array_keys($resultset);
$rows = array_values($resultset);

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
                <li class="nav-item">
                    <a class="nav-link" href="welcome_user.php">Home </a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="view_userdata.php">VIEW USER DATA</a><span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <table class="table">
        <thead>
            <tr>
                <?php // loopt door columns heen en plaatst ze als table head
                foreach ($columns as $column) {
                    echo "<th> $column </th>";
                }
                ?>
            </tr>
        </thead>
        <tbody>
            <tr>
                <?php // Loopt door data WHERE username = :username heen. en plaatst in tabel.
                foreach ($rows as $value) {
                    echo "<td> $value </td>";
                }
                ?>
            </tr>
        </tbody>
    </table>



</body>





</html>