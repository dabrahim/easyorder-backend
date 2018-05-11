<?php
	
// IMPORTS
require '/core/utils/Request.class.php';
require '/core/utils/Response.class.php';
require '/core/utils/Action.class.php';
require '/core/utils/functions.php';
require '/core/sections.php';
require '/persistence/CustomPDO.php';

// FONCTION AUTOLOAD
spl_autoload_register(function ($class){
    if ( preg_match('/Service$/', $class) ) {
        require("/persistence/service/" . $class . ".class.php");

    } else if ( preg_match('/DAO$/', $class) ) {
        require( "/persistence/interface/" . $class . ".class.php");

    } else {
        require ("/persistence/model/".$class.".class.php");
    }
});

session_name( "PHARMALIV_SESSION" );
session_start();