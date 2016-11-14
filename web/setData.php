<?php

include "mysqliconfig.php";//contiene el objeto mysqli en $mysqli ya conectado 
include "autencificacion.php";//importa la funcion isLogin($(object mysqli))


function ExistIp($mysqli,$ip,$user)
{
	echo $user;
	$result=$mysqli->query("SELECT id FROM ips WHERE ip='$ip' AND  WHERE estado='escaneando' AND usuario=(SELECT id FROM usuarios  WHERE usuario='$user')");
	if(isset($mysqli->error)){
		echo $mysqli->error;
	}
	if($result->num_rows>0){
		return $result->fetch_assoc()[0];
	}else{
		return false;
	}

}
if(!isLogin($mysqli)) {
	die("no autorizado");
}

if(!isset($_POST["data"])){
	die("no data");
}

$user=$_SERVER['PHP_AUTH_USER'];


$data=json_decode($_POST["data"]);
var_dump($data);
$ip=$data[0];

if (!($ipid=ExistIp($mysqli,$ip,$user)) ) {
	die(sprintf("no existe ninguna ip-> "+$ip+" asignada para el usuario %d", $user));
}

if (isset($data[1])) {
	$ports=$data[1];

	foreach ($ports as $port) {
		$mysqli->query("INSERT INTO puertos (idip,puerto) VALUES ('$ipid','$port')");
	}
}else{
	echo "OK!";
}


?>