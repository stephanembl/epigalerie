<?php
session_start();
session_destroy();
$_SESSION['logstate'] = false;
$_SESSION['login'] = '';
header("location: ./");
?>