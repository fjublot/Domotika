<?php
/*----------------------------------------------------------------*
* Titre : Submit.php                                              *
* Programme permettant de maintenir les objets                    *
*----------------------------------------------------------------*/
	header("Content-type: application/json; charset=utf-8");
	$result=array();
	$model = fn_GetModel($class, $numero, $model);
	$result = array('class' => $class, 'model' => $model, 'message' => ''); 
	$current = new $model($numero);

	switch ($action) {
		case "BT_ConfigPushOnCard":
			list($errorcode, $message) = $current->configpushoncard();
			$result = array_merge($result, array('errorcode' => $errorcode, 'message' => $message));
			break;
			
		case "BT_PushToTest":
			list($code, $text) = fn_pushto('Ceci est un message de test', $current->numero);
			$result = array_merge($result,array('errorcode' => 0, 'message' => $code));
			break;
		
		case "BT_SetCnt":
			list($errorcode, $message) = $current->setvalue($valsetcnt);
			$result = array_merge($result, array('errorcode' => $errorcode, 'message' => $message));
			break;

		case "BT_GetCnt":
			list($errorcode, $value) = fn_GetBruteValue('cnt', $numero);
			$message = ucfirst(fn_GetTranslation('readcnt',$numero));
			$result = array_merge($result, array('errorcode' => $errorcode, 'message' => $message, 'brutevalue' => $value));
			break;
			
		case "BT_Supprimer": 
			if ($current->verif_before_del()) {
				$result = array_merge($result, $current->del());
			}
			else 
				$result = array_merge($result, array('errorcode' => 0, 'message' => fn_GetTranslation('enable_to_delete')));
			break;
		
		case "BT_Annuler":  
			$result = array_merge($result, array('errorcode' => 0, 'message' => fn_GetTranslation('operation_cancel')));
			break;
		
		case "BT_Envoyer": 
			list($status, $message) = $current->receive_form();
		    if ($message != "")
				$result = array('errorcode' => 0, 'message' => $message);
			
			if ( $current->required_field() ) {
				if ( $status ) { 
					$result = $current->save();
				}
				else
					$result = array_merge($result, array('errorcode' => 0, 'message' => $message));
			}
			break;
	}
	
	if (isset($result))
		echo json_encode($result);
?>
