<?php

$db_name = "epigalerie";
$login_db = "epigalerie";
$pass_db = "xxxxxx";

try {
    $PDO = new PDO('mysql:host=localhost;dbname='.$db_name, $login_db, $pass_db);
} catch(Exception $e) {
    echo 'PDO Erreur : '.$e->getMessage().'<br />';
    echo 'N° : '.$e->getCode();
}