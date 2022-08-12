<?php
$serverName = "corporativoabmx.ddns.net";
$connectionInfo = array("Database"=>"Corpo_Abastemex", "UID"=>$usuario, "PWD"=>$password);
$conn = sqlsrv_connect($serverName, $connectionInfo);
