<p>Pour modifier les droits d'une personne, indiquez son login et les nouveaux droits.</p>
<p>
	<form method="post" action="req/changeRights.php" id="changeRights">
		<input type="text" name="loginrights" placeholder="login" />
		<select name="droits" style="width: 100px;">
			<option value="">droits</option>
			<option value="0">aucun</option>
			<option value="21">validateur</option>
			<option value="42">admin</option>
		</select>
		<input type="submit" name="lesdroits" value="MODIFIER" />
	</form>
</p>
<p class="success"></p>