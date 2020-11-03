<?php
include 'db.php';
include 'helper.php';


// TODO:session start 
session_start();
$msg = $msg_update = '';
// zorgt ervoor dat pagina niet via URL te vinden is. 
if (isset($_SESSION['username']) && $_SESSION['username'] == true) {

    $_SESSION['loggedin'] = true;
} else {
    echo 'U bent niet ingelogd.';

    header("refresh:3;url=index.php");
    exit;
}

$db = new Database('localhost', 'project1', 'root', '',  'utf8');
$val = new fieldVal();

// form is om een user toe te voegen, default staat op false, pas als er op edit geklikt wordt wordt dit deel uitgevoerd.
$user_update_true = false;
$input_name = 'addUser';
//$voornaam = $achternaam = $tussenvoegsel = $password = $msg = $msg_update = "";

$method = count($_GET) > 0 ? $_GET : $_POST; // bij het laden hebben we een get array (met persoon_id/account_id). onclick hebben we een gevulde $_POST, waardoor post array gevuld is
// print_r($method['account_id']);

if (isset($method['account_id']) && isset($method['persoon_id'])) {


    $user_update_true = true; // todo: cehck if needed
    // account
    // account_id meegeven met edit button (edit_user.php)
    if ($method == $_GET) {
        $account_id = $method['account_id'];
        print_r($account_id);
        // returned een assoc array
        $account_details = $db->get_account_details($account_id);
        $username = $account_details['username'];
        $email = $account_details['email'];
        //persoon
        // persoon_id meegeven met edit button (edit_user.php)
        $persoon_id = $method['persoon_id'];
        // returned een assoc array
        $persoon_details = $db->get_persoon_details($persoon_id);
        $voornaam = $persoon_details['voornaam'];
        $tussenvoegsel = $persoon_details['tussenvoegsel'];
        $achternaam = $persoon_details['achternaam'];
        // print_r($persoon_details);
    }
}

// echo $user_update_true;
// print_r($_SERVER['REQUEST_METHOD']);


if ($user_update_true) {
    // if (array_key_exists('persoon_id', $_POST) && array_key_exists('account_id', $_POST) ) {
    $input_name = 'update';
    // $user_update_true = true;
    // echo 'test';
}

// print_r($_POST);
echo "input_name is $input_name";
print_r($method);
if ($_SERVER["REQUEST_METHOD"] == "POST" &&  count($_POST) > 0) {
    echo 'check';
    // sleep(3);
    print_r($_POST);
    // komt hier niet
    $fieldnames = ['type', 'username', 'email', 'password', 'voornaam', 'achternaam']; // password is optional

    $fields_are_validated = $val->filled_fields($fieldnames);

    // functie field_validator returnt een true of false
    // if true --> sla user value op in $_POST variabelen
    if ($fields_are_validated) {

        // admin/user (1/2)
        $type = $_POST['type'];

        //inputs trimmen naar lowercase
        $username = trim(strtolower($_POST['username']));
        $email = trim(strtolower($_POST['email']));
        $password = trim(strtolower($_POST['password']));

        $voornaam = trim(strtolower($_POST['voornaam']));
        $tussenvoegsel = isset($_POST['tussenvoegsel']) ? trim(strtolower($_POST['tussenvoegsel'])) : NULL;
        $achternaam = trim(strtolower($_POST['achternaam']));

        echo "input in if $input_name";
        if ($input_name == 'update') {
            echo 'doei';
            // deze twee arrays geven we mee aan alterUser function
            $account = [
                'account_id' => $_POST['account_id'],
                'username' => $_POST['username'],
                'email' => $_POST['email'],
                'type' => $_POST['type']
            ];
            // print_r($account);
            $persoon = [
                'persoon_id' => $_POST['persoon_id'],
                'voornaam' => $_POST['voornaam'],
                'tussenvoegsel' => $_POST['tussenvoegsel'],
                'achternaam' => $_POST['achternaam'],
            ];
            // print_r($persoon);
            echo 'whooo';

            $msg_update = $db->alterUser($account, $persoon);

            // header("refresh:6;url=edit_user.php");
            // exit;

        } else {
            echo 'hallo';
            $msg = $db->register($username, $voornaam, $tussenvoegsel, $achternaam, $email, $type, $password);
            echo $msg;
        }
    }
}


// password in html: value -> add user = hello world. update user = get from db, load in input (readonly)
?>



<html>

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <title>User data</title>
</head>

