<?php
header('Content-Type: application/json; charset=utf-8');
$ds          = DIRECTORY_SEPARATOR;  
if ($_REQUEST['filename']) 
{
    $targetPath = $GLOBALS["root_path"] . 'config' . $ds . 'images' . $ds ;  
    $targetFile =  $targetPath . $_REQUEST['filename'];
    $result[] = array(
        'status'   => unlink($targetFile) ,
        'filename' => $_REQUEST['filename'],
        'path'     => $targetFile);
    echo json_encode($result);
}

else
{    $result[] = array(
        'status'   => false,
        'filename' => "none",
        'path'     => "none");
    echo json_encode($result);
}
?>