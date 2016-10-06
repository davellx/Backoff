<!DOCTYPE html>
<html>
<head>
<title><?php echo SITENAME ?> - Back Office</title>
<?php boPage::echoBaseMeta();
?>
</head>
<?php boPage::echoStartStructure(); ?>
<p>Bonjour, nous sommes le <?php echo utf8_encode(strftime('%A %#d %B %Y')); ?></p>
<p>Choisissez la rubrique à administrer.</p>
<?php boPage::echoEndStructure(); ?>
</html>