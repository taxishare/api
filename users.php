<?php
session_start();
ob_start();

// users DB
$users = array(
		'gabriele' => array(
			"password" => "guest1",
			"nome" => "Gabriele Marazzi"
			),
		'riccardo' => array(
			"password" => "guest2",
			"nome" => "Riccardo Mercanti"
			)
	);


function checkLogin(){
	global $users; 
	
	if( isset($users[$_POST['userid']]) ){

		if($users[$_POST['userid']]['password'] == $_POST['password']){
	
			$_SESSION['user'] = $_POST['userid']; 
			echo json_encode( array('user'=>$users[$_POST['userid']]) );

			return true;
		}
	}

	header('HTTP/1.0 403 Forbidden');
	echo json_encode( array('error'=>'login not correct') );
	return false;
}


if(isset($_GET['logout'])){
	unset($_SESSION['user']);
	echo json_encode( array('logout!') );

}else if(isset($_POST['userid']) AND isset($_POST['password'])){
	return checkLogin(); 
}else{

	if(!isset($_SESSION['user']) ){
	header('HTTP/1.0 401 Not Authorized');
	echo json_encode( array('error'=>'Not Authorized') );
	}else{
		$user = $users[$_SESSION['user']];
		unset($user['password']);
		$user['userid'] = $_SESSION['user'];
		echo json_encode( $user );	
	}
}










