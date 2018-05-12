<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 5/11/2018
 * Time: 12:40 PM
 */

abstract class AbstractService {
    protected $_db;

    public function __construct() {
        $this->_db = CustomPDO::getInstance();
    }
}