<?php 

session_start(); 

$cats = array("fdf", "wolf3d", "rt");
if (isset($_GET['cat']) && in_array($_GET['cat'], $cats))
{
	$cat = $_GET['cat'];
} else {
	$cat = "all";
}
			
?>
<!DOCTYPE HTML>
<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>{EpiGalerie}</title>
		<link rel="icon" href="favicon.ico" type="image/x-icon" />
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
		<script type="text/javascript" src="vegas/jquery.vegas.min.js"></script>
		<script type="text/javascript" src="js/jquery.filter_input.js"></script>
		<script type="text/javascript" src="js/jquery.nailthumb.1.1.min.js"></script>
		<script type="text/javascript" src="js/jquery.fancybox.js?v=2.1.5"></script>
		<link rel="stylesheet" type="text/css" href="js/jquery.fancybox.css?v=2.1.5" media="screen" />
		<link rel="stylesheet" type="text/css" href="vegas/jquery.vegas.css" />
		<link rel="stylesheet" type="text/css" href="css/style.css" />
		<script type="text/javascript">
			$(document).ready(function() {
				$.vegas('slideshow', {
					backgrounds:[
						{ src:'images/bg.jpg', fade: 1000 },
						{ src:'images/fdf.jpg', fade:1000 },
						{ src:'images/wolf.png', fade:1000 },
						{ src:'images/rt.png', fade:1000 }
					]
				})('overlay', {
					src:'overlays/02.png'
				})('pause');
				
				var cat = "<?php echo $cat; ?>";
				var lejump = 0;
				if (cat == "fdf") {
					lejump = 1;
				} else if (cat == "wolf3d") {
					lejump = 2;
				} else if (cat == "rt") {
					lejump = 3;
				}
				
				$("h2.fdf").hover(function(){
					$.vegas('jump', 1);
				}, function(){
					$.vegas('jump', lejump);
				});
				
				$("h2.wolf").hover(function(){
					$.vegas('jump', 2);
				}, function(){
					$.vegas('jump', lejump);
				});
				
				$("h2.rt").hover(function(){
					$.vegas('jump', 3);
				}, function(){
					$.vegas('jump', lejump);
				});
				
				$.vegas('jump', lejump);
				
				$('input[name=login]').filter_input({regex:'[a-z0-9_-]'});
				$('input[name=loginrights]').filter_input({regex:'[a-z0-9_-]'});
				$('input[name=droits]').filter_input({regex:'[0-9]'});
				$('#changeRights').on('submit', function() {
					var login = $('input[name=loginrights]').val();
					var droits = $('input[name=droits]').val();
					
					if(login == '' || droits == '') {
						return false;
					} else {
						$.ajax({
							url: $(this).attr('action'),
							type: $(this).attr('method'),
							data: $(this).serialize(),
							dataType: 'json',
							success: function(json) {
								if (json.response == 'ok')
								{
									$(".success").html("Les droits ont été modifiés.");
								}
								else if (json.response == 'error')
								{
									$(".success").html(json.error);
								}
								$(".success").fadeIn(600);
							}
						});
					}
					return false;
				});
				
				$('.pic').css('display','none');
                $('.nailthumb-container').nailthumb({
                    width:230,height:150,onFinish:function(){
                        $('.pic').fadeIn();
                    }
                });

                $('.fancybox').fancybox();
			});
		</script>
	</head>
	<body>
		<?php
			require_once('classes/Login.php');
			require_once('classes/Image.php');
			require_once('classes/Votes.php');
			require_once('req/PDO.php');

			$login = new Login($PDO);
		
			$error = "";
			if (isset($_POST['check']) && !empty($_POST['login']) && !empty($_POST['password'])){
				$result = $login->check_login($_POST['login'], $_POST['password']);
				$page = $result['content'];
				$arr = explode("// Epitech JSON webservice ...\n", $page, 2);
				$lecontent = substr($arr[1], 0, strpos($arr[1], '}')+1);
				$json = json_decode($lecontent, true);
				if (!isset($json['message']) OR ($json['message'] != "Login or password does not match." AND $json['message'] != "Veuillez vous connecter")){
					$_SESSION['logstate'] = true;
					$_SESSION['login'] = $_POST['login'];
				} else if ($json['message'] == "Login or password does not match.") {
					$error = "login incorrect";
				}
			}
		?>
		<div id="colleft">
			<div id="pres">
				<h1 onclick="window.location.href='./'">{EpiGalerie}</h1>
				<p>Visualisez les réalisations des étudiants d'Epitech et votez pour vos préférées dans chaque catégorie</p>
			</div>
			<div id="login">
				<?php
					if (!isset($_SESSION['logstate']) OR !($_SESSION['logstate']))
					{
						?>
						<form method="post" action="">
							<input type="text" name="login" autocomplete=off placeholder="login" />
							<input type="password" name="password" autocomplete=off placeholder="password unix" />
							<input type="submit" name="check" value="CONNEXION" >
						</form>
						<?php
						if (!empty($error))
						{
							?>
							<span class="error"><?php echo $error; ?></span>
							<?php
						}
					} else {
						?>
						<p>Bonjour, <strong><?php echo $_SESSION['login']; ?></strong></p>
						<p><br /><a href="?pg=upload">Envoyer une image</a></p>
						<?php
						if ($login->checkRights($_SESSION['login'],42))
						{
							?>
							<p><a href="?pg=adm42">Admin</a></p>
							<?php
						}
						?>
						<p><a href="logout.php">Déconnexion</a></p>
						<?php
					}
				?>
			</div>
			<div id="categories">
				<h2 class="fdf" onclick="window.location.href='?promo=all&ville=all&cat=fdf'">Fil de fer</h2>
				<h2 class="wolf" onclick="window.location.href='?promo=all&ville=all&cat=wolf3d'">Wolf3D</h2>
				<h2 class="rt" onclick="window.location.href='?promo=all&ville=all&cat=rt'">Raytracer</h2>
			</div>
			<div id="about">
				<p><a href="http://www.stephane-m.fr/" target="_blank">Stéphane Mombuleau</a><br />Epitech Marseille</p>
			</div>
		</div>
		<div id="colright">
			<?php
			$auth_pages = array("404", "home", "adm42", "upload");
			if (isset($_GET['pg']) && in_array($_GET['pg'], $auth_pages) && file_exists('pages/'.$_GET['pg'].'.php'))
			{
				$page = $_GET['pg'];
			}
			else
			{
				$page = 'home';
			}
			include ('pages/'.$page.'.php');
			?>
		</div>
		<div style="clear:both;"></div>
	</body>
</html>
