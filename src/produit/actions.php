<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 5/13/2018
 * Time: 1:22 PM
 */

function find ( $action ) {
    $req = $action->request();
    $get = $req->get();

    $result = array(
        'success' => false
    );

    try {
        $payload = getTokenData();
        $data = json_decode($get['data'], true);

        $pService = new ProduitService();
        $produits = $pService->find($data);

        if ($produits) {
            $result['success'] = true;
            $result['data'] = $produits;

        } else {
            $result['message'] = "Aucun résultat correspondant à votre recherche n'a été trouvé!";
        }

    } catch (Exception $e) {
        $result['message'] = $e->getMessage();
    }

    $action->response()->toJson( $result );
}

function addProduct ( \Esmt\Pharmaliv\Action $action ){
    $req = $action->request();
    $result = array(
        'success' => false
    );

    if ( $req->type() === 'POST' ) {
        $post = $req->post();
        $files = $req->files();

        $result = array(
            'success' => false
        );

        try {
            $data = getTokenData();
            $nomFichier = saveFile($files['photo'], 'uploads/', array('jpg', 'jpeg', 'png'),true);

            $produit = new Produit();
            $produit->setNomFichier( $nomFichier );

            $produit->setTitre($post['titre']);
            $produit->setDescription($post['description']);
            $produit->setPrix($post['prix']);

            $f = new Fournisseur();
            $f->setIdUser( $data['id_user'] );
            $produit->setFournisseur($f);

            $categorie = new Categorie();
            $categorie->setId($post['id_categorie']);
            $produit->setCategorie( $categorie );

            $pService = new ProduitService();
            $pService->create( $produit );

            $result['success'] = true;

        } catch (Exception $e) {
            $result['message'] = $e->getMessage();
        }

    }

    $action->response()->toJson( $result );
}