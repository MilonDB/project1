<?php

include "database.php";

// start session en zorgt dat loggedin true is.
session_start();

$db = new database('localhost', 'root', '', 'project1', 'utf8');

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !$db->admin_check($_SESSION['username'])) {
    echo "U heeft deze rechten niet!";
    header('refresh:3;index.php');
    exit;
}

$_SESSION['loggedin'] = true;
$username = $_SESSION['username'];


// Print de session_status. 0 = uit, 1 = none, en 2 = active
// print_r(session_status());


echo $username;



//data ophalen
$userData = $db->fetch_user_data_from_database(NULL);


//data splitten
$columns = array_keys($userData[0]);

// Als account_id en persoon_id geset zijn, worden variableen aangemaakt en met die variabelen wordt bepaald welke rij uit de tabel verwijderd moet worden
if (isset($_GET['account_id']) && isset($_GET['persoon_id'])) {
    $account_id = $_GET['account_id'];
    echo $account_id . "<br>";
    $persoon_id = $_GET['persoon_id'];
    echo $persoon_id . "<br>";
    $deleted = $db->delete_user_from_database($persoon_id, $account_id);

    // redirect terug naar de tabel
    header("refresh:3;view_delete_exportuser.php");
    echo "User verwijderd";
    exit;
}

//Exporteren naar excel bstand
if (isset($_POST['export'])) {
    $filename = "Export_gebruikerschema.xls";
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    $print_header = false;

    //kijkt of $result gevuld wordt met data, en vult de rows van de file met strings door imploden.
    $result = $db->fetch_user_data_from_database(NULL);
    if (!empty($result)) {
        foreach ($result as $row) {
            if (!$print_header) {
                echo implode("\t", array_keys($row)) . "\n";
                $print_header = true;
            }
            echo implode("\t", array_values($row)) . "\n";
        }
    }
    exit;
}





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
                    <a class="nav-link" href="welcome_admin.php">HOME</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="add_user.php">ADD USER</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="view_delete_export_user.php">VIEW/DELETE/EXPORT USERDATA</a><span class="sr-only">(current)</span></a>
                </li>
                </li>
                <li class="nav-item">
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>


    <table class="table">
        <thead>
            <tr>
                <?php
                // vult kolomnamen in
                foreach ($columns as $column) {
                    echo "<th> $column </th>";
                }
                ?>
            </tr>
        </thead>
        <tbody>

            <!-- Vult rijen in met de data -->
            <?php foreach ($userData as $rows => $row) { ?>
                <?php $row_id = $row['id']; ?>

                <tr>
                    <?php foreach ($row as $user_data) { ?>
                        <td> <?php echo $user_data ?></td>
                    <?php } ?>


                    <td> <a href="view_delete_export_user.php?account_id=<?php echo $row_id; ?>&persoon_id=<?php echo $row['persoon_id'] ?>" class="del_btn">Delete</a> </td>


                </tr>
            <?php } ?>



        </tbody>
    </table>

    <form action='view_delete_export_user.php' method='POST'>
        <input type='submit' name='export' value='Export to excel file' />
    </form>




</body>


</html>