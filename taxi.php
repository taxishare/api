<?php

define("LOG", "book/save");

include("users.php");
include("_include/calculate_path.php");

//error_reporting(E_ALL);
//ini_set('display_errors', 1);
date_default_timezone_set('Europe/Rome');



    if(!isset($_SESSION['user']) ){
        echo json_encode( array('error'=>'user not found') );
        exit;
    }

    if( 
        isset($_GET['lat1']) 
        AND  isset($_GET['long1']) 
        AND isset($_GET['lat2']) 
        AND isset($_GET['long2']) 
      ){
        calculatePath(); 
    }


    if(isset($_GET['delete_share']))    {
        deleteShare();
    }



