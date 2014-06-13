<?php
session_start();
require_once('../classes/Login.php');
try {
	$PDO = new PDO('mysql:host=localhost;dbname=epigalerie', 'epigalerie', 'YxWTYWdeZ5HXfuTV');
} catch(Exception $e) {
	echo 'PDO Erreur : '.$e->getMessage().'<br />';
	echo 'N° : '.$e->getCode();
}	
$log = new Login($PDO);
if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && isset($_SESSION['login']) && ($log->checkRights($_SESSION['login'], 42) || $log->checkRights($_SESSION['login'], 21)) && isset($_POST['loginrights'],$_POST['droits']))
{
	$ret = $log->changeRights($_POST['loginrights'], $_POST['droits']);
	if ($ret > 0){
		$response = 'ok';
		$error = '';
	} else {
		$response = 'error';
		$error = 'Erreur lors de la modification des droits.';
	}
} else {
	$response = 'error';
	$error = 'Erreur... Bizarre.';
}
$array['response'] = $response;
$array['error'] = $error;
echo json_encode($array);
?>