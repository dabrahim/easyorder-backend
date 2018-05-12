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

}