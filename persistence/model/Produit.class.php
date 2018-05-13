<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 5/13/2018
 * Time: 12:40 PM
 */

class Produit {
    private $_id;
    private $_titre;
    private $_description;
    private $_prix;
    private $_fournisseur;
    private $_categorie;
    private $_nomFichier;

    /**
     * @return mixed
     */
    public function getNomFichier()
    {
        return $this->_nomFichier;
    }

    /**
     * @param mixed $nomFichier
     */
    public function setNomFichier($nomFichier)
    {
        $this->_nomFichier = $nomFichier;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->_id = $id;
    }

    /**
     * @return mixed
     */
    public function getTitre()
    {
        return $this->_titre;
    }

    /**
     * @param mixed $titre
     */
    public function setTitre($titre)
    {
        $this->_titre = $titre;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->_description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->_description = $description;
    }

    /**
     * @return mixed
     */
    public function getPrix()
    {
        return $this->_prix;
    }

    /**
     * @param mixed $prix
     */
    public function setPrix($prix)
    {
        $this->_prix = $prix;
    }

    /**
     * @return mixed
     */
    public function getFournisseur()
    {
        return $this->_fournisseur;
    }

    /**
     * @param mixed $fournisseur
     */
    public function setFournisseur( Fournisseur $fournisseur ) {
        $this->_fournisseur = $fournisseur;
    }

    /**
     * @return mixed
     */
    public function getCategorie() {
        return $this->_categorie;
    }

    /**
     * @param mixed $categorie
     */
    public function setCategorie( Categorie $categorie ) {
        $this->_categorie = $categorie;
    }

}