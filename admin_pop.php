<?php  

include "database.php";


$db = new database('localhost', 'root', '', 'project1', 'utf8');

$populate = $db->register('admin',$db::ADMIN,'admin','de','admin','admin@mail.nl','admin');



?>