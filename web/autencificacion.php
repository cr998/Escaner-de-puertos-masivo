<?php 
//comprueba si el login mandado es correcto
function isLogin($mysqli){
	$login=true;
	if(!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])){
		$login=false;
	}else{
		$user=$_SERVER['PHP_AUTH_USER'];
		$pass=$_SERVER['PHP_AUTH_PW'];
		$sql=sprintf("SELECT pass FROM usuarios WHERE usuario='%s'"
		,$mysqli->real_escape_string($user));
		$result=$mysqli->query($sql);
		$asoc=$result->fetch_assoc();
		if($result->num_rows<1 || $asoc["pass"]!=$pass){
			$login=false;
		}
	}
	
	return $login;
}

?>