<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 5/13/2018
 * Time: 12:43 PM
 */

interface ProduitDAO {
    public function create ( Produit $produit );

    public function find (array $filters);

    public function findAll ();

    public function getUpdates(User $user);

    public function saveCommande($idUser,$idFournisseur, $montant, $data);

    public function getDetailsCommande ($idCommande);
}