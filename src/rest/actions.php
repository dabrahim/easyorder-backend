<?php
use \Firebase\JWT\JWT;
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 5/12/2018
 * Time: 9:00 PM
 */

function connexion ( $action ) {
    $result = array();
    $req = $action->request();
    $post = $req->post();

    $user = new User();
    $user->setEmail( $post['email'] );
    $user->setPassword( $post['password'] );

    $uService = new UserService();
    $user = $uService->authenticate($user);

    $key = "5wu{@N\"i!^G>M5z0Zzk,e8,w1G$5[#";

    if ( $user ) {
        $token = array (
            'id_user' => $user['id_user'],
            'type' => $user['type'],
            'email' => $user['email']
        );

        $result['success'] = true;
        $result['token'] = JWT::encode($token, $key, 'HS256');
        $result['user'] = $user;

        $cService = new CategorieService();
        $result['categories'] = $cService->getAll();

   } else {
       $result['success'] = false;
   }

    $action->response()->toJson($result);
}

function getAllCategories( $action ) {
    $cService = new CategorieService();
    $action->response()->toJson( $cService->getAll() ) ;
}

function saveOrder ($action) {
    $req = $action->request();
    $result = array(
        'success' => false
    );

    if($req->type() == 'POST') {
        $post = $req->post();
        $idUser = $post['idUser'];
        $idFournisseur = $post['idFournisseur'];
        $montant = $post['montant'];

        $data = json_decode($post['data'], true);

        $pService = new ProduitService();
        $idCommande = $pService->saveCommande($idUser, $idFournisseur, $montant, $data);
        $result['success'] = true;
        $result['idCommande'] = $idCommande;
    }

    $action->response()->toJson($result);
}

function detailsCommande($action, $params) {
    $idCommande = $params[1];
    $pService = new ProduitService();
    $action->response()->toJson($pService->getDetailsCommande($idCommande));
}

function payer ($action) {

}