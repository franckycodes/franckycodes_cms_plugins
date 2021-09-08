<div class="appLogin"> 
	<h2>subscribe</h2>

<?php 
if(isset($error))
{
	echo '<div class="errorLogin error redBt">';
	// echo $error;
	switch($error)
	{
		case 'all':
		echo '<div>Vous devez remplir les champs</div>';
		break;
		case 'user exists':
		echo '<div>Cet utilisateur existe deja</div>';
		break;
		default:
		echo '<div>Erreur: '.$error.'</div>';
		break;
	}
	echo '</div>';
}

 ?>
<form method="POST" action="<?php echo WEBROOT.'subscribe/';?>">
    <label for="">Initiale</label>
	<input type="text" name="initiale" id="matricule"><br>

	<label for="">Matricule</label>
	<input type="text" name="matricule" id="matricule"><br>

	<label for="">email</label>
	<input type="text" name="login"><br>
	<label for="">Mot de passe</label>
	<input type="password" name="password"><br>

	<label for="loginSubmitBt" class="blueBt">créer ce compte</label>
	<input type="submit" id="loginSubmitBt" class="hide">
	<a href="<?php echo WEBROOT.'login/';?>" class="blueBt">J'ai déjà un compte</a>
</form>
</div>