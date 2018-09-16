<?php
	//CETTE PAGE GERE LA CONNEXION A LA BASE DE DONNEES
	$host     = $GLOBALS["config"]->general->mysql_host ;
	$dbname   = $GLOBALS["config"]->general->mysql_db ;
	$user     = $GLOBALS["config"]->general->mysql_user ;
	$password = $GLOBALS["config"]->general->mysql_password ;
    $port     = $GLOBALS["config"]->general->mysql_port ;

	try {
		// On se connecte à MySQL
		$bdd = new PDO('mysql:host=' . $host . ';port=' . $port . ';dbname=' . $dbname, $user, $password, array(PDO::ATTR_PERSISTENT => TRUE));
		$bdd->exec("SET CHARACTER SET utf8");
		$db = new database($bdd);
	}
	catch(Exception $e) {
		// En cas d'erreur, on affiche un message et on arrête tout
		header ("Refresh: 3;URL=?app=Mn&page=Setup&mysqlenabled=off");
		include($GLOBALS["page_inc_path"] . 'head.php');
		include($GLOBALS["page_inc_path"] . 'headloadjs.php');
?>
		<script type="text/javascript">
			new PNotify({
				title: "<?php echo ucfirst(fn_GetTranslation('error')); ?>",
				text: "<?php echo $e->getMessage();?>",
				type: "error",
				nonblock: false
			});
		</script>
<?php
		die() ;
	}
?>
