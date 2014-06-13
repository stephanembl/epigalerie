<div id="content">
<?php
	if (isset($_SESSION['login']) && ($login->checkRights($_SESSION['login'], 42) || $login->checkRights($_SESSION['login'], 21)))
	{
		$auth_admin = array("home", "rights", "valid");
		if (isset($_GET['act']) && in_array($_GET['act'], $auth_admin))
		{
			if ($_GET['act'] == 'rights' && !$login->checkRights($_SESSION['login'], 42))
			{
				$act = 'home';
			} else {
				$act = $_GET['act'];
			}
		} else {
			$act = 'home';
		}
		?>
		<h1>ADMIN</h1>
		<p class="menu">
			<a href="?pg=adm42&act=home" <?php echo ($act == 'home' ? 'style="font-weight: bold;"' : ''); ?>>Index</a> - <?php if ($login->checkRights($_SESSION['login'], 42)) { ?><a href="?pg=adm42&act=rights" <?php echo ($act == 'rights' ? 'style="font-weight: bold;"' : ''); ?>>DROITS</a> - <?php } ?><a href="?pg=adm42&act=valid"  <?php echo ($act == 'valid' ? 'style="font-weight: bold;"' : ''); ?>>Valider</a> - <a href="?pg=home">Retour</a>
		</p>
		<?php
		include ('adm/'.$act.'.php');
	} else {
		header("location: ./");
	}
?>
</div>