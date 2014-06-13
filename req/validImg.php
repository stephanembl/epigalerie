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
if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && isset($_SESSION['login']) && ($log->checkRights($_SESSION['login'], 42) || $log->checkRights($_SESSION['login'], 21)) && isset($_POST['pic'],$_POST['valid']) && !empty($_POST['pic']) && $_POST['valid'] >= 0 && $_POST['valid'] <= 1)
{
	require_once('../classes/Image.php');	
	$image = new Image($PDO, $log);
	$parts = explode('%', $_POST['pic']);
	$ret = $image->validImg($parts[0], $parts[1], $_POST['valid']);
	if ($ret > 0){
		$response = 'ok';
		$error = '';
	} else {
		$response = 'error';
		$error = 'Erreur lors de la validation';
	}
} else {
	$response = 'error';
	$error = 'Erreur... Bizarre.';
}
$array['response'] = $response;
$array['error'] = $error;
echo json_encode($array);
?>