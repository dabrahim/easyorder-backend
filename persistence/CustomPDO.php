<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 4/3/2018
 * Time: 12:04 PM
 */

class CustomPDO {
    private static $_instance;

    private function __construct (){}

    public function __clone() {}

    /**
     * @return mixed
     */
    public static function getInstance()
    {
        if ( self::$_instance== null ){
            self::$_instance = new PDO("mysql:host=localhost;dbname=easyorder", "dabrahim", "131296",
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
        }
        return self::$_instance;
    }
}