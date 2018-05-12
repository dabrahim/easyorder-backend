<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 5/11/2018
 * Time: 12:31 PM
 */

class Fournisseur extends User {
    private $nomSociete;
    private $nomImageProfil;

    /**
     * @return mixed
     */
    public function getNomSociete()
    {
        return $this->nomSociete;
    }

    /**
     * @param mixed $nomSociete
     */
    public function setNomSociete($nomSociete)
    {
        $this->nomSociete = $nomSociete;
    }

    /**
     * @return mixed
     */
    public function getNomImageProfil()
    {
        return $this->nomImageProfil;
    }

    /**
     * @param mixed $nomImageProfil
     */
    public function setNomImageProfil($nomImageProfil)
    {
        $this->nomImageProfil = $nomImageProfil;
    }
}