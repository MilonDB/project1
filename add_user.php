<?php

//include fieldval en database classes
include "fieldval.php";
include "database.php";


//start de session
session_start();

$db = new database('localhost', 'root', '', 'project1', 'utf8');

//Als loggedin is NIET true, direct terug naar login om te voorkomen dat gevoelige data zichtbaar wordt
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !$db->admin_check($_SESSION['username'])) {
    echo "U heeft deze rechten niet!";
    header('refresh:3;index.php');
    exit;
}


//Zet loggedin op true na de check
$_SESSION['loggedin'] = true;
$username = $_SESSION['username'];

echo "Dag " . $username . ". Hier kun je een gebruiker toevoegen aan de database.      ";

//Na de button press wordt de array aangemaakt met de fieldnames
if (isset($_POST['add'])) {
    $fields = ['username', 'password', 'repassword', 'voornaam', 'achternaam', 'email'];


    //Roept de fieldValidation op.
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

            // Maak database connectie aan en voer data in de tabellen.
            $db = new database('localhost', 'root', '', 'project1', 'utf8');
            $aanmaken = $db->register($username, $db::USER, $voornaam, $tussenvoegsel, $achternaam, $email, $password);

            echo "User added to database!";
            header('refresh:4;url=add_user.php');
        } else {
            // Als er iets niet klopt, wordt error op true gezet, dat betekend dat de wachtwoorden niet overeen komen.
            $error = true;
            echo 'wachtwoorden komen niet overeen! Probeer opnieuw!';
        }
    }
}



?>


<html>

<head>
    <title>Register</title>
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
                <li class="nav-item active">
                    <a class="nav-link" href="add_user.php">ADD USER</a><span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="view_delete_export_user.php">VIEW/DELETE/EXPORT USERDATA</a>
                </li>
                </li>
                <li class="nav-item">
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>





    <form method="post" action="add_user.php" id="register">
        <input type="text" name="voornaam" id="voornaam" value="<?php echo isset($_POST['voornaam']) ?>" placeholder="voornaam"><br>
        <input type="text" name="tussenvoegsel" id="tussenvoegsel" value="<?php echo isset($_POST['tussenvoegsel']) ? htmlentities($_POST['tussenvoegsel']) : ''; ?>" placeholder="tussenvoegsel (optioneel)"><br>
        <input type="text" name="achternaam" id="achternaam" placeholder="achternaam" value="<?php echo isset($_POST['achternaam']) ? htmlentities($_POST['achternaam']) : ''; ?>"><br>
        <input type="email" name="email" id="email" placeholder="email" value="<?php echo isset($_POST['email']) ? htmlentities($_POST['email']) : ''; ?>" required><br>
        <input type="text" name="username" id="username" placeholder="username" value="<?php echo isset($_POST['username']) ? htmlentities($_POST['username']) : ''; ?>" required><br>
        <input type="password" name="password" id="password" placeholder="password" value="<?php echo isset($_POST['password']) ? htmlentities($_POST['password']) : ''; ?>" required><br>
        <input type="password" name="repassword" id="repassword" placeholder="repeat password" required><br>
        <input type="submit" name='add' value="add to database"><br>
    </form>

</body>

</html>