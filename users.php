<?php
session_start();
ob_start();

define("LOG", "book/save");
define("SHARE", "book/share");


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
			$user = setUserData($_POST['userid']);
			
			//echo json_encode( array('user'=>$user) );
			echo json_encode($user);

			return true;
		}
	}

	header('HTTP/1.0 403 Forbidden');
	echo json_encode( array('error'=>'login not correct') );
	return false;
}



function setUserData($userid){
	global $users; 
	$user = $users[$userid];
	unset($user['password']);
	$user['userid'] = $userid;
	return $user;
}




	//if(!isset($_SESSION['user']) ){
    //    echo json_encode( array('error'=>'user not found') );
    //    exit;
    //}



if(isset($_GET['accept_share'])){
	file_put_contents(SHARE, "1");
	echo json_encode( array('ok') );

}else if(isset($_GET['check_share'])){



	$current = @file_get_contents(SHARE);

	if(empty($current)){
		echo json_encode( array('result'=>'false') );
	} else {

		file_put_contents(SHARE, "");
		echo json_encode( array('result'=>'true') );
	}

}else if(isset($_GET['logout'])){
	unset($_SESSION['user']);
	session_destroy();

	echo json_encode( array('logout!') );

}else if(isset($_POST['userid']) AND isset($_POST['password']) AND !isset($_GET['lat1'])){


	return checkLogin(); 
}else if( !isset($_GET['lat1']) ){

	if(!isset($_SESSION['user']) ){
	header('HTTP/1.0 401 Not Authorized');
	echo json_encode( array('error'=>'Not Authorized') );
	}else {
		$user = setUserData($_SESSION['user']);
		echo json_encode( $user );	
	}
}










