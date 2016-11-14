<?php

include "mysqliconfig.php";//contiene el objeto mysqli en $mysqli ya conectado 
include "autencificacion.php";//importa la funcion isLogin($(object mysqli))

//funcion para buscar si el usuario tiene ya una ip asignada, si la tiene la funcion devuelve un string con la direccion ip
function isIpForUser($user,$mysqli){
        $sql="SELECT ip FROM ips WHERE estado='escaneando' AND  usuario=(SELECT id FROM usuarios WHERE usuario='$user')";
        $result=$mysqli->query($sql);
        if($result->num_rows==0){
		return false;
	}else{
		return $result->fetch_assoc()["ip"];
	}
}

//genera una ip al azar
function ipAlazar()
{
	switch (rand(1,3)) {
		case 1:
			$n1=rand(1,126);
			break;
		case 2:
			$n1=rand(128,191);
			break;
		case 3:
			$n1=rand(192,223);
			break;
	}

	$n2=rand(0,255);
	$n3=rand(0,255);
	$n4=rand(0,255);

	return $n1.".".$n2.".".$n3.".".$n4;
}

//comprueba si existe ninguna ip igual a la dada por el argumento
function existIp($ip,$mysqli){
	$exist=false;
	$sql="SELECT * FROM ips WHERE ip='$ip'";
	$mysqli->real_query($sql);
	$resultado = $mysqli->use_result();
	if ($resultado->num_rows>0) {
		$exist=true;
	}
	return true;
}


//Genera ips al alzar hasta encontrar una que no existe en la bd
function ipAlazarComprobada($mysqli){
	do {
		$ip=ipAlazar();
	} while (!existIp($ip,$mysqli));
	return $ip;
}


//Este debe de ser el primer bloque en ejecutarse en el scrip ya que 
//su uso es saber si el usuario esta logedo o no correctamente
if(!isLogin($mysqli)){
	$no_auth="no autorizado";
	header("HTTP/1.1 403 Forbidden");
        die($no_auth);
}

$user=$mysqli->real_escape_string($_SERVER['PHP_AUTH_USER']);

//comprobamos si el usuario tiene una ip asignada en la bs, y si no la tiene se la asignamos
if(true//!($ip=isIpForUser($user,$mysqli))
	){
	$ip=ipAlazarComprobada($mysqli);
	$mysqli->query("INSERT INTO ips (ip,estado,usuario) VALUES('$ip','escaneando',(SELECT id FROM usuarios  WHERE usuario='$user'))");
	
}

header("HTTP/1.1 200 OK");
echo $ip



?>
