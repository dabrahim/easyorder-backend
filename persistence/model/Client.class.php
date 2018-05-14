<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 5/14/2018
 * Time: 12:54 PM
 */

class Client extends User {
    private $nom;
    private $prenom;
    private $coordGeo;

    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param mixed $nom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    /**
     * @return mixed
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @param mixed $prenom
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    }

    /**
     * @return mixed
     */
    public function getCoordGeo()
    {
        return $this->coordGeo;
    }

    /**
     * @param mixed $coordGeo
     */
    public function setCoordGeo($coordGeo)
    {
        $this->coordGeo = $coordGeo;
    }

}