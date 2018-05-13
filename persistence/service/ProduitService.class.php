<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 5/13/2018
 * Time: 12:44 PM
 */

class ProduitService extends AbstractService implements ProduitDAO {

    public function __construct() {
        parent::__construct();
    }

    public function create(Produit $produit){
        $pdoStatement = $this->getDb()->prepare( "INSERT INTO produit VALUES (NULL, :titre, :description, :prix, :nomFichier, :idFournisseur, :idCategorie)" );
        $pdoStatement->bindValue(':titre', $produit->getTitre(), PDO::PARAM_STR);
        $pdoStatement->bindValue(':description', $produit->getDescription(), PDO::PARAM_STR);
        $pdoStatement->bindValue(':prix', $produit->getPrix(), PDO::PARAM_INT);
        $pdoStatement->bindValue(':nomFichier', $produit->getNomFichier(), PDO::PARAM_STR);
        $pdoStatement->bindValue(':idFournisseur', $produit->getFournisseur()->getIdUser(), PDO::PARAM_INT);
        $pdoStatement->bindValue(':idCategorie', $produit->getCategorie()->getId(), PDO::PARAM_INT);
        return $pdoStatement->execute();
    }

    public function find(array $filters) {
        $sql = "SELECT * FROM produit";

        if (isset($filters['idFournisseur'])) {
            $sql .= " WHERE id_fournisseur = :idFournisseur";
        }

        if (isset($filters['idCategorie'])) {
            $sql .= " AND id_categorie = :idCategorie";
        }

        //REQUETE PREPAREE
        $pdoStatement = $this->getDb()->prepare( $sql );

        if (isset($filters['idFournisseur'])) {
            $pdoStatement->bindValue(":idFournisseur", $filters['idFournisseur'], PDO::PARAM_INT);
        }

        if (isset($filters['idCategorie'])){
            $pdoStatement->bindValue(":idCategorie", $filters['idCategorie'], PDO::PARAM_INT);
        }

        $pdoStatement->execute();
        return $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findAll() {
        $pdoStatement = $this->getDb()->query("SELECT * FROM produit");
        return $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
    }


}