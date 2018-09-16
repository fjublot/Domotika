<!DOCTYPE html>
<html class="ui-mobile" lang="fr">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Connexion
    </title>
    <?php 
		echo $login->css(); 
        echo $login->initJquery();
    ?>
    <script type="text/javascript" src="js/ajax.js"></script>

  </head>
  <body>
<div id="content">
<?php
echo $login->html();
?>

</div>
<?php
echo $login->initLogin();
?>
</body>
</html>