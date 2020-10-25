<?php
session_start();
unset($_SESSION);
session_destroy();
session_write_close();
echo "U wordt nu naar de login page gebracht";
header("refresh:3;url=index.php");
die;
?>