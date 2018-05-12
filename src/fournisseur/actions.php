<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 5/11/2018
 * Time: 11:44 AM
 */

function home ( $action ) {
    $action->response()->render( 'accueil-fournisseur.html' );
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