<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 5/11/2018
 * Time: 11:44 AM
 */

function home ( $action ) {
    $cService = new CategorieService();
    $categories = $cService->getAll();
    $action->response()->render( 'accueil-fournisseur.html', array(
      'categories' => $categories
    ) );
}

function publierProduit ( \Esmt\Pharmaliv\Action $action ){
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

function getDetails ( $action ) {
    $result = array(
        'success' => false
    );

    try {
        $data = getTokenData();

        $idUser = $data['id_user'];

        $fService = new FournisseurService();
        $f = $fService->getDetails( $idUser );

        if ($f) {
            $result['success'] = true;
            $result['data'] = $f;
        } else {
            $result['message'] = "Aucun fournisseur correspondant à cet id n'a été trouvé";
        }

        http_response_code(200);

    } catch (Exception $e) {
        $result['message'] = $e->getMessage();
        http_response_code(400);
    }

    $action->response()->toJson( $result );
}

function getDetailsFournisseur ($action, $params) {
    $req = $action->request();

    $result = array();
    $result['success'] = false;

    if ($req->type() == 'GET'){
        $id = intval($params[1]);
        if ($id != 0) {
            $fService = new FournisseurService();
            $data = $fService->getDetails($id);

            if ($data) {
                $result['success'] = true;
                $result['data'] = $data;
            } else {
                $result['message'] = "Aucun résultat trouvé";
            }
            http_response_code(200);

        } else {
            $result['message'] = "L'id fourni est incorrect";
            http_response_code(400);
        }

    } else {
        $result['message'] = "Cette route n'est accessible qu'en GET";
        http_response_code(400);
    }

    $action->response()->toJson( $result );
}

function getAll ( $action ) {
    $result = array(
        'success' => false
    );

    if ( $action->request()->type() === 'GET' ) {
        $fService = new FournisseurService();
        $result['success'] = true;
        $result['data'] = $fService->getAll();
        http_response_code(200);

    } else {
        $result['message'] = "Cette route n'est accessible qu'en GET";
        http_response_code(400);
    }

    $action->response()->toJson( $result );
}

function inscription ( $action ) {
    $req = $action->request();

    if ($req->type() == 'GET') {
        $action->response()->render( 'inscription-fournisseur.html' );

    } else if ($req->type() == 'POST') {
        $post = $req->post();
        $files = $req->files();
        $result = array();

        try{
            $nomImage = saveFile($files['photo'], 'uploads/', array('png', 'jpg', 'jpeg'), true);

            $user = new User();
            $user->setEmail($post['email']);
            $user->setType('FOURNISSEUR');
            $user->setTelephone($post['telephone']);
            $user->setPassword($post['password']);

            $uService = new UserService();
            $idUser = $uService->create($user);

            $fournisseur = new Fournisseur();
            $fournisseur->setNomSociete($post['nomSociete']);
            $fournisseur->setNomImageProfil($nomImage);
            $fournisseur->setIdUser($idUser);

            $fService = new FournisseurService();
            $fService->create($fournisseur);

            $result['success'] = true;

        } catch (Exception $e){
            $result['success'] = false;
            $result['message'] = $e->getMessage();
        }

        $action->response()->toJson($result);
    }
}