<?php
function fn_SaveBdd($host, $dbname, $user, $password, $port) {
    $file = 'backup_'.@date("Y-m-d-H:i:s").'.gz';
    die(system("mysqldump --add-drop-table --create-options --skip-lock-tables --extended-insert --quick --set-charset --host=$host --user=$user --password=$password --port=$port $dbname | gzip > backup/database/$file"));
}
?>