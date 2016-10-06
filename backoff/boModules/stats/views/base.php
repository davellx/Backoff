<!DOCTYPE html>
<html>
<head>
<title>Exemple <?php echo SITENAME ?> - Back Office</title>
<?php boPage::echoBaseMeta(null,$this->path.'views/style.css'); ?>
</head>
<?php boPage::echoStartStructure(); ?>

<h1>Exemple de module</h1>

<h2>Value</h2>
<p id="value"><?=$value?></p>
<p><button id="increment">Increment</button></p>
<script>
     $(document).ready(function(){
         $('#increment').click(function(){
            $.get('stats/increment',{},function(data){
                data = JSON.parse(data);
                $('#value').html(data.value);
            });
         });
     });
</script>
<?php boPage::echoEndStructure(); ?>
</html>