<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 5/11/2018
 * Time: 12:34 PM
 */

class UserService extends AbstractService implements UserDAO {

    public function __construct() {
        parent::__construct();
    }

    public function create( User $user ) {
        $pdoStatement = $this->_db->prepare("INSERT INTO user SET email = :email, password = :password, date_inscription = NOW(), type = :type, telephone = :telephone, solde = 0");
        $pdoStatement->bindValue(':email', $user->getEmail(), PDO::PARAM_STR);
        $pdoStatement->bindValue(':password', $user->getPassword(), PDO::PARAM_STR);
        $pdoStatement->bindValue(':telephone', $user->getTelephone(), PDO::PARAM_STR);
        $pdoStatement->bindValue(':type', $user->getType(), PDO::PARAM_STR);
        $pdoStatement->execute();
        return $this->_db->lastInsertId();
    }

    public function authenticate( User $user ) {
        $pdoStatement = $this->_db->prepare("SELECT * FROM user WHERE email = :email");
        $pdoStatement->bindValue(':email', $user->getEmail(), PDO::PARAM_STR);
        $pdoStatement->execute();
        if ($usr = $pdoStatement->fetch(PDO::FETCH_ASSOC)) {
            if ($user->getEmail() == $usr['email'] && $user->getPassword() == $usr['password']){
                return $usr;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

}