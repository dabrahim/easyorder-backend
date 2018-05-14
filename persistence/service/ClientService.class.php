<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 5/14/2018
 * Time: 12:58 PM
 */

class ClientService extends AbstractService implements ClientDAO {

    public function __construct() {
        parent::__construct();
    }

    public function create(Client $client) {
        try {
            $this->getDb()->beginTransaction();

            $pdoStatement = $this->getDb()->prepare("INSERT INTO user VALUES (NULL, :email, :password, NOW(), 'CLIENT', :telephone, 0)");
            $pdoStatement->bindValue(":email", $client->getEmail(), PDO::PARAM_STR);
            $pdoStatement->bindValue(":password", $client->getPassword(), PDO::PARAM_STR);
            $pdoStatement->bindValue(":telephone", $client->getTelephone(), PDO::PARAM_STR);
            $pdoStatement->execute();

            $idUser = $this->getDb()->lastInsertId();

            $pdoStatement = $this->getDb()->prepare("INSERT INTO client VALUES (:nom, :prenom, :coordGeo, :idClient)");
            $pdoStatement->bindValue(":nom", $client->getNom(), PDO::PARAM_STR);
            $pdoStatement->bindValue(":prenom", $client->getPrenom(), PDO::PARAM_STR);
            $pdoStatement->bindValue(":coordGeo", $client->getCoordGeo(), PDO::PARAM_STR);
            $pdoStatement->bindValue(":idClient", $idUser, PDO::PARAM_INT);
            $pdoStatement->execute();

            $this->getDb()->commit();

        } catch (Exception $e) {
            $this->getDb()->rollback();
        }
    }
}