<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 5/13/2018
 * Time: 12:23 PM
 */

class CategorieService extends AbstractService implements CategorieDAO {

    public function __construct() {
        parent::__construct();
    }

    public function getAll() {
        $pdoStatement = $this->getDb()->query("SELECT * FROM categorie");
        return $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
    }

}