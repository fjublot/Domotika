<?php
	header('Content-Type: application/json; charset=utf-8');
	switch ($control) {
			Case 'iswritabledir':
			// Test si un répertoire est accessible en écriture
				$return = is_writable ($directory);
				$result[] = array(
					'control' => $control,
					'directory' => $directory,
					'valid' => $return?true:false,
					'message' => $return ? fn_GetTranslation('writing_dir', $directory) : fn_GetTranslation('no_writing_dir', $directory)
				);
				break;

			Case 'iswritablefile':
			// Test si un fichier est accessible en écriture
				$return=is_writable ($file);
				$result[] = array(
					'control' => $control,
					'file' => $directory,
					'valid' => $return?true:false,
					'message' => $return ? fn_GetTranslation('writing_file', $file) : fn_GetTranslation('no_writing_file', $file)
				);
				break;

			Case 'phpminversion':
			// Test si la version de php est supérieure à la version minimum
				$return=version_compare(phpversion(), $minversion, ">=");
					$result[] = array(
					'control' => $control,
					'minversion' => $minversion,
					'valid' => $return?true:false,
					'message' => fn_GetTranslation('necessary_min_version_php', $minversion).". ".fn_GetTranslation('actual_version', phpversion())
				);
				break;

			Case 'extensionisloaded':
			// Test si une extention est chargée
				$return=extension_loaded($extension);
				$result[] = array(
					'control' => $control,
					'extension' => $extension,
					'valid' => $return?true:false,
					'message' => $return ? fn_GetTranslation('present_extension', $extension) : fn_GetTranslation('necessary_extension', $extension)
				);
				break;

			Case 'optionenable':
			// Test si une option est active
				$return=ini_get($option);
				$result[] = array(
					'control' => $control,
					'option' => $option,
					'valid' => $return?true:false,
					'message' => $return ? fn_GetTranslation('enable_'.$option, $option) : fn_GetTranslation('disable_'.$option, $option)
				);
				break;
        
			Case 'accountserver':
			// Test le compte du serveur
				
				$currentuser=utf8_encode(get_current_user());
				$return=true;
				if ($account==$currentuser) {
					$return=true;
				}
				$result[] = array(
					'control' => $control,
					'account' => $account,
					'valid'  => $return?true:false,
					'message' => fn_GetTranslation('account_server', $currentuser)
				);
				break;

			Case 'email':
			// Test validité email
				if ($email)
				$return=fn_CheckEmail($email);
				else if($mailadmin)
				$return=fn_CheckEmail($mailadmin);
				
				$result[] = array(
					//'control' => $control,
					//'email' => $email,
					'valid' => $return ,
					//'message' => $return ? fn_GetTranslation('admin_email_valid') : fn_GetTranslation('admin_email_invalid')
				);
				break;

			Case 'validip':
			// Test validité ip
				$return=ValidateIP($ip);
				$result[] = array(
					'control' => $control,
					'ip' => $ip,
					'valid' => $return?true:false,
					'message' => $return ? fn_GetTranslation('mysql_host_correct') : fn_GetTranslation('mysql_host_incorrect')
				);
				break;
				
			Case 'numeric':
			// Test pregmatch
				$return=preg_match("/^[0-9]+$/", $value);
				$result[] = array(
					'control' => $control,
					'prefix' => $prefix,
					'value'  => $value,
					'valid' => $return?true:false,
					'message' => $return ? fn_GetTranslation($prefix.'_correct') : fn_GetTranslation($prefix.'_incorrect')
				);
				break;

			Default :
			// Dans tous les autres cas
				$result[] = array(
					'control' => 'none',
					'valid' => false,
					'message' => fn_GetTranslation('none')
				);

			}
// Encodage du tableau en json			
echo json_encode($result);
?>