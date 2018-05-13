<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 5/11/2018
 * Time: 12:31 PM
 */

class FournisseurService extends AbstractService implements FournisseurDAO {

    public function __construct() {
        parent::__construct();
    }

    public function create(Fournisseur $fournisseur) {
        $pdoStatement = $this->_db->prepare('INSERT INTO fournisseur SET id_fournisseur = :idFournisseur, nom_societe = :nomSociete, nom_image_profil = :nomImage');
        $pdoStatement->bindValue(':idFournisseur', $fournisseur->getIdUser(), PDO::PARAM_INT);
        $pdoStatement->bindValue(':nomSociete', $fournisseur->getNomSociete(), PDO::PARAM_STR);
        $pdoStatement->bindValue(':nomImage', $fournisseur->getNomImageProfil(), PDO::PARAM_STR);
        $pdoStatement->execute();
    }

    public function getAll() {
        $pdoStatement = $this->_db->query("SELECT id_user, email, telephone, nom_societe, nom_image_profil FROM user NATURAL JOIN fournisseur");
        return $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDetails ( $id ) {
        $pdoStatement = $this->_db->prepare("SELECT id_user, email, telephone, nom_societe, nom_image_profil FROM user NATURAL JOIN fournisseur WHERE id_user = :idUser");
        $pdoStatement->bindValue(":idUser", $id, PDO::PARAM_INT);
        $pdoStatement->execute();
        return $pdoStatement->fetch(PDO::FETCH_ASSOC);
    }

}