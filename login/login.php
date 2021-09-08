<div class="appLogin"> 
	<h2>login</h2>
<?php 
if(isset($error))
{
	echo '<div class="errorLogin error redBt">';
	// echo $error;
	switch($error)
	{
		 
		case 'user exists':
		echo '<div>Cet utilisateur n\'existe pas ou vous n\'avez pas remplit les champs</div>';
		break;
		default:
		echo '<div>Vous devez remplir les champs</div>';
		break;
	}
	echo '</div>';
}
?>
<form  method="POST"  >
	<label for="">login</label>
	<input type="text" name="login"><br>
	<label for="">Mot de passe</label>
	<input type="password" name="password"><br>

	<label for="loginSubmitBt" class="blueBt">se connecter</label>
	<input type="submit" id="loginSubmitBt" class="hide">
	<!-- <a href="<?php //echo WEBROOT.'subscribe/';?>" class="blueBt">Inscription</a> -->
</form>
</div>