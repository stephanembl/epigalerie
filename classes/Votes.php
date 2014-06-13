<?php

/**
 * Description of Votes
 *
 * @author stef
 */
 
class Votes {
	
	private $PDO;
	
	public function __construct($PDO){
		$this->PDO=$PDO;
	}

	public function getPics($cat, $promo, $ville) {
		$query = $this->PDO->prepare('SELECT *, SUM(votes.vote_rank) AS votecount
									FROM pics
									LEFT JOIN categories ON pics.pics_cat = categories.cat_id
									LEFT JOIN votes ON pics.pics_login = votes.vote_pic_login AND pics.pics_cat = votes.vote_pic_cat
									WHERE pics_valid=1
									GROUP BY pics.pics_login, pics.pics_cat, votes.vote_pic_login, votes.vote_pic_cat
									ORDER BY votecount DESC');
		$query->execute();
		$i = 0;
		$cats = array('fdf' => 'Fil de fer', 'wolf3d' => 'Wolf3D', 'rt' => 'Raytracer');
		?>
		<script type="text/javascript">
			 $(document).ready(function(){
				$("img.goldvote").on("click", function(event){
                    var login = $(this).attr("data-pic-login");
                    var cat = $(this).attr("data-pic-cat");
                    var promo = $(this).attr("data-pic-promo");
					$.ajax({
						url: "req/votePic.php",
						type: "POST",
						data: { login: login, cat: cat, promo: promo, type: "gold" },
						dataType: 'json',
						context: this,
						success: function(json) {
							if (json.response == 'ok')
							{
								$("img.goldvote").each(function(){
                                    if ($(this).attr("data-pic-cat") == cat && $(this).attr("data-pic-promo") == promo){
										$(this).attr('src', 'images/gold_medal_no.png');
										$(this).removeClass("goldvote").addClass("novote").off("click");
									}
								});
								$(this).parent().fadeOut(300, function(){
									$(this).html('<img src="images/gold_medal.png" class="novote" />');
									$(this).fadeIn(300);
								});
								$(this).parents('div.pic').find('.pts').fadeOut(300, function(){
									$(this).html(json.currentpts + ' pts');
									$(this).fadeIn(300);
								});
							}
						}
					});
					event.stopPropagation();
				});
				
				$("img.silvervote").on("click", function(event){
                    var login = $(this).attr("data-pic-login");
                    var cat = $(this).attr("data-pic-cat");
                    var promo = $(this).attr("data-pic-promo");
					$.ajax({
						url: "req/votePic.php",
						type: "POST",
						data: { login: login, cat: cat, promo: promo, type: "silver" },
						dataType: 'json',
						context: this,
						success: function(json) {
							if (json.response == 'ok')
							{
								$("img.silvervote").each(function(){
									if ($(this).attr("data-pic-cat") == cat && $(this).attr("data-pic-promo") == promo){
										$(this).attr('src', 'images/silver_medal_no.png');
										$(this).removeClass("silvervote").addClass("novote").off("click");
									}
								});
								$(this).parent().fadeOut(300, function(){
									$(this).html('<img src="images/silver_medal.png" class="novote" />');
									$(this).fadeIn(300);
								});
								$(this).parents('div.pic').find('.pts').fadeOut(300, function(){
									$(this).html(json.currentpts + ' pts');
									$(this).fadeIn(300);
								});
							}
						}
					});
					event.stopPropagation();
				});
				
				$("img.bronzevote").on("click", function(event){
					var login = $(this).attr("data-pic-login");
                    var cat = $(this).attr("data-pic-cat");
                    var promo = $(this).attr("data-pic-promo");
					$.ajax({
						url: "req/votePic.php",
						type: "POST",
						data: { login: login, cat: cat, promo: promo, type: "bronze" },
						dataType: 'json',
						context: this,
						success: function(json) {
							if (json.response == 'ok')
							{
								$("img.bronzevote").each(function(){
                                    if ($(this).attr("data-pic-cat") == cat && $(this).attr("data-pic-promo") == promo){
										$(this).attr('src', 'images/bronze_medal_no.png');
										$(this).removeClass("bronzevote").addClass("novote").off("click");
									}
								});
								$(this).parent().fadeOut(300, function(){
									$(this).html('<img src="images/bronze_medal.png" class="novote" />');
									$(this).fadeIn(300);
								});
								$(this).parents('div.pic').find('.pts').fadeOut(300, function(){
									$(this).html(json.currentpts + ' pts');
									$(this).fadeIn(300);
								});
							}
						}
					});
					event.stopPropagation();
				});
			});
		</script>
		<?php
		while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
			if ($cat == "all" || $row['cat_name'] == $cat)
			{
				if ($promo == "all" || $row['pics_promo'] == $promo)
				{
					if ($ville == "all" || $row['pics_ville'] == $ville)
					{
						if (!file_exists("snaps/".$row['pics_src'].""))
						{
							$row['pics_src'] = "broken.jpg";
						}
						?>
						<div class="pic">
							<div class="nailthumb-container">
								<a class="fancybox" href="snaps/<?php echo $row['pics_src']; ?>" data-fancybox-group="gallery" title="<?php echo $row['pics_login']; ?> (<?php echo ($row['votecount'] == NULL) ? 0 : $row['votecount']; ?> pts)"><img src="snaps/<?php echo $row['pics_src']; ?>" /></a>
							</div>
							<div class="infos">
								<span class="author"><?php echo $row['pics_login']; ?> <span class="promo">(<?php echo $row['pics_ville'].' - '.$row['pics_promo']; ?>)</span></span>
								<span class="votes pts"><?php echo ($row['votecount'] == NULL) ? 0 : $row['votecount']; ?> pts</span>
							</div>
							<div class="infos">
								<span class="author promo"><?php echo $cats[$row['cat_name']]; ?></span>
								<span class="votes cat">
									<?php
									if (isset($_SESSION['logstate']) AND ($_SESSION['logstate'])){
										if (($ranks = $this->hasVoted($_SESSION['login'], $row['pics_login'], $row['pics_cat'])) > 0){
											if ($ranks == 1) {
												?>
												<img src="images/gold_medal.png" class="novote" />
												<?php
											} else if ($ranks == 3) {
												?>
												<img src="images/silver_medal.png" class="novote" />
												<?php
											} else {
												?>
												<img src="images/bronze_medal.png" class="novote" />
												<?php
											}
										} else {
											if ($_SESSION['login'] != $row['pics_login']) {
												if ($this->isMedalAvailable(5, $_SESSION['login'], $row['pics_cat'], $row['pics_promo'])){
													?>
													<img src="images/gold_medal.png" class="goldvote" data-pic-login="<?php echo $row['pics_login']; ?>" data-pic-cat="<?php echo $row['cat_name']; ?>" data-pic-promo="<?php echo $row['pics_promo']; ?>" />
													<?php
												} else {
													?>
													<img src="images/gold_medal_no.png" class="novote" />
													<?php
												}
												
												if ($this->isMedalAvailable(3, $_SESSION['login'], $row['pics_cat'], $row['pics_promo'])){
													?>
													<img src="images/silver_medal.png" class="silvervote" data-pic-login="<?php echo $row['pics_login']; ?>" data-pic-cat="<?php echo $row['cat_name']; ?>" data-pic-promo="<?php echo $row['pics_promo']; ?>" />
													<?php
												} else {
													?>
													<img src="images/silver_medal_no.png" class="novote" />
													<?php
												}
												
												if ($this->isMedalAvailable(1, $_SESSION['login'], $row['pics_cat'], $row['pics_promo'])){
													?>
													<img src="images/bronze_medal.png" class="bronzevote" data-pic-login="<?php echo $row['pics_login']; ?>" data-pic-cat="<?php echo $row['cat_name']; ?>" data-pic-promo="<?php echo $row['pics_promo']; ?>" />
													<?php
												} else {
													?>
													<img src="images/bronze_medal_no.png" class="novote" />
													<?php
												}
											} else {
												?>
												<img src="images/medal_nope.png" class="novote" />
												<?php
											}
										}
									}
									?>
								</span>
							</div>
						</div>
						<?php
						++$i;
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
	
	public function picExists($login, $cat) {
		$query = $this->PDO->prepare('SELECT *
									FROM pics
									LEFT JOIN categories ON pics.pics_cat = categories.cat_id
									WHERE pics.pics_login = ? AND categories.cat_name = ? AND pics_valid=1');
		$success = $query->execute(array($login, $cat));
		if ($query->rowCount() > 0)
		{
			return true;
		} else {
			return false;
		}
	}
	
	public function hasVoted($user, $login, $cat) {
		$query = $this->PDO->prepare('SELECT votes.vote_rank
									FROM pics
									LEFT JOIN votes ON pics.pics_login = votes.vote_pic_login AND pics.pics_cat = votes.vote_pic_cat
									WHERE pics.pics_login = ? AND pics.pics_cat = ? AND votes.vote_user = ? AND pics.pics_valid = 1') or die(mysql_error());
		$success = $query->execute(array($login, $cat, $user));
		if ($query->rowCount() > 0)
		{
			$row = $query->fetch(PDO::FETCH_ASSOC);
			if ($row['vote_rank'] != NULL)
			{
				return ($row['vote_rank']);
			} else {
				return 0;
			}
		} else {
			return 0;
		}
	}
	
	public function getVotes($login, $cat){
		$query = $this->PDO->prepare('SELECT SUM(votes.vote_rank) AS votecount
									FROM pics
									LEFT JOIN categories ON pics.pics_cat = categories.cat_id
									LEFT JOIN votes ON pics.pics_login = votes.vote_pic_login AND pics.pics_cat = votes.vote_pic_cat
									WHERE pics.pics_login = ? AND categories.cat_name = ? AND pics_valid = 1
									GROUP BY pics.pics_login, pics.pics_cat, votes.vote_pic_login, votes.vote_pic_cat
									ORDER BY votecount DESC');
		$success = $query->execute(array($login, $cat));
		if ($query->rowCount() > 0)
		{
			$row = $query->fetch(PDO::FETCH_ASSOC);
			if ($row['votecount'] != NULL)
			{
				return $row['votecount'];
			} else {
				return 0;
			}
		} else {
			return 0;
		}
	}
	
	public function isMedalAvailable($type, $user, $cat, $promo) {
		$query = $this->PDO->prepare('SELECT *
									FROM votes
									LEFT JOIN pics ON votes.vote_pic_login = pics.pics_login AND votes.vote_pic_cat = pics.pics_cat
									WHERE votes.vote_rank = ? AND votes.vote_user = ? AND votes.vote_pic_cat = ? AND pics.pics_promo = ?');
		$query->execute(array($type, $user, $cat, $promo));
		if ($query->rowCount() > 0)
		{
			return false;
		} else {
			return true;
		}
	}
	
	public function alreadyVoted($login, $cat, $user, $promo) {
		$query = $this->PDO->prepare('SELECT *
									FROM votes 
									LEFT JOIN categories ON votes.vote_pic_cat = categories.cat_id
									LEFT JOIN pics ON votes.vote_pic_login = pics.pics_login AND votes.vote_pic_cat = pics.pics_cat
									WHERE votes.vote_pic_login = ? AND categories.cat_name = ? AND votes.vote_user = ? AND pics.pics_promo = ?');
		$query->execute(array($login, $cat, $user, $promo));
		if ($query->rowCount() > 0)
		{
			return true;
		} else {
			return false;
		}
	}
	
	public function votePic($login, $cat, $user, $rank, $promo) {
		if ($this->picExists($login, $cat)) {
			if (!$this->alreadyVoted($login, $cat, $user, $promo)) {
				if ($rank != 0) {
					$cats = array("fdf" => 1, "wolf3d" => 2, "rt" => 3);
					if ($this->isMedalAvailable($rank, $user, $cat, $promo)) {
						$res = $this->PDO->prepare("INSERT INTO votes VALUES(?, ?, ?, ?)");
						$success = $res->execute(array($login,$cats[$cat],$user,$rank));
						return ($res->rowCount());
					} else {
						return (-42);
					}
				} else {
					return (-84);
				}
			} else {
				return (-51);
			}
		} else {
			return (-21);
		}
	}
}
?>
