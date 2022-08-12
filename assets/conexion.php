<?php
session_start();
$serverName = "SERVER001";
$connectionInfo = array("Database"=>"SMAP_Quezada", "UID"=>$usuario, "PWD"=>$password);
$conn = sqlsrv_connect($serverName, $connectionInfo);

