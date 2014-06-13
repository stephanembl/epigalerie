<?php

/**
 * Description of Image
 *
 * @author stef
 */
 
class Image {
	
	private $PDO;
	private $login;
	
	public function __construct($PDO, $login){
		$this->PDO=$PDO;
		$this->login=$login;
	}

    public function uploadImage($cat, $myFile, $login){
		define("UPLOAD_DIR", "snaps/");
		if ($myFile['size'] > 716800) {
			?>
			<p>Erreur. Taille maximale autorisée : 700 kB.</p>
			<?php
		} else {
			if ($myFile['error'] !== UPLOAD_ERR_OK) {
				?>
				<p>Erreur lors de l'envoi.</p>
				<?php
			} else {
				$imageData = @getimagesize($myFile['tmp_name']);
				$fileType = exif_imagetype($myFile['tmp_name']);
				if ($imageData === FALSE || !($imageData[2] == IMAGETYPE_JPEG || $imageData[2] == IMAGETYPE_PNG)){
					?>
					<p>Erreur. Formats acceptés : JPG, PNG.</p>
					<?php
				} else {
					$name = preg_replace("/[^A-Z0-9._-]/i", "_", $myFile["name"]);
					$i = 0;
					$ext = pathinfo($name, PATHINFO_EXTENSION);
					$name = $login . "-" . $cat . "." . $ext;
					while (file_exists(UPLOAD_DIR . $name)) {
						$i++;
						$name = $login . "-" . $cat . "-" . $i . "." . $ext;
					}
					$success = move_uploaded_file($myFile["tmp_name"], UPLOAD_DIR . $name);
					if (!$success)
					{
						?>
						<p>Erreur saving.</p>
						<?php
					} else {
						chmod(UPLOAD_DIR . $name, 0644);
						$res = $this->PDO->prepare("INSERT INTO pics (pics_login, pics_promo, pics_ville, pics_valid, pics_src, pics_cat) VALUES (?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE pics_src=?, pics_valid=?");
						$success = $res->execute(array($login,$this->login->getPromo($login),$this->login->getCity($login),0,$name,$cat,$name,0));
						if ($res->rowCount() > 0)
						{
							?>
							<p>Image envoyée. Elle est désormais soumise à validation.</p>
							<?php
						} else {
							?>
							<p>Erreur BDD.</p>
							<?php
						}
					}
				}
			}
		}
	}
	
	public function validImg($login, $cat, $valid) {
		$query = $this->PDO->prepare('UPDATE pics SET pics_valid = ? WHERE pics_login = ? AND pics_cat = ?');
		$query->execute(array($valid, $login, $cat));
		return ($query->rowCount());
	}
	
	public function getPicsValid($promo, $ville, $cat, $type) {
		$query = $this->PDO->prepare('SELECT *
									FROM pics
									LEFT JOIN categories ON pics.pics_cat = categories.cat_id
									ORDER BY pics_valid ASC, pics_login ASC');
		$query->execute();
		$i = 0;
		$cats = array('fdf' => 'Fil de fer', 'wolf3d' => 'Wolf3D', 'rt' => 'Raytracer');
		?>
		<script type="text/javascript">
			 $(document).ready(function(){
				$("a.valid").click(function(event){
					var thepic = $(this).attr("title");
					if ($(this).hasClass("redc")){
						var levalid = 1;
					} else {
						var levalid = 0;
					}
					$.ajax({
						url: "req/validImg.php",
						type: "POST",
						data: { pic: thepic, valid: levalid },
						dataType: 'json',
						context: this,
						success: function(json) {
							if (json.response == 'ok')
							{
								if ($(this).hasClass("redc")){
									$(this).removeClass("redc");
									$(this).addClass("greenc");
									$(this).children().text('validée');
								} else {
									$(this).removeClass("greenc");
									$(this).addClass("redc");
									$(this).children().text('non validée');
								}
							}
						}
					});
					event.stopPropagation();
				});

                 $("button").on('click', function(){
                     $("a.redc").each(function(){
                         var thepic = $(this).attr("title");
                         $.ajax({
                             url: "req/validImg.php",
                             type: "POST",
                             data: { pic: thepic, valid: 1 },
                             dataType: 'json',
                             context: this,
                             success: function(json) {
                                 if (json.response == 'ok')
                                 {
                                     $(this).removeClass("redc");
                                     $(this).addClass("greenc");
                                     $(this).children().text('validée');
                                 }
                             }
                         });
                     });
                 });
			});
		</script>
        <p style="margin: 10px; margin-bottom: 15px;"><button style="padding: 5px;">TOUT VALIDER</button></p>
		<?php
		while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
			if ($promo == "all" || $row['pics_promo'] == $promo)
			{
				if ($ville == "all" || $row['pics_ville'] == $ville)
				{
					if ($cat == "all" || $row['cat_name'] == $cat)
					{
						if ($type === "all" || $row['pics_valid'] == $type)
						{
							if (!file_exists("snaps/".$row['pics_src'].""))
							{
								$row['pics_src'] = "broken.jpg";
							}
							?>
							<div class="pic">
								<div class="nailthumb-container">
									<a class="fancybox" href="snaps/<?php echo $row['pics_src']; ?>" data-fancybox-group="gallery" title="<?php echo $row['pics_login']; ?>"><img src="snaps/<?php echo $row['pics_src']; ?>" /></a>
								</div>
								<div class="infos">
									<span class="author"><?php echo $row['pics_login']; ?></span>
									<?php
										if ($row['pics_valid'] == 1){
											?>
											<a href="#" class="greenc valid" title="<?php echo $row['pics_login'].'%'.$row['pics_cat']; ?>"><span class="votes">validée</span></a>
											<?php
										} else {
											?>
											<a href="#" class="redc valid" title="<?php echo $row['pics_login'].'%'.$row['pics_cat']; ?>"><span class="votes">non validée</span></a>
											<?php
										}
									?>
								</div>
								<div class="infos">
									<span class="author promo"><?php echo $row['pics_ville']; ?> - <?php echo $row['pics_promo']; ?></span>
									<span class="votes cat"><?php echo $cats[$row['cat_name']]; ?></span>
								</div>
							</div>
							<?php
							++$i;
						}
					}
				}
			}
		}
		if ($i == 0)
		{
			?>
			<div id="content">
				Aucune image ne correspond à ces critères.
			</div>
			<?php
		}
	}
}
?>
