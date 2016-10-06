<!DOCTYPE html>
<html>
<head>
<title><?php echo SITENAME ?> - Back Office</title>
<?php boPage::echoBaseMeta(); ?>
<script>
	$(document).ready(function(){
		$("input:submit").button();
		$("input:button").button();
	});
</script>
</head>
<?php boPage::echoStartStructure();?>
<h2>Pour accèder au Back-Office, vous devez vous identifier.</h2>
<form id="login" class="form-inline" action="<?php echo boPage::getCurrentLink(''); ?>" method="post">
	<input name="login" class="input-small" type="text" placeholder="Identifiant" />
	<input name="pwd" class="input-small" type="password" placeholder="Mot de passe"  />
    <button class="btn" type="submit">Login <i class="fa fa-chevron-right"></i></button>
	<div class="erreur"><?php if(isset($erreur)) echo $erreur; ?></div>
</form>
<?php boPage::echoEndStructure(); ?>
</html>