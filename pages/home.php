<div id="content">
	<p style="text-align: center; margin: 0; padding: 0;">
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
		?>
		<select name="promo" onchange="window.location.href='?promo=' + this[this.selectedIndex].value + '&ville=<?php echo $ville; ?>&cat=<?php echo $cat; ?>'">
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
		<select name="ville" onchange="window.location.href='?promo=<?php echo $promo; ?>&ville=' + this[this.selectedIndex].value + '&cat=<?php echo $cat; ?>'">
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
		<select name="cat" onchange="window.location.href='?promo=<?php echo $promo; ?>&ville=<?php echo $ville; ?>&cat=' + this[this.selectedIndex].value">
			<option value="all">Toutes les catégories</option>
			<option value="fdf" <?php echo ($cat == "fdf") ? 'selected="selected"' : '' ?>>Fil de fer</option>
			<option value="wolf3d" <?php echo ($cat == "wolf3d") ? 'selected="selected"' : '' ?>>Wolf3D</option>
			<option value="rt" <?php echo ($cat == "rt") ? 'selected="selected"' : '' ?>>Raytracer</option>
		</select>
	</p>
</div>
<div id="pics">
	<?php
		$votes = new Votes($PDO);
		$votes->getPics($cat, $promo, $ville);
	?>
</div>