<body>
    <header>

    </header>
    <div align="center">
        <h3>Admin panel</h3>
        <p>Ingelogd als: <span style="font-weight:bold;"><?= $_SESSION["username"] ?></span></p>
        <a class="btn btn-secondary" href="welcome_admin.php">Home</a> |
        <a class="btn btn-secondary" href="adduser_seperate.php">Add/Edit user</a> |
        <a class="btn btn-secondary" href="edit_user.php">View, edit or delete user</a> |
        <a class="btn btn-danger" href="logout.php">Logout</a>
    </div>

    <form align="center" action="add_user.php" method="post" style="margin-top:30px;">
        <input type="hidden" name="account_id" value="<?php echo isset($_GET['account_id']) ? $_GET['account_id'] : ''; ?>">
        <input type="hidden" name="persoon_id" value="<?php echo isset($_GET['persoon_id']) ? $_GET['persoon_id'] : ''; ?>">

        <div class="form-group">
            <h3> Account gegevens </h3>
            <div class="dropdown">
                <label for="type">User type:</label><br>
                <select name='type' id='type'>
                    <option value=2>User</option>
                    <option value=1>Admin</option>
                </select>
            </div><br>
            <label for="email">E-mail:</label><br>
            <input required type="email" name="email" id="email" value="<?php if (isset($_POST["email"])) {
                                                                            echo htmlentities($_POST["email"]);
                                                                        } elseif ($user_update_true) {
                                                                            echo $email;
                                                                        } else {
                                                                            echo '';
                                                                        }; ?>"><span class="text-danger"></span><br>
            <label for="username">Username:</label><br>
            <input required pattern="[a-z]{1,15}+@" type="text" name="username" id="username" value="<?php if (isset($_POST["username"])) {
                                                                                                            echo htmlentities($_POST["username"]);
                                                                                                        } elseif ($user_update_true) {
                                                                                                            echo $username;
                                                                                                        } else {
                                                                                                            echo '';
                                                                                                        }; ?>"><span class="text-danger"></span><br>
            <label for="password" <?php if ($user_update_true) { ?> readonly <?php } ?>>Password:</label><br>
            <input required <?php if ($user_update_true) { ?> readonly <?php } ?> minlength="10" maxlength="20" type="password" name="password" id="password" value="helloworld"><span class="text-danger"></span><br>
        </div>
        <div class="form-group">
            <h3>Persoonsgegevens</h3>
            <label for="voornaam">Voornaam:</label><br>
            <input required type="text" name="voornaam" id="voornaam" value="<?php if (isset($_POST["voornaam"])) {
                                                                                    echo htmlentities($_POST["voornaam"]);
                                                                                } elseif ($user_update_true) {
                                                                                    echo $voornaam;
                                                                                } else {
                                                                                    echo '';
                                                                                }; ?>"><span class="text-danger"></span><br>
            <label for="tussenvoegsel">Tussenvoegsel:</label><br>
            <input type="text" name="tussenvoegsel" id="tussenvoegsel" value="<?php if (isset($_POST["tussenvoegsel"])) {
                                                                                    echo htmlentities($_POST["tussenvoegsel"]);
                                                                                } elseif ($user_update_true) {
                                                                                    echo $tussenvoegsel;
                                                                                } else {
                                                                                    echo '';
                                                                                }; ?>"><br>
            <label for="achternaam">Achternaam:</label><br>
            <input required type="text" name="achternaam" id="achternaam" value="<?php if (isset($_POST["achternaam"])) {
                                                                                        echo htmlentities($_POST["achternaam"]);
                                                                                    } elseif ($user_update_true) {
                                                                                        echo $achternaam;
                                                                                    } else {
                                                                                        echo '';
                                                                                    }; ?>"><span class="text-danger"></span><br>
        </div>
        <br><span class="text-success"><?php if ($msg) echo $msg; ?></span>
        <br><span class="text-success"><?php if ($msg_update) echo $msg_update; ?></span>


        <?php if ($user_update_true) { ?>

            <button class="btn" type="submit" name="update" value="update">Update</button>
        <?php } else { ?>
            <button class="btn" type="submit" name="addUser" value="addUser">Add</button>
        <?php } ?>
        <!-- <button class="btn btn-success" type="submit" name="<?php // if($user_update_true){echo 'update';}else{echo 'addUser';}
                                                                    ?>" value="<?php // if($user_update_true){echo 'Update';}else{echo 'Add user';}
                                                                                ?>"><?php // if($user_update_true){echo 'update';}else{echo 'Add user';}
                                                                                                                                                    ?></button> -->
    </form>
</body>

</html>