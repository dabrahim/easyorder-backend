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
            self::$_instance = new PDO("mysql:host=localhost;dbname=pharmaliv", "pharmaliv", "esmt",
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        }
        return self::$_instance;
    }
}