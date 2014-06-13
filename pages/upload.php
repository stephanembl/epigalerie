<div id="content">
<?php
	if (isset($_SESSION['logstate']) AND ($_SESSION['logstate']))
	{
		?>
		<h1>Envoyer une image</h1>
		<?php
		if (isset($_POST['uploadfile']))
		{
			if (isset($_POST['categorie']) && !empty($_POST['categorie']) && !empty($_FILES['leimage']['tmp_name']))
			{
				if ($_POST['categorie'] >= 1 && $_POST['categorie'] <= 3)
				{
					$img = new Image($PDO, $login);
					$img->uploadImage($_POST['categorie'], $_FILES['leimage'], $_SESSION['login']);
				} else {
					?>
					<p>Erreur. Catégorie inexistante.</p>
					<?php
				}
			} else {
				?>
				<p>Erreur. Des champs sont vides.</p>
				<?php
			}
			?>
			<p><a href="?pg=upload">Retour</a></p>
			<?php
		} else {
		?>
		<p>N'envoyez que des captures d'écran de <u>vos</u> projets. Les soumissions sont sujettes à validation.</p>
		<p>Si vous envoyez une autre image pour le même projet, elle remplacera la précédente.</p>
		<p>Formats acceptés : JPG, PNG.</p>
		<p>Taille maximale autorisée : 700 kB.</p>
		<p>
			<form method="post" action="" id="upload" enctype="multipart/form-data">
				<select name="categorie">
					<option value="">Projet :</option>
					<option value="1">Fil de fer</option>
					<option value="2">Wolf3D</option>
					<option value="3">Raytracer</option>
				</select>
				<input type="file" name="leimage" placeholder="image" />
				<input type="submit" name="uploadfile" value="ENVOYER" />
			</form>
		</p>
		<?php
		}
	} else {
		header("location: ./");
	}
?>
</div>