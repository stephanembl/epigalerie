<?php
session_start();
if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && isset($_SESSION['login']) && isset($_POST['login'],$_POST['cat'],$_POST['promo'],$_POST['type']) && !empty($_POST['login']) && !empty($_POST['cat']) && !empty($_POST['promo']) && !empty($_POST['type']))
{
	try {
		$PDO = new PDO('mysql:host=localhost;dbname=epigalerie', 'epigalerie', 'YxWTYWdeZ5HXfuTV');
	} catch(Exception $e) {
		echo 'PDO Erreur : '.$e->getMessage().'<br />';
		echo 'N° : '.$e->getCode();
	}
	require_once('../classes/Votes.php');	
	$votes = new Votes($PDO);
    $login = $_POST['login'];
    $cat = $_POST['cat'];
    $promo = $_POST['promo'];
	if ($_SESSION['login'] != $login)
	{
		if ($_POST['type'] == "gold")
		{
			$rank = 5;
		} else if ($_POST['type'] == "silver") {
			$rank = 3;
		} else if ($_POST['type'] == "bronze") {
			$rank = 1;
		} else {
			$rank = 0;
		}
		$ret = $votes->votePic($login, $cat, $_SESSION['login'], $rank, $promo);
		if ($ret > 0){
			$response = 'ok';
			$error = '';
			$pts = $votes->getVotes($login, $cat);
		} else {
			$response = 'error';
			$error = 'Erreur lors du vote';
			$pts = 0;
		}
	} else {
		$response = 'error';
		$error = 'On ne vote pas pour soi-même !';
		$pts = 0;
	}
} else {
	$response = 'error';
	$error = 'Erreur... Bizarre.';
	$pts = 0;
}
$array['response'] = $response;
$array['error'] = $error;
$array['currentpts'] = $pts;
echo json_encode($array);
?>