<?php
	error_reporting(E_ALL);
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_StripSlashesGpc.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_MergeXml.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_MergeXmlChild.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_DisplayXmlError.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_GetTranslation.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_LoadTraduction.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_RemoveAccents.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_CheckSyntax.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_SendMail.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_SetRelai.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_FurtifRelai.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_GetValue.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_GetNbValue.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_GetTime.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_GetBruteValue.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_GetValueFunction.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_GetNextTime.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_GetPrevTime.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_GetVariable.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_SetVariable.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_SetValue.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_SetCnt.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_GetVariableId.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_PushTo.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_AccesPushMeTo.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_AccesMySql.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_AccesHighStock.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_AccesHighCharts.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_AccesJpGraph.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_Help.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_ConvertUtcTime.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_Trace.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_IpAsLong.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_CheckEmail.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_CheckIp.php'); 
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_CheckIpCidr.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_FindExec.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_Wget.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_WgetText.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_WgetXml.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_CheckVersion.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_GetVersion.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_TraceInstall.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_IsVersionInstalledGood.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_PurgeAutoTrace.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_PurgeTrace.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_TraceOptimize.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_PurgeTable.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_MoyennerTable.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_PurgeAutoAn.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_PurgeAutoCnt.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_GetBeginTable.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_SaveImage.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_DirectoryToArray.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_FileAccessRight.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_DmsToDec.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_GetServeurUrl.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_Write.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_RmDirRec.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_HtmlInputField.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_HtmlSelectField.php'); 
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_HtmlLabel.php'); 
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_HtmlBinarySelectField.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_HtmlBinaryAuthField.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_HtmlHiddenField.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_HtmlStartForm.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_HtmlEndForm.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_HtmlTextAreaField.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_HtmlStartFieldset.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_HtmlEndFieldset.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_HtmlSpanLabel.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_GetModel.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_HtmlDivError.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_SetRasp433.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_HtmlStartPanel.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_GetIP.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_GetByXpath.php');	
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_GetListHelpFile.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_GetListItem.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_ParsageHelp.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_br2nl.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_GetUnUsedListItem.php');	
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_GestionAuth.php');	
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_GetContent.php');	
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_PictureResize.php');	
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_PictureDisplay.php');	
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_Alert.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_SessionInfo.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_GetListXmlId.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_SortByNumero.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_HtmlButtonPicto.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_SetRazDevice.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_Status.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_SetBdd.php');
	require_once ($GLOBALS["mvc_path"].'fctphp/fn_SaveBdd.php');
	
	
	

	/*
    $dir = $GLOBALS["mvc_path"].'fctphp';
    $dossier = array(); // initialisation du tableau
    $folder = opendir($dir); // Ouverture du dossier
    while ($file = readdir($folder)) { // Lecture de chaque entrée       
        if ($file != "." && $file != "..") { // Suppression des ./ et des ../
            $dossier[] = $file; // Mise en tableau du contenu
        }
    }
    sort($dossier); // Classement par ordre alphabétique
 
    foreach ($dossier as $nom_dossier) { // Boucle qui affiche les option du select, en attribuant sa position dans la liste
        //foreach ($dossier as $cle=>$valeur) {
            $valeur = $nom_dossier;
        //}
        $i++;
        if ( preg_match("/\.php$/", $valeur) ) {
					//require_once($GLOBALS["mvc_path"].'fctphp/'.$valeur);
					echo "<p>".$i.". ".$valeur."</p>"; 
				}
    }
    closedir($folder);
*/
?>