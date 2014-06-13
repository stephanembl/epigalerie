<p>
	<?php
		for ($i = 2005; $i <= (date('Y') + 5); $i++)
		{
			$promo_array[] = $i;
		}
		
		if (isset($_GET['promo']) && in_array($_GET['promo'], $promo_array))
		{
			$promo = $_GET['promo'];
		} else {
			$promo = "all";
		}
		
		$villes_array = array("Bordeaux", "Lille", "Lyon", "Marseille", "Montpellier", "Nancy", "Nantes", "Nice", "Paris", "Rennes", "Strasbourg", "Toulouse");
		if (isset($_GET['ville']) && in_array($_GET['ville'], $villes_array))
		{
			$ville = $_GET['ville'];
		} else {
			$ville = "all";
		}
		
		$cats = array("fdf", "wolf3d", "rt");
		if (isset($_GET['cat']) && in_array($_GET['cat'], $cats))
		{
			$cat = $_GET['cat'];
		} else {
			$cat = "all";
		}
		
		$types = array(0, 1, "all");
		if (isset($_GET['type']) && in_array($_GET['type'], $types))
		{
			$type = $_GET['type'];
		} else {
			$type = 0;
		}
	?>
	<select name="promo" onchange="window.location.href='?pg=adm42&act=valid&promo=' + this[this.selectedIndex].value + '&ville=<?php echo $ville; ?>&cat=<?php echo $cat; ?>&type=<?php echo $type; ?>'">
		<option value="all">Toutes les promos</option>
		<?php
		for ($i = 2005; $i <= (date('Y') + 5); $i++)
		{
			?>
			<option value="<?php echo $i; ?>" <?php echo ($promo == $i) ? 'selected="selected"' : '' ?>><?php echo $i; ?></option>
			<?php
		}
		?>
	</select>
	<select name="ville" onchange="window.location.href='?pg=adm42&act=valid&promo=<?php echo $promo; ?>&ville=' + this[this.selectedIndex].value + '&cat=<?php echo $cat; ?>&type=<?php echo $type; ?>'">
		<option value="all">Toutes les villes</option>
		<?php
			foreach ($villes_array as $t)
			{
				?>
				<option value="<?php echo $t; ?>" <?php echo ($ville == $t) ? 'selected="selected"' : '' ?>><?php echo $t; ?></option>
				<?php
			}
		?>
	</select>
	<select name="cat" onchange="window.location.href='?pg=adm42&act=valid&promo=<?php echo $promo; ?>&ville=<?php echo $ville; ?>&cat=' + this[this.selectedIndex].value + '&type=<?php echo $type; ?>'">
		<option value="all">Toutes les catégories</option>
		<option value="fdf" <?php echo ($cat == "fdf") ? 'selected="selected"' : '' ?>>Fil de fer</option>
		<option value="wolf3d" <?php echo ($cat == "wolf3d") ? 'selected="selected"' : '' ?>>Wolf3D</option>
		<option value="rt" <?php echo ($cat == "rt") ? 'selected="selected"' : '' ?>>Raytracer</option>
	</select>
	<select name="type" onchange="window.location.href='?pg=adm42&act=valid&promo=<?php echo $promo; ?>&ville=<?php echo $ville; ?>&cat=<?php echo $cat; ?>&type=' + this[this.selectedIndex].value">
		<option value="0">Non validées</option>
		<option value="1" <?php echo ($type == 1) ? 'selected="selected"' : '' ?>>Validées</option>
		<option value="all" <?php echo ($type === "all") ? 'selected="selected"' : '' ?>>Toutes les images</option>
	</select>
</p>
</div>
<div id="pics">
	<?php
		$img = new Image($PDO, $login);
		$img->getPicsValid($promo, $ville, $cat, $type);
	?>