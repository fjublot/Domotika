<?php
	/*
	 * Cette fonction permet le chargement automatique des classes. Cela permet de ne pas avoir à instancier chaque classe.
	 * Cette fonction inclus le fichier de class
	 * @param string $class = Nom de la classe a aller chercher
	 */
	function autoload($class) {
		if (file_exists($GLOBALS["class_path"]  . $class . '.class.php'))
			require_once($GLOBALS["class_path"]  . $class . '.class.php');
		else if (file_exists($GLOBALS["classexternal_path"]  . $class . '.class.php'))
			require_once($GLOBALS["classexternal_path"] . $class . '.class.php');
		else if (file_exists($GLOBALS["model_path"]  . $class . '.php'))
			require_once($GLOBALS["model_path"] . $class . '.php');
		else if (file_exists($GLOBALS["lib_path"]  . $class . '.php'))
			require_once($GLOBALS["lib_path"] . $class . '.php');
		else 
			die(ucfirst(fn_GetTranslation ('canot_find_class', $class)));
	}
	
	spl_autoload_register('autoload');
